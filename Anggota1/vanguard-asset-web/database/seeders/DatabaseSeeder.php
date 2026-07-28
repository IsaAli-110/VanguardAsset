<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Asset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles & Users
        $admin = User::create([
            'name' => 'Vanguard IT Admin',
            'email' => 'admin@vanguard.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $manager = User::create([
            'name' => 'Vanguard Manager',
            'email' => 'manager@vanguard.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        $staff = User::create([
            'name' => 'Vanguard Employee',
            'email' => 'staff@vanguard.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // 2. Seed Company Assets (Physical & Digital)
        Asset::create([
            'id' => 'AST-PH-001',
            'name' => 'MacBook Pro M3 Max 16"',
            'type' => 'physical',
            'status' => 'Available',
            'purchase_cost' => 48500000.00,
            'purchase_date' => Carbon::parse('2024-01-15'),
            'detail_json' => [
                'serial_number' => 'C02XYZ123MBP3M',
                'maintenance_interval' => 180
            ]
        ]);

        Asset::create([
            'id' => 'AST-PH-002',
            'name' => 'Dell XPS 15 9530',
            'type' => 'physical',
            'status' => 'Available',
            'purchase_cost' => 32000000.00,
            'purchase_date' => Carbon::parse('2023-06-10'),
            'detail_json' => [
                'serial_number' => 'DXPS9530-8877665',
                'maintenance_interval' => 120
            ]
        ]);

        Asset::create([
            'id' => 'AST-DG-001',
            'name' => 'Adobe Creative Cloud Enterprise 1 Year',
            'type' => 'digital',
            'status' => 'Available',
            'purchase_cost' => 14200000.00,
            'purchase_date' => Carbon::parse('2025-06-01'), // Expiring soon in the future relative to current time 2026
            'detail_json' => [
                'license_key' => 'ADOBE-CC-ENT-2025-9988-7766',
                'expiry_date' => '2026-06-01'
            ]
        ]);

        Asset::create([
            'id' => 'AST-DG-002',
            'name' => 'JetBrains All Products Pack Academic License',
            'type' => 'digital',
            'status' => 'Available',
            'purchase_cost' => 8600000.00,
            'purchase_date' => Carbon::parse('2025-10-15'),
            'detail_json' => [
                'license_key' => 'JB-APP-ACAD-2025-XYZ123',
                'expiry_date' => '2026-10-15'
            ]
        ]);
        
        // Let's create an asset that is currently borrowed by staff to test returning
        $borrowedAsset = Asset::create([
            'id' => 'AST-PH-003',
            'name' => 'Sony Alpha 7 IV Camera',
            'type' => 'physical',
            'status' => 'Borrowed',
            'purchase_cost' => 35000000.00,
            'purchase_date' => Carbon::parse('2024-09-01'),
            'detail_json' => [
                'serial_number' => 'SONY-A7M4-3322110',
                'maintenance_interval' => 90
            ],
            'borrowed_by_id' => $staff->id
        ]);
    }
}
