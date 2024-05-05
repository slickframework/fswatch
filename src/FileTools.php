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
 * FileTools
 *
 * @package Slick\FsWatch
 */
final class FileTools
{

    /**
     * Normalizes a file path by replacing backslashes with forward slashes,
     * removing trailing slashes, and converting Windows drive letter prefixes to forward slashes.
     *
     * @param string $path The file path to normalize.
     * @return string The normalized file path.
     */
    public function normalizePath(string $path): string
    {
        $path = rtrim(str_replace('\\', '/', $path), '/');
        $path = (string) preg_replace('/\w:\//i', '/', $path);
        return is_dir($path) === true ? $path.'/' : $path;
    }

    /**
     * Calculates the total size of a directory or file.
     *
     * @param string $path The path of the directory or file.
     * @return int The total size in bytes.
     */
    public function calculateSize(string $path): int
    {
        $result = 0;
        $path = $this->normalizePath($path);

        if (is_dir($path)) {
            $scannedFiles = scandir($path);
            if ($scannedFiles === false) {
                return $result;
            }
            $files = array_diff($scannedFiles, ['.', '..']);
            foreach ($files as $file) {
                if (is_dir($path . $file)) {
                    $result += $this->calculateSize($path . $file);
                    continue;
                }
                $result += intval(sprintf('%u', filesize($path . $file)));
            }
            return $result;
        }

        if (is_file($path)) {
            $result += intval(sprintf('%u', filesize($path)));
        }

        return $result;
    }
}
