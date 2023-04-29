<?php

use Dimtrovich\Validation\Rule;
use Dimtrovich\Validation\Validator;

describe("Gt", function() {
    it("1: Passe", function() {
        $post = [
            'value' => 5,
            'other' => 4
        ];

        $validation = Validator::make($post, [
            'value'     => 'gt:4'
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'value'     => 'gt:other'
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'value' => 5,
            'other' => 6,
            'fails' => [';']
        ];

        $validation = Validator::make($post, ['value' => 'gt:6']);
        expect($validation->passes())->toBe(false);
        
        $validation = Validator::make($post, ['fails' => 'gt:6']);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make($post, ['value' => 'gt:other']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Gte", function() {
    it("1: Passe", function() {
        $post = [
            'value' => 5,
            'other' => 4
        ];

        $validation = Validator::make($post, ['value' => 'gte:5']);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, ['value' => 'gte:other']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'value' => 5,
            'other' => 6,
            'fails' => false
        ];

        $validation = Validator::make($post, ['value' => 'gte:6']);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make($post, ['fails' => 'gte:6']);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make($post, ['value' => 'gte:other']);
        expect($validation->passes())->toBe(false);
    });
});

describe("InArray", function() {
    it("1: Passe", function() {
        $post = [
            'name'      => 'laravel',
            'framework' => ['laravel', 'codeigniter', 'symfony'],
        ];

        $validation = Validator::make($post, [
            'name' => 'in_array:framework',
        ]);
        expect($validation->passes())->toBe(true);
    });
    
    it("2: Echoue", function() {
        $post = [
            'name'      => 'nestjs',
            'author'    => 'johndoe',
            'framework' => ['laravel', 'codeigniter', 'symfony'],
        ];

        $validation = Validator::make($post, [
            'name' => 'in_array:framework',
            'name' => 'in_array:author',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("InstanceOf", function() {
    class Test {

    }

    it("1: Passe", function() {
        $post = [
            'obj'      => new Test,
            'obj_name' => Test::class,
        ];

        $validation = Validator::make($post, [
            'obj'      => 'instance_of:Test',
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'obj_name' => Rule::instanceOf(Test::class),
            'obj'      => Rule::instanceOf('Test'),
        ]);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, [
            'obj' => Rule::instanceOf(new Test),
        ]);
        expect($validation->passes())->toBe(true);
    });
    
    it("2: Echoue", function() {
        $post = [
            'obj'      => new stdClass,
        ];

        $validation = Validator::make($post, [
            'obj' => 'instance_of:Test',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("Lt", function() {
    it("1: Passe", function() {
        $post = [
            'value' => 4,
            'other' => 5
        ];

        $validation = Validator::make($post, ['value' => 'lt:5']);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, ['value' => 'lt:other']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'value' => 6,
            'other' => 5,
            'fails' => true
        ];

        $validation = Validator::make($post, ['value' => 'lt:5']);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make($post, ['fails' => 'lt:5']);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make($post, ['value' => 'lt:other']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Lte", function() {
    it("1: Passe", function() {
        $post = [
            'value' => 4,
            'other' => 5,
        ];

        $validation = Validator::make($post, ['value' => 'lte:4']);
        expect($validation->passes())->toBe(true);
        
        $validation = Validator::make($post, ['value' => 'lte:other']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'value' => 6,
            'other' => 5,
            'fails' => true,
        ];

        $validation = Validator::make($post, ['value' => 'lte:5']);
        expect($validation->passes())->toBe(false);
        
        $validation = Validator::make($post, ['fails' => 'lte:5']);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make($post, ['value' => 'lte:other']);
        expect($validation->passes())->toBe(false);
    });
});
