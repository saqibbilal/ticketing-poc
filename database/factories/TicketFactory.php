<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'affected_user_id' => User::factory(),
            'assigned_to_id' => null,
            'problem_description' => fake()->paragraph(3),
            'received_date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'additional_notes' => fake()->optional(0.5)->sentence(10),
        ];
    }
}
