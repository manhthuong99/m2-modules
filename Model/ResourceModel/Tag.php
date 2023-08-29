<?php

namespace SM\HelloWorld\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Sitemap
 * @package SM\HelloWorld\Model\ResourceModel
 */
class Tag extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('su1_tag', 'tag_id');
    }
}
