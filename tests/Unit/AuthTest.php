<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_can_register_a_user(): void
    {
        $email = fake()->email;
        $data = [
            'name' => 'John Doe',
            'email' => $email,
            'password' => '6482R_ato#!',
            'password_confirmation' => '6482R_ato#!',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user' => [
                        'name',
                        'email',
                        'created_at',
                    ]
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
    }

    public function test_it_can_login_a_user(): void
    {
        $email = fake()->email;
        User::factory()->create([
            'email' => $email,
            'password' => Hash::make('password123'),
        ]);

        $data = [
            'email' => $email,
            'password' => 'password123',
        ];


        $response = $this->postJson('/api/login', $data);


        $response->assertStatus(200);


        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'access_token',
                'token_type'
            ]
        ]);


    }

    public function test_it_can_logout_a_user(): void
    {

        $user = User::factory()->create();


        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(204);

//        $this->assertDatabaseMissing('personal_access_tokens', [
//            'tokenable_id' => 12,
//            'tokenable_type' => get_class($user),
//        ]);
    }
}
