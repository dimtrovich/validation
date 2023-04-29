<?php

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

describe("Slug", function() {
    it("1: Passe", function() {
        $post = [
            'slug_1'  => 'a-simple-article-of-blog',
            'slug_2'  => '2023-04-29-a-simple-article-of-blog',
            'slug_3'  => '2023-04-29_a-simple-article-of-blog',
            'slug_4'  => '2023-04-29',
        ];

        $validation = Validator::make($post, [
            'slug_1' => 'slug',
            'slug_2' => 'slug',
            'slug_3' => 'slug',
            'slug_4' => 'slug',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'slug_1'  => 'a.simple.article-of-blog',
            'slug_2'  => 'A simple article of blog',
            'slug_3'  => 2023,
        ];

        $validation = Validator::make($post, [
            'slug_1' => 'slug',
        ]);
        expect($validation->passes())->toBe(false);
        
        $validation = Validator::make($post, [
            'slug_2' => 'slug',
        ]);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make($post, [
            'slug_3' => 'slug',
        ]);
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
