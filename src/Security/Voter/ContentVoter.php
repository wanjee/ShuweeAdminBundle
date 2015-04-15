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
     * Crud view action
     */
    const VIEW = 'view';
    /**
     * Crud create action
     */
    const CREATE = 'create';
    /**
     * Crud edit action
     */
    const EDIT = 'edit';
    /**
     * Crud delete action
     */
    const DELETE = 'delete';

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
                self::VIEW,
                self::CREATE,
                self::EDIT,
                self::DELETE,
            )
        );
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Entity\Post';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @var mixed $post
     */
    public function vote(TokenInterface $token, $entity, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass(get_class($entity))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // Simplify management by allowing only one attribute at a time
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed for VIEW or EDIT'
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

        switch ($attribute) {
            case self::VIEW:
                // the data object could have for example a method isPrivate()
                // which checks the Boolean attribute $private
                if (!$post->isPrivate()) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;

            case self::EDIT:
                // we assume that our data object has a method getOwner() to
                // get the current owner user entity for this data object
                if ($user->getId() === $post->getOwner()->getId()) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}