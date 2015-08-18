<?php

namespace Omnipay\Stripe\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Stripe Response
 */
class Response extends AbstractResponse
{
    public function isSuccessful()
    {
        return !isset($this->data['error']);
    }

    public function getTransactionReference()
    {
        if (isset($this->data['object']) && 'charge' === $this->data['object']) {
            return $this->data['id'];
        }
    }

    public function getCardReference()
    {
        if (!isset($this->data['object'])) return;

        if ($this->data['object'] == 'customer') {
            return $this->data['default_card'];
        }

        if ($this->data['object'] == 'card') {
            return $this->data['id'];
        }
    }

    public function getCustomerReference()
    {
        if (!isset($this->data['object'])) return;

        if ($this->data['object'] == 'customer') {
            return $this->data['id'];
        }
    }

    public function getMessage()
    {
        if (!$this->isSuccessful()) {
            return $this->data['error']['message'];
        }
    }
}
