<?php

namespace SM\HelloWorld\Observer;

use Magento\Framework\Data\Tree\Node as TreeNode;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use SM\HelloWorld\Model\Config;
use SM\HelloWorld\Model\ResourceModel\Categories\CollectionFactory as CategoryCollection;

class Topmenu implements ObserverInterface
{
    /**
     * @var CategoryCollection
     */
    protected $CategoryCollection;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config                    $config
     * @param CategoryCollection $CategoryCollection
     */
    public function __construct(
        Config $config,
        CategoryCollection $CategoryCollection
    ) {
        $this->config                    = $config;
        $this->CategoryCollection = $CategoryCollection;
    }

    /**
     * {@inheritdoc}
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        if (!$this->config->isDisplayInMenu()) {
            return;
        }
        /** @var TreeNode $menu */
        $menu = $observer->getData();
        $categories = $this->CategoryCollection->create()
            ->excludeRoot()
            ->addVisibilityFilter();

        $tree = $categories->getTree();

        $rootNode = new TreeNode(
            [
                'id'   => '1',
                'name' => $this->config->getMenuTitle(),
                'url'  => $this->config->getBaseUrl(),
            ],
            'id',
            $menu->getTree(),
            null
        );
        //@todo find correct way to add class
        if ($menu->getPositionClass()) {
            $menu->setPositionClass('blog-mx' . $menu->getPositionClass());
        } else {
            $menu->setPositionClass('blog-mx nav' . $menu->getPositionClass());
        }
        $menu->addChild($rootNode);

        foreach ($tree as $category) {
            if (isset($tree[$category->getParentId()])) {
                $parentNode = $tree[$category->getParentId()]->getData(1);
            } else {
                $parentNode = $rootNode;
            }

            $node = new TreeNode(
                [
                    'id'   => 'blog-node-' . $category->getId(),
                    'name' => $category->getName(),
                    'url'  => $category->getUrl(),
                ],
                'id',
                $menu->getTree(),
                $parentNode
            );

            if ($parentNode) {
                $parentNode->addChild($node);
            } else {
                $menu->addChild($node);
            }

            $category->setData('node', $node);
        }
    }
}
