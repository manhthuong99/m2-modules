<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\HelloWorld\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use SM\HelloWorld\Api\Data\CategoriesInterface;

class Categories extends AbstractModel implements CategoriesInterface, IdentityInterface
{
    /**
     * CMS block cache tag
     */
    const CACHE_TAG = 'Category_b';

    /**#@+
     * Block's statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**#@-*/

    /**
     * Tree root id
     */
    const TREE_ROOT_ID = 1;

    /**#@-*/
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'Category_Category';

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\SM\HelloWorld\Model\ResourceModel\Categories::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getName()];
    }

    /**
     * Retrieve Category id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::CATEGORIES_ID);
    }

    /**
     * Retrieve Category name
     *
     * @return string
     */
    public function getName()
    {
        return (string)$this->getData(self::NAME);
    }

    /**
     * Retrieve status
     *
     * @return string
     */
    public function getStatus()
    {
        return (bool)$this->getData(self::STATUS);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return Categories
     */
    public function setId($id)
    {
        return $this->setData(self::CATEGORIES_ID, $id);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Categories
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set status
     *
     * @param bool|int $status
     * @return Categories
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Prepare Categories's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => _('Enabled'), self::STATUS_DISABLED => _('Disabled')];
    }

    /**
     * Retrieve super id
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->getData(self::PARENT_ID);
    }

    /**
     * Set super id
     *
     * @param int $parentId
     * @return Categories
     */
    public function setParentId($parentId)
    {
        return $this->setData(self::PARENT_ID, $parentId);
    }


}