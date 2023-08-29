<?php


namespace SM\HelloWorld\Model\Source;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Option\ArrayInterface;
use SM\HelloWorld\Model\CategoriesFactory;


class ParentCategory implements ArrayInterface
{
    /**
     * @var CategoriesFactory
     */
    public $_categoriesFactory;

    public function __construct(
        CategoriesFactory $categoriesFactory
    ) {
        $this->_categoriesFactory = $categoriesFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->getCategories() as $value => $categories) {
            $options[] = [
                'value' => $value,
                'label' => $categories->getName()
            ];
        }

        return $options;
    }

    /**Collection
     * @return AbstractCollection
     */
    public function getCategories()
    {
        return $this->_categoriesFactory->create()->getCollection()->addFieldToFilter('status', '1');
    }
}