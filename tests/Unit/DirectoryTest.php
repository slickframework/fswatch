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
}
