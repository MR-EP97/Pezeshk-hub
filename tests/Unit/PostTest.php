<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_get_all_posts(): void
    {

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "status",
            "message",
            "data" => [
                "posts",
                "links" => [
                    "first",
                    "last",
                    "prev",
                    "next",
                ],
            ],
        ]);

        $this->assertCount(3, $response->json());
    }


    public function test_it_can_show_a_user(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200);


        $response->assertJsonStructure([
            "status",
            "message",
            "data" => [
                "post" => [
                    "title",
                    "content",
                    "created_at"
                ]
            ]
        ]);
    }


    public function test_it_can_store_a_new_user(): void
    {

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $title = Str::random(10);
        $content = Str::random(150);

        $data = [
            'title' => $title,
            'content' => $content,
        ];


        $response = $this->postJson('/api/posts', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            "status",
            "message",
            "data" => [
                "post" => [
                    "title",
                    "content",
                    "created_at"
                ]
            ],
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => $title,
            'content' => $content,
        ]);
    }

    public function test_it_can_update_a_user(): void
    {

        $user = User::factory()->create();

        $post = Post::query()->create([
            'title' => fake()->title,
            'content' => fake()->paragraph,
            'user_id' => $user->id,

        ]);

        Sanctum::actingAs($user);

        $title = Str::random(10);
        $content = Str::random(150);

        $data = [
            'title' => $title,
            'content' => $content,
        ];

        $response = $this->patchJson("/api/posts/{$user->id}", $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "status",
            "message",
            "data" => [
                "post" => [
                    "title",
                    "content",
                    "created_at"
                ]
            ],
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => $title,
            'content' => $content,
            'user_id' => $user->id,
        ]);
    }


    public function test_it_can_delete_a_user(): void
    {


        $user = User::factory()->create();

        $title = Str::random(10);
        $content = Str::random(150);

        $post = Post::query()->create([
            'title' => $title,
            'content' => $content,
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);


        $response = $this->deleteJson("/api/posts/{$post->id}");


        $response->assertStatus(204);


        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}
