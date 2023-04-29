<?php

use Dimtrovich\Validation\ValidatedInput;

describe("Validation / ValidatedInput", function() {
    beforeAll(function() {
        $this->items = [
            'name' => 'blitz-php',
            'author' => 'Dimitri Sitchet Tomkeu',
            'created_at' => 2022,
            'other' => ['dFramework', 'dane.js']
        ];
        $this->ValidatedInput = new ValidatedInput($this->items);
    });

    it("has", function() {
        expect($this->ValidatedInput->has('name'))->toBe(true);
        expect($this->ValidatedInput->has('surname'))->toBe(false);
    });

    it("missing", function() {
        expect($this->ValidatedInput->missing('surname'))->toBe(true);
        expect($this->ValidatedInput->missing('name', 'author'))->toBe(false);
    });

    it("only", function() {
        expect($this->ValidatedInput->only('name'))->toBe(["name" => "blitz-php"]);
        expect($this->ValidatedInput->only(['name', 'created_at']))->toBe([
            "name"       => "blitz-php",
            "created_at" => 2022
        ]);
    });
    
    it("except", function() {
        expect($this->ValidatedInput->except('name'))->toBe([
            'author' => 'Dimitri Sitchet Tomkeu',
            'created_at' => 2022,
            'other' => ['dFramework', 'dane.js']
        ]);
        expect($this->ValidatedInput->except('name', 'created_at'))->toBe([
            'author' => 'Dimitri Sitchet Tomkeu',
            'other' => ['dFramework', 'dane.js']
        ]);
    });
    
    it("merge", function() {
        $a = $this->ValidatedInput->merge(['language' => 'php']);

        expect($a)->toBeAnInstanceOf(ValidatedInput::class);
        expect($a->all())->toBe($this->items + ['language' => 'php']);
    });

    it("all", function() {
        expect($this->ValidatedInput->all())->toBe($this->items);
        expect($this->ValidatedInput->toArray())->toBe($this->items);
    });
    
    it("Iteration", function() {
        foreach ($this->ValidatedInput as $key => $value) {
            expect($this->items)->toContainKey($key);
            expect($value)->toBe($this->items[$key]);
        }
    });

    it("Magic methods", function() {
        expect($this->ValidatedInput->name)->toBe('blitz-php');
        expect($this->ValidatedInput['name'])->toBe('blitz-php');
        expect(isset($this->ValidatedInput->created_at))->toBe(true);

        $this->ValidatedInput->language = 'php';
        $this->ValidatedInput['type'] = 'framework';
        expect($this->ValidatedInput->toArray())->toBe($this->items + ['language' => 'php', 'type' => 'framework']);

        unset($this->ValidatedInput->language);
        unset($this->ValidatedInput['type']);
        expect($this->ValidatedInput->toArray())->toBe($this->items);
    });
});
