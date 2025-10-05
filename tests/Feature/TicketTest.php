<?php
namespace Tests\Feature;

use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles before each test
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Technician']);
        Role::create(['name' => 'Regular']);
    }

    public function test_admin_can_create_ticket()
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'Admin')->first()->id]);
        $user = User::factory()->create(['role_id' => Role::where('name', 'Regular')->first()->id]);

        $response = $this->actingAs($admin)->post('/tickets', [
            'affected_user_id' => $user->id,
            'problem_description' => 'Test problem',
            'received_date' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect('/tickets');
        $this->assertDatabaseHas('tickets', ['problem_description' => 'Test problem']);
    }

    public function test_technician_can_create_ticket()
    {
        $tech = User::factory()->create(['role_id' => Role::where('name', 'Technician')->first()->id]);
        $user = User::factory()->create(['role_id' => Role::where('name', 'Regular')->first()->id]);

        $response = $this->actingAs($tech)->post('/tickets', [
            'affected_user_id' => $user->id,
            'problem_description' => 'Test problem',
            'received_date' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect('/tickets');
        $this->assertDatabaseHas('tickets', ['problem_description' => 'Test problem']);
    }

    public function test_regular_user_cannot_create_ticket()
    {
        $regular = User::factory()->create(['role_id' => Role::where('name', 'Regular')->first()->id]);

        $response = $this->actingAs($regular)->get('/tickets/create');

        $response->assertStatus(403);
    }

    public function test_technician_can_assign_to_self()
    {
        $tech = User::factory()->create(['role_id' => Role::where('name', 'Technician')->first()->id]);
        $ticket = Ticket::factory()->create([
            'affected_user_id' => User::factory()->create(['role_id' => Role::where('name', 'Regular')->first()->id])->id,
        ]);

        $response = $this->actingAs($tech)->patch("/tickets/{$ticket->id}/assign-self");

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'assigned_to_id' => $tech->id]);
    }

    public function test_admin_can_delete_ticket()
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'Admin')->first()->id]);
        $ticket = Ticket::factory()->create([
            'affected_user_id' => User::factory()->create(['role_id' => Role::where('name', 'Regular')->first()->id])->id,
        ]);

        $response = $this->actingAs($admin)->delete("/tickets/{$ticket->id}");

        $response->assertRedirect('/tickets');
        $this->assertSoftDeleted('tickets', ['id' => $ticket->id]);
    }

    public function test_admin_can_restore_deleted_ticket()
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'Admin')->first()->id]);
        $ticket = Ticket::factory()->create([
            'affected_user_id' => User::factory()->create(['role_id' => Role::where('name', 'Regular')->first()->id])->id,
        ]);
        $ticket->delete();

        $response = $this->actingAs($admin)->patch("/tickets/{$ticket->id}/restore");

        $response->assertRedirect('/tickets');
        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'deleted_at' => null]);
    }

    public function test_additional_notes_max_128_characters()
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'Admin')->first()->id]);
        $user = User::factory()->create(['role_id' => Role::where('name', 'Regular')->first()->id]);

        $response = $this->actingAs($admin)->post('/tickets', [
            'affected_user_id' => $user->id,
            'problem_description' => 'Test',
            'received_date' => now()->format('Y-m-d'),
            'additional_notes' => str_repeat('a', 129),
        ]);

        $response->assertSessionHasErrors('additional_notes');
    }
}
