<?php

/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\FsWatch\Directory;

use Slick\FsWatch\Directory;
use Slick\FsWatch\FileTools;

/**
 * Snapshot
 *
 * @package Slick\FsWatch\Directory
 */
final class Snapshot
{
    protected array $sizeMap;
    private string $path;

    private FileTools $fileTools;

    public function __construct(Directory $directory)
    {
        $this->sizeMap = $directory->sizeMap();
        $this->path = $directory->path();
        $this->fileTools = new FileTools();
    }

    public function hash(): string
    {
        return sha1(serialize($this->sizeMap));
    }

    public function path(): string
    {
        return $this->path;
    }

    public function __serialize(): array
    {
        return [
            "path" => $this->path,
            "size" => $this->sizeMap,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->path = $data['path'];
        $this->sizeMap = $data['size'];
        $this->fileTools = new FileTools();
    }

    public function compareTo(Snapshot $secondSnapshot): Changes
    {
        $changes = new Changes();
        $second = $this->fileTools->flattenSizeMap($secondSnapshot->sizeMap);
        $first = $this->fileTools->flattenSizeMap($this->sizeMap);

        $diff = array_diff_assoc($second, $first);

        foreach (array_keys($diff) as $key) {
            if (array_key_exists($key, $first)) {
                $changes->add($this->path.'/'.$key, FileChange::CHANGED);
                continue;
            }
            $changes->add($this->path.'/'.$key, FileChange::ADDED);
        }

        $diff = array_diff_assoc($first, $second);
        foreach (array_keys($diff) as $key) {
            if (!array_key_exists($key, $second)) {
                $changes->add($this->path.'/'.$key, FileChange::DELETED);
            }
        }
        return $changes;
    }
}
