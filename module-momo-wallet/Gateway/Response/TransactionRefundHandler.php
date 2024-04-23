<?php
/************************************************************
 * *
 *  * Copyright © ManhThuong99. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author   thuongnm(mthuong03@gmail.com)
 * *  @project   Momo Wallet
 */
namespace ManhThuong99\MomoWallet\Gateway\Response;

use Magento\Sales\Model\Order\Payment;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;
use ManhThuong99\MomoWallet\Gateway\Validator\AbstractResponseValidator;

/**
 * Class TransactionRefundHandler
 * @package ManhThuong99\MomoWallet\Gateway\Response
 */
class TransactionRefundHandler implements HandlerInterface
{
    /**
     * @var array
     */
    private $additionalInformationMapping = [
        'transaction_type' => AbstractResponseValidator::TRANSACTION_TYPE,
        'transaction_id' => AbstractResponseValidator::TRANSACTION_ID,
    ];

    /**
     * @param array $handlingSubject
     * @param array $response
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function handle(array $handlingSubject, array $response)
    {
        $paymentDO = SubjectReader::readPayment($handlingSubject);
        /** @var Payment $orderPayment */
        $orderPayment = $paymentDO->getPayment();
        $orderPayment->setTransactionId($response[AbstractResponseValidator::TRANSACTION_ID] . '-refund');

        $orderPayment->setIsTransactionClosed(true);
        $orderPayment->setShouldCloseParentTransaction(!$orderPayment->getCreditmemo()->getInvoice()->canRefund());

        foreach ($this->additionalInformationMapping as $informationKey => $responseKey) {
            if (isset($response[$responseKey])) {
                $orderPayment->setAdditionalInformation($informationKey, ucfirst($response[$responseKey]));
            }
        }
    }
}
