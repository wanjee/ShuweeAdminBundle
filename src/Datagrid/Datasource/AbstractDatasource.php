<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Datasource;

/**
 * Class AbstractDatasource
 * @package \Wanjee\Shuwee\AdminBundle\Datagrid\Datasource
 */
abstract class AbstractDatasource implements DatasourceInterface
{
    /**
     * @var \Traversable
     */
    protected $iterator;

    /**
     * @var \Wanjee\Shuwee\AdminBundle\Datagrid\Paginator\PaginatorInterface
     */
    protected $paginator;

    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var int
     */
    protected $limitPerPage = 10;

    /**
     * @var string
     */
    protected $sortField;

    /**
     * @var string
     */
    protected $sortDirection;

    /**
     * @param int $page
     *
     * @return DatasourceInterface
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param string $field
     * @param string $direction
     * @throws \InvalidArgumentException
     */
    public function setSort($field, $direction)
    {
        if (!in_array($direction, array('asc', 'desc'))) {
            throw new \InvalidArgumentException('Datasource->setSort(): Argument "direction" must be "asc" or "desc".');
        }

        $this->sortField = $field;
        $this->sortDirection = $direction;
    }

    /**
     * @return string
     */
    public function getSortDirection()
    {
        return $this->sortDirection;
    }

    /**
     * @return string
     */
    public function getSortField()
    {
        return $this->sortField;
    }

    /**
     * @return int
     */
    public function count()
    {
        $this->initialize();

        return count($this->iterator);
    }

    /**
     * This method should populated the iterator and variables
     */
    abstract protected function initialize();
}