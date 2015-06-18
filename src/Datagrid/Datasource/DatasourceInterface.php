<?php

namespace Wanjee\Shuwee\AdminBundle\Datagrid\Datasource;

/**
 * Interface DatasourceInterface
 * @package Shuwee\AdminBundle\Datagrid\Datasource
 */
interface DatasourceInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param int $page
     */
    public function setPage($page);

    /**
     * @param string $field
     * @param string $direction
     * @throws \InvalidArgumentException
     */
    public function setSort($field, $direction);
}