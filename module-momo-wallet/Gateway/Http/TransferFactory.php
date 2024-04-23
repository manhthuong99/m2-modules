<?php
/************************************************************
 * *
 *  * Copyright © ManhThuong99. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author   thuongnm(mthuong03@gmail.com)
 * *  @project   Momo Wallet
 */
namespace ManhThuong99\MomoWallet\Gateway\Http;

/**
 * Class TransferFactory
 *
 * @package ManhThuong99\MomoWallet\Gateway\Http
 */
class TransferFactory extends AbstractTransferFactory
{
    const URL_PATH = 'v2/gateway/api/create';
    /**
     * @inheritdoc
     */
    public function create(array $request)
    {
        $header = $this->getAuthorization()
            ->setParameter($request)
            ->getHeaders();

        return $this->transferBuilder
            ->setMethod('POST')
            ->setHeaders($header)
            ->setBody($this->getAuthorization()->getParameter())
            ->setUri($this->getUrl())
            ->build();
    }

    /**
     * Get Url
     *
     * @return string
     */
    private function getUrl()
    {
        $prefix = $this->isSandboxMode() ? 'sandbox_' : '';
        $path   = $prefix . 'payment_url';

        return rtrim($this->config->getValue($path), '/') . '/' . self::URL_PATH;
    }
}
