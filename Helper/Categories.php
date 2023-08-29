<?php

namespace SM\HelloWorld\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use SM\HelloWorld\Model\ResourceModel\Categories\CollectionFactory;
use Magento\Framework\App\PageCache\Version;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class Categories extends AbstractHelper
{
    /**
     * @var CollectionFactory
     */
    private $categoryCollectionFactory;
    protected $cacheTypeList;
    protected $cacheFrontendPool;
    /**
     * @param TypeListInterface $cacheTypeList
     * @param Pool $cacheFrontendPool
     * @param CollectionFactory $categoryCollectionFactory
     * @param Context                         $context
     */
    public function __construct(
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool,
        CollectionFactory $categoryCollectionFactory,
        Context $context
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        parent::__construct($context);
    }

    /**
     * @return \SM\HelloWorld\Model\Categories|false
     */
    public function getRootCategories()
    {
        $category   = false;
        $collection = $this->categoryCollectionFactory->create()
            ->addFieldToFilter('parent_id', 0);

        if ($collection->count()) {
            $category = $collection->getFirstItem();
        }

        return $category;
    }
    public function flushCache(Version $subject)
    {
        $_types = [
            'config',
            'layout',
            'block_html',
            'collections',
            'reflection',
            'db_ddl',
            'eav',
            'config_integration',
            'config_integration_api',
            'full_page',
            'translate',
            'config_webservice'
        ];

        foreach ($_types as $type) {
            $this->cacheTypeList->cleanType($type);
        }
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}