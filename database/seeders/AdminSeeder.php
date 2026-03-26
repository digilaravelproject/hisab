<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Default admin account create karo
     *
     * Run: php artisan db:seed --class=AdminSeeder
     */
    public function run(): void
    {
        Admin::where('email', 'admin@vitai.com')->delete();

        Admin::create([
            'name'       => 'Super Admin',
            'email'      => 'admin@vitai.com',
            'password'   => Hash::make('Admin@123'),
            'is_active'  => true,
        ]);

        $this->command->info('✅ Admin created:');
        $this->command->line('   Email:    admin@vitai.com');
        $this->command->line('   Password: Admin@123');
        $this->command->warn('   ⚠  Production mein password zaroor change karein!');
    }
}
