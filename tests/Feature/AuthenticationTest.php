<?php

namespace Tests\Feature;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private Rol $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->role = Rol::create(['name' => 'Cliente']);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Iniciar Sesión');
    }

    public function test_users_can_authenticate_using_email(): void
    {
        $user = User::factory()->create([
            'email' => 'carlos@example.com',
            'password' => 'password123',
            'roles_id' => $this->role->id,
            'rol' => 'cliente',
        ]);

        $response = $this->post('/login', [
            'login' => 'carlos@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_can_authenticate_using_username(): void
    {
        $user = User::factory()->create([
            'name' => 'carlosgym',
            'email' => 'carlos2@example.com',
            'password' => 'password123',
            'roles_id' => $this->role->id,
            'rol' => 'cliente',
        ]);

        $response = $this->post('/login', [
            'login' => 'carlosgym',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'carlos@example.com',
            'password' => 'password123',
            'roles_id' => $this->role->id,
            'rol' => 'cliente',
        ]);

        $response = $this->from('/login')->post('/login', [
            'login' => 'carlos@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('login');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create([
            'roles_id' => $this->role->id,
            'rol' => 'cliente',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_unauthenticated_users_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_access_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Carlos Cliente',
            'roles_id' => $this->role->id,
            'rol' => 'cliente',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Carlos Cliente');
        $response->assertSee('GYM RD SPORT');
    }

    public function test_admin_is_redirected_to_admin_dashboard(): void
    {
        $adminRole = Rol::create(['name' => 'Administrador']);
        $admin = User::factory()->create([
            'name' => 'Marcos Admin',
            'roles_id' => $adminRole->id,
            'rol' => 'admin',
        ]);

        // Al acceder a /dashboard siendo administrador, se redirige a /admin/dashboard
        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Nuevo Miembro',
            'email' => 'nuevo@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles_id' => $this->role->id,
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'nuevo@example.com',
            'name' => 'Nuevo Miembro',
        ]);
        $response->assertRedirect(route('dashboard'));
    }
}
