<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin', 'email' => 'admin@sinar.co', 'password' => 'Admin123!', 'role' => 'admin'],
            ['name' => 'Operator', 'email' => 'opr@sinar.co', 'password' => '123opera#', 'role' => 'operator'],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                ],
            );

            $user->forceFill(['role' => $data['role']])->save();
        }
    }
}
