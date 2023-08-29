<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\HelloWorld\Controller\Adminhtml\Blog;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;
class Delete extends \SM\HelloWorld\Controller\Adminhtml\Blog implements HttpPostActionInterface
{
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    protected $cacheTypeList;
    protected $cacheFrontendPool;
    public function __construct(
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry)
    {
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        parent::__construct($context, $coreRegistry);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('blog_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\SM\HelloWorld\Model\Blog::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the blog.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('sumup/blog/edit', ['blog_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a blog to delete.'));
        // go to grid
        $this->flushCache();
        return $resultRedirect->setPath('*/*/');
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
