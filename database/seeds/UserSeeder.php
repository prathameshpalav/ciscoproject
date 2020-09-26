<?php

use App\User;
use Illuminate\Support\Str;
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
        if (!User::where('email', 'test@gmail.com')->exists()) {
            User::create([
                'name'      => 'Test',
                'email'     => 'test@gmail.com',
                'password'  => Hash::make('password'),
                'api_token' => Str::random(60)
            ]);
        }
    }
}
