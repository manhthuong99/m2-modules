<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Rohan Hapani
 */

namespace SM\HelloWorld\Plugin;

use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\UrlInterface;
use SM\HelloWorld\Model\ResourceModel\Categories\CollectionFactory as CategoryCollection;

class Topmenu
{
    /**
     * @var NodeFactory
     */
    protected $nodeFactory;
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    protected $categoryCollection;
    /**
     * @param CategoryCollection $categoryCollection
     * @param NodeFactory $nodeFactory
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        CategoryCollection $categoryCollection,
        NodeFactory $nodeFactory,
        UrlInterface $urlBuilder
    )
    {
        $this->categoryCollection = $categoryCollection;
        $this->nodeFactory = $nodeFactory;
        $this->urlBuilder = $urlBuilder;
    }

    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    )
    {
        $categories=$this->loadCategories();
        unset($categories[0]);
        /**
         * Parent Menu
         */
        $menuNode = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray("Blogs", "sumup/blog"),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree(),
            ]
        );
        /**
         * Add Child Menu
         */
        foreach ($categories as $category ){
            $menuNode->addChild(
                $this->nodeFactory->create(
                    [
//                        'data' => $this->getNodeAsArray($category['name'], 'sumup/blog/index?categoryid='.$category['categories_id']),
                        'data' => $this->getNodeAsArray($category['name'], $this->urlBuilder->getUrl('sumup/blog', ['categoryId' => $category['categories_id']])),
                        'idField' => 'id',
                        'tree' => $subject->getMenu()->getTree(),
                    ]
                )
            );
            $subject->getMenu()->addChild($menuNode);
        }

    }
    public function loadCategories()
    {
        $collection= $this->categoryCollection->create()->addFieldToFilter('status', '1');
        return $collection->getData();
    }
    protected function getNodeAsArray($name, $id)
    {
        $url = $this->urlBuilder->getUrl($id);
        return [
            'name' => __($name),
            'id' => $id,
            'url' => $url,
            'has_active' => false,
            'is_active' => false,
        ];
    }
}