<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\HelloWorld\Model\ResourceModel\Categories;

use SM\HelloWorld\Api\Data\CategoriesInterface;
use SM\HelloWorld\Helper\Categories;
use \SM\HelloWorld\Model\ResourceModel\AbstractCategoriesCollection;
use Magento\Framework\App\ObjectManager;

class Collection extends AbstractCategoriesCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'categories_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'categories';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'categories';


    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\SM\HelloWorld\Model\Categories::class, \SM\HelloWorld\Model\ResourceModel\Categories::class);
        $this->_map['fields']['categories_id'] = 'categories_id';
    }

    /**
     * Returns pairs block_id - title
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('categories_id', 'name');
    }

    /**
     * @return int
     */
    public function getRootId()
    {
        $objectManager = ObjectManager::getInstance();
        /* @var Categories $helper */
        $helper = $objectManager->get('\SM\HelloWorld\Helper\Categories');

        return $helper->getRootCategories()->getId();
    }

}
