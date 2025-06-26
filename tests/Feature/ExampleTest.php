<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::find('1');
        $this->assertAuthenticatedAs($user, $guard = null);
        $response = $this->get('/Home');

        $response->assertStatus(200);
    }
}
