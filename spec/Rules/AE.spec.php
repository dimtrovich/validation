<?php

use BlitzPHP\Filesystem\Files\UploadedFile;
use BlitzPHP\Utilities\Date;
use BlitzPHP\Utilities\Helpers;
use Dimtrovich\Validation\Rule;
use Dimtrovich\Validation\Validator;
use Rakit\Validation\MissingRequiredParameterException;

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

        $validation = Validator::make(['url' => 123], ['url' => 'active_url']);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make(['url' => 'foobar'], ['url' => 'active_url']);
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

describe("AnyOf", function() {
    it("1: Passe", function() {
        $post = [
            'name'  => 'dimtrovich',
            'admin' => 'foo',
        ];
        $validation = Validator::make($post, [
            'name'  => 'any_of:blitz,admin,dimtrovich',
            'admin' => 'any_of:foo,bar',
        ]);
        
        expect($validation->passes())->toBe(true);

        $post = [
            'name'  => 'dimtrovich|admin',
        ];
        $validation = Validator::make($post, [
            'name'  => Rule::anyOf()->values(['blitz','admin','dimtrovich'])->strict()->separator('|'),
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name'  => 'dimtrovich',
        ];
        $validation = Validator::make($post, [
            'name'  => 'any_of:blitz,admin',
        ]);
        
        expect($validation->passes())->toBe(false);
    });

    it("3: Strict", function() {
        $post = [
            'name'  => 1,
            'admin' => true,
        ];

        // Not strict
        $validation = Validator::make($post, [
            'name'  => 'any_of:1,2,3',
            'admin' => 'any_of:1,2,3',
        ]);
        expect($validation->passes())->toBe(true);

        // Strict
        $validation = Validator::make($post, [
            'name'  => Rule::anyOf(['1', '2', '3'])->strict(),
            'admin' => Rule::anyOf(['1', '2', '3'])->strict(),
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

describe("ArrayCanOnlyHaveKeys", function() {
    it("1: Passe", function() {
        $post = [
            'name' => ['foo' => 'bar', 'baz' => 'bob'],
        ];
        $validation = Validator::make($post, [
            'name'     => 'array_can_only_have_keys:foo,bar,baz',
        ]);
        
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, [
            'name' => Rule::arrayCanOnlyHaveKeys()->keys(['foo', 'bar', 'baz']),
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name' => ['foo' => 'bar', 'bob' => 'baz'],
        ];
        $validation = Validator::make($post, [
            'name'     => 'array_can_only_have_keys:foo,bar,baz',
        ]);
        
        expect($validation->passes())->toBe(false);

        $post = [
            'name' => 'foo',
        ];
        $validation = Validator::make($post, [
            'name'     => 'array_can_only_have_keys:foo,bar,baz',
        ]);
        
        expect($validation->passes())->toBe(false);
    });
});

describe("ArrayMustHaveKeys", function() {
    it("1: Passe", function() {
        $post = [
            'cart' => [
                'qty' => 'xyz',
                'itemName' => 'Lorem ipsum',
            ]
        ];
        $validation = Validator::make($post, [
            'cart' => 'array|array_must_have_keys:itemName,qty',
        ]);
        
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, [
            'name' => Rule::arrayMustHaveKeys()->keys(['itemName', 'qty']),
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'cart' => [
                'qty' => 'xyz',
                'itemName' => 'Lorem ipsum',
            ]
        ];
        $validation = Validator::make($post, [
            'cart' => 'array|array_must_have_keys:itemName,qty,amount',
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

describe("Between", function() {
    it("1: Passe", function() {
        $file = new UploadedFile(__FILE__, null, 0);
        allow($file)->toReceive('getSize')->andReturn(3072);

        $values = [
            ['anc', 'between:3,5'],
            ['ancf', 'between:3,5'],
            ['ancfs', 'between:3,5'],
            ['123', 'numeric|between:123,200'], // inclusive on min
            ['123', 'numeric|between:0,123'], // inclusive on max
            ['0.02', 'numeric|between:0.01,0.02'], // can work with float
            ['0.02', 'numeric|between:0.01,0.03'],
            ['3', 'numeric|between:1,5'],
            [[1, 2, 3], 'array|between:1,5'],
            [$file, 'between:1,5'],
        ];

        foreach ($values as $value) {
            $validation = Validator::make(['foo' => $value[0]], ['foo' => $value[1]]);
            expect($validation->passes())->toBe(true);
        }
    });
    
    it("2: Echoue", function() {
        $file = new UploadedFile(__FILE__, null, 0);
        allow($file)->toReceive('getSize')->andReturn(4072);

        $values = [
            [false, 'between:3,4'], // not countable
            ['asdad', 'between:3,4'],
            ['123', 'numeric|between:50,100'],
            ['0.001', 'numeric|between:0.01,0.03'],
            [[1, 2, 3], 'array|between:1,2'],
            [$file, 'between:1,2'],
        ];

        foreach ($values as $value) {
            $validation = Validator::make(['foo' => $value[0]], ['foo' => $value[1]]);
            expect($validation->passes())->toBe(false);
        }
    });
});

describe("Bic", function() {
    it('Bic', function() {
        $values = [
            [true, 'PBNKDEFF'],
            [true, 'NOLADE21SHO'],
            [false, 'foobar'],
            [false, 'xxx'],
            [false, 'ABNFDBF'],
            [false, 'GR82WEST'],
            [false, '5070081'],
            [false, 'DEUTDBBER'],
        ];
    
        foreach ($values as $value) {
            $post = ['field' => $value[1]];
    
            $validation = Validator::make($post, ['field' => 'bic']);
            expect($validation->passes())->toBe($value[0]);
        }
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

describe("Cidr", function() {
    it("Cidr", function() {
        $values = [
            [true, '0.0.0.0/0'],
            [true, '10.0.0.0/8'],
            [true, '1.1.1.1/32'],
            [true, '192.168.1.0/24'],
            [true, '192.168.1.1/24'],
            [false, '192.168.1.1'],
            [false, '1.1.1.1/3.14'],
            [false, '1.1.1.1/33'],
            [false, '1.1.1.1/100'],
            [false, '1.1.1.1/-3'],
            [false, '1.1.1/3'],
        ];
    
        foreach ($values as $value) {
            $post = ['field' => $value[1]];
    
            $validation = Validator::make($post, ['field' => 'cidr']);
            expect($validation->passes())->toBe($value[0]);
        }
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

describe("Currency", function() {
    it("Currency", function() {
        $values = [
            // normal
            ['USD', true],
            ['CAD', true],
            ['XAF', true],

            // should not be case-sensitive
            ['jpy', true],
            ['EuR', true],
            ['xof', true],

            // should fail
            ['US', false],
            ['USDD', false],
            ['US D', false],
            ['US-D', false],
            ['ABC', false],
            ['123', false],
        ];

        foreach ($values as $value) {
            $validation = Validator::make(['field' => $value[0]], ['field' => 'currency']);

            expect($validation->passes())->toBe($value[1]);
        }
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
            'fake'      => ['27-04-2023'],
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

        $validation = Validator::make($post, [
            'fake'      => 'date:Y-m-d',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("Datetime", function() {
    it("1: Passe", function() {
        $post = [
            'day' => '2024-01-10 10:49:05',
        ];
        
        $validation = Validator::make($post, [
            'day' => 'datetime',
        ]);
        expect($validation->passes())->toBeTruthy();
    });

    it("2: Echoue", function() {
        $post = [
            'dateinterface' => Date::now(),
            'badformat'     => '2024-01-10',
        ];

        $validation = Validator::make($post, [
            'badformat'     => 'datetime',
            'dateinterface' => 'datetime',
        ]);
        expect($validation->passes())->toBeFalsy();
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

describe("Dimensions", function() {
    it("Dimension", function() {
        $validation = Validator::make(['file' => [
            'name' => __DIR__ . '/../fixtures/image.png',
            'type' => 'image/png',
            'size' => 1024, // 1K
            'tmp_name' => __DIR__ . '/../fixtures/image.png',
            'error' => 0
        ]], ['file' => 'dimensions:min_width=5']);
        expect($validation->passes())->toBe(false);

        $file = new UploadedFile(__DIR__.'/../fixtures/image.png', null, 0);

        $validation = Validator::make(['file' => 'file'], ['file' => 'dimensions']);
        expect(fn() => $validation->passes())->toThrow(new MissingRequiredParameterException());
        
        $validation = Validator::make(['file' => 'file'], ['file' => 'dimensions:ratio=1/2']);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:min_width=5']);
        expect($validation->fails())->toBe(true);

        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:max_width=1']);
        expect($validation->fails())->toBe(true);

        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:min_height=5']);
        expect($validation->fails())->toBe(true);

        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:max_height=1']);
        expect($validation->fails())->toBe(true);

        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:ratio=1/1']);
        expect($validation->fails())->toBe(true);

        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:ratio=1']);
        expect($validation->fails())->toBe(true);

        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:min_width=1']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:max_width=10']);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:min_height=1']);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:max_height=10']);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:width=3']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:height=2']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:min_height=2,ratio=3/2']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:ratio=1.5']);
        expect($validation->passes())->toBe(true);

        // Knowing that demo image2.png has width = 4 and height = 2
        $file = new UploadedFile(__DIR__.'/../fixtures/image2.png', null, 0);
        
        // Ensure validation doesn't erroneously fail when ratio has no fractional part
        $validation = Validator::make(['file' => $file], ['file' => 'dimensions:ratio=2/1']);
        expect($validation->passes())->toBe(true);

        // This test fails without suppressing warnings on getimagesize() due to a read error.
        $emptyUploadedFile = new UploadedFile(__DIR__.'/../fixtures/empty.png', null, 0);
        
        $validation = Validator::make(['file' => $emptyUploadedFile], ['file' => 'dimensions:min_width=1']);
        expect($validation->fails())->toBe(true);
        
        // Knowing that demo image3.png has width = 7 and height = 10
        $uploadedFile = new UploadedFile(__DIR__.'/../fixtures/image3.png', null, 0);

        // Ensure validation doesn't erroneously fail when ratio has no fractional part
        $validation = Validator::make(['file' => $uploadedFile], ['file' => 'dimensions:ratio=2/3']);
        expect($validation->passes())->toBe(true);

        // Ensure svg images always pass as size is irrelevant (image/svg+xml)
        $svgXmlUploadedFile = new UploadedFile(__DIR__.'/../fixtures/image.svg', null, 0, null, 'image/svg+xml');
 
        $validation = Validator::make(['file' => $svgXmlUploadedFile], ['file' => 'dimensions:max_width=1,max_height=1']);
        expect($validation->passes())->toBe(true);

        // Ensure svg images always pass as size is irrelevant (image/svg)
        $svgUploadedFile = new UploadedFile(__DIR__.'/../fixtures/image2.svg', null, 0);

        $validation = Validator::make(['file' => $svgUploadedFile], ['file' => 'dimensions:max_width=1,max_height=1']);
        expect($validation->passes())->toBe(true);

        // Knowing that demo image4.png has width = 64 and height = 65
        $uploadedFile = new UploadedFile(__DIR__.'/../fixtures/image4.png', null, 0);

        $validation = Validator::make(['file' => $uploadedFile], ['file' => 'dimensions:ratio=1']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Distinct", function() {
    it("1: Passe", function() {
        $post = [
            'integer' => [81, 3, 15],
            'string'  => ['white', 'green', 'blue'],
            'mixed'   => [81, false, 'orange'],
            'foo' => [
                [
                    'id' => 5,
                    'name' => 'foo',
                ],
                [
                    'id' => 6,
                    'name' => 'bar',
                ],
                [
                    'id' => 4,
                    'name' => 'baz',
                ]
            ]
        ];
        $validation = Validator::make($post, [
            'foo.*.id' => 'distinct',
            'integer' => 'distinct',
            'string'  => 'distinct',
            'mixed'   => 'distinct',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'integer' => [81, 3, 15, 3],
            'string'  => ['white', 'green', 'blue', 'blue'],
            'foo' => [
                [
                    'id' => 5,
                    'name' => 'foo',
                ],
                [
                    'id' => 6,
                    'name' => 'bar',
                ],
                [
                    'id' => 5,
                    'name' => 'baz',
                ]
            ]
        ];
        $validation = Validator::make($post, [
            'foo.*.id' => 'distinct',
            'integer' => 'distinct',
            'string'  => 'distinct',
        ]);
        expect($validation->passes())->toBe(false);
    });

    it("3: Ignore case", function() {
        $post = [
            'string'  => ['white', 'green', 'BLUE', 'blue'],
            'foo' => [
                [
                    'id' => 5,
                    'name' => 'foo',
                ],
                [
                    'id' => 6,
                    'name' => 'BAR',
                ],
                [
                    'id' => 5,
                    'name' => 'bar',
                ]
            ]
        ];
        $validation = Validator::make($post, [
            'foo.*.name' => 'distinct',
            'string'  => 'distinct',
        ]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, [
            'foo.*.name' => 'distinct:ignore_case',
            'string'  => 'distinct:ignore_case',
        ]);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make($post, [
            'foo.*.name' => Rule::distinct()->strict()->ignoreCase(),
        ]);
        expect($validation->passes())->toBe(false);
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

describe("Ean", function() {
    it('Ean sans parametre', function() {       
        $values = [
            [true, '9789510475270'],
            [true, '4012345678901'],
            [true, '0712345678911'],
            [true, '5901234123457'],
            [true, '40123455'],
            [true, '96385074'],
            [true, '65833254'],
            [false, 'foo'],
            [false, '0000000000001'],
            [false, 'FFFFFFFFFFFFF'],
            [false, 'FFFFFFFFFFFF0'],
            [false, '4012345678903'],
            [false, '1xxxxxxxxxxx0'],
            [false, '4012342678901'],
            [false, '07123456789110712345678911'],
            [false, '10123455'],
            [false, '40113455'],
            [false, '978-3499255496'],
            [false, '00123456000018'], // GTIN-14
            [false, '012345678905'], // GTIN-12
        ];

        foreach ($values as $value) {
            $post = ['field' => $value[1]];

            $validation = Validator::make($post, ['field' => 'ean']);
            expect($validation->passes())->toBe($value[0]);
        }
    });

    it('Ean13', function() {       
        $values = [
            [true, '9789510475270'],
            [true, '4012345678901'],
            [true, '0712345678911'],
            [true, '5901234123457'],
            [false, '40123455'],
            [false, '96385074'],
            [false, '65833254'],
        ];

        foreach ($values as $value) {
            $post = ['field' => $value[1]];

            $validation = Validator::make($post, ['field' => 'ean:13']);
            expect($validation->passes())->toBe($value[0]);
        }
    });

    it('Ean8', function() {       
        $values = [
            [false, '4012345678901'],
            [false, '0712345678911'],
            [false, '5901234123457'],
            [true, '40123455'],
            [true, '96385074'],
            [true, '65833254'],
        ];

        foreach ($values as $value) {
            $post = ['field' => $value[1]];

            $validation = Validator::make($post, ['field' => 'ean:8']);
            expect($validation->passes())->toBe($value[0]);
        }
    });
});

describe("Email", function() {
    it("1: Passe", function() {
        $post = [
            'email'        => 'contact@example.com',
            'valid_syntax' => 'contact@32.com'
        ];
        $validation = Validator::make($post, [
            'email'        => 'email',
            'valid_syntax' => 'email',
        ]);
        
        expect($validation->passes())->toBe(true);
        
        $post = ['email'  => 'contact@example.com'];
        $validation = Validator::make($post, [
            'email' => 'email:dns',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'email' => 'contact-example.com',
        ];
        $validation = Validator::make($post, [
            'email' => 'email',
        ]);
        
        expect($validation->passes())->toBeFalsy();
        
        $post = ['valid_syntax'  => 'contact@32.com'];
        $validation = Validator::make($post, [
            'valid_syntax' => 'email:dns',
        ]);
        
        expect($validation->passes())->toBeFalsy();

        $post = [
            'empty'      => '',
            'not_string' => 1,
        ];
        $validation = Validator::make($post, [
            'empty'      => 'email',
            'not_string' => 'email',
        ]);
        
        expect($validation->passes())->toBeFalsy();
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
        
        $post = [
            'alpha'  => range('a','z'),
        ];
        $validation = Validator::make($post, [
            'name'     => 'end_with:x,y,z',
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

        $validation = Validator::make(['suit' => false], [
            'suit'     => 'enum:'.Suit::class
        ]);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make(['suit' => 'O'], [
            'suit'     => 'enum:suite'
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

describe("Ext", function() {
    it("1: Passe", function() {
        $file = new UploadedFile(__FILE__, null, 0);
        allow($file)->toReceive('clientExtension')->andReturn('pdf');
    
        expect($file->clientExtension())->toBe('pdf');

        $validation = Validator::make(['file' => $file], ['file' => 'ext:pdf']);
        expect($validation->passes())->toBe(true);

        //----------------------------------------------------------------
        
        $file = new UploadedFile(__FILE__, null, 0);
        allow($file)->toReceive('clientExtension')->andReturn('jpg');

        $validation = Validator::make(['file' => $file], ['file' => 'ext:jpg']);
        expect($validation->passes())->toBe(true);
        
        //----------------------------------------------------------------

        $file = new UploadedFile(__FILE__, null, 0);
        allow($file)->toReceive('clientExtension')->andReturn('jpg');

        $validation = Validator::make(['file' => $file], ['file' => 'ext:jpeg,jpg']);
        expect($validation->passes())->toBe(true);
    });
    
    it("2: Echoue", function() {
        
        $file = new UploadedFile(__FILE__, null, 0);
        allow($file)->toReceive('clientExtension')->andReturn('jpg');

        $validation = Validator::make(['file' => $file], ['file' => 'ext:jpeg']);
        expect($validation->passes())->toBe(false);
        
        //----------------------------------------------------------------

        $file = new UploadedFile(__FILE__, null, 0);
        allow($file)->toReceive('clientExtension')->andReturn('jpeg');

        $validation = Validator::make(['file' => $file], ['file' => 'mimes:jpg|ext:jpg']);
        expect($validation->passes())->toBe(false);
    });
});
