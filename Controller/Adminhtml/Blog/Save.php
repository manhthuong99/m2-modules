<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\HelloWorld\Controller\Adminhtml\Blog;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use SM\HelloWorld\Model\Blog;
use SM\HelloWorld\Model\BlogFactory;
use SM\HelloWorld\Model\Blog\ImageUploader;
use SM\HelloWorld\Api\Data\BlogInterface;
use Magento\Framework\App\PageCache\Version;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;
use SM\HelloWorld\Model\ResourceModel\Blog\CollectionFactory as BlogCollection;

/**
 * Save CMS blog action.
 */
class Save extends \SM\HelloWorld\Controller\Adminhtml\Blog implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var BlogFactory
     */
    private $BlogFactory;
    private $blogCollection;
    protected $imageUploader;
    protected $cacheTypeList;
    protected $cacheFrontendPool;

    /**
     * @param BlogCollection $blogCollection
     * @param TypeListInterface $cacheTypeList
     * @param Pool $cacheFrontendPool
     * @param Context $context
     * @param Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     * @param ImageUploader $imageUploader
     * @param BlogFactory|null $BlogFactory
     */
    public function __construct(
        BlogCollection $blogCollection,
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool,
        Context $context,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        ImageUploader $imageUploader,
        BlogFactory $BlogFactory = null

    )
    {
        $this->blogCollection = $blogCollection;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        $this->dataPersistor = $dataPersistor;
        $this->BlogFactory = $BlogFactory
            ?: ObjectManager::getInstance()->get(BlogFactory::class);
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute()
    {
        /* @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = Blog::STATUS_ENABLED;
            }
            if (empty($data['blog_id'])) {
                $data['blog_id'] = null;
                if ($this->getRequest()->getPost('name')) {
                    $collection = $this->blogCollection->create()
                        ->addFieldToFilter('name', $this->getRequest()->getPost('name'));
                    if (count($collection)) {
                        $this->messageManager->addErrorMessage(__('This blog name already exists.'));
                        return $resultRedirect->setPath('*/*/edit');
                    }
                }
            }

            /* @var Blog $model */
            $model = $this->BlogFactory->create();
            $id = $this->getRequest()->getParam('blog_id');
            if ($id) {
                try {
//                    $model = $this->blogRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This blog no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $imgName = $this->getRequest()->getParam('gallery');
                if (isset($imgName) == null) {
                    $data['gallery'] = 'vietnam.jpg';

                } else  $model->setData('gallery', $imgName[0]['name']);
            }
            $productIds = $this->getRequest()->getParam("blog_form_product_listing");
            if ($productIds != null) {
                foreach ($productIds as $productId) {

                    $products[] = $productId['entity_id'];
                }
                $data['product_id'] = $products;
            }
            if ($this->getRequest()->getParam('url_key')==null){
                $data['url_key']=$this->createUrlKey($this->getRequest()->getParam('name'));
            }
            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the blog.'));
                $this->dataPersistor->clear('blog_blog');
                return $this->processBlogReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the blog.'));
            }

            $this->dataPersistor->set('blog_blog', $data);
            $this->flushCache();
            return $resultRedirect->setPath('sumup/blog/edit', ['blog_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    private function processBlogReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';

        if ($redirect === 'continue') {
            $resultRedirect->setPath('*/*/edit', ['blog_id' => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } elseif ($redirect === 'duplicate') {
            $duplicateModel = $this->BlogFactory->create(['data' => $data]);
            $duplicateModel->setId(null);
            $duplicateModel->setStatus(Blog::STATUS_DISABLED);
            $id = $duplicateModel->getId();
            $this->messageManager->addSuccessMessage(__('You duplicated the blog.'));
            $this->dataPersistor->set('blog_blog', $data);
            $resultRedirect->setPath('*/*/edit', ['blog_id' => $id]);
        }
        return $resultRedirect;
    }


    public function createUrlKey($blogName)
    {
        $url = preg_replace('#[^0-9a-z]+#i', '-', $blogName);
        $urlKey = strtolower($url);
        $isUnique = $this->checkUrlKeyDuplicates($urlKey);
        if ($isUnique) {
            return $urlKey;
        } else {
            return $urlKey . '-' . time();
        }
    }

    private function checkUrlKeyDuplicates($urlKey)
    {
        $urlKey .= '.html';
        $collection = $this->blogCollection->create()
            ->addFieldToFilter('url_key', $urlKey);
        if (count($collection)) {
            return false;
        } else {
            return true;
        }
    }

    public function flushCache()
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
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SM_HelloWorld::blog') || $this->_authorization->isAllowed('SM_HelloWorld::list');
    }
}

