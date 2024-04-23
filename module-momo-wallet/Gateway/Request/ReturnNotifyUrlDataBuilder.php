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

use Magento\Framework\UrlInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class OrderDetailsDataBuilder
 *
 * @package ManhThuong99\MomoWallet\Gateway\Request
 */
class ReturnNotifyUrlDataBuilder extends AbstractDataBuilder implements BuilderInterface
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * ReturnNotifyUrlDataBuilder constructor.
     *
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        return [
            self::REDIRECT_URL => $this->urlBuilder->getUrl('momo/payment/return'),
            self::IPN_URL => $this->urlBuilder->getUrl('momo/payment/ipn')
        ];
    }
}
