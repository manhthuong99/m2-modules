<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\HelloWorld\Model\Source;
use Magento\Framework\Option\ArrayInterface;
use Magento\Framework\Data\OptionSourceInterface;
use SM\HelloWorld\Model\TagFactory;

class Tag implements ArrayInterface
{
    /**
     * @var TagFactory
     */
    public $_tagFactory;

    public function __construct(
        tagFactory $tagFactory
    ) {
        $this->_tagFactory = $tagFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->getTag() as $value => $tag) {
            $options[] = [
                'value' => $value,
                'label' => $tag->getName()
            ];
        }
        return $options;
    }


    /**Collection
     * @return \Magento\Framework\Data\Collection\AbstractDb|\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|null
     */
    public function getTag()
    {
        return $this->_tagFactory->create()->getCollection();
    }
}