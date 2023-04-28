<?php
use Kahlan\Filter\Filters;
use Kahlan\Reporter\Coverage;
use Kahlan\Reporter\Coverage\Driver\Xdebug;
use Kahlan\Reporter\Coverage\Driver\Phpdbg;
use Kahlan\Reporter\Coverage\Exporter\Coveralls;

$commandLine = $this->commandLine();
$commandLine->option('ff', 'default', 1);
$commandLine->option('coverage-coveralls', 'default', 'coveralls.json');

Filters::apply($this, 'reporting', function($next) {

    // Get the reporter called `'coverage'` from the list of reporters
    $reporter = $this->reporters()->get('coverage');

    // Abort if no coverage is available.
    if (!$reporter || !$this->commandLine()->exists('coverage-coveralls')) {
        return $next();
    }

    // Use the `Coveralls` class to write the JSON coverage into a file
    Coveralls::write([
        'collector' => $reporter,
        'file' => $this->commandLine()->get('coverage-coveralls'),
        'service_name' => 'travis-ci',
        'service_job_id' => getenv('TRAVIS_JOB_ID') ?: null
    ]);

    return $next();
});

Filters::apply($this, 'coverage', function($next) {
    if (!extension_loaded('xdebug') && PHP_SAPI !== 'phpdbg') {
        return;
    }
    $reporters = $this->reporters();
    $coverage = new Coverage([
        'verbosity' => $this->commandLine()->get('coverage'),
        'driver'    => PHP_SAPI !== 'phpdbg' ? new Xdebug() : new Phpdbg(),
        'path'      => $this->commandLine()->get('src'),
        'exclude'   => [
            //Exclude init script
            'src/Core/Application.php',
        ],
        'colors'    => !$this->commandLine()->get('no-colors')
    ]);
    $reporters->add('coverage', $coverage);
});
