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
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Payment\Gateway\ConfigInterface;

/**
 * Class Authorization
 *
 * @package ManhThuong99\MomoWallet\Gateway\Helper
 */
class Authorization
{
    const VIETNAMESE = 'vi';
    const REQUEST_TYPE_V2 = 'captureWallet';
    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var string
     */
    protected $timestamp;

    /**
     * @var string
     */
    protected $params;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * Authorization constructor.
     * @param DateTime        $dateTime
     * @param Json            $serializer
     * @param ConfigInterface $config
     */
    public function __construct(
        DateTime $dateTime,
        Json $serializer,
        ConfigInterface $config
    ) {
        $this->dateTime   = $dateTime;
        $this->config     = $config;
        $this->serializer = $serializer;
    }

    /**
     * Set Parameter
     *
     * @param $params
     * @return $this
     */
    public function setParameter($params)
    {
        $params                                  = array_replace_recursive($params, $this->getPartnerInfo());
        $params[AbstractDataBuilder::REQUEST_ID] = $this->getTimestamp();
        $newParams                               = [];
        foreach ($this->getSignatureData() as $key) {
            if (!empty($params[$key])) {
                $newParams[$key] = $params[$key];
            }
        }
        $newParams[AbstractDataBuilder::REQUEST_TYPE] = self::REQUEST_TYPE_V2;
        $newParams[AbstractDataBuilder::LANG] = self::VIETNAMESE;
        $newParams[AbstractDataBuilder::SIGNATURE] = $this->getSignature($newParams);
        ksort($newParams);
        $this->params = $this->serializer->serialize($newParams);

        return $this;
    }

    /**
     * Signature
     *
     * @param $params
     * @return string
     */
    public function getSignature($params)
    {
        $partnerCode = $params["partnerCode"];
        $orderId = $params["orderId"];
        $orderInfo = $params["orderInfo"];
        $amount = $params["amount"];
        $requestId = $params["requestId"];
        $extraData = $params["extraData"];
        $accessKey = $this->getAccessKey();
        $ipnUrl = $params["ipnUrl"];
        $redirectUrl = $params["redirectUrl"];
        $requestType = self::REQUEST_TYPE_V2;
        $rawHash ="accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        return hash_hmac('sha256', $rawHash, $this->getSecretKey());
    }

    /**
     * @return array
     */
    public function getSignatureData()
    {
        return [
            AbstractDataBuilder::PARTNER_CODE,
            AbstractDataBuilder::ACCESS_KEY,
            AbstractDataBuilder::REQUEST_ID,
            AbstractDataBuilder::AMOUNT,
            AbstractDataBuilder::ORDER_ID,
            AbstractDataBuilder::TRANSACTION_ID,
            AbstractDataBuilder::ORDER_INFO,
            AbstractDataBuilder::REDIRECT_URL,
            AbstractDataBuilder::IPN_URL,
            AbstractDataBuilder::EXTRA_DATA,
            AbstractDataBuilder::LANG,
        ];
    }

    /**
     * @return string
     */
    public function getParameter()
    {
        return $this->params;
    }

    /**
     * @return array
     */
    private function getPartnerInfo()
    {
        return [
            AbstractDataBuilder::PARTNER_CODE => $this->getPartnerCode(),
            AbstractDataBuilder::ACCESS_KEY => $this->getAccessKey()
        ];
    }

    /**
     * Get Header
     *
     * @return array
     */
    public function getHeaders()
    {
        return [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($this->getParameter())
        ];
    }

    /**
     * @return string
     */
    private function getTimestamp()
    {
        if ($this->timestamp === null) {
            $this->timestamp = (string)($this->dateTime->gmtTimestamp() * 1000);
        }

        return $this->timestamp;
    }

    /**
     * @return mixed
     */
    private function getAccessKey()
    {
        return $this->config->getValue('access_key');
    }

    /**
     * @return mixed
     */
    private function getSecretKey()
    {
        return $this->config->getValue('secret_key');
    }

    /**
     * @return mixed
     */
    private function getPartnerCode()
    {
        return $this->config->getValue('partner_code');
    }
}
