<?php

namespace Janiaje\Benchmark\OutputFormats;

use Janiaje\Benchmark\Checkpoint;

class ArrayFormat implements OutputFormat
{
    /**
     * @param array $checkpoints
     *
     * @return array
     */
    public static function get(array $checkpoints)
    {
        $checkpoints = array_map(function (Checkpoint $checkpoint) {
            return [
                'name' => $checkpoint->getName(),
                'time' => $checkpoint->getTime(),
                'ram'  => $checkpoint->getRam(),
            ];
        }, $checkpoints);

        return $checkpoints;
    }
}