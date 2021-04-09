<?php

namespace ImportBundle\Common;


class Miscellanous
{
    public static function getParentChildHierarchy($idField, $parentField, $els, $parentID = 0, &$result = array(), &$depth = 0){
        foreach ($els as $key => $value) {
            if ($value[$parentField] == $parentID) {
                $value['depth'] = $depth;
                array_push($result, $value);
                unset($els[$key]);
                $oldParent = $parentID;
                $parentID = $value[$idField];
                $depth++;
                self::getParentChildHierarchy($idField, $parentField, $els, $parentID, $result, $depth);
                $parentID = $oldParent;
                $depth--;
            }
        }

        return $result;
    }
}
