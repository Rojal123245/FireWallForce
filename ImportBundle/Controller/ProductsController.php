<?php
/**
 * Created by PhpStorm.
 * User: ishwar
 * Date: 09/04/21
 * Time: 10:26 AM
 */

namespace ImportBundle\Controller;

use Carbon\Carbon;
use DirectoryIterator;
use ImportBundle\Importers\ProductImporter;
use ImportBundle\Service\ArrayChunkSplitter;

class ProductsController extends BaseController
{
    protected $chunkDirectory;

    public function importAction(ProductImporter $productImporter)
    {
        $folder = $this->getSourcePath('product_directory');
        if (!empty(glob($folder . '/PIM-2Tablets_developerUpdate*.json'))) {
            foreach (glob($folder . '/PIM-2Tablets_developerUpdate*.json') as $file) {
                $this->splitData($file);
                $this->consumeAction($productImporter);
            }
        } else {
            echo "No such file or directory found in path: ${$folder} \r\n";
        }
    }

    public function splitAction()
    {
        $filePath = $this->getSourcePath('product_directory_default_file') . '.json';
        var_dump($filePath);
        die;

        if (is_file($filePath)) {
            $this->splitData($filePath);
        } else {
            echo "No such file or directory found in path: ${filePath} \r\n";
        }
    }

    public function consumeAction(ProductImporter $productImporter)
    {
        $fileSourceDirectory = $this->getSourcePath('product_directory') . '/chunks';

        if (is_dir($fileSourceDirectory) && !is_dir_empty($fileSourceDirectory)) {
            // Read all the chunk files
            foreach (new DirectoryIterator($fileSourceDirectory) as $fileInfo) {
                if ($fileInfo->isDot() || $fileInfo->getBasename() === '.DS_Store') {
                    continue;
                }

                // start import of files only
                if (is_file($fileInfo->getFileInfo())) {
                    $this->consumeFile($fileInfo->getFileInfo(), $productImporter);
                }
            }
        } else {
            echo "No such file or directory found in path: ${fileSourceDirectory} \r\n";
        }
    }

    public function consumeFile($file, ProductImporter $productImporter)
    {
        if (is_file($file)) {
            $productImporter->processImport($file, true);
        } else {
            throw new \Exception('The file provided is invalid.');
        }

        return;
    }

    protected function splitData($filePath)
    {
        $date = strtotime(Carbon::now()->toDateString());
        $content = $this->getContent($filePath);
        $destination = $this->getSourcePath('product_directory') . '/imported/' . Carbon::now()->toDateString() . '/';

        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $productModels = $content['product'];

        if (count($productModels) > $this->chunkSize) {
            $directory = $this->getSourcePath('product_directory');
            $arrayChunkSplitter = new ArrayChunkSplitter();
            $chunkedProductModels = $arrayChunkSplitter
                ->split($productModels, $this->chunkSize);

            foreach ($chunkedProductModels as $key => $chunkedProductModel) {
                $chunkedData = [
                    'product' => $chunkedProductModel
                ];

                $this->chunkDirectory = "${directory}/chunks";

                $this->createChunkFile(
                    $this->chunkDirectory,
                    "PIM-2Tablets_developerUpdate${key}_${date}_v1.json",
                    $chunkedData
                );
            }

//            rename($filePath, $destination . 'PIM-2Tablets_developerUpdate'. strtotime('now') .'_v1.json');
        } else {
            $directory = $this->getSourcePath('product_directory') . '/chunks/';
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            $baseName = basename($filePath);
            copy($filePath, $directory . $baseName);

//            rename($filePath, $destination . 'PIM-2Tablets_developerUpdate'. strtotime('now') .'_v1.json');
        }
    }
}
