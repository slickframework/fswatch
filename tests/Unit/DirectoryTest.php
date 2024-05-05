<?php
/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UnitTests\Slick\FsWatch;

use PHPUnit\Framework\Attributes\Test;
use Slick\FsWatch\Directory;
use PHPUnit\Framework\TestCase;
use Slick\FsWatch\Directory\Snapshot;
use Slick\FsWatch\Exception\DirectoryNotAccecible;
use Slick\FsWatch\Exception\DirectoryNotFound;

class DirectoryTest extends TestCase
{

    #[Test]
    public function initializable()
    {
        $dir = new Directory(dirname(__DIR__) . '/fixtures');
        $this->assertInstanceOf(Directory::class, $dir);
    }

    #[Test]
    public function hasAPath()
    {
        $path = dirname(__DIR__) . '/fixtures';
        $dir = new Directory($path);
        $this->assertEquals($path, $dir->path());
    }

    #[Test]
    public function throwsNotFoundException()
    {
        $this->expectException(DirectoryNotFound::class);
        $dir = new Directory('/nonexistent/path');
    }

    #[Test]
    public function throwsNotAccecibleException()
    {
        $path = '/root';
        if (!is_dir($path)) {
            $this->markTestSkipped("/root directory does not exist");
        }
        $this->expectException(DirectoryNotAccecible::class);
        $dir = new Directory($path);
    }

    #[Test]
    public function normalizePathName()
    {
        $path = dirname(__DIR__) . "\\fixtures\\";
        $dir = new Directory($path);
        $this->assertEquals(dirname(__DIR__).'/fixtures', $dir->path());
    }

    #[Test]
    function directoryMap()
    {
        $file1 = dirname(__DIR__) . '/fixtures/test.txt';
        $file2 = dirname(__DIR__) . '/fixtures/other/other-test.txt';
        $size1 = intval(sprintf('%u', filesize($file1)));
        $size2 = intval(sprintf('%u', filesize($file2)));
        $dir = new Directory(dirname(__DIR__) . '/fixtures');

        $this->assertEquals([
            'test.txt' => $size1,
            'other' => [
                'other-test.txt' => $size2
            ]
        ], $dir->sizeMap());
    }

    #[Test]
    public function hasASnapshot()
    {
        $dir = new Directory(dirname(__DIR__) . '/fixtures');
        $this->assertInstanceOf(Snapshot::class, $dir->snapshot());
        print_r($dir->snapshot()->__serialize());
    }

    #[Test]
    public function checkChanges()
    {
        $path = dirname(__DIR__) . '/fixtures';
        $dir = new Directory($path);
        $data = serialize($dir->snapshot());
        file_put_contents($path.'/snapshot.txt', 'test');
        $dir = new Directory($path);
        $this->assertTrue($dir->hasChanged(unserialize($data)));
    }

    #[Test]
    public function noChangesCase()
    {
        $path = dirname(__DIR__) . '/fixtures';
        $dir = new Directory($path);
        $data = serialize($dir->snapshot());
        $dir = new Directory($path);
        $this->assertFalse($dir->hasChanged(unserialize($data)));
    }

    protected function tearDown(): void
    {
        if (is_file(dirname(__DIR__) . '/fixtures/snapshot.txt')) {
            unlink(dirname(__DIR__) . '/fixtures/snapshot.txt');
        }
    }


}
