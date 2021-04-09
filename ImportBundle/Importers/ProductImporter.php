<?php
/**
 * Created by PhpStorm.
 * User: ishwar
 * Date: 09/04/21
 * Time: 3:40 PM
 */

namespace ImportBundle\Importers;

use ImportBundle\Common\Loggy;
use ImportBundle\Common\Language;
use Pimcore\Model\DataObject;

class ProductImporter extends BaseImporter
{
    const FOLDER_NAME = 'Products';

    public function processImport($file, $isChunk = false)
    {
        if (file_exists($file)) {
            $content = $this->getContent($file);

            $products = $content['product'];

            $folderId = $this->createFolder(self::FOLDER_NAME, $this->createFolder('Master data'));

            // start import
            foreach ($products as $product) {
                echo "Importing Firewall Product with puid ${product['puid']}  \r\n";

                try {
                    $productPuid = $product['puid'];

                    $productObject = DataObject\FirewallProduct::getByPuid($productPuid, 1);
                    $key = strtolower($product['puid'] . '-' . $this->slugify('Firewall Product'));

                    if (!$productObject) {
                        $productObject = new DataObject\FirewallProduct();
                        $productObject->setPuid($productPuid);
                        $productObject->setKey($key);
                        $productObject->setParentId($folderId);
                    }

                    $productPriceInfos = $product['productPriceInfo'];
//                    foreach ($productPriceInfos as $productPriceInfo) {

                    $productObject->setPrice($productPriceInfos['price']);
                    $productObject->setPriceCalc($productPriceInfos['priceCalc']);
                    $productObject->setCurrencyCode($productPriceInfos['currencyCode']);
                    $productObject->setPriceCalcVat($productPriceInfos['priceCalcVat']);
                    $productObject->setPriceLastUpdate($this->fixDate($productPriceInfos['priceLastUpdate']));
                    $productObject->setMinScale($productPriceInfos['minScale']);
                    $productObject->setPriceSourceId($productPriceInfos['priceSourceId']);
                    $productObject->setPriceSourceName($productPriceInfos['priceSourceName']);
                    $productObject->setPriceStatus($productPriceInfos['priceStatus']);
                    $productObject->setPriceSupplierId($productPriceInfos['priceSupplierItemId']);
                    $productObject->setPriceSupplierName($productPriceInfos['priceSupplierName']);
                    $productObject->setPriceSupplierItemId($productPriceInfos['priceSupplierItemId']);
                    $productObject->setPriceSupplierSKU($productPriceInfos['priceSupplierSKU']);
//                    }

                    $productStockInfo = $product['productStockInfo'];

                    $productObject->setStockSupplierText($productStockInfo['stockSupplierText']);
                    $productObject->setStockStatus($productStockInfo['stockStatus']);
                    $productObject->setStockStatusText($productStockInfo['stockStatusText']);
                    $productObject->setStock($productStockInfo['stock']);
                    $productObject->setStockLastUpdate($this->fixDate($productStockInfo['stockLastUpdate']));
                    $productObject->setStockSourceId($productStockInfo['stockSourceId']);
                    $productObject->setStockSourceName($productStockInfo['stockSourceName']);
                    $productObject->setStockUnlimited($productStockInfo['stockUnlimited']);

                    $productSupplierItems = $product['supplierItems'];

                    foreach ($productSupplierItems as $productSupplierItem) {
                        $productObject->setSupplierItemsId($productSupplierItem['id']);
                        $productObject->setSupplierSKU($productSupplierItem['supplierSKU']);

                        $supplier = $productSupplierItem['supplier'];

                        $productObject->setSupplierId($supplier['id']);
                        $productObject->setName($supplier['name']);
                        $productObject->setDeeplink($supplier['deeplink']);

                        $supplierPriceInfos = $productSupplierItem['supplierPriceInfo'];
                        foreach ($supplierPriceInfos as $supplierPriceInfo) {
                            $productObject->setSupplierprice($supplierPriceInfo['price']);
                            $productObject->setSupplierPriceCalc($supplierPriceInfo['priceCalc']);
                            $productObject->setSupplierCurrencyCode($supplierPriceInfo['currencyCode']);
                            $productObject->setSupplierPriceCalcVat($supplierPriceInfo['priceCalcVat']);
                            $productObject->setSupplierPriceLastUpdate($this->fixDate($supplierPriceInfo['priceLastUpdate']));
                            $productObject->setSupplierMinScale($supplierPriceInfo['minScale']);
                            $productObject->setSupplierPriceSourceId($supplierPriceInfo['priceSourceId']);
                            $productObject->setSupplierPriceSourceName($supplierPriceInfo['priceSourceName']);
                            $productObject->setSupplierPriceStatus($supplierPriceInfo['priceStatus']);
                        }

                        $supplierStockInfos = $productSupplierItem['supplierStockInfo'];

                        foreach ($supplierStockInfos as $supplierStockInfo) {
                            $productObject->setSupplierStockSupplierText($supplierStockInfo['stockSupplierText']);
                            $productObject->setSupplierStockStatus($supplierStockInfo['stockStatus']);
                            $productObject->setSupplierStockStatusText($supplierStockInfo['stockStatusText']);
                            $productObject->setSupplierStock($supplierStockInfo['stock']);
                            $productObject->setSupplierStockLastUpdate($this->fixDate($supplierStockInfo['stockLastUpdate']));
                            $productObject->setSupplierStockSourceId($supplierStockInfo['stockSourceId']);
                            $productObject->setSupplierStockSourceName($supplierStockInfo['stockSourceName']);
                            $productObject->setSupplierStockUnlimited($supplierStockInfo['stockUnlimited']);
                        }
                    }
                    $productObject->setPublished(true);

                    $productObject->save();

                    // log
                    $message = "Success importing firewall product with code ${product['puid']}";
                    Loggy::info(self::FOLDER_NAME, 'Import', $message);

                    // collect garbage
                    //$this->collectGarbage();
                } catch (\Exception $e) {
                    // log
                    $message = "Error importing location with code ${product['puid']}";
                    Loggy::error(self::FOLDER_NAME, 'Import', $message);
                    echo $e->getMessage() . "\r\n";
                }
            }

            // unlink the chunk file
            if ($isChunk) {
                unlink($file);
            }
        }
    }
}
