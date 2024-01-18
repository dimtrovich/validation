<?php

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

return [
    'accepted_if'              => 'The :attribute must be accepted.',
    'active_url'               => 'The :attribute must be an active URL.',
    'after'                    => 'The :attribute must be a date after :time.',
    'after_or_equal'           => 'The :attribute must be a date after or equal :time.',
    'alpha'                    => 'The :attribute only allows alphabet characters.',
    'alpha_dash'               => 'The :attribute only allows a-z, 0-9, _ and -',
    'alpha_num'                => 'The :attribute only allows alphabet and numeric.',
    'ascii'                    => 'The :attribute must be a ASCII string.',
    'base_64'                  => 'The :attribute must be a valid Base64 encoded string.',
    'before'                   => 'The :attribute must be a date before :time.',
    'before_or_equal'          => 'The :attribute must be a date before or equal :time.',
    'between'                  => 'The :attribute must be between :min and :max',
    'bic'                      => 'The :attribute is not a valid Business Identifier Code (BIC).',
    'bitcoin_address'          => 'The :attribute must be a valid bitcoin address.',
    'camelcase'                => 'The content :attribute must be formatted in Camel case.',
    'capital_char_with_number' => 'All words of :attribute must be capital & with hyphen & number.',
    'car_number'               => 'The :attribute must be a valid car number.',
    'cidr'                     => 'The :attribute must be a valid CIDR notation.',
    'confirmed'                => 'The :attribute must be same with :field.',
    'contains'                 => 'The :attribute must contains :allowed_values',
    'contains_all'             => 'The :attribute must contains all of :allowed_values',
    'credit_card'              => 'The :attribute is not valid credit card number.',
    'currency'                 => 'The :attribute must be a valid currency code.',
    'date'                     => 'The :attribute is not valid date.',
    'date_equals'              => 'The :attribute must be a equals date to :date.',
    'decimal'                  => 'The :attribute is not valid decimal number.',
    'declined'                 => 'The :attribute must be declined.',
    'declined_if'              => 'The :attribute must be declined.',
    'distinct'                 => 'The :attribute field has a duplicate value.',
    'discord_username'         => 'The :attribute must be a valid discord username.',
    'doesnt_end_with'          => 'The :attribute must not end with :allowed_values.',
    'doesnt_start_with'        => 'The :attribute must not start with :allowed_values.',
    'domain'                   => ':attribute must be a well formed domainname.',
    'duplicate'                => 'The :attribute contains duplicated items.',
    'duplicate_character'      => 'The :attribute contains duplicated items.',
    'ean'                      => 'The :attribute is not a valid European Article Number (EAN).',
    'email'                    => 'The :attribute is not valid email.',
    'end_with'                 => 'The :attribute must end with :allowed_values.',
    'enum'                     => 'The selected :attribute is invalid.',
    'even_number'              => 'The :attribute must be a even number.',
    'ext'                      => 'The :attribute field must have one of the following extensions: :allowed_values.',
    'fullname'                 => 'The :attribute must be a valid full name (first name and last name).',
    'gt'                       => 'The :attribute must be a numeric value greater than :value.',
    'gte'                      => 'The :attribute must be a numeric value greater or equal than :value.',
    'gtin'                     => 'The :attribute is not a valid Global Trade Item Number (GTIN).',
    'htmlclean'                => 'The value :attribute contains forbidden HTML code.',
    'hash'                     => 'The :attribute must a valid :algorithm hash.',
    'hashtag'                  => 'The :attribute must a valid hashtag.',
    'hex'                      => 'The :attribute must a valid hexadecimal string.',
    'hexcolor'                 => 'The :attribute must be a valid hexadecimal color code.',
    'htmltag'                  => 'The :attribute must a valid html tag.',
    'iban'                     => 'The :attribute must be a valid International Bank Account Number (IBAN).',
    'image'                    => 'The :attribute must a valid image.',
    'imei'                     => 'The value :attribute must be a valid Mobile Equipment Identity (IMEI).',
    'in_array'                 => 'The :attribute only allows :allowed_values.',
    'isbn'                     => ':attribute must be a valid International Standard Book Number (ISBN).',
    'issn'                     => 'The value :attribute must be a valid International Standard Serial Number (ISSN).',
    'jwt'                      => 'The value :attribute does not correspond to the JSON Web Token Format',
    'kebabcase'                => 'The content :attribute must be formatted in Kebab case.',
    'lt'                       => 'The :attribute must be a numeric value lower than :value.',
    'lte'                      => 'The :attribute must be a numeric value lower or equal than :value.',
    'mac_address'              => 'The value :attribute is no valid MAC address.',
    'max'                      => 'The :attribute maximum is :max',
    'mimes'                    => 'The :attribute field must be a file of type: :allowed_values.',
    'mimetypes'                => 'The :attribute field must be a file of type: :allowed_values.',
    'min'                      => 'The :attribute minimum is :min',
    'missing'                  => 'The :attribute field must be missing.',
    'missing_if'               => 'The :attribute field must be missing when :other is :value.',
    'missing_unless'           => 'The :attribute field must be missing unless :other is :value.',
    'missing_with'             => 'The :attribute field must be missing when :values is present.',
    'missing_with_all'         => 'The :attribute field must be missing when :values are present.',
    'multiple_of'              => 'The :attribute field must be a multiple of :value.',
    'not_in_array'             => 'The :attribute not allows :allowed_values.',
    'not_regex'                => 'The :attribute is not valid format.',
    'odd_number'               => 'The :attribute must be a odd number.',
    'pascalcase'               => 'The :attribute must be a pascal case string.',
    'password'                 => [
        'letters' => 'The :attribute must contain at least one letter.',
        'mixed'   => 'The :attribute must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'The :attribute must contain at least one number.',
        'symbols' => 'The :attribute must contain at least one symbol.',
    ],
    'pattern'             => 'The :attribute is invalid.',
    'phone'               => 'The :attribute is not valid phone number.',
    'port'                => 'The :attribute dont match a valid pattern.',
    'postalcode'          => 'The value :attribute must be a valid postal code.',
    'prohibited'          => 'The :attribute field is prohibited.',
    'prohibited_if'       => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless'   => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits'           => 'The :attribute field prohibits :other from being present.',
    'semver'              => 'The value :attribute is no version number using Semantic Versioning.',
    'size'                => 'The :attribute must have :size.',
    'slash_end_of_string' => 'The :attribute must be a string end with slash.',
    'slug'                => 'The value :attribute is no SEO-friendly short text.',
    'snakecase'           => 'The content :attribute must be formatted in Snake case.',
    'string'              => 'The :attribute must be a string.',
    'start_with'          => 'The :attribute must start with :allowed_values.',
    'timezone'            => 'The :attribute a valid timezone.',
    'titlecase'           => 'All words from :attribute must begin with capital letters.',
    'array'               => 'The :attribute must be array.',
    'instance_of'         => 'The :attribute must be an instance of :type.',
    'ulid'                => 'The :attribute must be a valid Ulid.',
    'username'            => 'The :attribute must be a valid username.',
    'uuid'                => 'The :attribute must b a valid Uuid.',
    'vatid'               => 'The :attribute must be a valid VAT ID.',
];
