<?php
/**
 * Created by PhpStorm.
 * User: ishwar
 * Date: 09/04/21
 * Time: 4:01 PM
 */

namespace ImportBundle\Importers;

use Pimcore\Model\DataObject\Folder;
use Cocur\Slugify\Slugify;
use Carbon\Carbon;
use Pimcore\Cache;
use Pimcore\Model\Version;

class BaseImporter
{
    protected $slugify;


    public function __construct()
    {
        Cache::disable();
        Version::disable();
        $this->slugify = new Slugify();
    }

    protected function getContent($file, $expectArray = 1)
    {
        return json_decode(
            mb_convert_encoding(file_get_contents($file), "UTF-8"),
            $expectArray
        );
    }

    protected function slugify($string)
    {
        return $this->slugify->slugify($string);
    }

    protected function createFolder($folderName, $parentId = 1)
    {
        // check if folder exists
        if ($folderId = $this->isFolderExists($folderName, $parentId)) {
            return $folderId;
        } else {
            // create folder
            $folder = Folder::create(['o_key' => $folderName, 'o_parentId' => $parentId]);
            $folder->save();

            return $folder->getId();
        }


    }

    protected function isFolderExists($folderName, $parentId = 1)
    {
        // search if folder exists
        if ($parentId == 1) {
            $folder = Folder::getByPath("/${folderName}");
        } else {
            $path = Folder::getById($parentId)->getRealFullPath();
            $folder = Folder::getByPath("${path}/${folderName}");
        }



        if ($folder) {
            return $folder->getId();
        }

        return false;

    }

    protected function fixBoolean($value) {

        $knownTrueValues = array('true', 't', 'y', 'yes', 'j', 'ja', '1', 'on');
        $knownFalseValues = array('false', 'f', 'n', 'no', 'n', 'nein', 'nee', '0', 'off', 'null', '', null);

        if (preg_grep("/^$value$/i", $knownFalseValues)) {
            $value = false;
        } else if (preg_grep("/^$value$/i", $knownTrueValues)) {
            $value = true;
        } else {
            // Use this as a fallback in case the value is not registered
            // convert to boolean (also strings like "no", "off", "false" etc
            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        return $value;
    }

    protected static function fixDate($data) {

        if(!$data) return null;

        try {
            $carbonDate = Carbon::parse($data);
        } catch (\Exception $e) {
            $carbonDate = null;
        }

        return $carbonDate;

    }

    protected function collectGarbage()
    {
        // call the garbage collector if memory consumption is > 100MB
        if (memory_get_usage() > 100000000) {
            \Pimcore::collectGarbage();
        }
    }
}
