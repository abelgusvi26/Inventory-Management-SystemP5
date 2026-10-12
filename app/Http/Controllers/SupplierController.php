<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $suppliers = Supplier::withCount('products')
            ->orderBy('name', 'ASC')
            ->get();

        return view('suppliers.index', compact('suppliers'));
    }

    public function detail($id)
    {
        $supplier = Supplier::with('products')->findOrFail($id);
        return view('suppliers.detail', compact('supplier'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'           => 'required|string|max:150',
            'contact_person' => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:25',
            'email'          => 'nullable|email|max:100',
            'address'        => 'nullable|string',
        ]);

        Supplier::create([
            'name'           => trim($request->input('name')),
            'contact_person' => trim($request->input('contact_person', '')),
            'phone'          => trim($request->input('phone', '')),
            'email'          => trim($request->input('email', '')),
            'address'        => trim($request->input('address', '')),
        ]);

        return redirect()->route('suppliers.index')->with('flash_success', 'Pemasok / Supplier baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $this->validate($request, [
            'name'           => 'required|string|max:150',
            'contact_person' => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:25',
            'email'          => 'nullable|email|max:100',
            'address'        => 'nullable|string',
        ]);

        $supplier->update([
            'name'           => trim($request->input('name')),
            'contact_person' => trim($request->input('contact_person', '')),
            'phone'          => trim($request->input('phone', '')),
            'email'          => trim($request->input('email', '')),
            'address'        => trim($request->input('address', '')),
        ]);

        return redirect()->route('suppliers.index')->with('flash_success', 'Data pemasok berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->products()->detach();
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('flash_success', 'Data pemasok berhasil dihapus.');
    }
}
