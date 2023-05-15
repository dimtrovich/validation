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
