<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NoteTest extends TestCase
{
    public function test_create(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $title = fake()->title();
        $content = fake()->text();

        $response = $this->post('api/note/create', [
            'title' => $title,
            'content' => $content,
        ]);

        $response->assertSuccessful();
        $response->assertJson(fn (AssertableJson $json) => [
            $json->where('title', $title)
                ->where('content', $content)
                ->hasAll([
                    'id', 'created_at', 'updated_at',
                ])
        ]);

        $this->assertDatabaseHas(Note::class, [
            'id' => $response->json('id'),
            'user_id' => $user->id,
            'title' => $title,
            'content' => $content,
        ]);
    }

    public function test_create_unauthorize(): void
    {
        $title = fake()->title();
        $content = fake()->text();

        $response = $this->post('api/note/create', [
            'title' => $title,
            'content' => $content,
        ]);

        $response->assertUnauthorized();
        $this->assertDatabaseEmpty(Note::class);
    }

    public function test_create_invalid(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->post('api/note/create', [
            'title' => '',
            'content' => '',
        ]);

        $response->assertJsonValidationErrors([
            'title',
            'content',
        ]);
    }

    public function test_show(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->for($user)->create();

        Sanctum::actingAs($user);

        $response = $this->get('/api/note/'.$note->id);

        $response->assertSuccessful();
        $response->assertJson(fn (AssertableJson $json) => [
            $json->where('id', $note->id)
                ->where('title', $note->title)
                ->where('content', $note->content)
                ->whereType('created_at', 'string')
                ->whereType('updated_at', 'string')
                ->has('user', fn ($json) =>
                    $json
                        ->where('id', $user->id)
                        ->where('name', $user->name)
                        ->where('email', $user->email)
                        ->hasAll('created_at')
                    )
        ]);
    }

    public function test_update(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->for($user)->create();

        Sanctum::actingAs($user);

        $titleUpdated = fake()->title();

        $response = $this->put(sprintf('api/note/%d', $note->id),[
            'title' => $titleUpdated,
            'content' => $note->content,
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas(Note::class, [
            'id' => $note->id,
            'user_id' => $user->id,
            'title' => $titleUpdated,
            'content' => $note->content,
        ]);
    }

    public function test_update_unauthorize(): void
    {
        $user = User::factory()->create();
        $secondUser = User::factory()->create();
        $note = Note::factory()->for($secondUser)->create();

        Sanctum::actingAs($user);

        $titleUpdated = fake()->title();

        $response = $this->put(sprintf('api/note/%d', $note->id),[
            'title' => $titleUpdated,
            'content' => $note->content,
        ]);

        $response->assertUnauthorized();
    }

    public function test_destroy(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->for($user)->create();

        Sanctum::actingAs($user);

        $response = $this->delete('/api/note'.$note->id);

        $response->assertNotFound();
        // $response->assertNoContent($status = 204);
    }

    public function test_list(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        Note::factory(3)->for($user)->create();

        $response = $this->get('api/note/list');

        $response->assertSuccessful();

        $response->assertJsonCount(3);
    }

    public function test_list_authorize(): void
    {
        $user = User::factory()->create();
        $seconduser= User::factory()->create();

        Sanctum::actingAs($user);

        Note::factory(3)->for($user)->create();
        Note::factory(3)->for($seconduser)->create();

        $response = $this->get('api/note/list');

        $response->assertSuccessful();

        $response->assertJsonCount(3);
    }
}
