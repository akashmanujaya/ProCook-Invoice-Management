<?php

namespace Database\Factories;

use App\BO\Invoices\v100\Models\Invoices;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class InvoicesFactory extends Factory
{
    protected $model = Invoices::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_number' => 'INV-' . fake()->unique()->numberBetween(100, 999),
            'first_name' => fake()->name,
            'last_name' => fake()->name,
            'description' => fake()->sentence,
            'invoice_date' => fake()->dateTime,
            'payment_term' => fake()->numberBetween(1, 30),
            'total_amount' => fake()->randomFloat(2, 10, 1000),
            'due_date' => fake()->dateTime,
            'status' => 0,
        ];
    }
}
