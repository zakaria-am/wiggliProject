<?php


namespace App\Tests\Functional;

use App\DataFixtures\UsersFixtures;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UsersApiTest
 */
class UsersApiTest extends ApiTestCase
{

    const URL_API = '/v1/users';
    const URL_API_AUTH = '/authentication_token';


    public function testCreateUserByNormalUser(): void
    {
        $client = self::createClient();

        $response = $client->request(Request::METHOD_POST, self::URL_API, [
            'headers' => ['Content-Type' => 'application/json'],
            'auth_bearer' => $this->getToken(UsersFixtures::EMAIL, UsersFixtures::PASSWORD),
            'json' => $this->getUserData()
            ]
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testCreateUserByNormalAdmin(): void
    {
        $client = self::createClient();

        $response = $client->request(Request::METHOD_POST, self::URL_API, [
                'headers' => ['Content-Type' => 'application/json'],
                'auth_bearer' => $this->getToken(UsersFixtures::EMAIL_ADMIN, UsersFixtures::PASSWORD),
                'json' => $this->getUserData()
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateUserSameEmail(): void
    {
        $client = self::createClient();

        $response = $client->request(Request::METHOD_POST, self::URL_API, [
                'headers' => ['Content-Type' => 'application/json'],
                'auth_bearer' => $this->getToken(UsersFixtures::EMAIL_ADMIN, UsersFixtures::PASSWORD),
                'json' => $this->getUserData('test_email@test.test')
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $response = $client->request(Request::METHOD_POST, self::URL_API, [
                'headers' => ['Content-Type' => 'application/json'],
                'auth_bearer' => $this->getToken(UsersFixtures::EMAIL_ADMIN, UsersFixtures::PASSWORD),
                'json' => $this->getUserData('test_email@test.test')
            ]
        );

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    private function getUserData(string $email = null): array
    {
        $faker = Factory::create('fr_FR');

        return [
            "email" => $email ?? $faker->email,
            "firstName" => $faker->firstName,
            "lastName" => $faker->lastName,
            "phone" => $faker->phoneNumber,
            "age" => $faker->numberBetween(18, 60),
            "type" => $faker->name,
            "password" => $faker->city
        ];
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return string
     */
    private function getToken(string $email, string $password): string
    {
        $client = self::createClient();

        return $client->request(Request::METHOD_POST, self::URL_API_AUTH, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => $email,
                'password' => $password,
            ],
        ])->toArray()['token'];
    }
}