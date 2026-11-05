<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class CounselorSeeder extends Seeder
{
    public function run(): void
    {
        $password = config('temandengar.counselor.password');

        if (!$password) {
            throw new RuntimeException(
                'COUNSELOR_PASSWORD belum diatur di .env'
            );
        }

        User::updateOrCreate(
            [
                'email' => config('temandengar.counselor.email'),
            ],
            [
                'name' => config('temandengar.counselor.name'),
                'password' => Hash::make($password),
            ]
        );
    }
}