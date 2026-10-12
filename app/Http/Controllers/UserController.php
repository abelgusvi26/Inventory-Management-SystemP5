<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\UserProfile;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $users = User::with('profile')
            ->withCount('stockMovements')
            ->orderBy('id', 'ASC')
            ->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'username'  => 'required|string|max:50|unique:users,username',
            'password'  => 'required|string|min:6',
            'role'      => 'required|in:admin,staff',
            'full_name' => 'required|string|max:100',
            'phone'     => 'nullable|string|max:25',
            'bio'       => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username'      => trim($request->input('username')),
                'password_hash' => password_hash($request->input('password'), PASSWORD_BCRYPT),
                'role'          => $request->input('role'),
                'created_at'    => date('Y-m-d H:i:s'),
            ]);

            UserProfile::create([
                'user_id'   => $user->id,
                'full_name' => trim($request->input('full_name')),
                'phone'     => trim($request->input('phone', '')),
                'bio'       => trim($request->input('bio', '')),
            ]);

            DB::commit();
            return redirect()->route('users.index')->with('flash_success', 'Pengguna baru berhasil didaftarkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('flash_error', 'Gagal mendaftarkan pengguna: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::with('profile')->findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'role'      => 'required|in:admin,staff',
            'password'  => 'nullable|string|min:6',
            'full_name' => 'required|string|max:100',
            'phone'     => 'nullable|string|max:25',
            'bio'       => 'nullable|string',
        ]);

        // Cegah mencabut admin terakhir jika user ini adalah satu-satunya admin
        if ($user->role === 'admin' && $request->input('role') !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->back()->withInput()->with('flash_error', 'Tidak dapat mengubah peran admin terakhir sistem.');
            }
        }

        DB::beginTransaction();
        try {
            $user->role = $request->input('role');
            if ($request->filled('password')) {
                $user->password_hash = password_hash($request->input('password'), PASSWORD_BCRYPT);
            }
            $user->save();

            // Update or create profile (1-to-1)
            $profile = UserProfile::firstOrNew(['user_id' => $user->id]);
            $profile->full_name = trim($request->input('full_name'));
            $profile->phone = trim($request->input('phone', ''));
            $profile->bio = trim($request->input('bio', ''));
            $profile->save();

            DB::commit();
            return redirect()->route('users.index')->with('flash_success', 'Data pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('flash_error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('flash_error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->route('users.index')->with('flash_error', 'Tidak dapat menghapus administrator terakhir sistem.');
            }
        }

        if ($user->stockMovements()->count() > 0) {
            return redirect()->route('users.index')->with('flash_error', 'User "' . $user->username . '" tidak dapat dihapus karena memiliki catatan histori transaksi (RESTRICT).');
        }

        DB::beginTransaction();
        try {
            if ($user->profile) {
                $user->profile->delete();
            }
            $user->delete();
            DB::commit();
            return redirect()->route('users.index')->with('flash_success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.index')->with('flash_error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
