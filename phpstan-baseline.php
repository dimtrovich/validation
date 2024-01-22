<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Unsafe usage of new static\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/ErrorBag.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Dimtrovich\\\\Validation\\\\Rules\\\\File\\:\\:defaults\\(\\) should return static\\(Dimtrovich\\\\Validation\\\\Rules\\\\File\\)\\|null but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rules/File.php',
];
$ignoreErrors[] = [
	'message' => '#^Unsafe usage of new static\\(\\)\\.$#',
	'count' => 2,
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
	'message' => '#^Unsafe usage of new static\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Validation.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
