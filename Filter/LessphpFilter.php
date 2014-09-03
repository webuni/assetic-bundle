<?php

/*
 * The file is part of the Webuni AsseticBundle
 */

namespace Webuni\AsseticBundle\Filter;

use Assetic\Cache\ConfigCache;
use Assetic\Filter\LessphpFilter as BaseLessphpFilter;
use Assetic\Factory\AssetFactory;
use Assetic\Asset\AssetInterface;

/**
 * @author Martin Hasoň <martin.hason@gmai.com>
 */
class LessphpFilter extends BaseLessphpFilter
{
    private $formatter;
    private $webDir;
    private $cache;

    public function __construct($webDir, $cacheDir)
    {
        $this->webDir = $webDir;
        $this->cache = new ConfigCache($cacheDir);
    }

    /**
     * {@inheritdoc}
     */
    public function setFormatter($formatter)
    {
        $this->formatter = $formatter;
        parent::setFormatter($formatter);
    }

    /**
     * {@inheritdoc}
     */
    public function filterLoad(AssetInterface $asset)
    {
        $parser = new \Less_Parser(array('compress' => 'compressed' == $this->formatter, 'relativeUrls' => false));

        $importDirs = array_fill_keys((array) $this->loadPaths, '/');
        $importDirs[dirname($asset->getSourceRoot().'/'.$asset->getSourcePath())] = '/';

        $webDir = $this->webDir;
        $importDirs[] = function($path) use ($webDir) {
            $file = $webDir.'/'.$path;
            foreach (array('', '.less', '.css') as $extension) {
                if (file_exists($file.$extension)) {
                    return array($file.$extension, null);
                }
            }

            return null;
        };
        $parser->SetImportDirs($importDirs);

        $content = $asset->getContent();
        $parser->parse($content);
        $css = $parser->getCss();

        $this->cache->set(md5($content), \Less_Parser::AllParsedFiles());

        $asset->setContent($css);
    }

    public function getChildren(AssetFactory $factory, $content, $loadPath = null)
    {
        $hash = md5($content);
        if ($this->cache->has($hash)) {
            return $factory->createAsset($this->cache->get($hash), array(), array('root' => $loadPath));
        } else {
            return parent::getChildren($factory, $content, $loadPath);
        }
    }
}
