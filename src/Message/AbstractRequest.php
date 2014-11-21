<?php

namespace Omnipay\Stripe\Message;

/**
 * Stripe Abstract Request
 *
 * @method \Omnipay\Stripe\Message\Response send()
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $endpoint = 'https://api.stripe.com/v1';

    public function getLiveApiKey()
    {
        return $this->getParameter('liveApiKey');
    }

    public function setLiveApiKey($value)
    {
        return $this->setParameter('liveApiKey', $value);
    }

    public function getTestApiKey()
    {
        return $this->getParameter('testApiKey');
    }

    public function setTestApiKey($value)
    {
        return $this->setParameter('testApiKey', $value);
    }

    public function getMetadata()
    {
        return $this->getParameter('metadata');
    }

    public function setMetadata($value)
    {
        return $this->setParameter('metadata', $value);
    }

    abstract public function getEndpoint();

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function sendData($data)
    {
        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            null,
            $data
        );
        $httpResponse = $httpRequest
            ->setHeader('Authorization', 'Basic '.base64_encode($apikey.':'))
            ->setHeader('Stripe-Version', '2014-11-20')
            ->send();

        return $this->response = new Response($this, $httpResponse->json());
    }

    protected function getCardData()
    {
        $this->getCard()->validate();

        $data = array();
        $data['number'] = $this->getCard()->getNumber();
        $data['exp_month'] = $this->getCard()->getExpiryMonth();
        $data['exp_year'] = $this->getCard()->getExpiryYear();
        $data['cvc'] = $this->getCard()->getCvv();
        $data['name'] = $this->getCard()->getName();
        $data['address_line1'] = $this->getCard()->getAddress1();
        $data['address_line2'] = $this->getCard()->getAddress2();
        $data['address_city'] = $this->getCard()->getCity();
        $data['address_zip'] = $this->getCard()->getPostcode();
        $data['address_state'] = $this->getCard()->getState();
        $data['address_country'] = $this->getCard()->getCountry();

        return $data;
    }
}
