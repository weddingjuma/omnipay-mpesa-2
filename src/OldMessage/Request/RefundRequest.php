<?php


namespace Omnipay\Mpesa\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mpesa\Message\Response\RefundResponse;
use function is_string;

/**
 * Most payment methods support refunds. This means you can request your payment to be refunded to the consumer.
 * The amount of the refund will be withheld from your next settlement.
 *
 * @see https://docs.mollie.com/reference/v2/refunds-api/create-refund
 */
class RefundRequest extends AbstractMollieRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'transactionReference', 'amount', 'currency');

        $data = [];

        $data['amount'] = [
            "value" => $this->getAmount(),
            "currency" => $this->getCurrency()
        ];

        if (is_string($this->getParameter('description'))) {
            $data['description'] = $this->getParameter('description');
        }

        return $data;
    }

    /**
     * @param array $data
     * @return ResponseInterface|RefundResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            self::POST,
            '/payments/' . $this->getTransactionReference() . '/refunds',
            $data
        );

        return $this->response = new RefundResponse($this, $response);
    }
}