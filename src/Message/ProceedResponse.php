<?php

namespace Omnipay\DocdataPayments\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Cancel Request Response
 */
class ProceedResponse extends AbstractResponse
{
    /**
     * @var string When the proceed request was successfully received by Docdata
     */
    const PROCEEDSUCCESS_CODE_SUCCESSFUL = 'SUCCESS';

    /**
     * @var string When the proceed request was authorized for payment
     */
    const PAYMENT_SUCCESS_STATUS_AUTHORIZED = 'AUTHORIZED';

    /**
     * @var string When the proceed request was cancelled (they have a typo in their api)
     */
    const PAYMENT_SUCCESS_STATUS_CANCELLED = 'CANCELED'; // [sic]

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        if (
            isset($this->data->proceedSuccess)
            && $this->data->proceedSuccess->success->code === self::PROCEEDSUCCESS_CODE_SUCCESSFUL
            && isset($this->data->proceedSuccess->paymentResponse->paymentSuccess)
            && $this->data->proceedSuccess->paymentResponse->paymentSuccess->status === self::PAYMENT_SUCCESS_STATUS_AUTHORIZED
        ) {
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isCancelled()
    {
        if (
            isset($this->data->proceedSuccess)
            && $this->data->proceedSuccess->success->code === self::PROCEEDSUCCESS_CODE_SUCCESSFUL
            && isset($this->data->proceedSuccess->paymentResponse->paymentSuccess)
            && $this->data->proceedSuccess->paymentResponse->paymentSuccess->status === self::PAYMENT_SUCCESS_STATUS_CANCELLED
        ) {
            return true;
        }
        return false;
    }

    /**
     * Get a reference provided by the gateway to represent the payment.
     * This is the same as the transactionReference from the createRequest.
     *
     * @return null|string
     */
    public function getTransactionReference()
    {
        /** @var AbstractRequest $this->>request */
        return $this->request->getTransactionReference();
    }
}
