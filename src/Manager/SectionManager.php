<?php

namespace Wanjee\Shuwee\AdminBundle\Manager;

use Wanjee\Shuwee\AdminBundle\Section\SectionInterface;

class SectionManager
{
    /**
     * @var array list of admin services
     */
    private $sections = array();

    /**
     * @param string $alias
     */
    public function registerSection($alias, SectionInterface $section)
    {
        $section->setAlias($alias);

        $this->sections[$alias] = $section;
    }

    /**
     * @param $alias
     * @return \Wanjee\Shuwee\AdminBundle\Admin\AdminInterface
     * @throws \InvalidArgumentException
     */
    public function getSection($alias)
    {
        if (!array_key_exists($alias, $this->sections)) {
            throw new \InvalidArgumentException(sprintf('The section %s has not been registered with the Shuwee Admin bundle', $alias));
        }
        return $this->sections[$alias];
    }

    /**
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }
}