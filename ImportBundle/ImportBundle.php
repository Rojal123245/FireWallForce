<?php

namespace ImportBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class ImportBundle extends AbstractPimcoreBundle
{
    public function getJsPaths()
    {
        return [
            '/bundles/import/js/pimcore/startup.js'
        ];
    }
}
