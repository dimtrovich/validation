<?php

use BlitzPHP\Filesystem\Files\UploadedFile;
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

describe("Max", function() {
    it("1: Passe", function() {
        $file = new UploadedFile(__FILE__, null, 0);
        allow($file)->toReceive('isValid')->andReturn(true);
        allow($file)->toReceive('getSize')->andReturn(3072);

        $values = [
            ['anc', 'max:3'],
            ['3', 'numeric|max:3'], // an equal value qualifies.
            ['2.001', 'numeric|max:3'], // '2.001' is considered as a float when the "Numeric" rule exists.
            ['3', 'decimal:0|max:3'], // an equal value qualifies.
            ['2.001', 'decimal:0,3|max:3'], // '2.001' is considered as a float when the "Numeric" rule exists.
            ['22', 'numeric|max:33'],
            [[1, 2, 3], 'array|max:4'],
            [$file, 'max:10'],
        ];

        foreach ($values as $value) {
            $validation = Validator::make(['foo' => $value[0]], ['foo' => $value[1]]);
            expect($validation->passes())->toBe(true);
        }
    });
    
    it("2: Echoue", function() {
        $file = new UploadedFile(__FILE__, null, 0);
        allow($file)->toReceive('getSize')->andReturn(4072);
        allow($file)->toReceive('isValid')->andReturn(true);

        $values = [
            [false, 'max:3'], // not countable
            ['aslksd', 'max:3'],
            ['211', 'numeric|max:100'],
            ['2.001', 'max:3'],  // '2.001' is a string of length 5 in absence of the "Numeric" rule.
            ['211', 'decimal:0|max:100'],
            [[1, 2, 3], 'array|max:2'],
            [$file, 'max:2'],
        ];
        
        foreach ($values as $value) {
            $validation = Validator::make(['foo' => $value[0]], ['foo' => $value[1]]);
            expect($validation->passes())->toBe(false);
        }
    });
});

describe("Mimes", function() {
    it("ValidMimes", function() {
        $file = [
            'name' => pathinfo(__FILE__, PATHINFO_BASENAME),
            'type' => 'text/plain',
            'size' => filesize(__FILE__),
            'tmp_name' => __FILE__,
            'error' => UPLOAD_ERR_OK
        ];

        allow(\Rakit\Validation\Rules\Mimes::class)->toReceive('isUploadedFile')->andReturn(true);

        $validation = Validator::make(['file' => $file], ['file' => 'mimes:txt']);
        expect($validation->passes())->toBe(true);
    });

    it("Filetypes", function() {
        allow(\Rakit\Validation\Rules\Mimes::class)->toReceive('isUploadedFile')->andReturn(true);

        $validation = Validator::make(['file' => [
            'name' => pathinfo(__FILE__, PATHINFO_BASENAME),
            'type' => 'text/plain',
            'size' => 1024, // 1K
            'tmp_name' => __FILE__,
            'error' => 0
        ]], ['file' => 'mimes:png,jpeg']);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make(['file' => [
            'name' => pathinfo(__FILE__, PATHINFO_BASENAME),
            'type' => 'image/png',
            'size' => 10 * 1024,
            'tmp_name' => __FILE__,
            'error' => 0
        ]], ['file' => 'mimes:png,jpeg']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['file' => [
            'name' => pathinfo(__FILE__, PATHINFO_BASENAME),
            'type' => 'image/jpeg',
            'size' => 10 * 1024,
            'tmp_name' => __FILE__,
            'error' => 0
        ]], ['file' => 'mimes:png,jpeg']);
        expect($validation->passes())->toBe(true);
    });
});

