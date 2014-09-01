<?php
/*
 * The file is part of the Webuni AsseticBundle
 */

namespace Webuni\AsseticBundle\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\CssRewriteFilter as BaseCssRewriteFilter;

/**
 * @author Martin Hasoň <martin.hason@gmai.com>
 */
class CssRewriteFilter extends BaseCssRewriteFilter
{
    public function filterDump(AssetInterface $asset)
    {
        $originalTargetPath = $asset->getTargetPath();
        $targetPath = str_replace('_controller/', '', $originalTargetPath);
        $asset->setTargetPath($targetPath);

        try {
            parent::filterDump($asset);
        } catch (\Exception $e) {
            if ($targetPath === $asset->getTargetPath()) {
                $asset->setTargetPath($originalTargetPath);
            }

            throw $e;
        }

        if ($targetPath === $asset->getTargetPath()) {
            $asset->setTargetPath($originalTargetPath);
        }
    }
}
