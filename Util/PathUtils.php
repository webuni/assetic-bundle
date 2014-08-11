<?php
/*
 * This file is part of the Webuni Assetic Bundle
 */

namespace Webuni\AsseticBundle\Util;

/**
 * @author Martin Hasoň <martin.hason@gmai.com>
 */
class PathUtils
{
    /**
     * This class should not be instantiated
     */
    private function __construct()
    {
    }

    public static function isAbsolutePath($file)
    {
        if (strspn($file, '/\\', 0, 1)
            || (strlen($file) > 3 && ctype_alpha($file[0])
                && substr($file, 1, 1) === ':'
                && (strspn($file, '/\\', 2, 1))
            )
            || false !== strpos($file, '://')
        ) {
            return true;
        }

        return false;
    }

    public static function normalizePath($path)
    {
        $parts = array();
        $path = strtr($path, '\\', '/');
        $prefix = '';
        $absolute = false;

        if (preg_match('{^([0-9a-z]+:(?://(?:[a-z]:)?)?)}i', $path, $match)) {
            $prefix = $match[1];
            $path = substr($path, strlen($prefix));
        }

        if (substr($path, 0, 1) === '/') {
            $absolute = true;
            $path = substr($path, 1);
        }

        $up = false;
        foreach (explode('/', $path) as $chunk) {
            if ('..' === $chunk && ($absolute || $up)) {
                array_pop($parts);
                $up = !(empty($parts) || '..' === end($parts));
            } elseif ('.' !== $chunk && '' !== $chunk) {
                $parts[] = $chunk;
                $up = '..' !== $chunk;
            }
        }

        return $prefix.($absolute ? '/' : '').implode('/', $parts);
    }

    public static function findShortestPath($from, $to, $directories = false)
    {
        $from = lcfirst(static::normalizePath($from));
        $to = lcfirst(static::normalizePath($to));

        if ($directories) {
            $from .= '/dummy_file';
        }

        if (dirname($from) === dirname($to)) {
            return './'.basename($to);
        }

        $commonPath = $to;
        while (strpos($from.'/', $commonPath.'/') !== 0 && '/' !== $commonPath && !preg_match('{^[a-z]:/?$}i', $commonPath)) {
            $commonPath = strtr(dirname($commonPath), '\\', '/');
        }

        if (0 !== strpos($from, $commonPath) || '/' === $commonPath) {
            return $to;
        }

        $commonPath = rtrim($commonPath, '/') . '/';
        $sourcePathDepth = substr_count(substr($from, strlen($commonPath)), '/');
        $commonPathCode = str_repeat('../', $sourcePathDepth);

        return ($commonPathCode . substr($to, strlen($commonPath))) ?: './';
    }
}
