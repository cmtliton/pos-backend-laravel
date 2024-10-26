<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AccountTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'name' =>fake()->randomElement(['Current Asset', 'Fixed Asset', 'Current Liability', 'Fixed Liability', 'Income', 'Fixed Expenses', 'Variable Expenses', 'Operating Expenses', 'Non-operating Expenses', 'Deposit']),
            'code' =>fake()->randomElement(['CA', 'FA', 'CL', 'FL', 'I', 'FE', 'VE', 'OE', 'NOE', 'D']),
            'description' =>fake()->randomElement(['', '', ''])
        ];
    }
}
