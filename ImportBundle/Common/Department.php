<?php

namespace ImportBundle\Common;


class Department
{
    /**
     * file => pimcore
     * @var array
     */
    static $departments = [
        'cbe1' => 'cbe1', // CBE1
        'cch1' => 'cch1', // CCH1
        'cde1' => 'cde1', // CDE1
        'ces1' => 'ces1', //CES1
        'cfr1' => 'cfr1', // CFR1
        'cgb1' => 'cgb1', // CGB1
        'cit1' => 'cit1', // CIT1
        'cnl1' => 'cnl1', // CNL1
        'cnl2' => 'cnl2', // CNL2
        'cno1' => 'cno1', // CNO1
        'cse1' => 'cse1', // CSE1
    ];

//    public static function getLanguageCode($fileDepartment)
//    {
//        return isset(self::$departments[$fileDepartment]) ? self::$departments[$fileDepartment] : null;
//    }

    public static function getAllDepartments()
    {
        return self::$departments;
    }
}
