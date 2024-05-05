<?php
/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UnitTests\Slick\FsWatch\Directory;

use PHPUnit\Framework\Attributes\Test;
use Slick\FsWatch\Directory\Changes;
use PHPUnit\Framework\TestCase;
use Slick\FsWatch\Directory\FileChange;

class ChangesTest extends TestCase
{

    #[Test]
    public function addChange()
    {
        $changes = new Changes();
        $key = '/some/file';
        $fileChange = FileChange::CHANGED;
        $return = $changes->add($key, $fileChange);
        $this->assertSame($changes, $return);
        $this->assertCount(1, $changes);
        $this->assertSame($changes[$key], $fileChange);
        $this->assertArrayHasKey($key, $changes);
    }

    #[Test]
    public function canBeUsedAsArray()
    {
        $changes = new Changes();
        $key = '/some/file';
        $fileChange = FileChange::ADDED;
        $changes[$key] = $fileChange;
        $this->assertSame($changes[$key], $fileChange);
        unset($changes[$key]);
        $this->assertArrayNotHasKey($key, $changes);
    }
}
