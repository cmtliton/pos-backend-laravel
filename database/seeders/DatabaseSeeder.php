<?php

namespace Database\Seeders;

use App\Models\InventoryModels\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('account_types')->insert([
            ['name' => 'Current Asset', 'code' => 'CA', 'description' => ''],
            ['name' => 'Fixed Asset', 'code' => 'FA', 'description' => ''],
            ['name' => 'Current Liability', 'code' => 'CL', 'description' => ''],
            ['name' => 'Fixed Liability', 'code' => 'FL', 'description' => ''],
            ['name' => 'Income', 'code' => 'I', 'description' => ''],
            ['name' => 'Fixed Expenses', 'code' => 'FE', 'description' => ''],
            ['name' => 'Variable Expenses', 'code' => 'VE', 'description' => ''],
            ['name' => 'Operating Expenses', 'code' => 'OE', 'description' => ''],
            ['name' => 'Non-operating Expenses', 'code' => 'NOE', 'description' => ''],
            ['name' => 'Deposit', 'code' => 'D', 'description' => ''],
        ]);
    }
}
