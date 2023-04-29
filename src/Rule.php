<?php

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dimtrovich\Validation;

use BlitzPHP\Utilities\String\Text;

/**
 * Facade to access to rules validator
 *
 * @method static \Rakit\Validation\Rules\Accepted             accepted()
 * @method static \Dimtrovich\Validation\Rules\AcceptedIf      acceptedIf()
 * @method static \Dimtrovich\Validation\Rules\ActiveURL       activeUrl()
 * @method static \Dimtrovich\Validation\Rules\After           after()
 * @method static \Dimtrovich\Validation\Rules\AfterOrEqual    afterOrEqual()
 * @method static \Dimtrovich\Validation\Rules\Alpha           alpha()
 * @method static \Dimtrovich\Validation\Rules\AlphaDash       alphaDash()
 * @method static \Dimtrovich\Validation\Rules\AlphaNum        alphaNum()
 * @method static \Rakit\Validation\Rules\AlphaSpaces          alphaSpaces()
 * @method static \Dimtrovich\Validation\Rules\TypeArray       array()
 * @method static \Dimtrovich\Validation\Rules\Ascii           ascii()
 * @method static \Dimtrovich\Validation\Rules\Before          before()
 * @method static \Dimtrovich\Validation\Rules\BeforeOrEqual   beforeOrEqual()
 * @method static \Rakit\Validation\Rules\Between              between()
 * @method static \Rakit\Validation\Rules\Boolean              boolean()
 * @method static \Rakit\Validation\Rules\Callback             callback()
 * @method static \Dimtrovich\Validation\Rules\Confirmed       confirmed()
 * @method static \Dimtrovich\Validation\Rules\Contains        contains()
 * @method static \Dimtrovich\Validation\Rules\ContainsAll     containsAll()
 * @method static \Dimtrovich\Validation\Rules\Date            date()
 * @method static \Dimtrovich\Validation\Rules\DateEquals      dateEquals()
 * @method static \Dimtrovich\Validation\Rules\Decimal         decimal()
 * @method static \Dimtrovich\Validation\Rules\Declined        declined()
 * @method static \Dimtrovich\Validation\Rules\DeclinedIf      declinedIf()
 * @method static \Rakit\Validation\Rules\Defaults             defaults()
 * @method static \Rakit\Validation\Rules\Different            different()
 * @method static \Rakit\Validation\Rules\Digits               digits()
 * @method static \Rakit\Validation\Rules\DigitsBetween        digitsBetween()
 * @method static \Dimtrovich\Validation\Rules\DoesntEndWith   doesntEndWith()
 * @method static \Dimtrovich\Validation\Rules\DoesntStartWith doesntStartWith()
 * @method static \Rakit\Validation\Rules\Email                email()
 * @method static \Dimtrovich\Validation\Rules\EndWith         endWith()
 * @method static \Dimtrovich\Validation\Rules\Enum            enum()
 * @method static \Rakit\Validation\Rules\Extension            extension()
 * @method static \Dimtrovich\Validation\Rules\Gt              gt()
 * @method static \Dimtrovich\Validation\Rules\Gte             gte()
 * @method static \Dimtrovich\Validation\Rules\Image           image()
 * @method static \Rakit\Validation\Rules\In                   in()
 * @method static \Dimtrovich\Validation\Rules\InArray         inArray()
 * @method static \Dimtrovich\Validation\Rules\TypeInstanceOf  instanceOf()
 * @method static \Rakit\Validation\Rules\Integer              integer()
 * @method static \Rakit\Validation\Rules\Ip                   ip()
 * @method static \Rakit\Validation\Rules\Ipv4                 ipv4()
 * @method static \Rakit\Validation\Rules\Ipv6                 ipv6()
 * @method static \Rakit\Validation\Rules\Json                 json()
 * @method static \Rakit\Validation\Rules\Lowercase            lowercase()
 * @method static \Dimtrovich\Validation\Rules\Lt              lt()
 * @method static \Dimtrovich\Validation\Rules\Lte             lte()
 * @method static \Dimtrovich\Validation\Rules\MacAddress      macAddress()
 * @method static \Rakit\Validation\Rules\Max                  max()
 * @method static \Rakit\Validation\Rules\Mimes                mimes(array $allowed_types)
 * @method static \Rakit\Validation\Rules\Min                  min()
 * @method static \Rakit\Validation\Rules\NotIn                notIn()
 * @method static \Dimtrovich\Validation\Rules\NotInArray      notInArray()
 * @method static \Dimtrovich\Validation\Rules\NotRegex        notRegex()
 * @method static \Rakit\Validation\Rules\Nullable             nullable()
 * @method static \Rakit\Validation\Rules\Numeric              numeric()
 * @method static \Rakit\Validation\Rules\Present              present()
 * @method static \Dimtrovich\Validation\Rules\Prohibited      prohibited()
 * @method static \Rakit\Validation\Rules\Regex                regex()
 * @method static \Rakit\Validation\Rules\Required             required()
 * @method static \Rakit\Validation\Rules\RequiredIf           requiredIf()
 * @method static \Rakit\Validation\Rules\RequiredUnless       requiredUnless()
 * @method static \Rakit\Validation\Rules\RequiredWith         requiredWith()
 * @method static \Rakit\Validation\Rules\RequiredWithout      requiredWithout()
 * @method static \Rakit\Validation\Rules\RequiredWithoutAll   requiredWithoutAll()
 * @method static \Rakit\Validation\Rules\Same                 same(string $field)
 * @method static \Dimtrovich\Validation\Rules\Size            size()
 * @method static \Dimtrovich\Validation\Rules\Slug            slug()
 * @method static \Dimtrovich\Validation\Rules\StartWith       startWith()
 * @method static \Dimtrovich\Validation\Rules\TypeString      string()
 * @method static \Dimtrovich\Validation\Rules\Timezone        timezonr()
 * @method static \Dimtrovich\Validation\Rules\Ulid            ulid()
 * @method static \Rakit\Validation\Rules\UploadedFile         uploadedFile()
 * @method static \Rakit\Validation\Rules\Uppercase            uppercase()
 * @method static \Rakit\Validation\Rules\Url                  url()
 * @method static \Dimtrovich\Validation\Rules\Uuid            uuid()
 */
abstract class Rule
{
    /**
     * Create and return a validator on the fly
     *
     * @return \Rakit\Validation\Rule
     */
    public static function __callStatic(string $name, array $arguments = [])
    {
        $name = Text::convertTo($name, 'snake');

        return Validator::rule($name, ...$arguments);
    }
}
