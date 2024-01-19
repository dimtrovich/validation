<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Unsafe usage of new static\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/ErrorBag.php',
];
$ignoreErrors[] = [
	'message' => '#^PHPDoc tag @method has invalid value \\(static \\\\Rakit\\\\Validation\\\\Rules\\\\Ibsn                       ibsn\\(array lengths \\= \\[10, 13\\]\\)\\)\\: Unexpected token "lengths", expected variable at offset 5819$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rule.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method static\\(Dimtrovich\\\\Validation\\\\Rules\\\\Dimensions\\)\\:\\:getFile\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rules/Dimensions.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method static\\(Dimtrovich\\\\Validation\\\\Rules\\\\Dimensions\\)\\:\\:image\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rules/Dimensions.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$field$#',
	'count' => 2,
	'path' => __DIR__ . '/src/Rules/Dimensions.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$height$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rules/Dimensions.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$width$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rules/Dimensions.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Dimtrovich\\\\Validation\\\\Rules\\\\File\\:\\:default\\(\\) should return static\\(Dimtrovich\\\\Validation\\\\Rules\\\\File\\) but returns Dimtrovich\\\\Validation\\\\Rules\\\\File\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rules/File.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Dimtrovich\\\\Validation\\\\Rules\\\\File\\:\\:defaults\\(\\) should return static\\(Dimtrovich\\\\Validation\\\\Rules\\\\File\\)\\|null but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rules/File.php',
];
$ignoreErrors[] = [
	'message' => '#^Unsafe usage of new static\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rules/File.php',
];
$ignoreErrors[] = [
	'message' => '#^Binary operation "\\*" between string and 2 results in an error\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rules/Imei.php',
];
$ignoreErrors[] = [
	'message' => '#^Unsafe usage of new static\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/Rules/Postalcode.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Rakit\\\\Validation\\\\Rule\\:\\:check\\(\\) invoked with 2 parameters, 1 required\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rules/ProhibitedIf.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Rakit\\\\Validation\\\\Rule\\:\\:check\\(\\) invoked with 2 parameters, 1 required\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rules/ProhibitedUnless.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Dimtrovich\\\\Validation\\\\Spec\\\\File\\:\\:create\\(\\) should return static\\(Dimtrovich\\\\Validation\\\\Spec\\\\File\\) but returns Dimtrovich\\\\Validation\\\\Spec\\\\File\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Spec/File.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Dimtrovich\\\\Validation\\\\Spec\\\\File\\:\\:createWithContent\\(\\) should return static\\(Dimtrovich\\\\Validation\\\\Spec\\\\File\\) but returns Dimtrovich\\\\Validation\\\\Spec\\\\File\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Spec/File.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Dimtrovich\\\\Validation\\\\Spec\\\\File\\:\\:image\\(\\) should return static\\(Dimtrovich\\\\Validation\\\\Spec\\\\File\\) but returns Dimtrovich\\\\Validation\\\\Spec\\\\File\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Spec/File.php',
];
$ignoreErrors[] = [
	'message' => '#^Unsafe usage of new static\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Validation.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
