<?php

/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\FsWatch;

use Slick\FsWatch\Directory\Snapshot;
use Slick\FsWatch\Exception\DirectoryNotAccecible;
use Slick\FsWatch\Exception\DirectoryNotFound;

/**
 * Directory
 *
 * @package Slick\FsWatch
 */
class Directory
{
    private FileTools $fileTools;
    /**
     * Creates a Directory
     *
     * @param string $path
     * @throws DirectoryNotFound|DirectoryNotAccecible When directory is not accessible
     */
    public function __construct(private string $path)
    {
        $this->fileTools = new FileTools();
        $this->path = $this->fileTools->normalizePath($this->path);
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

    /**
     * Creates a size map of all directories and files within the specified directory.
     *
     * @return array<string, mixed> Array containing the size information of directories and files
     */
    public function sizeMap(): array
    {
        return $this->map($this->path);
    }

    /**
     * Recursively maps a directory and its content sizes.
     *
     * @param string $path The path to the directory.
     * @return array<string, mixed> The mapped directory with its contents.
     */
    private function map(string $path): array
    {
        $result = [];
        $path = $this->fileTools->normalizePath($path);
        $files = scandir($path);
        $files = $files !== false ? array_diff($files, ['.', '..']) : [];

        foreach ($files as $file) {
            if (is_dir($path . $file) === true) {
                $result[$file] = $this->map($path . $file);
                continue;
            }
            $result[$file] = $this->fileTools->calculateSize($path . $file);
        }

        return $result;
    }

    public function snapshot(): Snapshot
    {
        return new Snapshot($this);
    }

    public function hasChanged(Snapshot $snapshot): bool
    {
        return $this->snapshot()->hash() !== $snapshot->hash();
    }
}
