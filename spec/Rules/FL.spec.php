<?php

use Dimtrovich\Validation\Rule;
use Dimtrovich\Validation\Validator;

describe("Float", function() {
    it('1: Passe', function() {  
        $post = [
            'long'  => '3.1415926535897932384626433832795',
            'long2' => 3.1415926535897932384626433832795,
        ];

        $validation = Validator::make($post, [
            'long'  => 'float',
            'long2' => 'float',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it('2: Echoue', function() {       
        $validation = Validator::make([
            'foo' => 'bar',
            'bar' => 1.121,
            'baz' => '1.121',
        ], [
            'foo' => 'float',
            'bar' => 'float',
            'baz' => 'float',
        ]);
        expect($validation->passes())->toBe(false);
    });
});

xdescribe("Fullname", function() {
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

describe("Gtin", function() {
    it('Gtin sans parametre', function() {       
        $values = [
            [true, '9789510475270'],
            [true, '4012345678901'],
            [true, '0712345678911'],
            [true, '5901234123457'],
            [true, '40123455'],
            [true, '96385074'],
            [true, '65833254'],
            [true, '00123456000018'],
            [true, '012345678905'],
            [true, '012345000041'],
            [true, '012345000058'],
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
            [false, '012341000058'],
            [false, '1012345678905'],
        ];

        foreach ($values as $value) {
            $post = ['field' => $value[1]];

            $validation = Validator::make($post, ['field' => 'gtin']);
            expect($validation->passes())->toBe($value[0]);
        }
    });

    it('Gtin 8', function() {       
        $values = [
            [false, '4012345678901'],
            [false, '0712345678911'],
            [false, '5901234123457'],
            [true, '40123455'],
            [true, '96385074'],
            [true, '65833254'],
            [false, '00123456000018'],
            [false, '012345678905'],
            [false, '012345000041'],
            [false, '012345000058'],
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
            [false, '012341000058'],
        ];

        foreach ($values as $value) {
            $post = ['field' => $value[1]];

            $validation = Validator::make($post, ['field' => 'gtin:8']);
            expect($validation->passes())->toBe($value[0]);
        }
    });

    it('Gtin 12', function() {       
        $values = [
            [false, '4012345678901'],
            [false, '0712345678911'],
            [false, '5901234123457'],
            [false, '40123455'],
            [false, '96385074'],
            [false, '65833254'],
            [false, '00123456000018'],
            [true, '012345678905'],
            [true, '012345000041'],
            [true, '012345000058'],
            [true, '012345000058'],
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
            [false, '012341000058'],
            [true, '000040123455'],
        ];

        foreach ($values as $value) {
            $post = ['field' => $value[1]];

            $validation = Validator::make($post, ['field' => 'gtin:12']);
            expect($validation->passes())->toBe($value[0]);
        }
    });

    it('Gtin 13', function() {       
        $values = [
            [true, '9789510475270'],
            [true, '0012345000058'],
            [true, '4012345678901'],
            [true, '0712345678911'],
            [true, '5901234123457'],
            [false, '40123455'],
            [false, '96385074'],
            [false, '65833254'],
            [false, '00123456000018'],
            [false, '012345678905'],
            [false, '012345000041'],
            [false, '012345000058'],
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
            [false, '012341000058'],
            [true, '0000040123455'],
            [true, '0012345000058'],
        ];

        foreach ($values as $value) {
            $post = ['field' => $value[1]];

            $validation = Validator::make($post, ['field' => 'gtin:13']);
            expect($validation->passes())->toBe($value[0]);
        }
    });

    it('Gtin 14', function() {       
        $values = [
            [true, '00012345000058'],
            [false, 'w0012345000058'],
            [false, '4012345678901'],
            [false, '0712345678911'],
            [false, '5901234123457'],
            [false, '40123455'],
            [false, '96385074'],
            [false, '65833254'],
            [true, '00123456000018'],
            [false, '012345678905'],
            [false, '012345000041'],
            [false, '012345000058'],
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
            [false, '012341000058'],
            [true, '00000040123455'],
            [true, '00012345000058'],
            [true, '05901234123457'],
        ];

        foreach ($values as $value) {
            $post = ['field' => $value[1]];

            $validation = Validator::make($post, ['field' => 'gtin:14']);
            expect($validation->passes())->toBe($value[0]);
        }
    });
});

describe("Hash", function() {
    it("1: Passe", function() {
        $values = [
            [hash('md5', ''), 'md5', false],
            [strtoupper(hash('md5', '')), 'md5', true],
            [hash('sha1', ''), 'sha1', false],
            [hash('sha256', ''), 'SHA256', false],
            [hash('sha512', ''), 'SHA512', false],
            [hash('crc32', ''), 'CRC32', false],
        ];
        foreach ($values as $value) {
            $validation = Validator::make(
                ['field' => $value[0]], 
                ['field' => 'hash:' . implode(',', [$value[1], $value[2]])]
            );
            expect($validation->passes())->toBe(true);
        }
    });

    it("2: Echoue", function() {
        $values = [
            [hash('sha512', ''), 'MD5', false],
            [strtoupper(hash('md5', '')), 'MD5', false],
            [hash('md5', ''), 'SHA1', false],
            [hash('crc32', ''), 'SHA256', false],
            [hash('sha1', ''), 'SHA512', false],
            [hash('sha256', ''), 'CRC32', false],
        ];
        foreach ($values as $value) {
            $validation = Validator::make(
                ['field' => $value[0]], 
                ['field' => 'hash:' . implode(',', [$value[1], $value[2]])]
            );
            expect($validation->passes())->toBe(false);
        }
    });

    it("2: Echoue - Leve une exception", function() {
        $validation = Validator::make(
            ['field' => md5('foo')], 
            ['field' => 'hash:cezar']
        );
        
        expect(fn() => $validation->passes())->toThrow(new \InvalidArgumentException());
    });
});

describe("Hashtag", function() {
    it("1: Passe", function() {
        $post = ['field' => '#php'];

        $validation = Validator::make($post, ['field' => 'hashtag']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'php'];

        $validation = Validator::make($post, ['field' => 'hashtag']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Hexcolor", function() {
    it("1: Passe", function() {
        $post = ['field' => '#76b4ee'];

        $validation = Validator::make($post, ['field' => 'hexcolor']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'php'];

        $validation = Validator::make($post, ['field' => 'hexcolor']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Htmlclean", function() {
    it("Htmlclean", function() {
        $values = [
            [true, '123456'],
            [true, '1+2=3'],
            [true, 'The quick brown fox jumps over the lazy dog.'],
            [true, '>>>test'],
            [true, '>test'],
            [true, 'test>'],
            [true, 'attr="test"'],
            [true, 'one < two'],
            [true, 'two>one'],
            [false, 'The quick brown fox jumps <strong>over</strong> the lazy dog.'],
            [false, '<html>'],
            [false, '<HTML>test</HTML>'],
            [false, '<html attr="test">'],
            [false, 'Test</p>'],
            [false, 'Test</>'],
            [false, 'Test<>'],
            [false, '<0>'],
            [false, '<>'],
            [false, '><'],
        ];
    
        foreach ($values as $value) {
            $post = ['field' => $value[1]];
    
            $validation = Validator::make($post, ['field' => 'htmlclean']);
            expect($validation->passes())->toBe($value[0]);
        }
    });
});

describe("Htmltag", function() {
    it("1: Passe", function() {
        $post = ['field' => '<section></section>'];

        $validation = Validator::make($post, ['field' => 'htmltag']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'php'];

        $validation = Validator::make($post, ['field' => 'htmltag']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Iban", function() {
    it("1: Passe", function() {
        // Valid IBANs from all countries
        $ibans = [
            'AD1400080001001234567890',
            'AE460090000000123456789',
            'AL35202111090000000001234567',
            'AO06004400006729503010102',
            'AT483200000012345864',
            'AZ77VTBA00000000001234567890',
            'BA393385804800211234',
            'BE71096123456769',
            'BF42BF0840101300463574000390',
            'BG18RZBB91550123456789',
            'BH02CITI00001077181611',
            'BI43220001131012345678912345',
            'BJ66BJ0610100100144390000769',
            'BR1500000000000010932840814P2',
            'BY86AKBB10100000002966000000',
            'CF4220001000010120069700160',
            'CG3930011000101013451300019',
            'CH5604835012345678009',
            'CI93CI0080111301134291200589',
            'CM2110002000300277976315008',
            'CR23015108410026012345',
            'CV64000500000020108215144',
            'CY21002001950000357001234567',
            'CZ5508000000001234567899',
            'DE75512108001245126199',
            'DJ2110002010010409943020008',
            'DK9520000123456789',
            'DO22ACAU00000000000123456789',
            'DZ580002100001113000000570',
            'EE471000001020145685',
            'EG800002000156789012345180002',
            'ES7921000813610123456789',
            'FI1410093000123458',
            'FO9264600123456789',
            'FR7630006000011234567890189',
            'GA2140021010032001890020126',
            'GB33BUKB20201555555555',
            'GE60NB0000000123456789',
            'GI56XAPO000001234567890',
            'GL8964710123456789',
            'GQ7050002001003715228190196',
            'GR9608100010000001234567890',
            'GT20AGRO00000000001234567890',
            'GW04GW1430010181800637601',
            'HN54PISA00000000000000123124',
            'HR1723600001101234565',
            'HU93116000060000000012345676',
            'IE64IRCE92050112345678',
            'IL170108000000012612345',
            'IQ20CBIQ861800101010500',
            'IR062960000000100324200001',
            'IR710570029971601460641001',
            'IS750001121234563108962099',
            'IT60X0542811101000000123456',
            'JO71CBJO0000000000001234567890',
            'KM4600005000010010904400137',
            'KW81CBKU0000000000001234560101',
            'KZ244350000012344567',
            'LB92000700000000123123456123',
            'LC14BOSL123456789012345678901234',
            'LI7408806123456789012',
            'LT601010012345678901',
            'LU120010001234567891',
            'LV97HABA0012345678910',
            'LY38021001000000123456789',
            'MA64011519000001205000534921',
            'MC5810096180790123456789085',
            'MD21EX000000000001234567',
            'ME25505000012345678951',
            'MG4600005030071289421016045',
            'MK07200002785123453',
            'ML13ML0160120102600100668497',
            'MN580050099123456789',
            'MR1300020001010000123456753',
            'MT31MALT01100000000000000000123',
            'MU43BOMM0101123456789101000MUR',
            'MZ59000301080016367102371',
            'NE58NE0380100100130305000268',
            'NI92BAMC000000000000000003123123',
            'NL02ABNA0123456789',
            'NO8330001234567',
            'PK36SCBL0000001123456702',
            'PL10105000997603123456789123',
            'PS92PALS000000000400123456702',
            'PT50002700000001234567833',
            'QA54QNBA000000000000693123456',
            'RO66BACX0000001234567890',
            'RS35105008123123123173',
            'RU0204452560040702810412345678901',
            'SA4420000001234567891234',
            'SC74MCBL01031234567890123456USD',
            'SD8811123456789012',
            'SE7280000810340009783242',
            'SI56192001234567892',
            'SK8975000000000012345671',
            'SM76P0854009812123456789123',
            'SN08SN0100152000048500003035',
            'SO061000001123123456789',
            'ST23000200000289355710148',
            'SV43ACAT00000000000000123123',
            'TD8960002000010271091600153',
            'TG53TG0090604310346500400070',
            'TL380010012345678910106',
            'TN5904018104004942712345',
            'TR320010009999901234567890',
            'UA903052992990004149123456789',
            'VA59001123000012345678',
            'VG07ABVI0000000123456789',
            'XK051212012345678906',
        ];

        foreach ($ibans as $iban) {
            $post = ['field' => $iban];
            $validation = Validator::make($post, ['field' => 'iban']);
            expect($validation->passes())->toBe(true);
        }
    });

    it("2: Echoue", function() {
        // Valid IBANs from all countries
        $ibans = [
            'IR06296000000010032420001',
            'IR71057002997160146064101',
        ];
        
        foreach ($ibans as $iban) {
            $post = ['field' => $iban];
            $validation = Validator::make($post, ['field' => 'iban']);
            expect($validation->passes())->toBe(false);
        }
    });
});

describe("Imei", function() {
    it("1: Passe", function() {
        $post = ['field' => '354809104295874'];

        $validation = Validator::make($post, ['field' => 'imei']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => '80484080484'];

        $validation = Validator::make($post, ['field' => 'imei']);
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

describe("Isbn", function() {
    it('Isbn sans parametre', function() {       
        $values = [
            [true, '3498016709'],
            [true, '978-3499255496'],
            [true, '85-359-0277-5'],
            [true, '048665088X'],
            [true, '9788371815102'],
            [true, '9971502100'],
            [true, '99921-58-10-7'],
            [true, '960 425 059 0'],
            [true, '9780306406157'],
            [true, '978-0-306-40615-7'],
            [true, '978 0 306 40615 7'],
            [false, '123459181'],
            [false, '048665088A'],
            [false, '03064061521'],
            [false, '048662088X'],
            [false, '12'],
            [false, '123'],
            [false, 'ABC'],
            [false, '978-0-306-40615-6'],
            [false, '99921-58-10-6'],
            [false, '0123456789012'],
        ];

        foreach ($values as $value) {
            $post = ['field' => $value[1]];

            $validation = Validator::make($post, ['field' => 'isbn']);
            expect($validation->passes())->toBe($value[0]);
        }
    });

    it('Isbn long', function() {       
        $values = [
            [false, '3498016709'],
            [true, '978-3499255496'],
            [false, '978-3495255496'],
            [false, '85-359-0277-5'],
            [false, '048665088X'],
            [true, '9788371815102'],
            [false, '9971502100'],
            [false, '99921-58-10-7'],
            [false, '960 425 059 0'],
            [true, '9780306406157'],
            [true, '978-0-306-40615-7'],
            [true, '978 0 306 40615 7'],
            [false, '123459181'],
            [false, '048665088A'],
            [false, '03064061521'],
            [false, '048662088X'],
            [false, '12'],
            [false, '123'],
            [false, 'ABC'],
            [false, '978-0-306-40615-6'],
            [false, '99921-58-10-6'],
            [false, '0123456789012'],
        ];

        foreach ($values as $value) {
            $post = ['field' => $value[1]];

            $validation = Validator::make($post, ['field' => 'isbn:13']);
            expect($validation->passes())->toBe($value[0]);
        }
    });

    it('Isbn short', function() {       
        $values = [
            [true, '3498016709'],
            [false, '978-3499255496'],
            [true, '85-359-0277-5'],
            [true, '048665088X'],
            [false, '9788371815102'],
            [true, '9971502100'],
            [true, '99921-58-10-7'],
            [true, '960 425 059 0'],
            [false, '9780306406157'],
            [false, '978-0-306-40615-7'],
            [false, '978 0 306 40615 7'],
            [false, '123459181'],
            [false, '048665088A'],
            [false, '03064061521'],
            [false, '048662088X'],
            [false, '12'],
            [false, '123'],
            [false, 'ABC'],
            [false, '978-0-306-40615-6'],
            [false, '99921-58-10-6'],
        ];

        foreach ($values as $value) {
            $post = ['field' => $value[1]];

            $validation = Validator::make($post, ['field' => 'isbn:10']);
            expect($validation->passes())->toBe($value[0]);
        }
    });
});

describe("Issn", function() {
    it("Issn", function() {
        $values = [
            [true, '2049-3630'],
            [false, '0317-8472'],
            [false, '1982047x'],
            [false, 'DE0005810058'],
            [false, 'ZA9382189201'],
            [false, '2434-561Y'],
            [false, '2434561X'],
            [false, 'foo'],
            [false, '1234-1234'],
        ];
    
        foreach ($values as $value) {
            $post = ['field' => $value[1]];
    
            $validation = Validator::make($post, ['field' => 'issn']);
            expect($validation->passes())->toBe($value[0]);
        }
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
            'obj'      => Rule::instanceOf(new Test),
            'obj_name' => Rule::instanceOf(new Test),
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

describe("Jwt", function() {
    it("1: Passe", function() {
        $post = ['field' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjEyMzQ1Njc4OTAiLCJuYW1lIjoiSm9obiBEb2UiLCJhZG1pbiI6dHJ1ZSwiZXhwIjoxNTgyNjE2MDA1fQ.umEYVDP_kZJGCI3tkU9dmq7CIumEU8Zvftc-klp-334'];

        $validation = Validator::make($post, ['field' => 'jwt']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => '1ZSwiZXhwIjoxNTgyNjE2MDA1fQ.umEYVDP_kZJGCI3tkU9dmq7CIumEU8Zvftc-klp-334'];

        $validation = Validator::make($post, ['field' => 'jwt']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Kebabcase", function() {
    it("1: Passe", function() {
        $post = ['field' => 'blitz-php'];

        $validation = Validator::make($post, ['field' => 'kebabcase']);
        expect($validation->passes())->toBe(true);
    });

    it("2: Echoue", function() {
        $post = ['field' => 'blitzPhp'];

        $validation = Validator::make($post, ['field' => 'kebabcase']);
        expect($validation->passes())->toBe(false);
    });
});

describe("Length", function() {
    it('1: Passe', function() {  
        $post = [
            'a' => 'foobar',
            'b' => 'футбол',
            'c' => 23,
        ];

        $validation = Validator::make($post, [
            'a'  => 'length:6',
            'b' => 'length:6',
            'c' => 'length:2',
        ]);
        expect($validation->passes())->toBe(true);
    });

    it('2: Echoue', function() {       
        $post = [
            'a' => 'foobar',
            'b' => 'футбол',
            'c' => 23,
        ];

        $validation = Validator::make($post, [
            'a'  => 'length:5',
            'b' => 'length:5',
            'c' => 'length:6',
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
