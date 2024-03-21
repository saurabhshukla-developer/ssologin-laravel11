<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SocialLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_to_google_oauth_page()
    {
        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturnSelf()
            ->shouldReceive('redirect')
            ->andReturn(new RedirectResponse('https://accounts.google.com/o/oauth2/auth'));

        $response = $this->get(route('social.redirect', ['provider' => 'google']));

        $response->assertRedirect('https://accounts.google.com/o/oauth2/auth');
    }

    /** @test */
    public function it_redirects_to_github_oauth_page()
    {
        Socialite::shouldReceive('driver')
            ->with('github')
            ->andReturnSelf()
            ->shouldReceive('redirect')
            ->andReturn(new RedirectResponse('https://github.com/login/oauth/authorize'));

        $response = $this->get(route('social.redirect', ['provider' => 'github']));

        $response->assertRedirect('https://github.com/login/oauth/authorize');
    }

    /** @test */
    public function it_creates_or_links_a_user_after_successful_google_login()
    {
        // Mock the Socialite user
        $socialiteUser = $this->createMock(SocialiteUser::class);
        $socialiteUser->token = 'mock_access_token';
        $socialiteUser->id = 'google_user_id';
        $socialiteUser->name = 'Test User';
        $socialiteUser->email = 'user@example.com';
        $socialiteUser->avatar = 'http://example.com/avatar.jpg';

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($socialiteUser);

        // Simulate the callback being received from Google
        $callbackUrl = route('social.callback', ['provider' => 'google']) . '?code=mock_auth_code';
        $response = $this->get($callbackUrl);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'user@example.com',
        ]);
    }

    /** @test */
    public function it_creates_or_links_a_user_after_successful_github_login()
    {
        // Mock the Socialite user for GitHub
        $socialiteUser = $this->createMock(SocialiteUser::class);
        $socialiteUser->token = 'mock_access_token';
        $socialiteUser->id = 'github_user_id';
        $socialiteUser->name = 'Test User';
        $socialiteUser->email = 'user1@example.com';
        $socialiteUser->avatar = 'http://example.com/avatar.jpg';

        Socialite::shouldReceive('driver')
            ->with('github')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($socialiteUser);

        // Simulate the callback being received from GitHub
        $callbackUrl = route('social.callback', ['provider' => 'github']) . '?code=mock_auth_code';
        $response = $this->get($callbackUrl);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'user1@example.com',
        ]);
    }
}
