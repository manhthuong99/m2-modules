<?php
/************************************************************
 * *
 *  * Copyright © ManhThuong99. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author   thuongnm(mthuong03@gmail.com)
 * *  @project   Momo Wallet
 */
namespace ManhThuong99\MomoWallet\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class AbstractDataBuilder
 * @package ManhThuong99\MomoWallet\Gateway\Request
 */
abstract class AbstractDataBuilder implements BuilderInterface
{
    /**
     * Pay Url
     */
    const PAY_URL_TYPE = 'captureWallet';

    /**@#+
     * Momo AIO Url path
     *
     * @const
     */
    const PAY_URL_PATH = 'v2/gateway/api/create';

    /**
     * Refund Url Path
     */
    const REFUND_TYPE = 'refundWallet';

    /**
     * Transaction Type: Refund
     */
    const REFUND = 'refund';

    /**
     * Transaction Id
     */
    const TRANSACTION_ID = 'transId';

    /**
     * Access Key
     */
    const ACCESS_KEY = 'accessKey';

    /**
     * Secret key
     */
    const SECRET_KEY = 'secretKey';

    /**
     * Partner code
     */
    const PARTNER_CODE = 'partnerCode';

    /**
     * Request Id
     */
    const REQUEST_ID = 'requestId';

    /**
     * Order Info
     */
    const ORDER_INFO = 'orderInfo';

    /**
     * Return Url
     */
    const REDIRECT_URL = 'redirectUrl';

    /**
     * Notify Url
     */
    const IPN_URL = 'ipnUrl';

    /**
     * Extra Data
     */
    const EXTRA_DATA = 'extraData';

    /**
     * Request Type
     */
    const REQUEST_TYPE = 'requestType';

    /**
     * Signature
     */
    const SIGNATURE = 'signature';

    /**
     * Merchant Ref
     */
    const ORDER_ID = 'orderId';

    /**
     * Amount
     */
    const AMOUNT = 'amount';

    /**
     * Amount
     */
    const LANG = 'lang';
}
