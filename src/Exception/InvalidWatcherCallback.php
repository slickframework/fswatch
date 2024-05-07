<?php

/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\FsWatch\Exception;

use Slick\FsWatch\FsWatchException;

/**
 * InvalidWatcherCallback
 *
 * @package Slick\FsWatch\Exception
 */
final class InvalidWatcherCallback extends \InvalidArgumentException implements FsWatchException
{

}
