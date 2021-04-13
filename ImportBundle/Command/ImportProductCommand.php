<?php

namespace ImportBundle\Command;

use ImportBundle\Common\LogMan;
use ImportBundle\Controller\ProductsController;
use ImportBundle\Importers\ProductImporter;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportProductCommand extends AbstractCommand
{
    const FILE_NAME = 'Products';

    private $firewall_Import_yaml;

    public function __construct(string $firewall_Import_yaml)
    {
        $this->firewall_Import_yaml = $firewall_Import_yaml;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('firewall:product:import')
             ->setDescription('Imports Firewall product master file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        LogMan::info($output, self::FILE_NAME, 'Import');
        $controller = new ProductsController($this->firewall_Import_yaml);
        $controller->importAction(
            new ProductImporter()
        );
        LogMan::info($output, self::FILE_NAME, 'Import');
    }
}
