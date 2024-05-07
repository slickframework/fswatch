<?php

/**
 * This file is part of fswatch
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\FsWatch;

use Slick\FsWatch\Directory\Changes;
use Slick\FsWatch\Directory\FileChange;
use Slick\FsWatch\Directory\Snapshot;
use Slick\FsWatch\Exception\InvalidWatcherCallback;

/**
 * Watcher
 *
 * @package Slick\FsWatch
 */
final class Watcher
{
    public const SIGINT = 'SIGINT';
    public const ONCE = 'ONCE';
    public const FIRST_CHANGE = 'FIRST_CHANGE';

    /** @var callable|array<object|string> */
    private mixed $callback;

    private Snapshot $snapshot;

    private ?FileChange $lastChange = null;

    private ?Changes $changes = null;


    public function __construct(private readonly Directory $directory, mixed $callback)
    {
        $this->callback = $callback;
        $this->snapshot = new Snapshot($this->directory);
    }

    public function watch(mixed $exitStm = self::SIGINT, int $interval = 10): void
    {
        while ($this->checkExit($exitStm)) {
            $snapshot = $this->directory->snapshot();
            if ($this->snapshot->hash() !== $snapshot->hash()) {
                $this->changes = $this->snapshot->compareTo($snapshot);
                $this->snapshot = $snapshot;
                foreach ($this->changes as $file => $change) {
                    $this->lastChange = $change;
                    call_user_func([$this, "callback"], $file, $change);
                }
            }
            sleep($interval);
        }
    }


    private function checkExit(mixed $exitStm): bool
    {
        static $runs = -1;
        if (is_callable($exitStm)) {
            return !$exitStm(++$runs, $this->changes);
        }

        $runs++;

        return match ($exitStm) {
            self::SIGINT => true,
            self::ONCE => $runs < 1,
            self::FIRST_CHANGE => is_null($this->lastChange),
            default => !$exitStm
        };
    }

    private function callback(string $file, FileChange $changes): void
    {
        if (!is_callable($this->callback)) {
            throw new InvalidWatcherCallback(
                "Invalid callback passed to directory {$this->directory->path()} watcher."
            );
        }
        ($this->callback)($file, $changes);
    }
}
