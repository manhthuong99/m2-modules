<?php
/***********************************************************************
 * *
 *  *
 *  * @copyright Copyright © ManhThuong99. All rights reserved.
 *  * See COPYING.txt for license details.
 *  * @author   thuongnm(mthuong03@gmail.com)
 * *
 */

namespace ManhThuong99\MomoWallet\Controller\Payment;

use AHT\CustomApi\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action as AppAction;
use Magento\Framework\App\Action\Context;
use Magento\Payment\Gateway\Helper\ContextHelper;
use Magento\Payment\Model\MethodInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;

/**
 * Class ReturnAction
 * @package ManhThuong99\MomoWallet\Controller\Payment
 */
class ReturnAction extends AppAction
{
    const MOMO_SECRET_KEY = 'payment/momo/secret_key';
    const MOMO_ACCESS_KEY = 'payment/momo/access_key';
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var MethodInterface
     */
    private $method;
    /**
     * @var OrderFactory
     */
    private $orderFactory;
    /**
     * @var Data
     */
    private $helper;

    /**
     * ReturnAction constructor.
     *
     * @param Context $context
     * @param Session $checkoutSession
     * @param MethodInterface $method
     * @param OrderFactory $orderFactory
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        Session $checkoutSession,
        MethodInterface $method,
        OrderFactory $orderFactory,
        Data $helper
    ) {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->method = $method;
        $this->orderFactory = $orderFactory;
        $this->helper = $helper;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $orderSessionId = $this->checkoutSession->getLastOrderId();
            if ($orderSessionId) {
                $response = $this->getRequest()->getParams();
                $order = $this->orderFactory->create()->load($orderSessionId);
                $payment = $order->getPayment();
                ContextHelper::assertOrderPayment($payment);
                if ($payment->getMethod() === $this->method->getCode()) {
                    if ($order->getState() == Order::STATE_PENDING_PAYMENT) {
                        $secretKey = $this->helper->getConfigValue(self::MOMO_SECRET_KEY);
                        $accessKey = $this->helper->getConfigValue(self::MOMO_ACCESS_KEY);
                        $partnerCode = $response["partnerCode"];
                        $orderId = $response["orderId"];
                        $message = $response["message"];
                        $transId = $response["transId"];
                        $orderInfo = $response["orderInfo"];
                        $amount = $response["amount"];
                        $resultCode = $response["resultCode"];
                        $responseTime = $response["responseTime"];
                        $requestId = $response["requestId"];
                        $extraData = $response["extraData"];
                        $payType = $response["payType"];
                        $orderType = $response["orderType"];
                        $signature = $response["signature"];
                        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&message=$message&orderId=$orderId&orderInfo=$orderInfo&orderType=$orderType&partnerCode=$partnerCode&payType=$payType&requestId=$requestId&responseTime=$responseTime&resultCode=$resultCode&transId=$transId";
                        $partnerSignature = hash_hmac("sha256", $rawHash, $secretKey);
                        $ipn = $this->helper->getResponseIpnMoMo($resultCode);
                        $this->helper->logger('momo_payment.log', 'Data:' . json_encode($response));
                        $this->helper->logger('momo_payment.log', 'Ipn:' . json_encode($ipn));
                        if ($signature == $partnerSignature && $order->getIncrementId() == $orderId) {
                            if ($resultCode == '0') {
                                $order->setTotalPaid(floatval($amount));
                                $orderState = $order::STATE_COMPLETE;
                                $order->setState($orderState)->setStatus($order::STATE_COMPLETE);
                                $order->save();
                                $this->messageManager->addSuccessMessage(__($ipn['massage']));
                                $resultRedirect->setPath('checkout/onepage/success');
                                return $resultRedirect;
                            } else {
                                $order->addStatusHistoryComment($ipn['massage']);
                                $orderState = $order::STATE_PENDING_PAYMENT;
                                $order->setState($orderState)->setStatus($order::STATE_PENDING_PAYMENT);
                                $order->save();
                            }
                        }
                        $this->messageManager->addErrorMessage(__($ipn['massage']));
                    }
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Transaction has been declined. Please try again later.'));
            $this->helper->logger('momo_payment.log', $e->getMessage());
        }
        $resultRedirect->setPath('checkout/onepage/failure');
        return $resultRedirect;
    }
}
