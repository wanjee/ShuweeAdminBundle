<?php

namespace Wanjee\Shuwee\AdminBundle\Security;

use Doctrine\ORM\EntityManager;
use Wanjee\Shuwee\AdminBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class UserManager
 * @package Wanjee\Shuwee\AdminBundle\Security
 */
class UserManager
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;


    /**
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, EntityManager $em)
    {
        $this->encoderFactory = $encoderFactory;
        $this->em = $em;
    }

    /**
     * Check if a username is already in use
     *
     * @param string $userName
     * @return bool
     */
    public function usernameExists($userName)
    {
        $existing = $this->em->getRepository(User::class)->findOneBy(
            [
                'username' => $userName,
            ]
        );

        return (bool) $existing;
    }

    /**
     * @param string $userName
     * @param string $password
     * @param array $roles
     * @return \Wanjee\Shuwee\AdminBundle\Entity\User
     */
    public function createUser($userName, $password, array $roles)
    {
        $user = new User();

        $encoder = $this->encoderFactory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($password, $user->getSalt());

        $user
            ->setUsername($userName)
            ->setPassword($encodedPassword)
            ->setRoles($roles);

        $this->em->persist($user);
        $this->em->flush($user);
    }
}
