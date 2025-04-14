<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'acyepez@udlanet.com.ec')->first();
        if (is_null($user)) {
            $user = new User();
            $user->name = "Augusto Yépez";
            $user->email = "acyepez@udlanet.com.ec";
            $user->password = Hash::make('acyepez123456');
            $user->save();
        }
    }
}
