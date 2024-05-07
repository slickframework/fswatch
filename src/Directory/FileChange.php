<?php

/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\FsWatch\Directory;

use Stringable;

/**
 * FileChange
 *
 * @package Slick\FsWatch\Directory
 */
enum FileChange: string
{
    case ADDED   = 'Added';
    case CHANGED = 'Changed';
    case DELETED = 'Deleted';
}
