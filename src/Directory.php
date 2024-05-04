<?php

/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\FsWatch;

use Slick\FsWatch\Exception\DirectoryNotAccecible;
use Slick\FsWatch\Exception\DirectoryNotFound;

/**
 * Directory
 *
 * @package Slick\FsWatch
 */
final class Directory
{
    /**
     * Creates a Directory
     *
     * @param string $path
     * @throws FsWatchException When directory is not accessible
     */
    public function __construct(private string $path)
    {
        $this->path = FileTools::normalizePath($this->path);
        if (!is_dir($this->path)) {
            throw new DirectoryNotFound("Directory $this->path does not exist.");
        }

        if (!is_readable($this->path)) {
            throw new DirectoryNotAccecible("Directory $this->path is not readable.");
        }
    }

    /**
     * Returns the directory path.
     *
     * @return string The path.
     */
    public function path(): string
    {
        return rtrim($this->path, '/');
    }

    public function sizeMap(): array
    {
        return $this->map($this->path);
    }

    private function map(string $path): array
    {
        $result = [];
        $path = FileTools::normalizePath($this->path);
        $files = array_diff(scandir($path), ['.', '..']);

        foreach ($files as $file) {
            if (is_dir($path . $file) === true) {
                $result[$file] = $this->map($path . $file);
                continue;
            }
            $result[$file] = FileTools::calculateSize($path . $file);
        }

        return $result;
    }
}
