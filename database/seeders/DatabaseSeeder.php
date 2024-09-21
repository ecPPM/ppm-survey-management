<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //$this->call(RoleSeeder::class);
        //$this->call(SurveySeeder::class);

        User::create([
            'name' => 'admin',
            'enum_code' => 'A001',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'remember_token' => Str::random(10),
            'mode' => 'admin'
        ]);

        for ($i=1; $i<10; $i++) {
            User::create([
                'name' => 'enum' . $i,
                'enum_code' => 'E00' . $i,
                'password' => Hash::make('password'),
                'role_id' => 2,
                'remember_token' => Str::random(10),
                'mode' => 'none'
            ]);
        }

    }
}
