<?php

use Dimtrovich\Validation\Rule;
use Dimtrovich\Validation\Utils\Country;
use Dimtrovich\Validation\Validator;

describe("MacAddress", function() {
    it("1: Passe", function() {
        $post = [
            'mac'      => '35-D2-2A-13-B0-0F',
        ];

        $validation = Validator::make($post, ['mac' => 'mac_address']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'mac'      => '127.0.0.1',
        ];

        $validation = Validator::make($post, ['mac' => 'mac_address']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Missing", function() {
    it("1: Passe", function() {
        $post = ['a' => 5];

        $validation = Validator::make($post, ['b' => 'missing']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['b' => 15];

        $validation = Validator::make($post, ['b' => 'missing']);
        expect($validation->passes())->toBe(false);
    });
});

describe("MissingIf", function() {
    it("1: Passe", function() {
        $post = [
            'name'  => 'blitz',
        ];
        $validation = Validator::make($post, [
            'name'  => 'required',
            'admin' => 'missing_if:name,blitz',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name'  => 'blitz',
            'admin' => 'present',
        ];
        $validation = Validator::make($post, [
            'name'  => 'required',
            'admin' => 'missing_if:name,blitz',
        ]);
        
        expect($validation->passes())->toBe(false);
    });

    it("3: Passe - car les donnees ne correspondent pas", function() {
        $post = [
            'name'  => 'not-blitz',
            'admin' => 'present',
        ];
        $validation = Validator::make($post, [
            'name'  => 'required',
            'admin' => 'missing_if:name,blitz',
        ]);
        expect($validation->passes())->toBe(true);

        $post = [
            'name'  => 'not-blitz',
            'admin' => 'present',
        ];
        $validation = Validator::make($post, [
            'name'  => 'required',
            'admin' => 'missing_if:name,not-blitz',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("MissingUnless", function() {
    it("1: Passe", function() {
        $post = [
            'name'  => 'blitz',
            'admin' => 'present',
        ];
        $validation = Validator::make($post, [
            'name'  => 'required',
            'admin' => 'missing_unless:name,blitz',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name'  => 'blitz',
            'admin' => 'true',
        ];
        $validation = Validator::make($post, [
            'name'  => 'required',
            'admin' => 'missing_unless:name,not-blitz',
        ]);

        expect($validation->passes())->toBe(false);
    });
});

describe("MissingWith", function() {
    it("1: Passe", function() {
        $post = [
            'name'  => 'blitz',
        ];
        $validation = Validator::make($post, [
            'name'  => 'required',
            'admin' => 'missing_with:name,blitz',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'schild' => 'blitz',
            'admin'  => 'true',
        ];
        $validation = Validator::make($post, [
            'name'  => 'required',
            'admin' => 'missing_with:schild,blitz',
        ]);

        expect($validation->passes())->toBe(false);
    });
});

describe("MissingWithAll", function() {
    it("1: Passe", function() {
        $post = [
            'name'  => 'blitz',
        ];
        $validation = Validator::make($post, [
            'name'  => 'required',
            'admin' => 'missing_with_all:name,blitz',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'schild' => 'blitz',
            'admin'  => 'true',
        ];
        $validation = Validator::make($post, [
            'name'  => 'required',
            'admin' => 'missing_with_all:schild,blitz',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("MultipleOf", function() {
    it("1: Passe", function() {
        $post = [
            'a' => 5, 
            'b' => 15,
            'c' => 3,
            'd' => 9,
            'e' => 0,
        ];

        $validation = Validator::make($post, [
            'a' => 'multiple_of:5',
            'b' => 'multiple_of:3',
            'd' => 'multiple_of:c',
            'e' => 'multiple_of:5'
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'a' => 5, 
            'b' => 15,
            'c' => 3,
            'd' => 9,
            'e' => 0,
            'f' => 0,
            'g' => false,
        ];

        $validation = Validator::make($post, [
            'a' => 'multiple_of:4',
            'b' => 'multiple_of:4',
            'c' => 'multiple_of:d',
            'd' => 'multiple_of:0',
            'e' => 'multiple_of:f',
            'g' => 'multiple_of:f',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("NotInArray", function() {
    it("1: Passe", function() {
        $post = [
            'name'      => 'nestjs',
            'author'    => 'johndoe',
            'framework' => ['laravel', 'codeigniter', 'symfony'],
        ];

        $validation = Validator::make($post, [
            'name' => 'not_in_array:framework',
            'name' => 'not_in_array:author',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name'      => 'laravel',
            'framework' => ['laravel', 'codeigniter', 'symfony'],
        ];

        $validation = Validator::make($post, [
            'name' => 'not_in_array:framework',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("NotRegex", function() {
    it("1: Passe", function() {
        $post = [
            'name' => 'blitz',
        ];

        $validation = Validator::make($post, [
            'name'      => 'not_regex:/^[0-9]+$/',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name'   => 'blitz',
            'author' => new stdClass
        ];

        $validation = Validator::make($post, [
            'name' => 'not_regex:/^[a-z]+$/',
            'author' => 'not_regex:/^[a-z]+$/',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("OddNumber", function() {
    it("1: Passe", function() {
        $post = ['field' => '5'];

        $validation = Validator::make($post, ['field' => 'odd_number']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => '4'];

        $validation = Validator::make($post, ['field' => 'odd_number']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Pascalcase", function() {
    it("1: Passe", function() {
        $post = ['field' => 'BlitzPhp'];

        $validation = Validator::make($post, ['field' => 'pascalcase']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'blitzPhp'];

        $validation = Validator::make($post, ['field' => 'pascalcase']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Password", function() {
    it("1: Passe", function() {
        $post = ['field' => 'blitzphp'];

        $validation = Validator::make($post, ['field' => 'password']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'blitzphp'];

        $validation = Validator::make($post, ['field' => 'password:9']);
        expect($validation->passes())->toBe(false);
    });
    
    it("3: Complexe", function() {
        $post = ['field' => 'blitzphp'];

        $validation = Validator::make($post, [
            'field' => [Rule::password()->letters()->mixedCase()->numbers()]
        ]);
        expect($validation->passes())->toBe(false);
    });

    it("4: Complexe", function() {
        $post = [
            'field1' => 'Bl1tzphp@',
            'field2' => 'Bl1tzphp@'
        ];

        $validation = Validator::make($post, [
            'field1' => [Rule::password(9)->letters()->mixedCase()->numbers()->symbols()],
            'field2' => [Rule::password()->strong()]
        ]);
        expect($validation->passes())->toBe(true);
    });
});

describe("Pattern", function() {
    it("1: Passe", function() {
        $post = ['field' => '4444-4444-4444'];

        $validation = Validator::make($post, ['field' => 'pattern:4']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => '4444-4444-44444'];

        $validation = Validator::make($post, ['field' => 'pattern:4']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Phone", function() {
    it("1: Passe", function() {
        $rules = [
            'phone_number' => 'phone',
            'phone_cm'     => Rule::phone(Country::CAMEROON),
        ];
        $data = ['phone_number' => '09366000000', 'phone_cm' => '+237677889900'];
    
        $validation = Validator::make($data, $rules);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $rules = [
            'phone_number' => 'phone',
            'phone_bj' => Rule::phone(Country::BENIN),
        ];
        $data = [
            'phone_number' => '123456789',
            'phone_bj' => '+22697000000',
        ];

        $validation = Validator::make($data, $rules);
        expect($validation->passes())->toBe(false);
    });
});

describe("Port", function() {
    it("1: Passe", function() {
        $post = ['field' => '8080'];

        $validation = Validator::make($post, ['field' => 'port']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => '158754'];

        $validation = Validator::make($post, ['field' => 'port']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Prohibited", function() {
    it("1: Passe", function() {
       $validation = Validator::make([], [
            'name'      => 'prohibited',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name' => 'blitz',
        ];

        $validation = Validator::make($post, [
            'name' => 'prohibited',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("Size", function() {
    it("1: Passe", function() {
        $post = [
            'name'    => 'blitz',
            'license' => ['MIT', 'GPL', ['M', 'I', 'T'], 'MPL'],
            'date'    => 2022,
        ];

        $validation = Validator::make($post, [
            'name'      => 'size:5',
            'license'   => 'size:4|array',
            'license.*' => 'size:3',
            'date'      => 'size:2022',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name' => 'blitz',
        ];

        $validation = Validator::make($post, [
            'name' => 'size:2',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("SlashEndOfString", function() {
    it("1: Passe", function() {
        $post = ['field' => 'dimtrovich/'];

        $validation = Validator::make($post, ['field' => 'slash_end_of_string']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'dimtrovich'];

        $validation = Validator::make($post, ['field' => 'slash_end_of_string']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Slug", function() {
    it("1: Passe", function() {
        $post = [
            'slug_1'  => 'a-simple-article-of-blog',
            'slug_2'  => '2023-04-29-a-simple-article-of-blog',
            'slug_3'  => '2023-04-29',
        ];

        $validation = Validator::make($post, [
            'slug_1' => 'slug',
            'slug_2' => 'slug',
            'slug_3' => 'slug',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'slug_1'  => 'a.simple.article-of-blog',
            'slug_2'  => 'A simple article of blog',
            'slug_3'  => 2023,
            'slug_4'  => '2023-04-29_a-simple-article-of-blog',
        ];

        foreach (array_keys($post) as $rule) {
            $validation = Validator::make($post, [$rule => 'slug']);
            expect($validation->passes())->toBe(false);
        }
    });
});

describe("Snakecase", function() {
    it("1: Passe", function() {
        $post = ['field' => 'blitz_php'];

        $validation = Validator::make($post, ['field' => 'snakecase']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'blitzPhp'];

        $validation = Validator::make($post, ['field' => 'snakecase']);
        expect($validation->passes())->toBe(false);
    });
});

describe("StartWith", function() {
    it("1: Passe", function() {
        $post = [
            'name'  => 'blitz',
        ];
        $validation = Validator::make($post, [
            'name'     => 'start_with:bli',
        ]);
        
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name'  => 'blitz',
        ];
        $validation = Validator::make($post, [
            'name'     => 'start_with:php',
        ]);
        
        expect($validation->passes())->toBe(false);
    });
});

describe("String", function() {
    it("1: Passe", function() {
        $post = [
            'name'  => 'blitz',
        ];

        $validation = Validator::make($post, ['name' => 'string']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'year'  => 2023,
        ];
        
        $validation = Validator::make($post, ['year' => 'string']);
        expect($validation->passes())->toBe(false);
    });
});
