<?php

namespace Omnipay\Stripe\Message;

/**
 * Stripe Create Customer Request
 */
class CreateCustomerRequest extends AbstractRequest
{
    public function getData()
    {
        $data = array();
        $data['description'] = $this->getDescription();
        $data['email'] = $this->getEmail();
        $data['metadata'] = $this->getMetadata();

        return $data;
    }

    public function getEndpoint()
    {
        return $this->endpoint.'/customers';
    }
}