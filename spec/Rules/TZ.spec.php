<?php

use BlitzPHP\Utilities\String\Uuid;
use Dimtrovich\Validation\Validator;

describe("Time", function() {
    it("1: Passe", function() {
        $post = [
            'time'        => '10:49',
            'with_second' => '10:49:05',
        ];
        
        $validation = Validator::make($post, [
            'time'        => 'time',
            'with_second' => 'time:strict',
        ]);
        expect($validation->passes())->toBeTruthy();
    });

    it("2: Echoue", function() {
        $post = [
            'time'        => '2024-01-10 10:49',
            'with_second' => '2024-01-10 10:49:05',
        ];

        $validation = Validator::make($post, [
            'time'        => 'time',
            'with_second' => 'time:strict',
        ]);
        expect($validation->passes())->toBeFalsy();
    });
});

describe("Timezone", function() {
    it("1: Passe", function() {
        $post = [
            'zone' => 'Africa/Douala',
        ];

        $validation = Validator::make($post, ['zone' => 'timezone']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'zone' => 'Africa/Bafoussam',
        ];

        $validation = Validator::make($post, ['zone' => 'timezone']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Titlecase", function() {
    it("Titlecase", function() {
        $values = [
            [true, 'Foo'],
            [true, 'FooBar'],
            [true, 'Foo Bar'],
            [true, 'F Bar'],
            [true, '6 Bar'],
            [true, 'FooBar Baz'],
            [true, 'Foo Bar Baz'],
            [true, 'Foo-Bar Baz'],
            [true, 'Ba_r Baz'],
            [true, 'F00 Bar Baz'],
            [true, 'Ês Üm Ñõ'],
            [false, 21],
            [false, 'foo'],
            [false, 'Foo '],
            [false, ' Foo'],
            [false, 'Foo bar'],
            [false, 'foo bar'],
            [false, 'Foo Bar baz'],
            [false, 'Foo bar baz'],
            [false, '-fooBar'],
            [false, '-fooBar-'],
            [false, 'The quick brown fox jumps over the lazy dog.'],
        ];

        foreach ($values as $value) {
            $validation = Validator::make(['field' => $value[1]], ['field' => 'titlecase']);

            expect($validation->passes())->toBe($value[0]);
        }
    });
});

describe("Username", function() {
    it("1: Passe", function() {
        $post = ['field' => 'dimtrovich'];

        $validation = Validator::make($post, ['field' => 'username']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'dimtrovich!@!'];

        $validation = Validator::make($post, ['field' => 'username']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Ulid", function() {
    it("1: Passe", function() {
        $post = ['field' => '01ARZ3NDEKTSV4RRFFQ69G5FAV'];

        $validation = Validator::make($post, ['field' => 'ulid']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => '01ARZ3NDEKTSV4RRFFQ69G5FA'];

        $validation = Validator::make($post, ['field' => 'ulid']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Uuid", function() {
    it("1: Passe", function() {
        $post = [
            'uid_3' => Uuid::v3('79c984e8-27f6-4232-86ab-a38ccebd6826', 'Validation'),
            'uid_4' => Uuid::v4(),
            'uid_5' => Uuid::v5('79c984e8-27f6-4232-86ab-a38ccebd6826', 'Validation'),
        ];

        $validation = Validator::make($post, [
            'uid_3' => 'uuid',
            'uid_4' => 'uuid',
            'uid_5' => 'uuid',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'uid' => 'Africa/Bafoussam',
        ];

        $validation = Validator::make($post, ['uid' => 'uuid']);
        expect($validation->passes())->toBe(false);
    });
});

describe("VatId", function() {
    it("1: Passe", function() {
        $post = ['field' => 'EL123456789'];

        $validation = Validator::make($post, ['field' => 'vatid']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'EL123456789123678912'];

        $validation = Validator::make($post, ['field' => 'vatid']);
        expect($validation->passes())->toBe(false);
    });
});
