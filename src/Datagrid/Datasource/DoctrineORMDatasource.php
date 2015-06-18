<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Datasource;

use Doctrine\ORM\QueryBuilder;
use Wanjee\Shuwee\AdminBundle\Datagrid\Paginator\DoctrineORMPaginator;

/**
 * Class DoctrineORMDatasource
 * @package Shuwee\AdminBundle\Datagrid\Datasource
 */
class DoctrineORMDatasource extends AbstractDatasource
{
    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @return \Wanjee\Shuwee\AdminBundle\Datagrid\Paginator\DoctrineORMPaginator
     */
    public function getPaginator()
    {
        $this->initialize();

        return $this->paginator;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $this->initialize();

        return $this->iterator;
    }

    /**
     * Load the collection
     *
     */
    protected function initialize()
    {
        if ($this->initialized) {
            return;
        }

        // Handle pagination
        if (isset($this->limitPerPage)) {
            $paginator = new DoctrineORMPaginator($this->queryBuilder->getQuery());

            $paginator
                ->setLimitPerPage($this->limitPerPage)
                ->setPage($this->page);

            $this->iterator = $paginator->getIterator();
            $this->paginator = $paginator;
        }
        else {
            $items = $this->queryBuilder->getQuery()->getResult();
            $this->iterator = new \ArrayIterator($items);
            $this->paginator = null;
        }

        $this->initialized = true;
    }
}