describe("Mimetypes", function() {
    beforeEach(function() {
        allow(UploadedFile::class)->toReceive('clientExtension')->andReturn('rtf');
        allow(UploadedFile::class)->toReceive('guessExtension')->andReturn('rtf');
    });

    it("1: Passe", function() {
        $file = new UploadedFile(__FILE__, null, 0);
        allow($file)->toReceive('getMimeType')->andReturn('text/rtf');
        
        expect($file->getMimeType())->toBe('text/rtf');

        $validation = Validator::make(['file' => $file], ['file' => 'mimetypes:text/*']);
        expect($validation->passes())->toBe(true);

        //----------------------------------------------------------------
        
        $file = new UploadedFile(__FILE__, null, 0);
        allow($file)->toReceive('getMimeType')->andReturn('image/jpeg');

        $validation = Validator::make(['file' => $file], ['file' => 'mimetypes:image/jpeg']);
        expect($validation->passes())->toBe(true);
    });
    
    it("2: Echoue", function() {
        $file = new UploadedFile(__FILE__, null, 0);
        allow($file)->toReceive('getMimeType')->andReturn('application/pdf');

        $validation = Validator::make(['file' => $file], ['file' => 'mimetypes:text/rtf']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Min", function() {
    it("1: Passe", function() {
        $file = new UploadedFile(__FILE__, null, 0);
        allow($file)->toReceive('getSize')->andReturn(3072);

        $values = [
            ['3', 'numeric|min:3'], // an equal value qualifies.
            ['anc', 'min:3'],
            ['2.001', 'min:3'], // '2.001' is a string of length 5 in absence of the "Numeric" rule.
            ['3', 'decimal:0|min:3'], // an equal value qualifies.
            ['5', 'numeric|min:3'],
            [[1, 2, 3, 4], 'array|min:3'],
            [$file, 'min:2'],
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
            [false, 'min:3'], // not countable
            ['3', 'min:3'],
            ['2.001', 'numeric|min:3'], // '2.001' is considered as a float when the "Numeric" rule exists.
            ['2', 'numeric|min:3'],
            ['20', 'min:3'], // '20' is a string of length 2 in absence of the "Numeric" rule.
            ['2', 'decimal:0|min:3'],
            ['2.001', 'decimal:0|min:3'], // '2.001' is considered as a float when the "Numeric" rule exists.
            [[1, 2], 'array|min:3'],
            [$file, 'min:10'],
        ];

        foreach ($values as $value) {
            $validation = Validator::make(['foo' => $value[0]], ['foo' => $value[1]]);
            expect($validation->passes())->toBe(false);
        }
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

describe("PresentIf", function() {
    it('1: Passe', function() {  
        $validation = Validator::make(['bar' => 1, 'foo' => null], ['foo' => 'present_if:bar,2']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['bar' => 1, 'foo' => ''], ['foo' => 'present_if:bar,1']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['bar' => 1, 'foo' => [['id' => '', 'name' => 'a']]], ['foo.*.id' => 'present_if:bar,1']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['bar' => 1, 'foo' => [['id' => null, 'name' => 'a']]], ['foo.*.id' => 'present_if:bar,1']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['bar' => 1, 'foo' => '2'], ['foo' => 'present_if:bar,1']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['bar' => 2], ['foo' => 'present_if:bar,1']);
        expect($validation->passes())->toBe(true);
    });

    it('2: Echoue', function() {
        $validation = Validator::make(['bar' => 1], ['foo' => 'present_if:bar,1']);
        expect($validation->passes())->toBe(false);
        expect($validation->errors()->first('foo'))->toBe("The Foo field must be present when 'bar' is '1'.");
        
        $validation = Validator::make(['bar' => 1, 'foo' => [['name' => 'a']]], ['foo.*.id' => 'present_if:bar,1']);
        expect($validation->passes())->toBe(false);
        expect($validation->errors()->first('foo.0.id'))->toBe("The Foo 1 id field must be present when 'bar' is '1'.");
    });
});

describe("PresentUnless", function() {
    it('1: Passe', function() {  
        $validation = Validator::make(['bar' => 2, 'foo' => null], ['foo' => 'present_unless:bar,1']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['bar' => 2, 'foo' => ''], ['foo' => 'present_unless:bar,1']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['bar' => 2, 'foo' => [['id' => '', 'name' => 'a']]], ['foo.*.id' => 'present_unless:bar,1']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['bar' => 2, 'foo' => [['id' => null, 'name' => 'a']]], ['foo.*.id' => 'present_unless:bar,1']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['bar' => 2, 'foo' => '2'], ['foo' => 'present_unless:bar,1']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['bar' => 1], ['foo' => 'present_unless:bar,1']);
        expect($validation->passes())->toBe(true);
    });

    it('2: Echoue', function() {
        $validation = Validator::make(['bar' => 2], ['foo' => 'present_unless:bar,1']);
        expect($validation->passes())->toBe(false);
        expect($validation->errors()->first('foo'))->toBe("The Foo field must be present unless 'bar' is '1'.");
        
        $validation = Validator::make(['bar' => 2, 'foo' => [['name' => 'a']]], ['foo.*.id' => 'present_unless:bar,1']);
        expect($validation->passes())->toBe(false);
        expect($validation->errors()->first('foo.0.id'))->toBe("The Foo 1 id field must be present unless 'bar' is '1'.");
    });
});

describe("PresentWith", function() {
    it('1: Passe', function() {  
        $validation = Validator::make(['foo' => 1, 'bar' => 2], ['foo' => 'present_with:bar']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['foo' => null, 'bar' => 2], ['foo' => 'present_with:bar']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['foo' => '', 'bar' => 2], ['foo' => 'present_with:bar']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['foo' => [['id' => '']], 'bar' => 2], ['foo.*.id' => 'present_with:bar']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['foo' => [['id' => null]], 'bar' => 2], ['foo.*.id' => 'present_with:bar']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['foo' => 1], ['foo' => 'present_with:bar']);
        expect($validation->passes())->toBe(true);
    });

    it('2: Echoue', function() {
        $validation = Validator::make(['foo' => [['name' => 'a']], 'bar' => 2], ['foo.*.id' => 'present_with:bar']);
        expect($validation->passes())->toBe(false);
        expect($validation->errors()->first('foo.0.id'))->toBe("The Foo 1 id field must be present when 'bar' is present.");
        
        $validation = Validator::make(['bar' => 2], ['foo' => 'present_with:bar']);
        expect($validation->passes())->toBe(false);
        expect($validation->errors()->first('foo'))->toBe("The Foo field must be present when 'bar' is present.");
    });
});

describe("PresentWithAll", function() {
    it('1: Passe', function() {  
        $validation = Validator::make(['foo' => 1, 'bar' => 2, 'baz' => 1], ['foo' => 'present_with_all:bar,baz']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['foo' => null, 'bar' => 2, 'baz' => 1], ['foo' => 'present_with_all:bar,baz']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['foo' => '', 'bar' => 2, 'baz' => 1], ['foo' => 'present_with_all:bar,baz']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['foo' => [['id' => '']], 'bar' => 2, 'baz' => 1], ['foo.*.id' => 'present_with_all:bar,baz']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['foo' => [['id' => null]], 'bar' => 2, 'baz' => 1], ['foo.*.id' => 'present_with_all:bar,baz']);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make(['foo' => 1, 'bar' => 2], ['foo' => 'present_with_all:bar,baz']);
        expect($validation->passes())->toBe(true);
    });

    it('2: Echoue', function() {
        $validation = Validator::make(['foo' => [['name' => 'a']], 'bar' => 2, 'baz' => 1], ['foo.*.id' => 'present_with_all:bar,baz']);
        expect($validation->passes())->toBe(false);
        expect($validation->errors()->first('foo.0.id'))->toBe("The Foo 1 id field must be present when 'bar' or 'baz' are present.");
        
        $validation = Validator::make(['bar' => 2, 'baz' => 1], ['foo' => 'present_with_all:bar,baz']);
        expect($validation->passes())->toBe(false);
        expect($validation->errors()->first('foo'))->toBe("The Foo field must be present when 'bar' or 'baz' are present.");
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

describe("ProhibitedIf", function() {
    it("1: Passe", function() {
        $post = [
            'name' => true,
        ];
        $validation = Validator::make($post, [
            'foo' => 'prohibited_if:name,blitz',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name' => 'blitz',
            'foo'  => 'bar',
        ];
        $validation = Validator::make($post, [
            'foo' => 'prohibited_if:name,blitz',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("ProhibitedUnless", function() {
    it("1: Passe", function() {
        $post = [
            'name' => 'blitz',
        ];
        $validation = Validator::make($post, [
            'foo' => 'prohibited_unless:name,blitz',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name' => 'blitz',
            'foo'  => 'bar',
        ];
        $validation = Validator::make($post, [
            'foo' => 'prohibited_unless:name,bar',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("Prohibits", function() {
    it("1: Passe", function() {
        $post = [
            'foo' => 'bar',
        ];
        $validation = Validator::make($post, [
            'foo' => 'prohibits:name,version',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = [
            'name'    => 'blitz',
            'version' => '1.0',
            'foo'     => 'bar',
        ];
        $validation = Validator::make($post, [
            'foo' => 'prohibits:name,version',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

describe("Semver", function() {
    it("Semver", function() {
        $values = [
            [true, '1.0.0'],
            [true, '0.0.0'],
            [true, '3.2.1'],
            [true, '1.0.0-alpha'],
            [true, '1.0.0-alpha.1'],
            [true, '1.0.0-alpha1'],
            [true, '1.0.0-1'],
            [true, '1.0.0-0.3.7'],
            [true, '1.0.0-x.7.z.92'],
            [true, '1.0.0+20130313144700'],
            [true, '1.0.0-beta+exp.sha.5114f85'],
            [true, '1000.1000.1000'],
            [false, '1'],
            [false, '1.0'],
            [false, 'v1.0.0'],
            [false, '1.0.0.0'],
            [false, 'x.x.x'],
            [false, '1.x.x'],
            [false, '10.0.0.beta'],
            [false, 'foo'],
        ];
    
        foreach ($values as $value) {
            $post = ['field' => $value[1]];
    
            $validation = Validator::make($post, ['field' => 'semver']);
            expect($validation->passes())->toBe($value[0]);
        }
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
