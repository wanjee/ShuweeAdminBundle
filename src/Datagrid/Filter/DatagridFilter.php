<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Filter;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DatagridFilter
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Filter
 */
class DatagridFilter implements DatagridFilterInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type\DatagridFilterTypeInterface
     */
    protected $type;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var mixed
     */
    protected $value;


    /**
     * @param string $name
     * @param \Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type\DatagridFilterTypeInterface $type
     * @param array $options
     */
    public function __construct($name, $type, $options = [])
    {
        $this->name = $name;
        $this->type = $type;
        $this->options = $options + [
                'label' => $this->name,
            ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Datagrid\Filter\Type\DatagridFilterTypeInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        if (!is_null($this->value)) {
            return $this->getType()->formatValue($this->value);
        }

        return null;
    }

    /**
     * @param mixed $value
     *
     * @return DatagridFilter
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $form)
    {
        $form
            ->add(
                $this->name,
                $this->type->getFormType(),
                $this->type->resolveOptions($this->options)
            );
    }

    /**
     * @inheritdoc
     */
    public function getExpression()
    {
        return $this->type->getCriteriaExpression();
    }
}
