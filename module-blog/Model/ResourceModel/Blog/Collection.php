<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\HelloWorld\Model\ResourceModel\Blog;

use SM\HelloWorld\Api\Data\BlogInterface;
use \SM\HelloWorld\Model\ResourceModel\AbstractBlogCollection;

/**
 * CMS Block Collection
 */
class Collection extends AbstractBlogCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'blog_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'blog';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'blog';

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
//    protected function _afterLoad()
//    {
//        $entityMetadata = $this->metadataPool->getMetadata(BlogInterface::class);
//
//        $this->performAfterLoad('blog_store', $entityMetadata->getLinkField());
//
//        return parent::_afterLoad();
//    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\SM\HelloWorld\Model\Blog::class, \SM\HelloWorld\Model\ResourceModel\Blog::class);
        $this->_map['fields']['blog_id'] = 'blog_id';
    }

    /**
     * Returns pairs block_id - title
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('blog_id', 'name');
    }

    public function loadBlogByTag($id)
    {
        $listId = [];
        $listName = [];
        $select = $this->getConnection()->select()->from(
            ['su1_blog_tag' => "su1_blog_tag"],
            "tag_id"
        )->join(
            ['su1_tag' => $this->getResource()->getTable('su1_tag')],
            'su1_tag.tag_id = su1_blog_tag.tag_id',
            ["tag_id", "name"])
            ->join(
                ['su1_blog' => $this->getResource()->getTable('su1_blog')],
                'su1_blog.blog_id = su1_blog_tag.blog_id',
                ['blog_id', 'name', 'short_description', 'description', 'gallery', 'publish_date_from', 'publish_date_to']
            )->where(
                'su1_tag.tag_id IN (1)',
                $id
            );
        $data = $this->getConnection()->fetchAll($select);
        foreach ($data as $id) {
            $listId[] = $id['tag_id'];
            $listName[] = $id['name'];
        }
        return $data;
    }

    public function loadBlogByCategory($id)
    {
        $select = $this->getConnection()->select()->from(
            ['su1_blog_cate' => "su1_blog_cate"]
        )->join(
            ['su1_categories' => $this->getResource()->getTable('su1_categories')],
            'su1_categories.categories_id = su1_blog_cate.categories_id'
        )
            ->join(
                ['su1_blog' => $this->getResource()->getTable('su1_blog')],
                'su1_blog.blog_id = su1_blog_cate.blog_id',
                ['blog_id', 'name', 'short_description', 'description', 'gallery', 'publish_date_from', 'publish_date_to']
            )->where(
                'su1_categories.categories_id IN (?)',
                $id
            );
        $data = $this->getConnection()->fetchAll($select);
        return $data;
    }

    public function getProducts($id)
    {
        $select = $this->getConnection()->select()->from(
            ['su1_blog_product' => "su1_blog_product"],
            ['product_id']
        )
            ->join(
                ['su1_blog' => $this->getResource()->getTable('su1_blog')],
                'su1_blog.blog_id = su1_blog_product.blog_id',
                ['name']
            )
            ->where(
                'su1_blog.blog_id IN (?)',
                $id
            );
        $data = $this->getConnection()->fetchAll($select);
        return $data;
    }
}
