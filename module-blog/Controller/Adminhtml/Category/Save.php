<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\HelloWorld\Controller\Adminhtml\Category;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;

//use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use SM\HelloWorld\Model\Categories;
use SM\HelloWorld\Model\CategoriesFactory;

/**
 * Save CMS Categories action.
 */
class Save extends \SM\HelloWorld\Controller\Adminhtml\Categories implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var CategoriesFactory
     */
    private $CategoriesFactory;

//    /**
//     * @var BlockRepositoryInterface
//     */
//    private $blockRepository;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     * @param CategoriesFactory|null $categoriesFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        CategoriesFactory $categoriesFactory = null
    )
    {
        $this->dataPersistor = $dataPersistor;
        $this->CategoriesFactory = $categoriesFactory
            ?: ObjectManager::getInstance()->get(CategoriesFactory::class);
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
                $data['status'] = Categories::STATUS_ENABLED;
            }
            if (empty($data['categories_id'])) {
                $data['categories_id'] = null;
            }

            /* @var $categories $model */
            $model = $this->CategoriesFactory->create();
            $id = $this->getRequest()->getParam('categories_id');
            if ($id) {
                try {
                    //                    $model = $this->blockRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This categories no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $model->save();
//                $this->blockRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the categories.'));
                $this->dataPersistor->clear('categories');
                return $this->processCategoriesReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the categories.'));
            }

            $this->dataPersistor->set('categories', $data);
            return $resultRedirect->setPath('sumup/categories/edit', ['categories_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process and set the block return
     *
     * @param Categories $model
     * @param array $data
     * @param ResultInterface $resultRedirect
     * @return ResultInterface
     */
    private function processCategoriesReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';

        if ($redirect === 'continue') {
            $resultRedirect->setPath('sumup/categories/edit', ['categories_id' => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } elseif ($redirect === 'duplicate') {
            $duplicateModel = $this->CategoriesFactory->create(['data' => $data]);
            $duplicateModel->setId(null);
//            $duplicateModel->setIdentifier($data['identifier'] . '-' . uniqid());
            $duplicateModel->setStatus(Categories::STATUS_DISABLED);
//            $this->blockRepository->save($duplicateModel);
            $id = $duplicateModel->getId();
            $this->messageManager->addSuccessMessage(__('You duplicated the Categories.'));
            $this->dataPersistor->set('Categories', $data);
            $resultRedirect->setPath('sumup/categories/edit', ['categories_id' => $id]);
        }
        return $resultRedirect;
    }
}

