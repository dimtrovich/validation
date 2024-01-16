<?php

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dimtrovich\Validation\Rules;

class Currency extends AbstractRule
{
    private array $codes = [
        "ALL",
        "AFN",
        "ARS",
        "AWG",
        "AUD",
        "AZN",
        "BSD",
        "BBD",
        "BDT",
        "BYR",
        "BZD",
        "BMD",
        "BOB",
        "BAM",
        "BWP",
        "BGN",
        "BRL",
        "BND",
        "KHR",
        "CAD",
        "KYD",
        "CLP",
        "CNY",
        "COP",
        "CRC",
        "HRK",
        "CUP",
        "CZK",
        "DKK",
        "DOP",
        "XCD",
        "EGP",
        "SVC",
        "EEK",
        "EUR",
        "FKP",
        "FJD",
        "GHC",
        "GIP",
        "GTQ",
        "GGP",
        "GYD",
        "HNL",
        "HKD",
        "HUF",
        "ISK",
        "INR",
        "IDR",
        "IRR",
        "IMP",
        "ILS",
        "JMD",
        "JPY",
        "JEP",
        "KZT",
        "KPW",
        "KRW",
        "KGS",
        "LAK",
        "LVL",
        "LBP",
        "LRD",
        "LTL",
        "MKD",
        "MYR",
        "MUR",
        "MXN",
        "MNT",
        "MZN",
        "NAD",
        "NPR",
        "ANG",
        "NZD",
        "NIO",
        "NGN",
        "NOK",
        "OMR",
        "PKR",
        "PAB",
        "PYG",
        "PEN",
        "PHP",
        "PLN",
        "QAR",
        "RON",
        "RUB",
        "SHP",
        "SAR",
        "RSD",
        "SCR",
        "SGD",
        "SBD",
        "SOS",
        "ZAR",
        "LKR",
        "SEK",
        "CHF",
        "SRD",
        "SYP",
        "TWD",
        "THB",
        "TTD",
        "TRY",
        "TRL",
        "TVD",
        "UAH",
        "GBP",
        "USD",
        "UYU",
        "UZS",
        "VEF",
        "VND",
        "XAF",
        "XOF",
        "YER",
        "ZWD",
    ];

    /**
     * Check if the given value is a valid currency
     *
     * @credit <a href="https://github.com/stellarwp/validation/">stellarwp/validation - StellarWP\Validation\Rules\Currency</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        return in_array(strtoupper($value), $this->codes, true);
    }
}
