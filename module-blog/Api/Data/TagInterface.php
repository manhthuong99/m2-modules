<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\HelloWorld\Api\Data;

/**
 * CMS block interface.
 * @api
 * @since 100.0.2
 */
interface TagInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const TAG_ID = 'Tag_id';
    const NAME = 'name';
    /**#@-*/


    /**
     * Get ID
     *
     * @return int|null
     */
    public function getTag_id();

    /**
     * Get identifier
     *
     * @return string
     */
    public function getName();


    /**
     * Set ID
     *
     * @param int $tag_id
     * @return TagInterface
     */
    public function setTag_id($tag_id);

    /**
     * Set name
     *
     * @param string $name
     * @return TagInterface
     */
    public function setName($name);

}
