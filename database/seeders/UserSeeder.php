<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a new user
        $user = User::create([
            'name' => 'ProCook User',
            'email' => 'user@procook.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
