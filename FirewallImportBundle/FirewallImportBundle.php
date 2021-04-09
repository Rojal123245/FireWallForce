<?php

namespace FirewallImportBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class FirewallImportBundle extends AbstractPimcoreBundle
{
    public function getJsPaths()
    {
        return [
            '/bundles/firewallimport/js/pimcore/startup.js'
        ];
    }
}