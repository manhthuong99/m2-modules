<?php
/************************************************************
 * *
 *  * Copyright © ManhThuong99. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author   thuongnm(mthuong03@gmail.com)
 * *  @project   Momo Wallet
 */
namespace ManhThuong99\MomoWallet\Gateway\Helper;

use ManhThuong99\MomoWallet\Gateway\Request\AbstractDataBuilder;
use ManhThuong99\MomoWallet\Gateway\Validator\AbstractResponseValidator;

/**
 * Class TransactionReader
 *
 * @package ManhThuong99\MomoWallet\Gateway\Helper
 */
class TransactionReader
{

    /**
     * Is IPN request
     */
    const IS_IPN = 'is_ipn';

    /**
     * Read Pay Url from transaction data
     *
     * @param array $transactionData
     * @return string
     */
    public static function readPayUrl(array $transactionData)
    {
        if (empty($transactionData[AbstractResponseValidator::PAY_URL])) {
            throw new \InvalidArgumentException('Pay Url should be provided');
        }

        return $transactionData[AbstractResponseValidator::PAY_URL];
    }

    /**
     * Read Order Id from transaction data
     *
     * @param array $transactionData
     * @return string
     */
    public static function readOrderId(array $transactionData)
    {
        if (empty($transactionData[AbstractDataBuilder::ORDER_ID])) {
            throw new \InvalidArgumentException('Order Id doesn\'t exit');
        }

        return $transactionData[AbstractDataBuilder::ORDER_ID];
    }

    /**
     * Check Is IPN from transaction data
     *
     * @param array $transactionData
     * @return string
     */
    public static function isIpn(array $transactionData)
    {
        if (!empty($transactionData[self::IS_IPN]) && $transactionData[self::IS_IPN]) {
            return true;
        }

        return false;
    }
}
