<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\HelloWorld\Api;

/**
 * CMS block CRUD interface.
 * @api
 * @since 100.0.2
 */
interface BlogRepositoryInterface
{
    /**
     * Save block.
     *
     * @param \SM\HelloWorld\Api\Data\BlogInterface $block
     * @return \SM\HelloWorld\Api\Data\BlogInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\BlogInterface $block);

    /**
     * Retrieve block.
     *
     * @param int $blockId
     * @return \SM\HelloWorld\Api\Data\BlogInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($blockId);

    /**
     * Retrieve blocks matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SM\HelloWorld\Api\Data\BlockSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete block.
     *
     * @param \SM\HelloWorld\Api\Data\BlogInterface $block
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\BlogInterface $block);

    /**
     * Delete block by ID.
     *
     * @param int $blockId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($blockId);
}
