<?php

namespace ImportBundle\Common;

use Carbon\Carbon;
use ImportBundle\Common\Loggy;
use Symfony\Component\Console\Output\OutputInterface;

class LogMan
{
    const LINE_FORMATTER = '##################################################----------##################################################';

    /**
     * Use Loggy for Witting log
     *
     * @param OutputInterface $output
     * @param $file_name
     * @param $type
     * @param string $action
     */
    public static function info(OutputInterface $output, $file_name, $type, $action = 'Started')
    {
        $output->writeln('<info>' . $file_name . ' '. $type . ' ' . $action .' At: ' . Carbon::now()->toDateTimeString() . '</info>');
        Loggy::info($file_name, $type, self::LINE_FORMATTER);
        Loggy::info($file_name, $type, $file_name . ' '. $type . ' ' . $action .' At: ' . Carbon::now()->toDateTimeString());
        Loggy::info($file_name, $type, self::LINE_FORMATTER);
    }
}
