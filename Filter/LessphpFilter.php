<?php

/*
 * The file is part of the Webuni AsseticBundle
 */

namespace Webuni\AsseticBundle\Filter;

use Assetic\Filter\LessphpFilter as BaseLessphpFilter;
use Assetic\Asset\AssetInterface;

/**
 * @author Martin Hasoň <martin.hason@gmai.com>
 */
class LessphpFilter extends BaseLessphpFilter
{
    private $formatter;

    private $webDir;

    public function __construct($webDir)
    {
        $this->webDir = $webDir;
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

        $parser->parse($asset->getContent());

        $asset->setContent($parser->getCss());
    }
}
