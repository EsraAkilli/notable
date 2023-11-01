<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login(): void
    {
        $email = fake()->email();
        $password = fake()->password();

        User::factory()->create([
            'email' => $email,
            'password' => $password,
        ]);

        $response = $this->post('api/login',[
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertSuccessful();

        $response->assertJson(fn (AssertableJson $json) => [
            $json ->where('token_type', 'Bearer')
                ->has('access_token')
        ]);
    }

    public function test_invalid_password(): void
    {
        $password = fake()->password;
        $user = User::factory()->create();

        $response = $this->post('api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertForbidden();
    }
}
