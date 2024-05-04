<?php
/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UnitTests\Slick\FsWatch;

use PHPUnit\Framework\Attributes\Test;
use Slick\FsWatch\FileTools;
use PHPUnit\Framework\TestCase;

class FileToolsTest extends TestCase
{

    #[Test]
    public function directorySuffix()
    {
        $path = dirname(__DIR__) . '/fixtures';
        $this->assertEquals($path . '/', FileTools::normalizePath($path));
    }

    #[Test]
    public function widowsPath()
    {
        $this->assertEquals('/example/test', FileTools::normalizePath('c:\\example\\test'));
    }

    #[Test]
    public function calculatesSize()
    {
        $file1 = dirname(__DIR__) . '/fixtures/test.txt';
        $file2 = dirname(__DIR__) . '/fixtures/other/other-test.txt';
        $size = intval(sprintf('%u', filesize($file1)));
        $size += intval(sprintf('%u', filesize($file2)));

        $this->assertEquals($size, FileTools::calculateSize(dirname(__DIR__) . '/fixtures'));

    }
}
