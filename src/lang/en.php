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
    'base_64'                  => 'The :attribute must be a valid base64 encoded string.',
    'before'                   => 'The :attribute must be a date before :time.',
    'before_or_equal'          => 'The :attribute must be a date before or equal :time.',
    'bitcoin_address'          => 'The :attribute must be a valid bitcoin address.',
    'camelcase'                => 'The :attribute must be a camel case string.',
    'capital_char_with_number' => 'All words of :attribute must be capital & with hyphen & number.',
    'car_number'               => 'The :attribute must be a valid car number.',
    'confirmed'                => 'The :attribute must be same with :field.',
    'contains'                 => 'The :attribute must contains :allowed_values',
    'contains_all'             => 'The :attribute must contains all of :allowed_values',
    'credit_card'              => 'The :attribute is not valid credit card number.',
    'date'                     => 'The :attribute is not valid date.',
    'date_equals'              => 'The :attribute must be a equals date to :date.',
    'decimal'                  => 'The :attribute is not valid decimal number.',
    'declined'                 => 'The :attribute must be declined.',
    'declined_if'              => 'The :attribute must be declined.',
    'discord_username'         => 'The :attribute must be a valid discord username.',
    'doesnt_end_with'          => 'The :attribute must not end with :allowed_values.',
    'doesnt_start_with'        => 'The :attribute must not start with :allowed_values.',
    'domain'                   => 'The :attribute must be a valid domain name.',
    'duplicate'                => 'The :attribute contains duplicated items.',
    'duplicate_character'      => 'The :attribute contains duplicated items.',
    'email'                    => 'The :attribute is not valid email.',
    'end_with'                 => 'The :attribute must end with :allowed_values.',
    'enum'                     => 'The selected :attribute is invalid.',
    'even_number'              => 'The :attribute must be a even number.',
    'gt'                       => 'The :attribute must be a numeric value greater than :value.',
    'gte'                      => 'The :attribute must be a numeric value greater or equal than :value.',
    'hashtag'                  => 'The :attribute must a valid hashtag.',
    'hexcolor'                 => 'The :attribute must a valid hex color.',
    'htmltag'                  => 'The :attribute must a valid html tag.',
    'iban'                     => 'The :attribute must a valid iban number.',
    'image'                    => 'The :attribute must a valid image.',
    'imei'                     => 'The :attribute must a valid IMEI address.',
    'in_array'                 => 'The :attribute only allows :allowed_values.',
    'jwt'                      => 'The :attribute must be a vqlid json web token.',
    'kebabcase'                => 'The :attribute must be a kebab case string.',
    'lt'                       => 'The :attribute must be a numeric value lower than :value.',
    'lte'                      => 'The :attribute must be a numeric value lower or equal than :value.',
    'mac_address'              => 'The :attribute a valid mac address.',
    'multiple_of'              => 'The :attribute must be a numeric value multiple of :value.',
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
    'prohibited'          => 'The :attribute is prohibited.',
    'size'                => 'The :attribute must have :size.',
    'slash_end_of_string' => 'The :attribute must be a string end with slash.',
    'slug'                => 'The :attribute must be a valid slug.',
    'snakecase'           => 'The :attribute must be a snake case string.',
    'string'              => 'The :attribute must be a string.',
    'start_with'          => 'The :attribute must start with :allowed_values.',
    'timezone'            => 'The :attribute a valid timezone.',
    'array'               => 'The :attribute must be array.',
    'instance_of'         => 'The :attribute must be an instance of :type.',
    'ulid'                => 'The :attribute must be a valid Ulid.',
    'username'            => 'The :attribute must be a valid username.',
    'uuid'                => 'The :attribute must b a valid Uuid.',
    'vatid'               => 'The :attribute must be a valid VAT ID.',
];
