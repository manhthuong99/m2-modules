<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\HelloWorld\Api\Data;

/**
 * Category data interface.
 *
 * @api
 * @since 100.0.2
 */
interface CategoriesInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const CATEGORIES_ID = "categories_id";
    const PARENT_ID = "parent_id";
    const NAME = "name";
    const STATUS = "status";


    /**
     * Retrieve category id.
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set category id.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get parent category ID
     *
     * @return int|null
     */
    public function getParentId();

    /**
     * Set parent category ID
     *
     * @param int $superId
     * @return $this
     */
    public function setParentId($superId);

    /**
     * Get category name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set category name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Check whether category is active
     *
     * @return bool|null
     */
    public function getStatus();

    /**
     * Set whether category is active
     *
     * @param bool $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Retrieve category creation date and time.
     *
     * @return string|null
     */
}