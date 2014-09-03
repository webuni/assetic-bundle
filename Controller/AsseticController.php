<?php
/*
 * The file is part of the Webuni AsseticBundle
 */

namespace Webuni\AsseticBundle\Controller;

use Assetic\Asset\AssetCache;
use Assetic\Asset\AssetInterface;
use Symfony\Bundle\AsseticBundle\Controller\AsseticController as BaseAsseticController;
use Webuni\AsseticBundle\Asset\ChildrenModifiedAsset;

/**
 * @author Martin Hasoň <martin.hason@gmai.com>
 */
class AsseticController extends BaseAsseticController
{
    protected function cachifyAsset(AssetInterface $asset)
    {
        return new AssetCache(new ChildrenModifiedAsset($asset, $this->am), $this->cache);
    }
}
