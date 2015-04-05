<?php

namespace Wanjee\Shuwee\AdminBundle\Section;

interface SectionInterface
{
    /**
     * Returns a list of pages to be added in the admin menu as a section.
     * Each page is defined as a controller action: 'ShuweeExampleBundle:Controller:
     * Each page is also associated a label (its title)
     * This can be a nested array (for children pages)
     * @return mixed
     */
    public function getSectionItems();
}