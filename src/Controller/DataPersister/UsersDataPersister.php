<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UsersDataPersister
 */
final class UsersDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var $entityManager
     */
    private $entityManager;

    /**
     * UsersDataPersister constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager)
    {
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Users;
    }

    public function persist($data, array $context = [])
    {
        $data->setPassword($this->encoder->encodePassword($data, $data->getPassword()));

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        // call your persistence layer to delete $data
    }
}