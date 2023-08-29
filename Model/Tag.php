<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\HelloWorld\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use SM\HelloWorld\Api\Data\TagInterface;

class Tag extends AbstractModel implements TagInterface, IdentityInterface
{
    /**
     * CMS Category cache tag
     */
    const CACHE_TAG = 'cms_b';

    /**#@+
     * Category's statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**#@-*/

    /**#@-*/
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'tag';

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\SM\HelloWorld\Model\ResourceModel\Tag::class);
    }

    /**
     * Prevent Categorys recursion
     *
     * @return AbstractModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
//        if ($this->hasDataChanges()) {
//            $this->setUpdateTime(null);
//        }

        $needle = 'tag_id="' . $this->getId() . '"';
        if (false == strstr($this->getName(), (string)$needle)) {
            return parent::beforeSave();
        }
        throw new \Magento\Framework\Exception\LocalizedException(
            __('Make sure that static Tag content does not reference the Category itself.')
        );
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getTag_id()];
    }

    /**
     * Retrieve Category id
     *
     * @return int
     */
    public function getTag_id()
    {
        return $this->getData(self::TAG_ID);
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
     * Set Tag_id
     *
     * @param int $tag_id
     * @return TagInterface
     */
    public function setTag_id($tag_id)
    {
        return $this->setData(self::TAG_ID, $tag_id);
    }

    /**
     * Set $name
     *
     * @param string $name
     * @return TagInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

}
