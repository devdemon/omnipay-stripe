<?php

namespace Omnipay\Stripe\Message;

/**
 * Stripe Create Credit Card Request
 */
class CreateCardRequest extends AbstractRequest
{
    public function getData()
    {
        $customerRef = $this->getCustomerReference();

        $data = array();

        if (!$customerRef) {
            $data['description'] = $this->getDescription();
        }

        if ($this->getToken()) {
            $data['card'] = $this->getToken();
        } elseif ($this->getCard()) {
            $data['card'] = $this->getCardData();

            if (!$customerRef) {
                $data['email'] = $this->getCard()->getEmail();
            }

        } else {
            // one of token or card is required
            $this->validate('card');
        }

        return $data;
    }

    public function getEndpoint()
    {
        if ($this->getCustomerReference()) {
            return $this->endpoint.'/customers/' . $this->getCustomerReference() . '/cards';
        } else {
            return $this->endpoint.'/customers';
        }
    }
}