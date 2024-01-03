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

/**
 * @credit <a href="https://github.com/milwad-dev/laravel-validate">milwad/laravel-validate - Milwad\LaravelValidate\Rules\ValidCreditCard</a>
 * @credit <a href="https://codeigniter.com">CodeIgniter4 - CodeIgniter\Validation\CreditCardRules</a>
 *
 * @see http://en.wikipedia.org/wiki/Credit_card_number
 */
class CreditCard extends AbstractRule
{
    /**
     * @var array
     */
    protected $fillableParams = ['type'];

    /**
     * The cards that we support, with the defining details:
     *
     *  name        - The type of card as found in the form. Must match the user's value
     *  length      - List of possible lengths for the card number
     *  prefixes    - List of possible prefixes for the card
     *  checkdigit  - Boolean on whether we should do a modulus10 check on the numbers.
     */
    protected array $cards = [
        'American Express' => [
            'name'       => 'amex',
            'length'     => '15',
            'prefixes'   => '34,37',
            'checkdigit' => true,
        ],
        'China UnionPay' => [
            'name'       => 'unionpay',
            'length'     => '16,17,18,19',
            'prefixes'   => '62',
            'checkdigit' => true,
        ],
        'Dankort' => [
            'name'       => 'dankort',
            'length'     => '16',
            'prefixes'   => '5019,4175,4571,4',
            'checkdigit' => true,
        ],
        'DinersClub' => [
            'name'       => 'dinersclub',
            'length'     => '14,16',
            'prefixes'   => '300,301,302,303,304,305,309,36,38,39,54,55',
            'checkdigit' => true,
        ],
        'DinersClub CarteBlanche' => [
            'name'       => 'carteblanche',
            'length'     => '14',
            'prefixes'   => '300,301,302,303,304,305',
            'checkdigit' => true,
        ],
        'Discover Card' => [
            'name'       => 'discover',
            'length'     => '16,19',
            'prefixes'   => '6011,622,644,645,656,647,648,649,65',
            'checkdigit' => true,
        ],
        'InterPayment' => [
            'name'       => 'interpayment',
            'length'     => '16,17,18,19',
            'prefixes'   => '4',
            'checkdigit' => true,
        ],
        'JCB' => [
            'name'       => 'jcb',
            'length'     => '16,17,18,19',
            'prefixes'   => '352,353,354,355,356,357,358',
            'checkdigit' => true,
        ],
        'Maestro' => [
            'name'       => 'maestro',
            'length'     => '12,13,14,15,16,18,19',
            'prefixes'   => '50,56,57,58,59,60,61,62,63,64,65,66,67,68,69',
            'checkdigit' => true,
        ],
        'MasterCard' => [
            'name'       => 'mastercard',
            'length'     => '16',
            'prefixes'   => '51,52,53,54,55,22,23,24,25,26,27',
            'checkdigit' => true,
        ],
        'NSPK MIR' => [
            'name'       => 'mir',
            'length'     => '16',
            'prefixes'   => '2200,2201,2202,2203,2204',
            'checkdigit' => true,
        ],
        'Troy' => [
            'name'       => 'troy',
            'length'     => '16',
            'prefixes'   => '979200,979289',
            'checkdigit' => true,
        ],
        'UATP' => [
            'name'       => 'uatp',
            'length'     => '15',
            'prefixes'   => '1',
            'checkdigit' => true,
        ],
        'Verve' => [
            'name'       => 'verve',
            'length'     => '16,19',
            'prefixes'   => '506,650',
            'checkdigit' => true,
        ],
        'Visa' => [
            'name'       => 'visa',
            'length'     => '13,16,19',
            'prefixes'   => '4',
            'checkdigit' => true,
        ],
        // Canadian Cards
        'BMO ABM Card' => [
            'name'       => 'bmoabm',
            'length'     => '16',
            'prefixes'   => '500',
            'checkdigit' => false,
        ],
        'CIBC Convenience Card' => [
            'name'       => 'cibc',
            'length'     => '16',
            'prefixes'   => '4506',
            'checkdigit' => false,
        ],
        'HSBC Canada Card' => [
            'name'       => 'hsbc',
            'length'     => '16',
            'prefixes'   => '56',
            'checkdigit' => false,
        ],
        'Royal Bank of Canada Client Card' => [
            'name'       => 'rbc',
            'length'     => '16',
            'prefixes'   => '45',
            'checkdigit' => false,
        ],
        'Scotiabank Scotia Card' => [
            'name'       => 'scotia',
            'length'     => '16',
            'prefixes'   => '4536',
            'checkdigit' => false,
        ],
        'TD Canada Trust Access Card' => [
            'name'       => 'tdtrust',
            'length'     => '16',
            'prefixes'   => '589297',
            'checkdigit' => false,
        ],
    ];

    /**
     * @param mixed $value
     */
    public function check($value): bool
    {
        $value = preg_replace('/\D/', '', (string) $value);
        $value = str_replace([' ', '-'], '', $value);

        // Make sure we have a valid length
        if ($value === '') {
            return false;
        }

        // Non-numeric values cannot be a number...duh
        if (! is_numeric($value)) {
            return false;
        }

        if (empty($type = $this->parameter('type'))) {
            // if the card type is not specified, a rough check is performed directly using Luhn's algorithm,
            // without taking into account the constraints of each card type (prefix, number of characters).
            return $this->isValidLuhn($value);
        }

        $info = null;

        // Get our card info based on provided name.
        foreach ($this->cards as $card) {
            if ($card['name'] === $type) {
                $info = $card;
                break;
            }
        }

        // If empty, it's not a card type we recognize, or invalid type.
        if (empty($info)) {
            return false;
        }

        // Make sure it's a valid length for this card
        $lengths = explode(',', $info['length']);

        if (! in_array((string) strlen($value), $lengths, true)) {
            return false;
        }

        // Make sure it has a valid prefix
        $prefixes = explode(',', $info['prefixes']);

        $validPrefix = false;

        foreach ($prefixes as $prefix) {
            if (strpos($value, $prefix) === 0) {
                $validPrefix = true;
                break;
            }
        }

        if ($validPrefix === false) {
            return false;
        }

        // Still here? Then check the number against the Luhn algorithm, if required
        if ($info['checkdigit'] === true) {
            return $this->isValidLuhn($value);
        }

        return true;
    }

    /**
     * Checks the given number to see if the number passing a Luhn algorithm check.
     *
     * @see https://en.wikipedia.org/wiki/Luhn_algorithm
     * @credit <a href="https://github.com/milwad-dev/laravel-validate">milwad/laravel-validate - Milwad\LaravelValidate\Rules\ValidCreditCard</a>
     */
    protected function isValidLuhn(string $number): bool
    {
        $numLength = strlen($number);
        $sum       = 0;
        $reverse   = strrev($number);

        for ($i = 0; $i < $numLength; $i++) {
            $currentNum = (int) ($reverse[$i]);
            if ($i % 2 === 1) {
                $currentNum *= 2;
                if ($currentNum > 9) {
                    $currentNum -= 9;
                }
            }
            $sum += $currentNum;
        }

        return $sum % 10 === 0;
    }
}
