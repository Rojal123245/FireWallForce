<?php
/**
 * Created by PhpStorm.
 * User: ishwar
 * Date: 9/4/21
 * Time: 10:27 AM
 */

namespace ImportBundle\Controller;

use Pimcore\Controller\FrontendController;
use Psr\Container\ContainerInterface;
use ImportBundle\Service\YamlFileTransformer;
use Symfony\Component\Yaml\Yaml;


class BaseController extends FrontendController
{
    protected $chunkSize = 100;

    protected $jsonOptions = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;
    static $result = [];

    private $firewall_Import_yaml;

    public function __construct(string $firewall_Import_yaml)
    {
        $this->firewall_Import_yaml = $firewall_Import_yaml;

    }

    public function getSourcePath($name)
    {
        $filePath = $this->firewall_Import_yaml;
        $yamlTransformer = new YamlFileTransformer();

        return $yamlTransformer->getKey($filePath, $name)[0];
    }

    public function getContent($file, $expectArray = 1)
    {
        return json_decode(
            mb_convert_encoding(file_get_contents($file), "UTF-8"),
            $expectArray
        );
    }

    /**
     * @param $directory
     * @param $key
     * @param $chunkedUsp
     */
    public function createChunkFile($chunkDirectory, $fileName, $chunkedData)
    {

        if (!file_exists($chunkDirectory)) {
            mkdir($chunkDirectory, 0777, 1);
        }


        file_put_contents(
            "${chunkDirectory}/${fileName}",
            json_encode(
                $chunkedData,
                $this->jsonOptions)
        );
    }


}
