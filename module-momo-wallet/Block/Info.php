<?php
/************************************************************
 * *
 *  * Copyright © ManhThuong99. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author   thuongnm(mthuong03@gmail.com)
 * *  @project   Momo Wallet
 */
namespace ManhThuong99\MomoWallet\Block;

use Magento\Framework\Phrase;
use Magento\Payment\Block\ConfigurableInfo;

/**
 * Class Info
 *
 * @package ManhThuong99\MomoWallet\Block
 */
class Info extends ConfigurableInfo
{
    /**
     * Returns label
     *
     * @param string $field
     * @return Phrase
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function getLabel($field)
    {
        switch ($field) {
            case 'transaction_type':
                return __('Transaction Type');
            case 'transaction_id':
                return __('Transaction ID');
            case 'response_code':
                return __('Response Code');
            case 'approve_messages':
                return __('Approve Messages');
            default:
                return parent::getLabel($field);
        }
    }
}
