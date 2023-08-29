<?php
/**
 * Created by PhpStorm.
 * User: thelightsp
 * Date: 7/17/20
 * Time: 8:58 AM
 */

namespace SM\HelloWorld\Ui\Component\DataProvider;

use SM\HelloWorld\Model\ResourceModel\Blog\CollectionFactory;

/**
 * Class Test
 * @package Thelight\Test\Ui\Component\DataProvider
 */
class Blog extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * Test constructor.
     * @param CollectionFactory $collectionFactory
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name,$primaryFieldName,$requestFieldName,$meta,$data);
    }


}