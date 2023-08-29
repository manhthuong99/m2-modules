<?php

namespace SM\HelloWorld\Model\ResourceModel;

use \Magento\Framework\Model\AbstractModel;
use Magento\Eav\Model\Entity\Context;
use Magento\Framework\DataObject;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use SM\HelloWorld\Api\Data\BlogInterface;
use SM\HelloWorld\Model\Config;
use SM\HelloWorld\Model\TagFactory as TagModelFactory;
use Magento\Eav\Model\Entity\AbstractEntity;

class Blog extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('su1_blog', 'blog_id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {


        $image = $object->getGallery();


        if (is_array($image)) {
            $object->setGallery($image[0]['name']);
        }


        return $this;
    }

    protected function _afterLoad(AbstractModel $blog)
    {
        /* @var BlogInterface $blog */

        $blog->setCategoryIds($this->getCategoryIds($blog));
        $blog->setTagIds($this->getTagIds($blog));
        $blog->setProductIds($this->getProductIds($blog));

        return parent::_afterLoad($blog);
    }


    /**
     * @param BlogInterface $model
     *
     * @return array
     */
    private function getCategoryIds(BlogInterface $model)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('su1_blog_cate'),
            'categories_id'
        )->where(
            'blog_id = ?',
            (int)$model->getId()
        );

        return $connection->fetchCol($select);
    }

    /**
     * @param BlogInterface $model
     *
     * @return array
     */
    private function getTagIds(BlogInterface $model)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('su1_blog_tag'),
            'tag_id'
        )->where(
            'blog_id = ?',
            (int)$model->getId()
        );

        return $connection->fetchCol($select);
    }

    /**
     * @param BlogInterface $model
     *
     * @return array
     */
    private function getProductIds(BlogInterface $model)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('su1_blog_product'),
            'product_id'
        )->where(
            'blog_id = ?',
            (int)$model->getId()
        );

        return $connection->fetchCol($select);
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterSave(AbstractModel $blog)
    {
        /* @var BlogInterface $blog */
        $this->saveCategoryIds($blog);
        $this->saveTagIds($blog);
        $this->saveProductIds($blog);

        return parent::_afterSave($blog);
    }

    /**
     * @param BlogInterface $model
     *
     * @return $this
     */
    private function saveCategoryIds(\SM\HelloWorld\Model\Blog $model)
    {
        $connection = $this->getConnection();

        $table = $this->getTable('su1_blog_cate');

        if (!$model->getCategoryIds()) {
            return $this;
        }

        $categoryIds = $model->getCategoryIds();
        $oldCategoryIds = $this->getCategoryIds($model);

        $insert = array_diff($categoryIds, $oldCategoryIds);
        $delete = array_diff($oldCategoryIds, $categoryIds);

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $categoryId) {
                if (empty($categoryId)) {
                    continue;
                }

                $data[] = [
                    'categories_id' => (int)$categoryId,
                    'blog_id' => (int)$model->getId(),
                ];
            }

            if ($data) {
                $connection->insertMultiple($table, $data);
            }
        }

        if (!empty($delete)) {
            foreach ($delete as $categoryId) {
                $where = ['blog_id = ?' => (int)$model->getId(), 'categories_id = ?' => (int)$categoryId];
                $connection->delete($table, $where);
            }
        }

        return $this;
    }

    /**
     * @param BlogInterface $model
     *
     * @return $this
     */
    private function saveTagIds(\SM\HelloWorld\Model\Blog $model)
    {
        $connection = $this->getConnection();

        $table = $this->getTable('su1_blog_tag');

        if (!$model->getTagIds()) {
            return $this;
        }

        $tagIds = $model->getTagIds();
        $oldTagIds = $this->getTagIds($model);

        $insert = array_diff($tagIds, $oldTagIds);
        $delete = array_diff($oldTagIds, $tagIds);

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $tagId) {
                if (empty($tagId)) {
                    continue;
                }
                $data[] = [
                    'tag_id' => (int)$tagId,
                    'blog_id' => (int)$model->getId(),
                ];
            }

            if ($data) {
                $connection->insertMultiple($table, $data);
            }
        }

        if (!empty($delete)) {
            foreach ($delete as $tagId) {
                $where = ['blog_id = ?' => (int)$model->getId(), 'tag_id = ?' => (int)$tagId];
                $connection->delete($table, $where);
            }
        }

        return $this;
    }

    /**
     * @param BlogInterface $model
     *
     * @return $this
     */
    private function saveProductIds(\SM\HelloWorld\Model\Blog $model)
    {
        $connection = $this->getConnection();

        $table = $this->getTable('su1_blog_product');

        if (!$model->getProductIds()) {
            return $this;
        }

        $productIds = $model->getProductIds();
        $oldProductIds = $this->getProductIds($model);

        $insert = array_diff($productIds, $oldProductIds);
        $delete = array_diff($oldProductIds, $productIds);

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $productId) {
                if (empty($productId)) {
                    continue;
                }
                $data[] = [
                    'product_id' => (int)$productId,
                    'blog_id' => (int)$model->getId(),
                ];
            }

            if ($data) {
                $connection->insertMultiple($table, $data);
            }
        }

        if (!empty($delete)) {
            foreach ($delete as $productId) {
                $where = ['blog_id = ?' => (int)$model->getId(), 'product_id = ?' => (int)$productId];
                $connection->delete($table, $where);
            }
        }

        return $this;
    }

}