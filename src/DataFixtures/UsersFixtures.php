<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UsersFixtures
 */
class UsersFixtures extends Fixture
{
    const PASSWORD = 'fake_password';
    const EMAIL_ADMIN = 'email_admin@test.test';
    const EMAIL = 'email@test.test';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UsersFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $roles = [
            self::EMAIL_ADMIN => ['ROLE_ADMIN'],
            self::EMAIL => []
        ];

        foreach ($roles as $key => $value) {
            $adminUser = (new Users())
                ->setEmail($key)
                ->setAge($faker->numberBetween(18, 60))
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setPhone($faker->phoneNumber)
                ->setRoles($value)
                ->setType('fake_type');

            $adminUser->setPassword($this->encoder->encodePassword($adminUser, self::PASSWORD));
            $manager->persist($adminUser);
        }


        $manager->flush();
    }
}
