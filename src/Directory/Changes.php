<?php

/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\FsWatch\Directory;

use ArrayAccess;
use ArrayObject;
use IteratorAggregate;
use Traversable;

/**
 * Changes
 *
 * @package Slick\FsWatch\Directory
 */
final class Changes implements IteratorAggregate, ArrayAccess
{
    private array $changes = [];

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayObject($this->changes);
    }

    public function add(string $string, FileChange $fileChange): Changes
    {
        $this->changes[$string] = $fileChange;
        return $this;
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->changes);
    }

    public function offsetGet(mixed $offset): FileChange
    {
        return $this->changes[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->add($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->changes[$offset]);
    }
}
