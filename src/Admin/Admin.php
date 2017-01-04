<?php

/**
 *
 */

namespace Wanjee\Shuwee\AdminBundle\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Wanjee\Shuwee\AdminBundle\Datagrid\Action\DatagridCreateAction;
use Wanjee\Shuwee\AdminBundle\Datagrid\Action\DatagridDeleteAction;
use Wanjee\Shuwee\AdminBundle\Datagrid\Action\DatagridUpdateAction;
use Wanjee\Shuwee\AdminBundle\Datagrid\Datagrid;
use Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface;

/**
 * Class Admin
 * @package Wanjee\Shuwee\AdminBundle\Admin
 */
abstract class Admin implements AdminInterface
{
    /**
     * List of global options
     * @var array
     */
    protected $options = [];

    /**
     * Cache for grants
     * @var array
     */
    protected $cacheIsGranted = [];

    /**
     * Store setup state to avoid setting it up several times
     */
    protected $setup = false;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * This function is used to boot up Admin implementation when first used.
     * We do not use __construct as we want the end user to be able to use it
     * without having to thing about calling parent constructor.
     */
    final public function setup()
    {
        if (!$this->setup) {
            // configure our datagrid
            $resolver = new OptionsResolver();
            $this->configureOptions($resolver);
            $this->options = $resolver->resolve($this->getOptions());

            $this->setup = true;
        }
    }

    /**
     * @return string
     */
    final public function getAlias()
    {
        $fqnParts = explode('\\', get_class($this));
        $className = strtolower(end($fqnParts));

        return str_replace('admin', '', $className);
    }

    /**
     * @inheritDoc
     */
    final public function buildDatagrid(DatagridInterface $datagrid)
    {
        $this->attachFields($datagrid);
        $this->attachDefaultActions($datagrid);
        $this->attachActions($datagrid);
        $this->attachFilters($datagrid);
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface $datagrid
     */
    private function attachDefaultActions(DatagridInterface $datagrid)
    {
        $em = $this->entityManager;
        $datagrid
            ->addAction(
                DatagridCreateAction::class,
                'shuwee_admin_'.$this->getAlias().'_index',
                [
                    'label' => 'Create',
                    'btn-style' => 'success',
                    'icon' => 'plus',
                ]
            )
            ->addAction(
                DatagridUpdateAction::class,
                'shuwee_admin_'.$this->getAlias().'_update',
                [
                    'label' => 'Edit',
                    'btn-style' => 'primary',
                    'icon' => 'edit',
                    'path_parameters' => function ($entity) use ($em) {
                        $meta = $em->getClassMetadata(get_class($entity));
                        $accessor = PropertyAccess::createPropertyAccessor();
                        return [
                            'id' => $accessor->getValue($entity, $meta->getSingleIdentifierFieldName())
                        ];
                    },
                ]
            )
            ->addAction(
                DatagridDeleteAction::class,
                'shuwee_admin_'.$this->getAlias().'_delete',
                [
                    'label' => 'Delete',
                    'btn-style' => 'danger',
                    'icon' => 'trash',
                    'path_parameters' => function ($entity) use ($em) {
                        $meta = $em->getClassMetadata(get_class($entity));
                        $accessor = PropertyAccess::createPropertyAccessor();
                        return [
                            'id' => $accessor->getValue($entity, $meta->getSingleIdentifierFieldName())
                        ];
                    },
                ]
            );
    }

    /**
     * Does the current admin implements a previewUrlCallback function
     *
     * @return bool True if current admin implements a previewUrlCallback function
     */
    final public function hasPreviewUrlCallback()
    {
        if (!$this->hasOption('preview_url_callback')) {
            return false;
        }

        return is_callable($this->getOption('preview_url_callback'));
    }

    /**
     * Get preview url using defined callback, if any
     *
     * @param mixed $entity
     *
     * @return string Preview URL for the given entity
     */
    final public function getPreviewUrl($entity)
    {
        if (!$this->hasPreviewUrlCallback()) {
            return null;
        }

        return call_user_func($this->options['preview_url_callback'], $entity);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    final public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'label' => ucfirst($this->getAlias()),
                    'description' => null,
                    'preview_url_callback' => null,
                    'menu_section' => 'content',
                ]
            )
            ->setAllowedTypes('label', ['string'])
            ->setAllowedTypes('description', ['string', 'null'])
            ->setAllowedTypes('preview_url_callback', ['callable', 'null'])
            ->setAllowedTypes('menu_section', ['string']);
    }

    /**
     * @param string $name
     * @return bool
     */
    final public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    final public function getOption($name, $default = null)
    {
        if ($this->hasOption($name)) {
            return $this->options[$name];
        }

        return $default;
    }

    /**
     * @param \Doctrine\Common\Persistence\ManagerRegistry $doctrine
     */
    public function setDoctrine(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
    }

    /**
     * @return array Options
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @return array Options
     */
    public function getDatagridOptions()
    {
        return [];
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface $datagrid
     */
    public function attachFields(DatagridInterface $datagrid)
    {
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface $datagrid
     */
    public function attachFilters(DatagridInterface $datagrid)
    {
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface $datagrid
     */
    public function attachActions(DatagridInterface $datagrid)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function hasAccess(UserInterface $user, $action, $object = null)
    {
        // allow access by default
        return VoterInterface::ACCESS_GRANTED;
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist($entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove($entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function postRemove($entity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function preCreateFormRender($form)
    {
    }
}
