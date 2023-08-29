<?php

namespace SM\HelloWorld\Model\Source;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Option\ArrayInterface;
use SM\HelloWorld\Api\Data\CategoriesInterface;
use SM\HelloWorld\Model\CategoriesFactory;

class Categories implements ArrayInterface
{
    /**
     * @var CategoriesFactory
     */
    public $_categoriesFactory;
    protected $context;

    public function __construct(
        CategoriesFactory $CategoriesFactory,
        Context $context
    )
    {
        $this->_categoriesFactory = $CategoriesFactory;
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $collection = $this->_categoriesFactory->create()->getCollection();
        $rootId = $collection->getRootId();

        return [$this->getOptions($rootId)];
    }

    /**
     * @param int $parentId
     *
     * @return array
     */
    private function getOptions($parentId)
    {
        $model = $this->context->getObjectManager()->create(\SM\HelloWorld\Model\Categories::class);
        $category = $model->load($parentId);

        $data = [
            'label' => $category->getName(),
            'value' => $category->getId(),
        ];

        $collection = $this->_categoriesFactory->create()->getCollection()
            ->addFieldToFilter(CategoriesInterface::PARENT_ID, $category->getId())
        ->addFieldToFilter('status','1');

        /* @var CategoriesInterface $item */
        foreach ($collection as $item) {
            $data['optgroup'][] = $this->getOptions($item->getId());
        }
        return $data;
    }
}