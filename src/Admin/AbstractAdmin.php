<?php

namespace Wanjee\Shuwee\AdminBundle\Admin;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface;

/**
 * Class AbstractAdmin
 * @package Wanjee\Shuwee\AdminBundle\Admin
 */
abstract class AbstractAdmin implements AdminInterface
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
        $this->attachActions($datagrid);
        $this->attachFilters($datagrid);
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
    final public function configureOptions(OptionsResolver $resolver) {
        $resolver
            ->setDefaults(
                [
                    'label' => ucfirst($this->getAlias()),
                    'description' => null,
                    'preview_url_callback' => null,
                    'menu_section' => 'menu.default.section.label',
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
     * @param mixed $default
     */
    final public function getOption($name, $default = null)
    {
        if ($this->hasOption($name)) {
            return $this->options[$name];
        }
        return $default;
    }

    /**
     * @return array Options
     */
    public function getOptions() {
        return [];
    }

    /**
     * @return array Options
     */
    public function getDatagridOptions() {
        return [];
    }

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface $datagrid
     */
    public function attachFields(DatagridInterface $datagrid) {}

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface $datagrid
     */
    public function attachFilters(DatagridInterface $datagrid) {}

    /**
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\DatagridInterface $datagrid
     */
    public function attachActions(DatagridInterface $datagrid) {}

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
    public function preUpdate($entity) {}

    /**
     * {@inheritdoc}
     */
    public function postUpdate($entity) {}

    /**
     * {@inheritdoc}
     */
    public function prePersist($entity) {}

    /**
     * {@inheritdoc}
     */
    public function postPersist($entity) {}

    /**
     * {@inheritdoc}
     */
    public function preRemove($entity) {}

    /**
     * {@inheritdoc}
     */
    public function postRemove($entity) {}

    /**
     * {@inheritdoc}
     */
    public function preCreateFormRender($form) {}
}
