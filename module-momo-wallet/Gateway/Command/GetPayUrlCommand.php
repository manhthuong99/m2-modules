<?php
/************************************************************
 * *
 *  * Copyright © ManhThuong99. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author   thuongnm(mthuong03@gmail.com)
 * *  @project   Momo Wallet
 */

namespace ManhThuong99\MomoWallet\Gateway\Command;

use Magento\Payment\Gateway\Command\Result\ArrayResultFactory;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Payment\Gateway\Validator\ValidatorInterface;

/**
 * Class GetPayUrlCommand
 *
 * @package ManhThuong99\MomoWallet\Gateway\Command
 */
class GetPayUrlCommand implements CommandInterface
{
    /**
     * @var BuilderInterface
     */
    private $requestBuilder;

    /**
     * @var TransferFactoryInterface
     */
    private $transferFactory;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ArrayResultFactory
     */
    private $resultFactory;

    /**
     * Constructor
     *
     * @param BuilderInterface $requestBuilder
     * @param TransferFactoryInterface $transferFactory
     * @param ClientInterface $client
     * @param ArrayResultFactory $resultFactory
     * @param ValidatorInterface $validator
     */
    public function __construct(
        BuilderInterface $requestBuilder,
        TransferFactoryInterface $transferFactory,
        ClientInterface $client,
        ArrayResultFactory $resultFactory,
        ValidatorInterface $validator
    ) {
        $this->requestBuilder = $requestBuilder;
        $this->transferFactory = $transferFactory;
        $this->client = $client;
        $this->resultFactory = $resultFactory;
        $this->validator = $validator;
    }

    public function execute(array $commandSubject)
    {
        $transferO = $this->transferFactory->create($this->requestBuilder->build($commandSubject));
        return $this->client->placeRequest($transferO);
    }
}
