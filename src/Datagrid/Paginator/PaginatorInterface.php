<?php
/**
 * Created by PhpStorm.
 * User: seb
 * Date: 18/06/15
 * Time: 20:35
 */

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Paginator;


/**
 * Interface PaginatorInterface
 * @package Wanjee\Shuwee\AdminBundle\Datagrid\Paginator
 */
interface PaginatorInterface
{
    /**
     * Set the current page to display.
     *
     * @param int $page
     * @return PaginatorInterface
     */
    public function setPage($page);

    /**
     * Get the current page to display.
     *
     * @return int
     */
    public function getPage();

    /**
     * Set the number of items to display per page.
     *
     * @param int $limitPerPage
     * @return PaginatorInterface
     */
    public function setLimitPerPage($limitPerPage);

    /**
     * Get the number of items to display per page.
     *
     * @return int
     */
    public function getLimitPerPage();

    /**
     * Get the number of page available.
     *
     * @return int
     */
    public function getPageCount();

    /**
     * Get the total number of items in the list.
     *
     * @return int
     */
    public function getCount();
}