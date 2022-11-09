<?php


namespace Tests\Feature\Auth;

use Gentcmen\Models\Role;
use Gentcmen\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BlogCategoryTest extends TestCase
{
    use DatabaseTransactions;

    protected $tokenName;
    protected $token;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->tokenName = 'test';

        parent::__construct($name, $data, $dataName);
    }

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $user->roles()->attach(Role::IS_ADMIN);

        $passport = Passport::actingAs($user);

        $this->token = $passport->createToken($this->tokenName)->accessToken;
    }

    public function testShouldCreateNewCategory()
    {
        $payload = [
            "name" => "First category",
        ];

        $headers = ['Authorization' => "Bearer $this->token"];

        $this->json('post', '/api/v1/admin/blog-categories', $payload, $headers)
            ->assertStatus(Response::HTTP_CREATED);
    }
}
