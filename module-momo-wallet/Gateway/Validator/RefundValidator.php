<?php
/************************************************************
 * *
 *  * Copyright © ManhThuong99. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author   thuongnm(mthuong03@gmail.com)
 * *  @project   Momo Wallet
 */
namespace ManhThuong99\MomoWallet\Gateway\Validator;

use ManhThuong99\MomoWallet\Gateway\Request\AbstractDataBuilder;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Validator\ResultInterface;

/**
 * Class RefundValidator
 *
 * @package ManhThuong99\MomoWallet\Gateway\Validator
 */
class RefundValidator extends AbstractResponseValidator
{
    /**
     * @param array $validationSubject
     * @return ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function validate(array $validationSubject)
    {
        $response      = SubjectReader::readResponse($validationSubject);
        $payment       = SubjectReader::readPayment($validationSubject);
        $amount        = round($payment->getOrder()->getGrandTotalAmount(), 2);
        $amount        = $this->helperRate->getVndAmount($payment->getPayment()->getOrder(), $amount);
        $errorMessages = [];

        $validationResult = $this->validateTotalAmount($response, $amount)
            && $this->validateTransactionId($response)
            && $this->validateErrorCode($response)
            && $this->validateSignature($response);

        if (!$validationResult) {
            $errorMessages = [__('Transaction has been declined. Please try again later.')];
        }

        return $this->createResult($validationResult, $errorMessages);
    }

    /**
     * Validate total amount.
     *
     * @param array               $response
     * @param array|number|string $amount
     * @return boolean
     */
    protected function validateTotalAmount(array $response, $amount)
    {
        return isset($response[self::TOTAL_AMOUNT])
            && (float)($response[self::TOTAL_AMOUNT]) <= (float)$amount;
    }

    /**
     * @inheritDoc
     */
    protected function getSignatureArray()
    {
        return [
            AbstractDataBuilder::PARTNER_CODE,
            AbstractDataBuilder::ACCESS_KEY,
            AbstractDataBuilder::REQUEST_ID,
            AbstractDataBuilder::ORDER_ID,
            self::ERROR_CODE,
            self::TRANSACTION_ID,
            self::RESPONSE_MESSAGE,
            self::RESPONSE_LOCAL_MESSAGE,
            AbstractDataBuilder::REQUEST_TYPE
        ];
    }
}
