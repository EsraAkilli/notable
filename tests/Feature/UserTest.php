<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_update(): void
    {
        $user = User::factory()->create([
            'password' => '12345',
        ]);

        Sanctum::actingAs($user);

        $nameUpdated = fake()->name();
        $emailUpdated = fake()->email();

        $response = $this->put('api/user/me',[
            'name' => $nameUpdated,
            'email' => $emailUpdated,
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas(User::class, [
            'id' => $user->id,
            'name' => $nameUpdated,
            'email' => $emailUpdated,
        ]);
    }

    public function test_update_with_password(): void
    {
        $user = User::factory()->create([
            'password'=>fake()->password,
        ]);

        Sanctum::actingAs($user);

        $newPassword = fake()->password;

        $this->put('api/user/me',[
            'name' => fake()->name(),
            'email' => $user->email,
            'password' => $newPassword,
        ])->assertSuccessful();

        $this->assertTrue(
            Hash::check(
                $newPassword,
                $user->refresh()->password,
            )
        );
    }
}
