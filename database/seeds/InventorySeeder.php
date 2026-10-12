<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Akun Default (Admin & Staff) jika belum ada
        if (DB::table('users')->where('username', 'admin')->count() === 0) {
            $adminId = DB::table('users')->insertGetId([
                'username'      => 'admin',
                'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
                'role'          => 'admin',
                'created_at'    => date('Y-m-d H:i:s'),
            ]);

            DB::table('user_profiles')->insert([
                'user_id'   => $adminId,
                'full_name' => 'Administrator Utama',
                'phone'     => '081234567890',
                'bio'       => 'Super Administrator pengelola sistem inventaris',
            ]);
        }

        if (DB::table('users')->where('username', 'staff')->count() === 0) {
            $staffId = DB::table('users')->insertGetId([
                'username'      => 'staff',
                'password_hash' => password_hash('staff123', PASSWORD_BCRYPT),
                'role'          => 'staff',
                'created_at'    => date('Y-m-d H:i:s'),
            ]);

            DB::table('user_profiles')->insert([
                'user_id'   => $staffId,
                'full_name' => 'Staff Operasional Gudang',
                'phone'     => '089876543210',
                'bio'       => 'Petugas pencatatan logistik dan operasional barang',
            ]);
        }
    }
}
