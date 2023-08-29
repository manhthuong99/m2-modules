<?php

namespace SM\HelloWorld\Api;

use SM\HelloWorld\Api\Data\CategoriesInterface;
use SM\HelloWorld\Model\ResourceModel\Categories\Collection;

interface CategoryRepositoryInterface
{
    /**
     * @return Collection | CategoriesInterface[]
     */
    public function getCollection();

    /**
     * @return CategoriesInterface
     */
    public function create();

    /**
     * @param CategoriesInterface $model
     *
     * @return CategoriesInterface
     */
    public function save(CategoriesInterface $model);

    /**
     * @param int $id
     *
     * @return CategoriesInterface|false
     */
    public function get($id);

    /**
     * @param CategoriesInterface $model
     *
     * @return bool
     */
    public function delete(CategoriesInterface $model);
}
