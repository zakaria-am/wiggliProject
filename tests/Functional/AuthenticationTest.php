<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\UsersFixtures;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthenticationTest
 */
class AuthenticationTest extends ApiTestCase
{
    const URL_API_AUTH = '/authentication_token';

    public function testLoginSuccess(): void
    {
        $client = self::createClient();

        $response = $client->request(Request::METHOD_POST, self::URL_API_AUTH, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => UsersFixtures::EMAIL_ADMIN,
                'password' => UsersFixtures::PASSWORD,
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $response->toArray());
    }

    public function testLoginError(): void
    {
        $client = self::createClient();

        $response = $client->request(Request::METHOD_POST, self::URL_API_AUTH, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'mail_for_test',
                'password' => 'password_for_test',
            ],
        ]);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}