<?php

/*
 * This file is part of Rocketeer
 *
 * (c) Maxime Fabre <ehtnam6@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Rocketeer\TestCases;

abstract class RocketeerTestCase extends ContainerTestCase
{
    /**
     * The test repository.
     *
     * @var string
     */
    protected $repository = 'Anahkiasen/html-object.git';

    /**
     * @var string
     */
    protected $username = 'anahkiasen';

    /**
     * @var string
     */
    protected $password = 'foobar';

    /**
     * A dummy AbstractTask to use for helpers tests.
     *
     * @var \Rocketeer\Tasks\AbstractTask
     */
    protected $task;

    /**
     * Cache of the paths to binaries.
     *
     * @var array
     */
    protected static $binaries = [];

    /**
     * Number of files an ls should yield.
     *
     * @var int
     */
    protected static $currentFiles;

    /**
     * Set up the tests.
     */
    public function setUp()
    {
        parent::setUp();

        // Compute ls results
        if (!static::$currentFiles) {
            $files = preg_grep('/^([^.0])/', scandir(__DIR__.'/../..'));
            sort($files);
            static::$currentFiles = array_values($files);
        }

        // Bind dummy AbstractTask
        $this->task = $this->task('Cleanup');

        // Mock current environment
        $this->replicateFolder($this->server);
        $this->mockOperatingSystem('Linux');

        // Cache paths
        static::$binaries = static::$binaries ?: [
            'bundle' => exec('which bundle') ?: 'bundle',
            'composer' => exec('which composer') ?: 'composer',
            'php' => exec('which php') ?: 'php',
            'phpunit' => exec('which phpunit') ?: 'phpunit',
            'rsync' => exec('which rsync') ?: 'rsync',
        ];
    }

    /**
     * Cleanup tests.
     */
    public function tearDown()
    {
        parent::tearDown();

        // Restore superglobals
        $_SERVER['HOME'] = $this->home;
    }
}
