<?php

namespace ImportBundle\Service;

class ArrayChunkSplitter
{
    public function split($source, $chunkSize, $preserveKey = true)
    {
        if (is_array($source)) {
            $data = $source;
        }

        if ($this->isDataBigEnoughToSplit($data, $chunkSize)) {
            return array_chunk($data, $chunkSize, $preserveKey);
        }
    }

    protected function isDataBigEnoughToSplit(array $data, int $chunkSize)
    {
        if (count($data) > $chunkSize) {
            return true;
        }

        return false;
    }
}
