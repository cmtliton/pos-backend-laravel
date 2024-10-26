<?php

namespace Database\Seeders;

use App\Models\InventoryModels\AccountType;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Account_TypesTableseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AccountType::factory()->create([
            'name' => 'Current Asset', 'Fixed Asset', 'Current Liability', 'Fixed Liability', 'Income', 'Fixed Expenses', 'Variable Expenses', 'Operating Expenses', 'Non-operating Expenses', 'Deposit',
            'code' => 'CA', 'FA', 'CL', 'FL', 'I', 'FE', 'VE', 'OE', 'NOE', 'D',
            'description' => ''
        ]);
    }
}
