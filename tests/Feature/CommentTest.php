<?php
namespace Tests\Feature;

use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Technician']);
        Role::create(['name' => 'Regular']);
    }

    public function test_admin_can_add_comment()
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'Admin')->first()->id]);
        $ticket = Ticket::factory()->create([
            'affected_user_id' => User::factory()->create(['role_id' => Role::where('name', 'Regular')->first()->id])->id,
        ]);

        $response = $this->actingAs($admin)->post('/comments', [
            'ticket_id' => $ticket->id,
            'body' => 'Test comment',
        ]);

        $response->assertRedirect("/tickets/{$ticket->id}");
        $this->assertDatabaseHas('comments', ['body' => 'Test comment']);
    }

    public function test_technician_can_add_comment()
    {
        $tech = User::factory()->create(['role_id' => Role::where('name', 'Technician')->first()->id]);
        $ticket = Ticket::factory()->create([
            'affected_user_id' => User::factory()->create(['role_id' => Role::where('name', 'Regular')->first()->id])->id,
        ]);

        $response = $this->actingAs($tech)->post('/comments', [
            'ticket_id' => $ticket->id,
            'body' => 'Technician comment',
        ]);

        $response->assertRedirect("/tickets/{$ticket->id}");
        $this->assertDatabaseHas('comments', ['body' => 'Technician comment']);
    }

    public function test_regular_user_cannot_add_comment()
    {
        $regular = User::factory()->create(['role_id' => Role::where('name', 'Regular')->first()->id]);
        $ticket = Ticket::factory()->create([
            'affected_user_id' => $regular->id,
        ]);

        $response = $this->actingAs($regular)->post('/comments', [
            'ticket_id' => $ticket->id,
            'body' => 'Regular user comment',
        ]);

        $response->assertStatus(403);
    }

    public function test_comment_body_max_255_characters()
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'Admin')->first()->id]);
        $ticket = Ticket::factory()->create([
            'affected_user_id' => User::factory()->create(['role_id' => Role::where('name', 'Regular')->first()->id])->id,
        ]);

        $response = $this->actingAs($admin)->post('/comments', [
            'ticket_id' => $ticket->id,
            'body' => str_repeat('a', 256),
        ]);

        $response->assertSessionHasErrors('body');
    }
}
