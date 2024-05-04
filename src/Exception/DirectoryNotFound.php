<?php

/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\FsWatch\Exception;

use InvalidArgumentException;
use Slick\FsWatch\FsWatchException;

/**
 * DirectoryNotFount
 *
 * @package Slick\FsWatch\Exception
 */
final class DirectoryNotFound extends InvalidArgumentException implements FsWatchException
{

}
