<?php

use Dimtrovich\Validation\Rule;
use Dimtrovich\Validation\Rules\File as RulesFile;
use Dimtrovich\Validation\Spec\File;
use Dimtrovich\Validation\Validator;

describe("File", function() {
    beforeAll(function() {
        $this->png = File::createWithContent('foo.png', file_get_contents(__DIR__.'/../fixtures/image.png'));
        $this->svg = File::createWithContent('foo.svg', file_get_contents(__DIR__.'/../fixtures/image.svg'));
        $this->txt = File::createWithContent('foo.txt', 'Hello World!');
    });

    it('Basic', function() {
        $validation = Validator::make([
            'file' => File::create('foo.txt'),
        ], [
            'file' => 'file',
        ]);
        expect($validation->passes())->toBe(true);  
        
        $validation = Validator::make([
            'file' => 'foo',
        ], [
            'file' => 'file',
        ]);
        expect($validation->passes())->toBe(false);  

        $validation = Validator::make([
            'file' => null,
        ], [
            'file' => 'file',
        ]);
        expect($validation->passes())->toBe(true);  
    });

    it('SingleMimetype', function() {
        $post = ['file' => $this->png];
        
        $validation = Validator::make($post, ['file' => Rule::file()->types('image/png')]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, ['file' => Rule::file()->types('text/plain')]);
        expect($validation->passes())->toBe(false);
    });

    it('MultipleMimeTypes', function() {
        $post = ['file' => $this->png];
        
        $validation = Validator::make($post, ['file' => Rule::file()->types(['text/plain', 'image/png'])]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, ['file' => Rule::file()->types(['text/plain', 'image/jpeg'])]);
        expect($validation->passes())->toBe(false);
    });

    it('SingleMime', function() {
        $post = ['file' => $this->png];
        
        $validation = Validator::make($post, ['file' => Rule::file()->types('png')]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, ['file' => Rule::file()->types('txt')]);
        expect($validation->passes())->toBe(false);
    });

    it('MultipleMime', function() {
        $post = [
            'file'  => $this->png,
            'file2' => $this->svg,
            'file3' => $this->txt,
        ];
        
        $validation = Validator::make($post, ['file' => Rule::file()->types(['png', 'jpg', 'jpeg', 'svg'])]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, ['file2' => Rule::file()->types(['png', 'jpg', 'jpeg', 'svg'])]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, ['file3' => Rule::file()->types(['png', 'jpg', 'jpeg', 'svg'])]);
        expect($validation->passes())->toBe(false);
    });

    it('MixOfMimetypesAndMimes', function() {
        $post = [
            'file' => $this->png,
            'file2' => $this->txt
        ];
        
        $validation = Validator::make($post, ['file' => Rule::file()->types(['png', 'image/png'])]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, ['file2' => Rule::file()->types(['png', 'image/png'])]);
        expect($validation->passes())->toBe(false);
    });

    it('SingleExtension', function() {
        $post = [
            'file' => $this->png,
            'file2' => File::createWithContent('foo', file_get_contents(__DIR__.'/../fixtures/image.png')),
            'file3' => File::createWithContent('foo.jpg', file_get_contents(__DIR__.'/../fixtures/image.png')),
        ];
        
        $validation = Validator::make($post, ['file' => Rule::file()->extensions('png')]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, ['file2' => Rule::file()->extensions('png')]);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make($post, ['file3' => Rule::file()->extensions('png')]);
        expect($validation->passes())->toBe(false);
        
        $validation = Validator::make(['file4' => $post['file3']], ['file4' => Rule::file()->extensions('jpeg')]);
        expect($validation->passes())->toBe(false);
    });

    it('MultipleExtensions', function() {
        $post = [
            'file' => $this->png,
            'file2' => File::createWithContent('foo', file_get_contents(__DIR__.'/../fixtures/image.png')),
            'file3' => File::createWithContent('foo.jpg', file_get_contents(__DIR__.'/../fixtures/image.png')),
        ];
        
        $validation = Validator::make($post, ['file' => Rule::file()->extensions(['png', 'jpeg', 'jpg'])]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, ['file2' => Rule::file()->extensions(['png', 'jpeg', 'jpg'])]);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make($post, ['file3' => Rule::file()->extensions(['png', 'jpeg'])]);
        expect($validation->passes())->toBe(false);
    });

    it('Image', function() {
        $post = [
            'file' => $this->png,
            'file2' => $this->txt,
        ];
        
        $validation = Validator::make($post, ['file' => Rule::file()->image()]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, ['file2' => Rule::file()->image()]);
        expect($validation->passes())->toBe(false);
    });

    it('Size', function() {
        $post = [
            'file' => File::create('foo.txt', 1024),
            'file2' => File::create('foo.txt', 1025),
            'file3' => File::create('foo.txt', 1023),
        ];
        
        $validation = Validator::make($post, ['file' => Rule::file()->size(1024)]);
        expect($validation->passes())->toBe(true);

        $validation = Validator::make($post, ['file2' => Rule::size(1024)]);
        expect($validation->passes())->toBe(false);

        $validation = Validator::make($post, ['file3' => Rule::size(1024)]);
        expect($validation->passes())->toBe(false);
    });

    it('Between', function() {
        $post = [
            'file1' => File::create('foo.txt', 1024),
            'file2' => File::create('foo.txt', 2048),
            'file3' => File::create('foo.txt', 1025),
            'file4' => File::create('foo.txt', 2047),
            'file5' => File::create('foo.txt', 1023),
            'file6' => File::create('foo.txt', 2049),
        ];
        
        for ($i = 1; $i <= 4; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->between(1024, 2048)]);
            expect($validation->passes())->toBe(true);
        }
        for ($i = 5; $i <= 6; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->between(1024, 2048)]);
            expect($validation->passes())->toBe(false);
        }
    });

    it('Min', function() {
        $post = [
            'file1' => File::create('foo.txt', 1024),
            'file2' => File::create('foo.txt', 1025),
            'file3' => File::create('foo.txt', 2048),
            'file4' => File::create('foo.txt', 1023),
        ];
        
        for ($i = 1; $i <= 3; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->min(1024)]);
            expect($validation->passes())->toBe(true);
        }
        for ($i = 4; $i < 5; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->min(1024)]);
            expect($validation->passes())->toBe(false);
        }
    });

    it('MinWithHumanReadableSize', function() {
        $post = [
            'file1' => File::create('foo.txt', 1024),
            'file2' => File::create('foo.txt', 1025),
            'file3' => File::create('foo.txt', 2048),
            'file4' => File::create('foo.txt', 1023),
        ];
        
        for ($i = 1; $i <= 3; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->min('1024kb')]);
            expect($validation->passes())->toBe(true);
        }
        for ($i = 4; $i < 5; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->min('1024kb')]);
            expect($validation->passes())->toBe(false);
        }
    });

    it('Max', function() {
        $post = [
            'file1' => File::create('foo.txt', 1024),
            'file2' => File::create('foo.txt', 1023),
            'file3' => File::create('foo.txt', 512),
            'file4' => File::create('foo.txt', 1025),
        ];
        
        for ($i = 1; $i <= 3; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->max(1024)]);
            expect($validation->passes())->toBe(true);
        }
        for ($i = 4; $i < 5; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->max(1024)]);
            expect($validation->passes())->toBe(false);
        }
    });

    it('MaxWithHumanReadableSize', function() {
        $post = [
            'file1' => File::create('foo.txt', 1024),
            'file2' => File::create('foo.txt', 1023),
            'file3' => File::create('foo.txt', 512),
            'file4' => File::create('foo.txt', 1025),
        ];
        
        for ($i = 1; $i <= 3; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->max('1024kb')]);
            expect($validation->passes())->toBe(true);
        }
        for ($i = 4; $i < 5; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->max('1024kb')]);
            expect($validation->passes())->toBe(false);
        }
    });

    it('MaxWithHumanReadableSizeAndMultipleValue', function() {
        $post = [
            'file1' => File::create('foo.txt', 1000),
            'file2' => File::create('foo.txt', 998),
            'file3' => File::create('foo.txt', 512),
            'file4' => File::create('foo.txt', 1025),
        ];
        
        for ($i = 1; $i <= 3; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->max('1mb')]);
            expect($validation->passes())->toBe(true);
        }
        for ($i = 4; $i < 5; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->max('1mb')]);
            expect($validation->passes())->toBe(false);
        }
    });

    it('Macro', function() {
        $post = [
            'file1' => File::create('foo.txt'),
            'file2' => File::create('foo.csv'),
            'file3' => File::create('foo.png'),
        ];

        RulesFile::macro('toDocument', function () {
            return static::default()->rules('mimes:txt,csv');
        });
        
        for ($i = 1; $i <= 2; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->toDocument()]);
            expect($validation->passes())->toBe(true);
        }
        for ($i = 3; $i < 4; $i++) {
            $validation = Validator::make($post, ['file' . $i => Rule::file()->toDocument()]);
            expect($validation->passes())->toBe(false);
        }
    });

    it('CanSetDefaultUsing', function() {
        expect(RulesFile::default())->toBeAnInstanceOf(RulesFile::class);

        RulesFile::defaults(function () {
            return RulesFile::types('text/plain')->max(12 * 1024);
        });
        
        $post = [
            'file1' => File::create('foo.txt', 1.5 * 1024),
            'file2' => File::create('foo.png', 13 * 1024),
        ];

        $validation = Validator::make($post, ['file1' => RulesFile::default()]);
        expect($validation->passes())->toBe(true);
     
        $validation = Validator::make($post, ['file2' => RulesFile::default()]);
        expect($validation->passes())->toBe(false);
    });
});
