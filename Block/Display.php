<?php


namespace SM\HelloWorld\Block;

use Magento\Framework\App\Action\Context;
use SM\HelloWorld\Model\ResourceModel\Blog\CollectionFactory;


class Display extends \Magento\Framework\View\Element\Template
{
    protected $smCollectionFactory;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                CollectionFactory $smCollectionFactory
    )
    {
        parent::__construct($context);
        $this->smCollectionFactory = $smCollectionFactory;
    }

    public function loadData() {
        $smCollectionFactory = $this->smCollectionFactory->create()->getItemById(32);
        return $smCollectionFactory->getData();
    }
}