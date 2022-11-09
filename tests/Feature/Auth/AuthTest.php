<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function testRequiredFieldsForRegistration()
    {
        $this->json('post', '/api/v1/client/sign-up')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "success" => false,
                "error" => "Validation Error.",
                "details" => [
                    "name" => ["The name field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ],
                "messages" => "Validation Error. Please try again"
            ]);
    }

    public function testRequiredFieldsForLogin()
    {
        $this->json('post', '/api/v1/client/sign-in')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "success" => false,
                "error" => "Validation Error.",
                "details" => [
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ],
                "messages" => "Validation Error. Please try again"
            ]);
    }

    public function testSuccessfulRegistration()
    {
        $payload = [
            "name" => "John",
            "email" => "doe@example.com",
            "password" => "demo12345",
        ];

        $this->json('post', '/api/v1/client/sign-up', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
            [
                'data' => [
                    'user_id',
                ]
            ]
        );;
    }

    public function testFailedRegistration()
    {
        $payload = [
            "name" => "John",
            "email" => "doe@example.com",
            "password" => "demo",
        ];

        $this->json('post', '/api/v1/client/sign-up', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(
                [
                    "success" => false,
                    "error" => "Validation Error.",
                    "details" => [
                        "password" => ["The password must be at least 8 characters."],
                    ],
                    "messages" => "Validation Error. Please try again"
                ]
            );
    }

    public function testFailedLogin()
    {
        $payload = [
            "name" => "Fake",
            "email" => "fake@example.com",
            "password" => "fake_password",
        ];

        $this->json('post', '/api/v1/client/sign-in', $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson(
                [
                    "success" => false,
                    "error" => "Authorization failed.",
                    "details" => "You have wrong credentials",
                    "messages" => "Provide correct credentials"
                ]
            );
    }

    public function testSuccessfulLogin()
    {
        $payload = [
            "name" => "John Doe",
            "email" => "doe@example.com",
            "password" => "demo12345",
        ];

        $this->json('post', '/api/v1/client/sign-up', $payload)
            ->assertStatus(Response::HTTP_CREATED);

        $this->json('post', '/api/v1/client/sign-in', $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'logged_in_user_id',
                    'token'
                ]
            );
    }
}
