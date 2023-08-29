<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\HelloWorld\Model;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use SM\HelloWorld\Api\Data\TagInterface;
use SM\HelloWorld\Model\Blog\FileInfo;
use Magento\Framework\DataObject\IdentityInterface;
use SM\HelloWorld\Api\Data\CategoriesInterface;
use SM\HelloWorld\Api\Data\BlogInterface;
//use SM\HelloWorld\Api\CategoryRepositoryInterface;
class Blog extends \Magento\Framework\Model\AbstractModel implements IdentityInterface,\SM\HelloWorld\Api\Data\BlogInterface
{
    const ENTITY    = 'blog';
    const CACHE_TAG = 'blog';
    protected $_storeManager;
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    protected $url;

    protected $productCollectionFactory;

    private $categoryRepository;

    public function __construct(
        ProductCollectionFactory $productCollectionFactory,
        StoreManagerInterface $storeManager,
        Context $context,
        Registry $registry
    )
    {
        $this->_storeManager = $storeManager;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $registry);
    }

    protected function _construct()
    {
        $this->_init('SM\HelloWorld\Model\ResourceModel\Blog');
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }


    /**
     * Get StoreManagerInterface instance
     *
     * @return StoreManagerInterface
     */
    private function _getStoreManager()
    {
        if ($this->_storeManager === null) {
            $this->_storeManager = ObjectManager::getInstance()->get(StoreManagerInterface::class);
        }
        return $this->_storeManager;
    }
    /**
    //     * {@inheritdoc}
    //     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    public function getId() {
        return $this->getData(self::BLOG_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($value)
    {
        return $this->setData(self::STATUS, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($value)
    {
        return $this->setData(self::NAME, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getShortDescription()
    {
        return $this->getData(self::SHORT_DESCRIPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setShortDescription($value)
    {
        return $this->setData(self::SHORT_DESCRIPTION, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($value)
    {
        return $this->setData(self::DESCRIPTION, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrlKey()
    {
        return $this->getData(self::URL_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setUrlKey($value)
    {
        return $this->setData(self::URL_KEY, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getGallery()
    {
        return $this->getData(self::GALLERY);
    }

    /**
     * {@inheritdoc}
     */
    public function setGallery($value)
    {
        return $this->setData(self::GALLERY, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishDateFrom()
    {
        return $this->getData(self::PUBLISH_DATE_FROM);
    }

    /**
     * {@inheritdoc}
     */
    public function setPublishDateFrom($value)
    {
        return $this->setData(self::PUBLISH_DATE_FROM, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishDateTo()
    {
        return $this->getData(self::PUBLISH_DATE_TO);
    }

    /**
     * {@inheritdoc}
     */
    public function setPublishDateTo($value)
    {
        return $this->setData(self::PUBLISH_DATE_TO, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function setCategoryIds(array $value)
    {
        return $this->setData(self::CATEGORY_IDS, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function setTagIds(array $value)
    {
        return $this->setData(self::TAG_IDS, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function setProductIds(array $value)
    {
        return $this->setData(self::PRODUCT_IDS, $value);
    }

    /**
     * @return ResourceModel\Blog\Collection
     */
    public function getCategories()
    {
        $ids   = $this->getCategoryIds();
        $ids[] = 0;

        $collection = $this->categoryRepository->getCollection()
            ->addAttributeToSelect(['*'])
            ->addFieldToFilter(CategoriesInterface::CATEGORIES_ID, $ids);

        return $collection;
    }
    /**
     * {@inheritdoc}
     */
    public function getCategoryIds()
    {
        return $this->getData(self::CATEGORY_IDS);
    }

    /**
     * @return AbstractDb|AbstractCollection
     */
    public function getTags()
    {
        $ids   = $this->getTagIds();
        $ids[] = 0;

        $collection = $this->tagRepository->getCollection()
            ->addFieldToFilter(TagInterface::TAG_ID, $ids);

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getTagIds()
    {
        return $this->getData(self::TAG_IDS);
    }

    /**
     * @return mixed|Collection
     */
    public function getRelatedProducts()
    {
        $ids = $this->getProductIds();
        $url = ObjectManager::getInstance()
            ->get('Magento\Framework\UrlInterface');
        if (strpos($url->getCurrentUrl(), 'rest/all/V1/blog') > 0) {
            return $ids;
        }

        $ids[] = 0;

        return $this->productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', $ids);
    }

    /**
     * {@inheritdoc}
     */
    public function getProductIds()
    {
        return $this->getData(self::PRODUCT_IDS);
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getThumbnailUrl()
    {
        return $this->config->getMediaUrl($this->getGallery());
    }

//    /**
//     * @param bool $useSid
//     *
//     * @return string
//     */
//    public function getUrl($useSid = true)
//    {
//        return $this->url->getPostUrl($this, $useSid);
//    }

    /**
     * Prepare category's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => _('Enabled'), self::STATUS_DISABLED => _('Disabled')];
    }
}