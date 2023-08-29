<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\HelloWorld\Model\ResourceModel\Tag;

use SM\HelloWorld\Api\Data\CategoriesInterface;
use \SM\HelloWorld\Model\ResourceModel\AbstractCategoriesCollection;

class Collection extends AbstractCategoriesCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'tag_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'tag';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'tag';


    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\SM\HelloWorld\Model\Tag::class, \SM\HelloWorld\Model\ResourceModel\Tag::class);
        $this->_map['fields']['tag_id'] = 'tag_id';
    }

    /**
     * Returns pairs block_id - title
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('tag_id', 'name');
    }

}
