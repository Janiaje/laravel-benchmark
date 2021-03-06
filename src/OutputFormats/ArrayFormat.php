<?php

namespace Janiaje\Benchmark\OutputFormats;

use Illuminate\Support\Collection;
use Janiaje\Benchmark\Checkpoint;

class ArrayFormat implements OutputFormat
{
    /**
     * @param Illuminate\Support\Collection $checkpoints
     *
     * @return array
     */
    public static function get(Collection $checkpoints)
    {
        $checkpoints = $checkpoints->map(function (Checkpoint $checkpoint) {
            $array = [
                'id'             => $checkpoint->getId(),
                'name'           => $checkpoint->getName(),
                'time'           => $checkpoint->getTime(),
                'timeDifference' => $checkpoint->getTimeDifference(),
                'ram'            => $checkpoint->getRam(),
            ];

            if (config('benchmark.log_queries') === true) {
                $array['queries'] = $checkpoint->getQueries();
            }

            return $array;
        });

        return $checkpoints;
    }
}