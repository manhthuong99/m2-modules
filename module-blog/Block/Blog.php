<?php

namespace SM\HelloWorld\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use SM\HelloWorld\Model\Tag;
use SM\HelloWorld\Model\ResourceModel\Categories\CollectionFactory as CategoryCollection;
use SM\HelloWorld\Model\ResourceModel\Blog\CollectionFactory as BlogCollection;
use SM\HelloWorld\Model\ResourceModel\Tag\CollectionFactory as TagCollection;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;
use Magento\Framework\Pricing\Helper\Data as priceHelper;

class Blog extends Template

{
    public $_storeManager;
    private $blogCollection;
    const FORMAT_DATE = 'Y-m-d H:i:s';
    private $dateTimeFactory;
    protected $priceHepler;
    protected $_productCollectionFactory;

    protected $_productVisibility;
    private $productVisibility;

    public function __construct(Context $context,
                                CategoryCollection $categoryCollection,
                                BlogCollection $blogCollection,
                                TagCollection $tagCollection,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                \Magento\Framework\View\Result\PageFactory $pageFactory,
                                DateTimeFactory $dateTimeFactory,
                                priceHelper $priceHepler,
                                \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
                                \Magento\Catalog\Model\Product\Visibility $productVisibility,
                                array $data = [])
    {
        parent::__construct($context, $data);
        $this->categoryCollection = $categoryCollection;
        $this->blogCollection = $blogCollection;
        $this->tagCollection = $tagCollection;
        $this->_storeManager = $storeManager;
        $this->pageFactory = $pageFactory;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->priceHepler = $priceHepler;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productVisibility = $productVisibility;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->loadBlogs()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'test.news.pager'
            )->setAvailableLimit(array(5 => 5, 10 => 10, 15 => 15))->setShowPerPage(true)->setCollection(
                $this->loadBlogs()
            );
            $this->setChild('pager', $pager);
            $this->loadBlogs()->load();
        }
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function loadCategories()
    {
        $collection = $this->categoryCollection->create();
        return $collection->addFieldtoFilter("parent_id", ['neq' => 0])->getData();
    }

    public function loadBlogs()
    {
        $dateToday = $this->getCurrentDate();
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
        $id = ($this->getRequest()->getParam('categoryid')) ? $this->getRequest()->getParam('categoryid') : null;
        $search = ($this->getRequest()->getParam('name')) ? $this->getRequest()->getParam('name') : '';
        $collection = $this->blogCollection->create();
        $categoryId = $this->getRequest()->getParam("categoryId");
        if ($categoryId) {
            $listCategoryId = $this->findChildren($categoryId);
            $collection->getSelect()->join(
                "su1_blog_cate",
                "main_table.blog_id = su1_blog_cate.blog_id"
            )->where(
                'su1_blog_cate.categories_id IN (?)',
                $listCategoryId
            );
            $collection->getSelect()->group('main_table.blog_id');
        }
        else {
            $collection->setOrder('blog_id');
        }
        $collection->addFieldToFilter('status', 1);
        $collection->addFieldToFilter('publish_date_from', array('lt' => $dateToday));
        $collection->addFieldToFilter('publish_date_to', array('gt' => $dateToday));
        $collection->addFieldToFilter('name', array('like' => '%' . $search . '%'));
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        return $collection;

    }

    public function loadTags()
    {
        $collection = $this->tagCollection->create();
        return $collection->getData();
    }

    public function loadDetailBlog()
    {
        $id = $this->getRequest()->getParam('id');
        $collection = $this->blogCollection->create()->getItemById($id);
        return $collection->getData();
    }

    public function getCurrentDate()
    {
        $dateModel = $this->dateTimeFactory->create();
        return $dateModel->gmtDate(self::FORMAT_DATE);
    }

    public function loadProducts()
    {
        $ar = [];
        foreach ($this->getRelatedProducts() as $product) {
            array_push($ar, $product['product_id']);
        }
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addFieldToFilter('entity_id', array('in' => $ar));
        return $collection;
    }

    public function getRelatedProducts()
    {
        $id = $this->getRequest()->getParam('id');
        $collection = $this->blogCollection->create();
        return $collection->getProducts($id);
    }

    /**
     * @param null $id
     * @return array
     */
    public function findChildren($id = null)
    {
        $list = [];
        if ($id != null) {
            $children = $this->loadCategories();
            $list = $this->recursiveFindChildren($id, $children);
            $list[] = $id;
        }
        return $list;
    }

    /**
     * @param $superId
     * @param $children
     * @return array
     */
    private function recursiveFindChildren($parentId, $children)
    {
        $list = [];
        foreach ($children as $child) {
            if ($child['parent_id'] == $parentId) {
                foreach ($this->recursiveFindChildren($child['categories_id'], $children) as $id) {
                    $list[] = $id;
                }
                $list[] = $child['categories_id'];
            }
        }
        return $list;
    }

}