<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed roles first
        $this->call(RoleSeeder::class);

        $adminRole = Role::where('name', 'Admin')->first();
        $techRole = Role::where('name', 'Technician')->first();
        $regularRole = Role::where('name', 'Regular')->first();

        // Create default admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
        ]);

        // Create technicians
        $technicians = User::factory(3)->create([
            'role_id' => $techRole->id,
        ]);

        // Create regular users
        User::factory(10)->create([
            'role_id' => $regularRole->id,
        ]);

        // Create tickets with random assignments
        $allUsers = User::all();

        foreach (range(1, 15) as $i) {
            $ticket = Ticket::factory()->create([
                'affected_user_id' => $allUsers->random()->id,
                'assigned_to_id' => fake()->boolean(60) ? $technicians->random()->id : null,
            ]);

            // Add 0-3 comments per ticket (only by Admin/Technician)
            if (fake()->boolean(70)) {
                $commentsCount = fake()->numberBetween(1, 3);

                for ($j = 0; $j < $commentsCount; $j++) {
                    Comment::factory()->create([
                        'ticket_id' => $ticket->id,
                        'author_id' => User::whereIn('role_id', [$adminRole->id, $techRole->id])
                            ->inRandomOrder()
                            ->first()
                            ->id,
                    ]);
                }
            }
        }

        // Soft delete a few tickets for testing
        Ticket::inRandomOrder()->limit(2)->get()->each->delete();
    }
}
