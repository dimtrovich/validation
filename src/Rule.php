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
 * @method static \Rakit\Validation\Rules\Accepted                   accepted()
 * @method static \Dimtrovich\Validation\Rules\AcceptedIf            acceptedIf()
 * @method static \Dimtrovich\Validation\Rules\ActiveURL             activeUrl()
 * @method static \Dimtrovich\Validation\Rules\After                 after()
 * @method static \Dimtrovich\Validation\Rules\AfterOrEqual          afterOrEqual()
 * @method static \Dimtrovich\Validation\Rules\Alpha                 alpha()
 * @method static \Dimtrovich\Validation\Rules\AlphaDash             alphaDash()
 * @method static \Dimtrovich\Validation\Rules\AlphaNum              alphaNum()
 * @method static \Rakit\Validation\Rules\AlphaSpaces                alphaSpaces()
 * @method static \Dimtrovich\Validation\Rules\AnyOf                 anyOf(array $allowed_values)
 * @method static \Dimtrovich\Validation\Rules\TypeArray             array()
 * @method static \Dimtrovich\Validation\Rules\ArrayCanOnlyHaveKeys  arrayCanOnlyHaveKeys(array $keys)
 * @method static \Dimtrovich\Validation\Rules\ArrayMustHaveKeys     arrayMustHaveKeys(array $keys)
 * @method static \Dimtrovich\Validation\Rules\Ascii                 ascii()
 * @method static \Dimtrovich\Validation\Rules\Base64                base64()
 * @method static \Dimtrovich\Validation\Rules\Before                before()
 * @method static \Dimtrovich\Validation\Rules\BeforeOrEqual         beforeOrEqual()
 * @method static \Dimtrovich\Validation\Rules\Between               between()
 * @method static \Dimtrovich\Validation\Rules\Bic                   bic()
 * @method static \Dimtrovich\Validation\Rules\BitcoinAddress        bitcoinAddress()
 * @method static \Rakit\Validation\Rules\Boolean                    boolean()
 * @method static \Rakit\Validation\Rules\Callback                   callback()
 * @method static \Dimtrovich\Validation\Rules\Camelcase             camelcase()
 * @method static \Dimtrovich\Validation\Rules\CapitalCharWithNumber capitalCharWithNumber()
 * @method static \Dimtrovich\Validation\Rules\CarNumber             carNumber()
 * @method static \Dimtrovich\Validation\Rules\Cidr                  cidr()
 * @method static \Dimtrovich\Validation\Rules\Confirmed             confirmed()
 * @method static \Dimtrovich\Validation\Rules\Contains              contains()
 * @method static \Dimtrovich\Validation\Rules\ContainsAll           containsAll()
 * @method static \Dimtrovich\Validation\Rules\CreditCard            creditCard()
 * @method static \Dimtrovich\Validation\Rules\Currency              currency()
 * @method static \Dimtrovich\Validation\Rules\Date                  date(?string $format = null)
 * @method static \Dimtrovich\Validation\Rules\DateEquals            dateEquals()
 * @method static \Dimtrovich\Validation\Rules\Datetime              datetime()
 * @method static \Dimtrovich\Validation\Rules\Decimal               decimal(float $min, float $max = null)
 * @method static \Dimtrovich\Validation\Rules\Declined              declined()
 * @method static \Dimtrovich\Validation\Rules\DeclinedIf            declinedIf()
 * @method static \Rakit\Validation\Rules\Defaults                   defaults()
 * @method static \Rakit\Validation\Rules\Different                  different()
 * @method static \Rakit\Validation\Rules\Digits                     digits()
 * @method static \Rakit\Validation\Rules\DigitsBetween              digitsBetween()
 * @method static \Dimtrovich\Validation\Rules\Dimensions            dimensions()
 * @method static \Dimtrovich\Validation\Rules\DiscordUsername       discordUsername()
 * @method static \Dimtrovich\Validation\Rules\Distinct              distinct()
 * @method static \Dimtrovich\Validation\Rules\DoesntEndWith         doesntEndWith()
 * @method static \Dimtrovich\Validation\Rules\DoesntStartWith       doesntStartWith()
 * @method static \Dimtrovich\Validation\Rules\Domain                domain()
 * @method static \Dimtrovich\Validation\Rules\Duplicate             duplicate()
 * @method static \Dimtrovich\Validation\Rules\DuplicateCharacter    duplicateCharacter()
 * @method static \Dimtrovich\Validation\Rules\Ean                   ean(array $lengths = [8, 13])
 * @method static \Dimtrovich\Validation\Rules\Email                 email()
 * @method static \Dimtrovich\Validation\Rules\EndWith               endWith()
 * @method static \Dimtrovich\Validation\Rules\Enum                  enum(string $type = null)
 * @method static \Dimtrovich\Validation\Rules\EvenNumber            evenNumber()
 * @method static \Dimtrovich\Validation\Rules\Ext                   ext()
 * @method static \Rakit\Validation\Rules\Extension                  extension()
 * @method static \Dimtrovich\Validation\Rules\File                  file()
 * @method static \Dimtrovich\Validation\Rules\TypeFloat             float()
 * @method static \Dimtrovich\Validation\Rules\Fullname              fullname()
 * @method static \Dimtrovich\Validation\Rules\Gt                    gt()
 * @method static \Dimtrovich\Validation\Rules\Gte                   gte()
 * @method static \Dimtrovich\Validation\Rules\Gtin                  gtin(array $lengths = [8, 12, 13, 14])
 * @method static \Dimtrovich\Validation\Rules\Hash                  hash(string $algo, bool $allowUppercase = false)
 * @method static \Dimtrovich\Validation\Rules\Hashtag               hashtag()
 * @method static \Dimtrovich\Validation\Rules\Hex                   hex()
 * @method static \Dimtrovich\Validation\Rules\Hexcolor              hexcolor()
 * @method static \Dimtrovich\Validation\Rules\Htmlclean             htmlclean()
 * @method static \Dimtrovich\Validation\Rules\Htmltag               htmltag()
 * @method static \Dimtrovich\Validation\Rules\Iban                  iban(array|string $countries = [])
 * @method static \Dimtrovich\Validation\Rules\Image                 image()
 * @method static \Dimtrovich\Validation\Rules\Imei                  imei()
 * @method static \Rakit\Validation\Rules\In                         in()
 * @method static \Dimtrovich\Validation\Rules\InArray               inArray()
 * @method static \Dimtrovich\Validation\Rules\TypeInstanceOf        instanceOf(string $class_name)
 * @method static \Rakit\Validation\Rules\Integer                    integer()
 * @method static \Rakit\Validation\Rules\Ip                         ip()
 * @method static \Rakit\Validation\Rules\Ipv4                       ipv4()
 * @method static \Rakit\Validation\Rules\Ipv6                       ipv6()
 * @method static \Dimtrovich\Validation\Rules\Isbn                  isbn(array $lengths = [10, 13])
 * @method static \Rakit\Validation\Rules\Issn                       issn()
 * @method static \Rakit\Validation\Rules\Json                       json()
 * @method static \Dimtrovich\Validation\Rules\Jwt                   jwt()
 * @method static \Dimtrovich\Validation\Rules\Kebabcase             kebabcase()
 * @method static \Dimtrovich\Validation\Rules\Length                length(int $length)
 * @method static \Rakit\Validation\Rules\Lowercase                  lowercase()
 * @method static \Dimtrovich\Validation\Rules\Lt                    lt()
 * @method static \Dimtrovich\Validation\Rules\Lte                   lte()
 * @method static \Dimtrovich\Validation\Rules\MacAddress            macAddress()
 * @method static \Dimtrovich\Validation\Rules\Max                   max()
 * @method static \Dimtrovich\Validation\Rules\Mimes                 mimes(array $allowed_types)
 * @method static \Dimtrovich\Validation\Rules\Mimetypes             mimetypes(array $allowed_types)
 * @method static \Dimtrovich\Validation\Rules\Min                   min()
 * @method static \Dimtrovich\Validation\Rules\Missing               missing()
 * @method static \Dimtrovich\Validation\Rules\MissingIf             missingIf()
 * @method static \Dimtrovich\Validation\Rules\MissingUnless         missingUnless()
 * @method static \Dimtrovich\Validation\Rules\MissingWith           missingWith()
 * @method static \Dimtrovich\Validation\Rules\MissingWithAll        missingWithAll()
 * @method static \Dimtrovich\Validation\Rules\MultipleOf            multipleOf()
 * @method static \Rakit\Validation\Rules\NotIn                      notIn()
 * @method static \Dimtrovich\Validation\Rules\NotInArray            notInArray()
 * @method static \Dimtrovich\Validation\Rules\NotRegex              notRegex()
 * @method static \Rakit\Validation\Rules\Nullable                   nullable()
 * @method static \Rakit\Validation\Rules\Numeric                    numeric()
 * @method static \Dimtrovich\Validation\Rules\OddNumber             oddNumber()
 * @method static \Dimtrovich\Validation\Rules\Pascalcase            pascalcase()
 * @method static \Dimtrovich\Validation\Rules\Password              password(?int $min = null)
 * @method static \Dimtrovich\Validation\Rules\Pattern               pattern()
 * @method static \Dimtrovich\Validation\Rules\Phone                 phone(?string $country_code = null)
 * @method static \Dimtrovich\Validation\Rules\Port                  port()
 * @method static \Dimtrovich\Validation\Rules\Postalcode            postalcode(string $country_code)
 * @method static \Rakit\Validation\Rules\Present                    present()
 * @method static \Dimtrovich\Validation\Rules\PresentIf             presentIf(string $field, array $values)
 * @method static \Dimtrovich\Validation\Rules\PresentUnless         presentUnless(string $field, array $values)
 * @method static \Dimtrovich\Validation\Rules\PresentWith           presentWith(array $fields)
 * @method static \Dimtrovich\Validation\Rules\PresentWithAll        presentWithAll(array $fields)
 * @method static \Dimtrovich\Validation\Rules\Prohibited            prohibited()
 * @method static \Dimtrovich\Validation\Rules\ProhibitedIf          prohibitedIf()
 * @method static \Dimtrovich\Validation\Rules\ProhibitedUnless      prohibitedUnless()
 * @method static \Dimtrovich\Validation\Rules\Prohibits             prohibits()
 * @method static \Rakit\Validation\Rules\Regex                      regex()
 * @method static \Rakit\Validation\Rules\Required                   required()
 * @method static \Rakit\Validation\Rules\RequiredIf                 requiredIf()
 * @method static \Rakit\Validation\Rules\RequiredIfAccepted         requiredIfAccepted(string $field)
 * @method static \Rakit\Validation\Rules\RequiredIfDeclined         requiredIfDeclined(string $field)
 * @method static \Rakit\Validation\Rules\RequiredUnless             requiredUnless()
 * @method static \Rakit\Validation\Rules\RequiredWith               requiredWith()
 * @method static \Rakit\Validation\Rules\RequiredWithout            requiredWithout()
 * @method static \Rakit\Validation\Rules\RequiredWithoutAll         requiredWithoutAll()
 * @method static \Rakit\Validation\Rules\Same                       same(string $field)
 * @method static \Dimtrovich\Validation\Rules\Semver                semver()
 * @method static \Dimtrovich\Validation\Rules\Size                  size()
 * @method static \Dimtrovich\Validation\Rules\SlashEndOfString      slashEndOfString()
 * @method static \Dimtrovich\Validation\Rules\Slug                  slug()
 * @method static \Dimtrovich\Validation\Rules\Snakecase             snakecase()
 * @method static \Dimtrovich\Validation\Rules\StartWith             startWith()
 * @method static \Dimtrovich\Validation\Rules\TypeString            string()
 * @method static \Dimtrovich\Validation\Rules\Time                  time()
 * @method static \Dimtrovich\Validation\Rules\Timezone              timezone()
 * @method static \Rakit\Validation\Rules\Titlecase                  titlecase()
 * @method static \Dimtrovich\Validation\Rules\Ulid                  ulid()
 * @method static \Rakit\Validation\Rules\UploadedFile               uploadedFile()
 * @method static \Rakit\Validation\Rules\Uppercase                  uppercase()
 * @method static \Rakit\Validation\Rules\Url                        url()
 * @method static \Dimtrovich\Validation\Rules\Username              username()
 * @method static \Dimtrovich\Validation\Rules\Uuid                  uuid()
 * @method static \Dimtrovich\Validation\Rules\vatid                 vatid()
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
        $name = Text::snake($name);

        return Validator::rule($name, ...$arguments);
    }
}
