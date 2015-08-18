<?php

namespace Omnipay\Stripe\Message;

/**
 * Stripe Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{

    public function getStatementDescriptor()
    {
        return $this->getParameter('statementDescriptor');
    }

    public function setStatementDescriptor($value)
    {
        return $this->setParameter('statementDescriptor', $value);
    }

    public function getData()
    {
        $this->validate('amount', 'currency');

        $data = array();
        $data['amount'] = $this->getAmountInteger();
        $data['currency'] = strtolower($this->getCurrency());
        $data['description'] = $this->getDescription();
        $data['metadata'] = $this->getMetadata();
        $data['capture'] = 'false';

        if ($this->getStatementDescriptor()) {
            $data['statement_descriptor'] = $this->getStatementDescriptor();
        }

        if ($this->getCustomerReference() && $this->getCardReference()) {
            $data['customer'] = $this->getCustomerReference();
            $data['card'] = $this->getCardReference();
        }
        elseif ($this->getCardReference()) {
            $data['customer'] = $this->getCardReference();
        } elseif ($this->getToken()) {
            $data['card'] = $this->getToken();
        } elseif ($this->getCard()) {
            $data['card'] = $this->getCardData();
        } else {
            // one of cardReference, token, or card is required
            $this->validate('card');
        }

        return $data;
    }

    public function getEndpoint()
    {
        return $this->endpoint.'/charges';
    }
}
