<?php

namespace ImportBundle\Service;

use Symfony\Component\Yaml\Yaml;

class YamlFileTransformer
{
    static $result = [];

    public function parse($string)
    {
        return Yaml::parse($string);
    }

    public function parseFile($file)
    {
        return Yaml::parseFile($file);
    }

    public function getKey($file, $key)
    {
        if (is_file($file)) {
            $data = $this->parseFile($file);

            return $this->searchKey($key, $data);

        } else {
            return false;
        }


    }


    public function dump($data, $file, $line = 2, $identation = 4)
    {
        file_put_contents(
            $file,
            Yaml::dump(
                $data,
                $line,
                $identation,
                Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK
            )
        );

        return true;
    }

    public function searchKey($needle, $haystack)
    {

        if (is_array($haystack)) {
            // clear the previous result
            //@todo replace static with local variable
            static::$result = [];

           foreach ($haystack as $key => $value) {
               if ($key === $needle) {
                  array_push(static::$result, $value);
               } else {
                   $this->searchKey($needle, $value);
               }
           }
        }

        return static::$result;

    }


}
