<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin Account ──────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@sms.com'],
            [
                'first_name' => 'Admin',
                'last_name'  => 'User',
                'password'   => Hash::make('password123'),
                'role'       => 'Admin',
            ]
        );

        // ── Student Accounts ───────────────────────────
        $student1 = User::firstOrCreate(
            ['email' => 'juan@student.com'],
            [
                'first_name' => 'Juan',
                'last_name'  => 'Dela Cruz',
                'password'   => Hash::make('password123'),
                'role'       => 'Student',
            ]
        );

        $student2 = User::firstOrCreate(
            ['email' => 'maria@student.com'],
            [
                'first_name' => 'Maria',
                'last_name'  => 'Santos',
                'password'   => Hash::make('password123'),
                'role'       => 'Student',
            ]
        );

        // ── Sample Scholarships ────────────────────────
        $sch1 = Scholarship::firstOrCreate(
            ['scholarship_name' => 'DOST Merit Scholarship'],
            [
                'description' => 'Scholarship for academically excellent students pursuing science and technology courses.',
                'amount'      => 40000.00,
                'deadline'    => now()->addMonths(2)->toDateString(),
                'created_by'  => $admin->user_id,
                'status'      => 'Active',
            ]
        );

        $sch2 = Scholarship::firstOrCreate(
            ['scholarship_name' => 'DepEd Teachers Scholarship'],
            [
                'description' => 'For students who wish to pursue education courses and become teachers.',
                'amount'      => 25000.00,
                'deadline'    => now()->addMonths(3)->toDateString(),
                'created_by'  => $admin->user_id,
                'status'      => 'Active',
            ]
        );

        // ── Sample Applications ────────────────────────
        Application::firstOrCreate(
            [
                'user_id'        => $student1->user_id,
                'scholarship_id' => $sch1->scholarship_id,
            ],
            [
                'date_applied' => now()->toDateString(),
                'status'       => 'Pending',
                'remarks'      => 'Interested in STEM scholarship.',
            ]
        );

        Application::firstOrCreate(
            [
                'user_id'        => $student2->user_id,
                'scholarship_id' => $sch2->scholarship_id,
            ],
            [
                'date_applied' => now()->toDateString(),
                'status'       => 'Approved',
                'remarks'      => 'Education track applicant.',
            ]
        );

        $this->command->info('Seeder complete!');
        $this->command->info('Admin   -> admin@sms.com / password123');
        $this->command->info('Student -> juan@student.com / password123');
        $this->command->info('Student -> maria@student.com / password123');
    }
}