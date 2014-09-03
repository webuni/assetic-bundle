<?php

/*
 * The file is part of the Webuni AsseticBundle
 */

namespace Webuni\AsseticBundle\Asset;

use Assetic\Asset\AssetInterface;
use Assetic\Factory\LazyAssetManager;
use Assetic\Filter\FilterInterface;

/**
 * @author Martin Hasoň <martin.hason@gmai.com>
 */
class ChildrenModifiedAsset implements AssetInterface
{
    private $asset;
    private $manager;

    public function __construct(AssetInterface $asset, LazyAssetManager $manager)
    {
        $this->asset = $asset;
        $this->manager = $manager;
    }

    public function ensureFilter(FilterInterface $filter)
    {
        $this->asset->ensureFilter($filter);
    }

    public function getFilters()
    {
        return $this->asset->getFilters();
    }

    public function clearFilters()
    {
        $this->asset->clearFilters();
    }

    public function load(FilterInterface $additionalFilter = null)
    {
        $this->asset->load($additionalFilter);
    }

    public function dump(FilterInterface $additionalFilter = null)
    {
        return $this->asset->dump($additionalFilter);
    }

    public function getContent()
    {
        return $this->asset->getContent();
    }

    public function setContent($content)
    {
        $this->asset->setContent($content);
    }

    public function getSourceRoot()
    {
        return $this->asset->getSourceRoot();
    }

    public function getSourcePath()
    {
        return $this->asset->getSourcePath();
    }

    public function getSourceDirectory()
    {
        return $this->asset->getSourceDirectory();
    }

    public function getTargetPath()
    {
        return $this->asset->getTargetPath();
    }

    public function setTargetPath($targetPath)
    {
        $this->asset->setTargetPath($targetPath);
    }

    public function getLastModified()
    {
        return $this->manager->getLastModified($this->asset);
    }

    public function getVars()
    {
        return $this->asset->getVars();
    }

    public function setValues(array $values)
    {
        $this->asset->setValues($values);
    }

    public function getValues()
    {
        return $this->asset->getValues();
    }
}
