<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\HelloWorld\Controller\Adminhtml\Category;

use Magento\Framework\App\Action\HttpPostActionInterface;

class Delete extends \SM\HelloWorld\Controller\Adminhtml\Categories implements HttpPostActionInterface
{
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('categories_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\SM\HelloWorld\Model\Categories::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the category.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('sumup/category/edit', ['categories_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a category to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
