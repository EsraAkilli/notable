<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_registration(): void
    {
        $name = fake()->name();
        $email = fake()->email();
        $password = fake()->password();

        $response = $this->post('api/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(201);

        $response->assertJson(fn (AssertableJson $json) => [
            $json ->where('name', $name)
                ->where('email', $email)
                ->hasAll([
                    'id', 'created_at',
                ])
        ]);

        $this->assertDatabaseHas(User::class, [
            'name' => $name,
            'email' => $email,
        ]);
    }

    public function test_used_email(): void
    {
        $user = User::factory()->create();

        $response = $this->post('api/register', [
            'email' => $user->email,
        ]);

        $response->assertJsonValidationErrors([
            'email',
        ]);
    }
}
