<?php

/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\FsWatch;

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
     */
    public function __construct(private readonly string $path)
    {

    }

    /**
     * Returns the directory path.
     *
     * @return string The path.
     */
    public function path(): string
    {
        return $this->path;
    }
}
