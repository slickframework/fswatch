<?php
/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UnitTests\Slick\FsWatch\Directory;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Serializable;
use Slick\FsWatch\Directory;
use Slick\FsWatch\Directory\Snapshot;

class SnapshotTest extends TestCase
{
    use ProphecyTrait;

    #[Test]
    public function initializable()
    {
        $dir = $this->prophesize(Directory::class);
        $directoryPath = '/usr/src/app/dir';
        $dir->path()->willReturn($directoryPath);
        $dir->sizeMap()->willReturn([]);
        $snapshot = new Snapshot($dir->reveal());
        $this->assertInstanceOf(Snapshot::class, $snapshot);
    }

    #[Test]
    public function hash()
    {
        $dir = $this->prophesize(Directory::class);
        $directoryPath = '/usr/src/app/dir';
        $dir->path()->willReturn($directoryPath);
        $sizeMap = [
            'foo' => 23,
            'bar' => [
                'baz' => 52,
            ]
        ];
        $dir->sizeMap()->willReturn($sizeMap);
        $snapshot = new Snapshot($dir->reveal());
        $this->assertEquals(sha1(serialize($sizeMap)), $snapshot->hash());
    }

    #[Test]
    public function hasPath()
    {
        $dir = $this->prophesize(Directory::class);
        $directoryPath = '/usr/src/app/dir';
        $dir->path()->willReturn($directoryPath);
        $dir->sizeMap()->willReturn([]);
        $snapshot = new Snapshot($dir->reveal());
        $this->assertEquals($directoryPath, $snapshot->path());
    }

    #[Test]
    public function serializable()
    {
        $dir = $this->prophesize(Directory::class);
        $directoryPath = '/usr/src/app/dir';
        $dir->path()->willReturn($directoryPath);
        $sizeMap = [
            'foo' => 23,
            'bar' => [
                'baz' => 52,
            ]
        ];
        $dir->sizeMap()->willReturn($sizeMap);
        $snapshot = new Snapshot($dir->reveal());
        $data = serialize($snapshot);
        $recovered = unserialize($data);

        $this->assertInstanceOf(Snapshot::class, $recovered);
        $this->assertNotSame($snapshot, $recovered);
        $this->assertEquals($directoryPath, $recovered->path());
    }

    #[Test]
    public function compare()
    {
        $dirPath = '/usr/src/app/dir';
        $first = $this->prophesize(Directory::class);
        $first->path()->willReturn($dirPath);
        $first->sizeMap()->willReturn(['foo' => 23, 'bar' => ['baz' => 5]]);

        $second = $this->prophesize(Directory::class);
        $second->path()->willReturn($dirPath);
        $second->sizeMap()->willReturn(['foo' => 32, 'bar' => ['zab' => 5]]);

        $firstSnapshot = new Snapshot($first->reveal());
        $secondSnapshot = new Snapshot($second->reveal());

        $this->assertEquals([
            $dirPath.'/foo' => Directory\FileChange::CHANGED,
            $dirPath.'/bar/zab' => Directory\FileChange::ADDED,
            $dirPath.'/bar/baz' => Directory\FileChange::DELETED,
        ], (array) $firstSnapshot->compareTo($secondSnapshot)->getIterator());
    }
}
