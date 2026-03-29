<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirects_to_fortify_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/auth/login');
    }

    public function test_login_screen_is_available(): void
    {
        $response = $this->get('/auth/login');

        $response->assertOk();
        $response->assertSee('Simple Invoices');
    }

    public function test_legacy_logout_redirects_to_login(): void
    {
        $response = $this->get('/auth/legacy/logout');

        $response->assertRedirect('/auth/login');
    }
}
