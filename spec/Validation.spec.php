<?php

use Dimtrovich\Validation\Exceptions\ValidationException;
use Dimtrovich\Validation\Validator;

describe("Validation / Validation", function() {
    describe("Validation simple", function() {

        it("Test simple", function() {
            $post = [
                'name'     => 'BlitzPHP',
                'password' => 'blitz',
            ];
            $validation = Validator::make($post, [
                'name'     => 'required',
                'password' => 'required',
            ]);
            
            expect($validation->passes())->toBe(true);
        });

        it("Recuperation des erreurs", function() {
            $post = [
                'email' => 'no-email',
            ];
            $validation = Validator::make($post, [
                'email'     => ['required', 'email'],
            ]);
            
            expect($validation->passes())->toBe(false);
            expect($validation->errors()->toArray())->toContainKey('email');
        });
        
        it("Callable", function() {
            $post = [
                'email' => 'email@example.com',
            ];

            $validation = Validator::make($post, [
                'email' => Validator::rule('email')
            ]);
            
            expect($validation->passes())->toBe(true);
        });
    });

    describe("Messages d'erreurs", function() {

        it("Messages", function() {
            $post = [
                'name'     => '',
                'password' => 'blitz-140',
            ];
            $validation = Validator::make($post, [
                'name'     => 'required',
                'password' => 'required|alpha',
            ], [
                'name:required'  => 'Le nom est obligatoire',
                'password:alpha' => 'Le mot de passe doit etre une chaine alphabetique'
            ]);
            
            expect($validation->passes())->toBe(false);
            expect($validation->errors()->first('name'))->toBe('Le nom est obligatoire');
            expect($validation->errors()->first('password'))->toBe('Le mot de passe doit etre une chaine alphabetique');
        });

        it("Alias", function() {
            $post = [
                'password' => 'blitz-140',
            ];
            $validation = Validator::make($post, [
                'password' => 'required|alpha',
            ])->alias([
                'password' => '`mot de passe`'
            ]);
            
            expect($validation->passes())->toBe(false);
            expect($validation->errors()->first('password'))->toBe('The `mot de passe` only allows alphabet characters');
        });

        it("Traductions: Regles", function() {
            $post = [
                'password' => '',
            ];
            $validation = Validator::make($post, [
                'password' => 'required',
            ])->translations([
                'required' => ':attribute est obligatiore'
            ]);
            
            expect($validation->passes())->toBe(false);
            expect($validation->errors()->first('password'))->toBe('Password est obligatiore');
        });
        
        it("Traductions: Operateurs", function() {
            $post = [
                'role' => 'customer',
            ];
            $validation = Validator::make($post, [
                'role' => 'in:admin,moderator,member'
            ])->translations([
                'or' => 'ou bien'
            ]);
            
            expect($validation->passes())->toBe(false);
            expect($validation->errors()->first('role'))->toBe("The Role only allows 'admin', 'moderator', ou bien 'member'");
        });
        
        it("Traductions: Operateurs et regle", function() {
            $post = [
                'role' => 'customer',
            ];
            $validation = Validator::make($post, [
                'role' => 'in:admin,moderator,member'
            ])->translations([
                'or' => 'ou bien',
                'in' => 'Le :attribute doit etre :allowed_values',
            ]);
            
            expect($validation->passes())->toBe(false);
            expect($validation->errors()->first('role'))->toBe("Le Role doit etre 'admin', 'moderator', ou bien 'member'");
        });
    });

    describe("Validation", function() {

        it("Donnees validees et non validees", function() {
            $post = [
                'password' => 'blitz-140',
                'name'     => 'blitz',
            ];
            $validation = Validator::make($post, [
                'password' => 'required|alpha',
                'name'     => 'required|alpha',
            ]);
            
            expect($validation->passes())->toBe(false);
            expect($validation->invalid())->toBe(['password' => 'blitz-140']);
            expect($validation->valid())->toBe(['name' => 'blitz']);
        });

        it("Donnees validees via validated", function() {
            $post = [
                'name' => 'blitz',
            ];
            $validation = Validator::make($post, [
                'name' => 'required',
            ]);
            
            expect($validation->validated())->toBe(['name' => 'blitz']);
        });
        
        it("Donnees validees via validate", function() {
            $post = [
                'name' => 'blitz',
            ];
            $validation = Validator::make($post, [
                'name' => 'required',
            ]);
            
            expect($validation->validate())->toBe(['name' => 'blitz']);
        });

        it("Exception via validated", function() {
            $post = [
                'name'     => '',
            ];
            $validation = Validator::make($post, [
                'name'     => 'required',
            ]);

            expect(function() use ($validation) {
                $validation->validated();
            })->toThrow(new ValidationException());
        });
        
        it("Exception via validate", function() {
            $post = [
                'name'     => '',
            ];
            $validation = Validator::make($post, [
                'name'     => 'required',
            ]);

            expect(function() use ($validation) {
                $validation->validate();
            })->toThrow(new ValidationException());
        });
        
        it("Safe", function() {
            $post = [
                'name' => 'blitz',
                'old' => 12
            ];
            $validation = Validator::make($post, [
                'name' => 'required',
                'old' => 'min:18',
            ]);
            
            try {
                expect($validation->safe()->all())->toBe(['name' => 'blitz']);
            }
            catch(Exception $e) {
                expect(get_class($e))->toBe(ValidationException::class);
            }
        });
    });
});
