<?php

namespace SM\HelloWorld\Ui\Component\DataProvider;

use Magento\Catalog\Model\ResourceModel\Product\Collection;

class ProductDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider
{
    public function getCollection()
    {
        /** @var Collection $collection */
        $collection = parent::getCollection();

        return $collection->addAttributeToSelect('status');
    }
}
