<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_student_can_access_student_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_student_cannot_access_educator_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->get('/educator/dashboard');

        $response->assertRedirect('/dashboard');
    }

    public function test_educator_can_access_educator_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'educator']);

        $response = $this->actingAs($user)->get('/educator/dashboard');

        $response->assertStatus(200);
    }

    public function test_educator_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'educator']);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertRedirect('/educator/dashboard');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200);
    }
}
