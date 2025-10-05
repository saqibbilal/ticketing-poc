<?php
namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Technician']);
        Role::create(['name' => 'Regular']);
    }

    public function test_admin_can_view_user_list()
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'Admin')->first()->id]);

        $response = $this->actingAs($admin)->get('/users');

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    public function test_technician_cannot_view_user_list()
    {
        $tech = User::factory()->create(['role_id' => Role::where('name', 'Technician')->first()->id]);

        $response = $this->actingAs($tech)->get('/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_user()
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'Admin')->first()->id]);
        $regularRole = Role::where('name', 'Regular')->first();

        $response = $this->actingAs($admin)->post('/users', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'role_id' => $regularRole->id,
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'role_id' => $regularRole->id,
        ]);
    }

    public function test_user_creation_validates_email_uniqueness()
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'Admin')->first()->id]);
        $existingUser = User::factory()->create([
            'email' => 'existing@example.com',
            'role_id' => Role::where('name', 'Regular')->first()->id,
        ]);

        $response = $this->actingAs($admin)->post('/users', [
            'name' => 'Another User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'role_id' => Role::where('name', 'Regular')->first()->id,
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}
