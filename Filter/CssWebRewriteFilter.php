<?php
/*
 * The file is part of the Webuni AsseticBundle
 */

namespace Webuni\AsseticBundle\Filter;

use Assetic\Filter\BaseCssFilter;
use Assetic\Asset\AssetInterface;
use Webuni\AsseticBundle\Util\PathUtils;

/**
 * @author Martin Hasoň <martin.hason@gmai.com>
 */
class CssWebRewriteFilter extends BaseCssFilter
{
    private $webDir;

    public function __construct($webDir)
    {
        $this->webDir = $webDir;
    }

    /**
     * {@inheritdoc}
     */
    public function filterLoad(AssetInterface $asset)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function filterDump(AssetInterface $asset)
    {
        $webDir = $this->webDir;
        $isController = 0 === strpos($asset->getTargetPath(), '_controller/');
        $target = PathUtils::normalizePath($this->webDir.'/'.str_replace('_controller/', '', $asset->getTargetPath()));
        $source = PathUtils::normalizePath($asset->getSourceRoot().'/'.$asset->getSourcePath());

        $content = $this->filterReferences($asset->getContent(), function($matches) use ($isController, $source, $target, $webDir) {
            if (file_exists($webDir.'/'.$matches['url'])) {
                return str_replace($matches['url'], PathUtils::findShortestPath($target, $webDir.'/'.$matches['url'], $isController), $matches[0]);
            }

            return $matches[0];
        });

        $asset->setContent($content);
    }
}
