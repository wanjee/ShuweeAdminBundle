<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Paginator;


use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

/**
 * Class DoctrineORMPaginator
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Paginator
 */
class DoctrineORMPaginator extends AbstractPaginator
{
    /**
     * @var \Doctrine\ORM\Tools\Pagination\Paginator
     */
    private $doctrinePaginator;

    /**
     * Initialize Doctrine ORM paginator
     */
    function __construct(AbstractQuery $query)
    {
        $this->doctrinePaginator = new DoctrinePaginator($query);
    }

    /**
     * Set the current page to display.
     *
     * FIXME This has to be called after setLimitPerPage otherwise offset is not calculated properly
     *
     * @param int $page
     * @return PaginatorInterface
     */
    public function setPage($page)
    {
        $this->page = max(0, $page);

        $this->doctrinePaginator->getQuery()->setFirstResult($this->getOffset());

        return $this;
    }

    /**
     * Set the number of items to display per page.
     *
     * @param int $limitPerPage
     * @return PaginatorInterface
     */
    public function setLimitPerPage($limitPerPage)
    {
        $this->limitPerPage = max(0, $limitPerPage);

        $this->doctrinePaginator->getQuery()->setMaxResults($limitPerPage);

        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->doctrinePaginator->count();
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return $this->doctrinePaginator->getIterator();
    }
}