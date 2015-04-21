<?php

namespace Wanjee\Shuwee\AdminBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Wanjee\Shuwee\AdminBundle\Manager\AdminManager;

/**
 * Class ContentVoter
 * @package Wanjee\Shuwee\AdminBundle\Security\Voter
 */
class ContentVoter implements VoterInterface
{
    /**
     * Crud list action
     */
    const LIST_CONTENT = 'list';
    /**
     * Crud view action
     */
    const VIEW_CONTENT = 'view';
    /**
     * Crud create action
     */
    const CREATE_CONTENT = 'create';
    /**
     * Crud update action
     */
    const UPDATE_CONTENT = 'update';
    /**
     * Crud delete action
     */
    const DELETE_CONTENT = 'delete';

    /**
     * @var \Wanjee\Shuwee\AdminBundle\Manager\AdminManager
     */
    private $adminManager;

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Manager\AdminManager $adminManager
     */
    public function __construct(AdminManager $adminManager)
    {
        $this->adminManager = $adminManager;
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function supportsAttribute($attribute)
    {
        return in_array(
            $attribute,
            array(
                self::LIST_CONTENT,
                self::VIEW_CONTENT,
                self::CREATE_CONTENT,
                self::UPDATE_CONTENT,
                self::DELETE_CONTENT,
            )
        );
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        // check is done using $this::getSupportedAdmin()
        return true;
    }


    /**
     * @param $class
     * @return bool
     */
    public function getSupportedAdmin($class)
    {
        $admins = $this->adminManager->getAdmins();

        /** @var \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface $admin */
        foreach ($admins as $admin) {
            $supportedClass = $admin->getEntityClass();

            if ($supportedClass === $class || is_subclass_of($class, $supportedClass)) {
                return $admin;
            }
        }

        return null;
    }

    /**
     * Returns the vote for the given parameters.
     *
     * This method must return one of the following constants:
     * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
     *
     * @param TokenInterface $token      A TokenInterface instance
     * @param object $object     The object to secure
     * @param array $attributes An array of attributes associated with the method being invoked
     *
     * @return integer either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function vote(TokenInterface $token, $entity, array $attributes)
    {
        /** @var \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface $admin */
        $admin = $this->getSupportedAdmin(get_class($entity));

        if (!$admin) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // Simplify management by allowing only one attribute at a time
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed for VIEW or UPDATE'
            );
        }

        // set the attribute to check against
        $attribute = reset($attributes);

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // get current logged in user
        $user = $token->getUser();

        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        // delegate decision to admin
        return $admin->isGranted($user, $attribute, $entity);
    }
}