<?php
/************************************************************
 * *
 *  * Copyright © ManhThuong99. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author   thuongnm(mthuong03@gmail.com)
 * *  @project   Momo Wallet
 */
namespace ManhThuong99\MomoWallet\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\UrlInterface;
use Magento\Payment\Helper\Data as PaymentHelper;

/**
 * Class MomoConfigProvider
 *
 * @package ManhThuong99\MomoWallet\Model
 */
class MomoConfigProvider implements ConfigProviderInterface
{
    /**
     * Momo Logo
     */
    const MOMO_LOGO_SRC = 'https://developers.momo.vn/images/logo.png';

    /**
     * @var ResolverInterface
     */
    protected $localeResolver;

    /**
     * @var PaymentHelper
     */
    protected $paymentHelper;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * MomoConfigProvider constructor.
     *
     * @param ResolverInterface $localeResolver
     * @param PaymentHelper     $paymentHelper
     * @param UrlInterface      $urlBuilder
     */
    public function __construct(
        ResolverInterface $localeResolver,
        PaymentHelper $paymentHelper,
        UrlInterface $urlBuilder
    ) {
        $this->localeResolver = $localeResolver;
        $this->paymentHelper  = $paymentHelper;
        $this->urlBuilder     = $urlBuilder;
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        $config = [
            'payment' => [
                'momoWallet' => [
                    'redirectUrl' => $this->urlBuilder->getUrl('momo/payment/start'),
                    'logoSrc' => self::MOMO_LOGO_SRC
                ]
            ]
        ];

        return $config;
    }
}
