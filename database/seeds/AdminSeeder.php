<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::where('username', 'superadmin')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "SUPER ADMIN";
            $admin->email    = "augusto.yepez@sppat.gob.ec";
            $admin->username = "superadmin";
            $admin->password = Hash::make('admin123456*');
            $admin->save();
        }

        $admin = Admin::where('username', 'gabriela.paez')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "GABRIELA PÁEZ";
            $admin->email    = "gabriela.paez@sppat.gob.ec";
            $admin->username = "gabriela.paez";
            $admin->password = Hash::make('gabriela.paez123');
            $admin->save();
        }

    }
}
