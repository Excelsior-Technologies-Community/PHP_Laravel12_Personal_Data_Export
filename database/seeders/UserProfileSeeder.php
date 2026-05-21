<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;

class UserProfileSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+1 (555) 123-4567',
                'address' => '123 Main Street, New York, NY 10001'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '+1 (555) 987-6543',
                'address' => '456 Oak Avenue, Los Angeles, CA 90001'
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'phone' => '+1 (555) 456-7890',
                'address' => '789 Pine Road, Chicago, IL 60601'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt('password123')
            ]);
            
            $user->profile()->create([
                'phone' => $userData['phone'],
                'address' => $userData['address']
            ]);
        }
        
        echo "Users and profiles created successfully!\n";
    }
}