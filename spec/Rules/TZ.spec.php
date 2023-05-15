<?php

use BlitzPHP\Utilities\String\Uuid;
use Dimtrovich\Validation\Validator;

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
