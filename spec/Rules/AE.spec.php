<?php

use BlitzPHP\Utilities\Date;
use BlitzPHP\Utilities\Helpers;
use Dimtrovich\Validation\Rule;
use Dimtrovich\Validation\Validator;

describe("AcceptedIf", function() {
    it("1: Passe", function() {
        $post = [
            'name'  => 'blitz',
            'admin' => true,
        ];
        $validation = Validator::make($post, [
            'name'     => 'required',
            'admin' => 'accepted_if:name,blitz',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name'  => 'blitz',
            'admin' => false,
        ];
        $validation = Validator::make($post, [
            'name'     => 'required',
            'admin' => 'accepted_if:name,blitz',
        ]);
        
        expect($validation->passes())->toBe(false);
    });

    it("3: Passe - car les donnees ne correspondent pas", function() {
        $post = [
            'name'  => 'not-blitz',
            'admin' => false,
        ];
        $validation = Validator::make($post, [
            'name'     => 'required',
            'admin' => 'accepted_if:name,blitz',
        ]);
        
        expect($validation->passes())->toBe(true);
    });
});

describe("ActiveURL", function() {
    if (Helpers::isConnected()) {
        it("1: Passe", function() {
            $post = [
                'url'  => 'https://google.com',
            ];
            $validation = Validator::make($post, [
                'url'     => 'active_url',
            ]);
            
            expect($validation->passes())->toBe(true);
        });
    }

    it("2: Echoue", function() {
        $post = [
            'url'  => 'http://example-qui-ne-donnera-certainement-jamais.com',
        ];
        $validation = Validator::make($post, [
            'url'     => 'active_url',
        ]);
        
        expect($validation->passes())->toBe(false);
    });

    it("3: Echoue - car n'est pas une URL", function() {
        $post = [
            'url_1'  => 'example-qui-ne-donnera-certainement-jamais',
            'url_2'  => 1,
            'url_3'  => true,
        ];
        $validation = Validator::make($post, [
            'url_1'     => 'active_url',
            'url_2'     => 'active_url',
            'url_3'     => 'active_url',
        ]);
        
        expect($validation->passes())->toBe(false);
    });
});

describe("After", function() {

    it("1: After - Utillisation d'une date", function() {
        $post = [
            'today'     => Date::now()->format('Y-m-d'),
        ];

        $validation = Validator::make($post, [
            'today'     => 'after:yesterday',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'today'     => 'after:2023-04-26',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: After - Utilisation d'un autre champ", function() {
        $post = [
            'today'  => '2023-04-27',
            'my_day' => '2023-04-26',
        ];
        $validation = Validator::make($post, [
            'today'     => 'after:my_day',
        ]);
        
        expect($validation->passes())->toBe(true);
    });
});

describe("AfterOrEqual", function() {
    it("1: AfterOrEqual - Utilisation d'une date", function() {
        $post = [
            'today'     => Date::now()->format('Y-m-d'),
        ];

        $validation = Validator::make($post, [
            'today'     => 'after_or_equal:yesterday',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'today'     => 'after_or_equal:2023-04-26',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'today'     => 'after_or_equal:today',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'today'     => 'after_or_equal:2023-04-27',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'today'     => 'after_or_equal:tomorrow',
        ]);
        expect($validation->passes())->toBe(false);
    });

    it("2: AfterOrEqual - Utilisation d'un autre champ", function() {
        $post = [
            'today'    => '2023-04-27',
            'my_day_1' => '2023-04-26',
            'my_day_2' => '2023-04-27',
            'my_day_3' => '2023-04-28',
        ];

        $validation = Validator::make($post, [
            'today'     => 'after_or_equal:my_day_1',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'today'     => 'after_or_equal:my_day_2',
        ]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, [
            'today'     => 'after_or_equal:my_day_3',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("Alpha", function() {
    it("1: Alpha simple", function() {
        $post = [
            'password' => 'blitz',
            'name' => 'blitz-php',
        ];

        $validation = Validator::make($post, [
            'password' => 'alpha',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'name' => 'alpha',
        ]);
        expect($validation->passes())->toBe(false);
    });

    it("2: Alpha ascii", function() {
        $post = [
            'name' => 'blitz',
        ];
        $validation = Validator::make($post, [
            'name' => 'alpha:ascii',
        ]);
        
        expect($validation->passes())->toBe(true);
    });
});

describe("AlphaDash & AlphaNum", function() {
    it("1: AlphaDash", function() {
        $post = [
            'password' => 'blitz.',
            'name'     => 'blitz-php',
            'author'   => []
        ];

        $validation = Validator::make($post, [
            'password' => 'alpha_dash',
            'author' => 'alpha_dash',
        ]);
        expect($validation->passes())->toBe(false);
        
        $validation = Validator::make($post, [
            'name' => 'alpha_dash',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("1: AlphaNum", function() {
        $post = [
            'password' => 'blitz123',
            'name'     => 'blitz-php',
            'author'   => []
        ];

        $validation = Validator::make($post, [
            'password' => 'alpha_num',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'name' => 'alpha_num',
            'author' => 'alpha_num',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("Array", function() {
    it("1: Array simple", function() {
        $post = [
            'name'  => ['blitz', 'php'],
        ];
        $validation = Validator::make($post, [
            'name'     => 'array',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Array avec cle", function() {
        $post = [
            'author'  => [
                'name' => 'Dimitri', 
                'role' => 'Lead Developer'
            ],
        ];
        $validation = Validator::make($post, [
            'author'     => 'array:name,role',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("3: Echoue - car une cle non autorisee (email) est presente", function() {
        $post = [
            'author'  => [
                'name' => 'Dimitri', 
                'role' => 'Lead Developer',
                'email' => 'dimitri@blitz-php.com',
            ],
        ];
        $validation = Validator::make($post, [
            'author'     => 'array:name,role',
        ]);
        
        expect($validation->passes())->toBe(false);
    });

    it("4: Echoue - car le champ n'est pas un tableau", function() {
        $post = [
            'author'  => 'Dimitri',
        ];
        $validation = Validator::make($post, [
            'author'     => 'array',
        ]);
        
        expect($validation->passes())->toBe(false);
    });
});

describe('Ascii', function() {
    it("1: Passe", function() {
        $post = [
            'name'  => null,
            'foo'  => 'bar',
        ];
        $validation = Validator::make($post, [
            'name' => 'ascii',
            'foo'  => 'ascii',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name'  => '这是一段中文',
        ];
        $validation = Validator::make($post, [
            'name' => 'ascii',
        ]);
        
        expect($validation->passes())->toBe(false);
    });
});

describe("Base64", function() {
    it("1: Passe", function() {
        $post = ['field' => 'bWlsd2Fk'];

        $validation = Validator::make($post, ['field' => 'base64']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'dimtrovich'];

        $validation = Validator::make($post, ['field' => 'base64']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Before", function() {

    it("1: Before - Utillisation d'une date", function() {
        $post = [
            'today'     => '2023-04-27',
        ];

        $validation = Validator::make($post, [
            'today'     => 'before:tomorrow',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'today'     => 'before:2023-04-28',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Before - Utilisation d'un autre champ", function() {
        $post = [
            'today'  => '2023-04-27',
            'my_day' => '2023-04-28',
        ];
        $validation = Validator::make($post, [
            'today'     => 'before:my_day',
        ]);
        
        expect($validation->passes())->toBe(true);
    });
});

describe("BeforeOrEqual", function() {
    it("1: BeforeOrEqual - Utilisation d'une date", function() {
        $post = [
            'today'     => Date::now()->format('Y-m-d'),
        ];

        $validation = Validator::make($post, [
            'today'     => 'before_or_equal:tomorrow',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'today'     => 'before_or_equal:2099-04-28',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'today'     => 'before_or_equal:today',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'today'     => 'before_or_equal:yesterday',
        ]);
        expect($validation->passes())->toBe(false);
    });

    it("2: BeforeOrEqual - Utilisation d'un autre champ", function() {
        $post = [
            'today'    => '2023-04-27',
            'my_day_1' => '2023-04-28',
            'my_day_2' => '2023-04-27',
            'my_day_3' => '2023-04-26',
        ];

        $validation = Validator::make($post, [
            'today'     => 'before_or_equal:my_day_1',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'today'     => 'before_or_equal:my_day_2',
        ]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, [
            'today'     => 'before_or_equal:my_day_3',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("BitcoinAddress", function() {
    it("1: Passe", function() {
        $post = ['field' => '1KFHE7w8BhaENAswwryaoccDb6qcT6DbYY'];

        $validation = Validator::make($post, ['field' => 'bitcoin_address']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'loremipsum'];

        $validation = Validator::make($post, ['field' => 'bitcoin_address']);
        expect($validation->passes())->toBe(false);
    });
});

describe("CamelCase", function() {
    it("1: Passe", function() {
        $post = ['field' => 'blitzPhp'];

        $validation = Validator::make($post, ['field' => 'camelcase']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'lorem_ipsum'];

        $validation = Validator::make($post, ['field' => 'camelcase']);
        expect($validation->passes())->toBe(false);
    });
});

describe("CapitalCharWithNumber", function() {
    it("1: Passe", function() {
        $post = ['field' => 'DIMTROVICH-237'];

        $validation = Validator::make($post, ['field' => 'capital_char_with_number']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'Dimtrovich-237'];

        $validation = Validator::make($post, ['field' => 'capital_char_with_number']);
        expect($validation->passes())->toBe(false);
    });
});

describe("CarNumber", function() {
    it("1: Passe", function() {
        $post = ['field' => 'KA01AB1234'];

        $validation = Validator::make($post, ['field' => 'car_number']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => '854128'];

        $validation = Validator::make($post, ['field' => 'car_number']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Confirmed", function() {
    it("1: Passe", function() {
        $post = [
            'password'              => 'blitz',
            'password_confirmation' => 'blitz',
        ];
        $validation = Validator::make($post, [
            'password'     => 'confirmed',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'password'              => 'blitz',
            'password_confirmation' => 'blitZ',
        ];
        $validation = Validator::make($post, [
            'password'     => 'confirmed',
        ]);
        
        expect($validation->passes())->toBe(false);
    });

    it("3: Echoue - car il n'y a pas le champ de confirmation", function() {
        $post = [
            'password'              => 'blitz',
        ];
        $validation = Validator::make($post, [
            'password'     => 'confirmed',
        ]);
        
        expect($validation->passes())->toBe(false);
    });
});

describe("Contains", function() {
    it("1: Passe", function() {
        $post = [
            'password'              => 'blitz',
        ];
        $validation = Validator::make($post, [
            'password'     => 'contains:blitz,php',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'password'              => 'blitz',
        ];
        $validation = Validator::make($post, [
            'password'     => 'contains:php',
        ]);
        
        expect($validation->passes())->toBe(false);
    });
});

describe("ContainsAll", function() {
    it("1: Passe", function() {
        $post = [
            'password'              => 'blitz-php',
        ];
        $validation = Validator::make($post, [
            'password'     => 'contains_all:blitz,php',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'password'              => 'blitz',
        ];
        $validation = Validator::make($post, [
            'password'     => 'contains_all:blitz,php',
        ]);
        
        expect($validation->passes())->toBe(false);
    });
});

describe("CreditCard", function() {
    it("1: Passe", function() {
        $post = [
            'card' => '4111111111111111', // A sample valid credit card number
        ];
        $validation = Validator::make($post, [
            'card' => 'credit_card',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'card' => '1234567812345678', // A sample invalid credit card number
        ];
        $validation = Validator::make($post, [
            'card' => 'credit_card',
        ]);
        
        expect($validation->passes())->toBe(false);
    });
});

describe("Date", function() {
    it("1: Date simple", function() {
        $post = [
            'birthday'      => '2023-04-27',
            'dateinterface' => Date::now(),
            'badday'        => 'no-valid-date',
        ];
        
        $validation = Validator::make($post, [
            'birthday'      => 'date',
            'dateinterface' => 'date',
        ]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, [
            'badday'      => 'date',
        ]);
        expect($validation->passes())->toBe(false);
    });

    it("2: Date avec format", function() {
        $post = [
            'birthday'  => '2023-04-27',
            'badday'    => 'no-valid-date',
            'badformat' => '27-04-2023',
        ];
        
        $validation = Validator::make($post, [
            'birthday'      => 'date:Y-m-d',
        ]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, [
            'badday'      => 'date:Y-m-d',
        ]);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make($post, [
            'badformat'      => 'date:Y-m-d',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("DateEquals", function() {
    it("1: DateEquals - Utillisation d'une date", function() {
        $post = [
            'today'     => '2023-04-27',
        ];

        $validation = Validator::make($post, [
            'today'     => 'date_equals:2023-04-27',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: DateEquals - Utilisation d'un autre champ", function() {
        $post = [
            'today'  => '2023-04-27',
            'my_day' => '2023-04-27',
        ];
        $validation = Validator::make($post, [
            'today'     => 'date_equals:my_day',
        ]);
        
        expect($validation->passes())->toBe(true);
    });
});

describe("Declined", function() {
    it("1: Passe", function() {
        $post = [
            'moderator' => 0,
            'admin'     => false,
            'customer'  => "false",
            'accept'    => "no",
            'light'     => "off",
        ];
        $validation = Validator::make($post, [
            'moderator' => 'declined',
            'admin'     => 'declined',
            'customer'  => 'declined',
            'accept'    => 'declined',
            'light'     => 'declined',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'moderator' => 1,
            'admin'     => true,
            'customer'  => "true",
            'accept'    => "yes",
            'light'     => "on",
        ];
        $validation = Validator::make($post, [
            'moderator' => 'declined',
            'admin'     => 'declined',
            'customer'  => 'declined',
            'accept'    => 'declined',
            'light'     => 'declined',
        ]);
        
        expect($validation->passes())->toBe(false);
    });
});

describe("DeclinedIf", function() {
    it("1: Passe", function() {
        $post = [
            'name'  => 'blitz',
            'admin' => false,
        ];
        $validation = Validator::make($post, [
            'name'     => 'required',
            'admin' => 'declined_if:name,blitz',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name'  => 'blitz',
            'admin' => true,
        ];
        $validation = Validator::make($post, [
            'name'     => 'required',
            'admin' => 'declined_if:name,blitz',
        ]);
        
        expect($validation->passes())->toBe(false);
    });

    it("3: Passe - car les donnees ne correspondent pas", function() {
        $post = [
            'name'  => 'not-blitz',
            'admin' => false,
        ];
        $validation = Validator::make($post, [
            'name'     => 'required',
            'admin' => 'declined_if:name,blitz',
        ]);
        
        expect($validation->passes())->toBe(true);
    });
});

describe("DiscordUsername", function() {
    it("1: Passe", function() {
        $post = ['field' => 'Dimtrovich#2134'];

        $validation = Validator::make($post, ['field' => 'discord_username']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => '#2134'];

        $validation = Validator::make($post, ['field' => 'discord_username']);
        expect($validation->passes())->toBe(false);
    });
});

describe("DoesntEndWith", function() {
    it("1: Passe", function() {
        $post = [
            'name'  => 'blitz',
        ];
        $validation = Validator::make($post, [
            'name'     => 'doesnt_end_with:php',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name'  => 'blitz',
        ];
        $validation = Validator::make($post, [
            'name'     => 'doesnt_end_with:tz',
        ]);
        
        expect($validation->passes())->toBe(false);
    });
});

describe("DoesntStartWith", function() {
    it("1: Passe", function() {
        $post = [
            'name'  => 'blitz',
        ];
        $validation = Validator::make($post, [
            'name'     => 'doesnt_start_with:php',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name'  => 'blitz',
        ];
        $validation = Validator::make($post, [
            'name'     => 'doesnt_start_with:bli',
        ]);
        
        expect($validation->passes())->toBe(false);
    });
});

describe("Domain", function() {

    it("1: Passe", function() {
        $post = ['field' => 'github.com'];

        $validation = Validator::make($post, ['field' => 'domain']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'github/com'];

        $validation = Validator::make($post, ['field' => 'domain']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Duplicate", function() {
    it("1: Passe", function() {
        $post = ['field' => 1283456];

        $validation = Validator::make($post, ['field' => 'duplicate']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 1123456];

        $validation = Validator::make($post, ['field' => 'duplicate']);
        expect($validation->passes())->toBe(false);
    });
});

describe("DuplicateCharacter", function() {

    it("1: Passe", function() {
        $post = ['field' => '1,2,3,4,5,6,7,8,9'];

        $validation = Validator::make($post, ['field' => 'duplicate_character']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => '1,2,2,3,3,3,4,5,6,7,8,9'];

        $validation = Validator::make($post, ['field' => 'duplicate_character']);
        expect($validation->passes())->toBe(false);
    });
});

describe("EndWith", function() {
    it("1: Passe", function() {
        $post = [
            'name'  => 'blitz',
        ];
        $validation = Validator::make($post, [
            'name'     => 'end_with:tz',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name'  => 'blitz',
        ];
        $validation = Validator::make($post, [
            'name'     => 'end_with:bli',
        ]);
        
        expect($validation->passes())->toBe(false);
    });
});

describe("Enum", function() {
    enum Suit: string
    {
        case Hearts = 'H';
        case Diamonds = 'D';
        case Spades = 'S';
    }

    it("1: Passe", function() {
        $validation = Validator::make(['suit' => 'H'], [
            'suit'     => 'enum:'.Suit::class
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make(['suit' => 'S'], [
            'suit'     => Validator::rule('enum', Suit::class)
        ]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['suit' => 'D'], [
            'suit'     => Rule::enum()->type(Suit::class)
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $validation = Validator::make(['suit' => 'O'], [
            'suit'     => 'enum:'.Suit::class
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("EvenNumber", function() {

    it("1: Passe", function() {
        $post = ['field' => '4'];

        $validation = Validator::make($post, ['field' => 'even_number']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => '5'];

        $validation = Validator::make($post, ['field' => 'even_number']);
        expect($validation->passes())->toBe(false);
    });
});
