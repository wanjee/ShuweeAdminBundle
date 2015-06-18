<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Paginator;


/**
 * Class AbstractPaginator
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Paginator
 */
abstract class AbstractPaginator implements PaginatorInterface
{

    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var int
     */
    protected $limitPerPage = 0;


    /**
     * Set the current page to display.
     *
     * @param int $page
     * @return PaginatorInterface
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the current page to display.
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set the number of items to display per page.
     *
     * @param int $limitPerPage
     * @return PaginatorInterface
     */
    public function setLimitPerPage($limitPerPage)
    {
        $this->limitPerPage = $limitPerPage;
        return $this;
    }

    /**
     * Get the number of items to display per page.
     *
     * @return int
     */
    public function getLimitPerPage()
    {
        return $this->limitPerPage;
    }

    /**
     * Get the number of available pages.
     *
     * @return int
     */
    public function getPageCount()
    {
        // pagination is disabled
        if ($this->limitPerPage < 1) {
            return 1;
        }

        // Not enough items to paginate
        if ($this->getCount() < $this->limitPerPage) {
            return 1;
        }

        // get the number of page full of items
        $pages = intval($this->getCount() / $this->getLimitPerPage());

        // we need to add one page if we have remaining items
        $rest = $this->getCount() % $this->getLimitPerPage();

        return ($rest == 0 ? $pages : $pages + 1);
    }

    /**
     * @return int
     */
    protected function getOffset()
    {
        // Calculate offset
        $offset = ($this->page - 1) * $this->limitPerPage;

        // Avoid negative offset, just in case
        return max(0, $offset);
    }
}