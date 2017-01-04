<?php

namespace Wanjee\Shuwee\AdminBundle\Admin;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface;
use Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type\DatagridFieldTypeCollection;
use Wanjee\Shuwee\AdminBundle\Datagrid\Field\Type\DatagridFieldTypeText;
use Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type\DatagridFilterTypeText;
use Wanjee\Shuwee\AdminBundle\Entity\User;
use Wanjee\Shuwee\AdminBundle\Form\UserType;

/**
 * Class AbstractUserAdmin
 *
 * @package AppBundle\Admin
 */
abstract class AbstractUserAdmin extends Admin
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function setEncoderFactory(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Return the main admin form for this content.
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getForm()
    {
        // Must return a fully qualified class name
        return UserType::class;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return User::class;
    }

    /**
     * @return array Options
     */
    public function getOptions() {
        return [
            'label' => '{0} Admin users|{1} Admin user|]1,Inf] Admin user',
            'description' => 'User that will manage content',
            'menu_section' => 'People',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getDatagridOptions()
    {
        return [
            'limit_per_page' => 25,
            'default_sort_column' => 'id',
            'default_sort_order' => 'asc',
            'show_actions_column' => true,
        ];
    }


    /**
     * @inheritdoc
     */
    public function attachFields(DatagridInterface $datagrid)
    {
        $datagrid
            ->addField(
                'id',
                DatagridFieldTypeText::class,
                [
                    'label' => '#',
                    'sortable' => true,
                ]
            )
            ->addField(
                'username',
                DatagridFieldTypeText::class,
                [
                    'label' => 'Username',
                    'sortable' => true,
                ]
            )
            ->addField(
                'roles',
                DatagridFieldTypeCollection::class,
                [
                    'label' => 'Roles',
                    'sortable' => true,
                ]
            )
        ;

        return $datagrid;
    }

    /**
     * @inheritdoc
     */
    public function attachFilters(DatagridInterface $datagrid)
    {
        $datagrid
            ->addFilter(
                'username',
                DatagridFilterTypeText::class,
                [
                    'label' => 'Username',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($entity) {
        $this->encodeUserPassword($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($entity) {
        $this->encodeUserPassword($entity);
    }

    /**
     * @param User $user
     */
    private function encodeUserPassword($user) {
        $newPassword = $user->getPlainPassword();

        // password is created or modified, encode the new version
        if (!empty($newPassword)) {
            $encoder = $this->encoderFactory->getEncoder($user);
            $encodedPassword = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());

            $user->setPassword($encodedPassword);
        }
    }
}
