<?php

namespace Zamoroka\PayPalAllCurrencies\Model\CurrencyService;

/**
 * Class FinanceGoogle
 * simple call https://finance.google.com/finance/converter?a=1&from=UAH&to=USD
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\CurrencyService
 */
class FinanceGoogle extends CurrencyServiceAbstract implements CurrencyServiceInterface
{
    /**
     * @return string
     */
    public function getApiUrl()
    {
        return 'https://finance.google.com/finance/converter';
    }

    /**
     * Exchange rates
     *
     * @param float $amt
     * @return float
     */
    public function exchange(float $amt)
    {
        $url = $this->getApiUrl() . '?' . http_build_query(
                [
                    'a'    => $amt,
                    'from' => $this->getStoreCurrencyCode(),
                    'to'   => $this->getPayPalCurrencyCode($this->getStoreId()),
                ]
            );

        $this->getCurl()->get($url);
        $response = $this->getCurl()->getBody();

        preg_match("/<span class=bld>(.*)<\/span>/", $response, $converted);

        return preg_replace("/[^0-9.]/", "", $converted[1]);
    }
}