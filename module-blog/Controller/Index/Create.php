<?php /**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\HelloWorld\Controller\Index;

use Magento\Framework\App\Action\Context;
use SM\HelloWorld\Model\BlogFactory;
use Magento\Framework\App\ResponseInterface;

use SM\HelloWorld\Model\ResourceModel\Blog\CollectionFactory;
use SM\HelloWorld\Model\ResourceModel\BlogFactory as ResourceSMFactory;

class Create extends \Magento\Framework\App\Action\Action
{
    protected $smFactory;
    protected $resourceSMFactory;
    protected $smCollectionFactory;

    public function __construct(
        Context $context,
        BlogFactory $blogFactory,
        ResourceSMFactory $resourceOSFactory,
        CollectionFactory $smCollectionFactory
    )
    {
        $this->smFactory = $blogFactory;
        $this->resourceSMFactory = $resourceOSFactory;
        $this->smCollectionFactory = $smCollectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {

//        $smCollectionFactory = $this->smCollectionFactory->create()->getItemById('32');
////
//     var_dump($smCollectionFactory->getData());
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}