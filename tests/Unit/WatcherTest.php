<?php
/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UnitTests\Slick\FsWatch;

use PHPUnit\Framework\Attributes\Test;
use Prophecy\PhpUnit\ProphecyTrait;
use Slick\FsWatch\Directory;
use Slick\FsWatch\Watcher;
use PHPUnit\Framework\TestCase;

class WatcherTest extends TestCase
{

    #[Test]
    public function initializable()
    {
        $dir = new Directory(dirname(__DIR__) . '/fixtures');
        $watcher = new Watcher($dir, fn() => null);
        $this->assertInstanceOf(Watcher::class, $watcher);
    }

    #[Test]
    public function watch()
    {
        $dir = new Directory(dirname(__DIR__) . '/fixtures');
        $checked = false;
        $watcher = new Watcher($dir, function (string $filePath, Directory\FileChange $change) use (&$checked) {
            $this->assertEquals($filePath, dirname(__DIR__) .'/fixtures/new-file.txt');
            $this->assertEquals(Directory\FileChange::ADDED, $change);
            $checked = true;
        });

        file_put_contents(dirname(__DIR__) . '/fixtures/new-file.txt', 'test');

        $watcher->watch(Watcher::ONCE, 1);
        $this->assertTrue($checked);
    }

    #[Test]
    public function checkExitCallback()
    {
        $dir = new Directory(dirname(__DIR__) . '/fixtures');
        $checked = false;
        $watcher = new Watcher($dir, function (string $filePath, Directory\FileChange $change) use (&$checked) {
            $this->assertEquals($filePath, dirname(__DIR__) .'/fixtures/new-file.txt');
            $this->assertEquals(Directory\FileChange::ADDED, $change);
            $checked = true;
        });

        file_put_contents(dirname(__DIR__) . '/fixtures/new-file.txt', 'test');
        $exitCallback = function (int $runs) {
            return $runs > 2;
        };

        $watcher->watch($exitCallback, 1);
        $this->assertTrue($checked);
    }

    protected function tearDown(): void
    {
        if (is_file(dirname(__DIR__) . '/fixtures/new-file.txt')) {
            unlink(dirname(__DIR__) . '/fixtures/new-file.txt');
        }
    }


}
