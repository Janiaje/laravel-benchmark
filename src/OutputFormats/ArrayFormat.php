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
        return $checkpoints->map(function (Checkpoint $checkpoint) {
            $array = [
                'id'             => $checkpoint->getId(),
                'name'           => $checkpoint->getName(),
                'group'          => $checkpoint->getGroup(),
                'time'           => $checkpoint->getTime(),
                'timeDifference' => $checkpoint->getTimeDifference(),
                'ram'            => $checkpoint->getRam(),
            ];

            if (config('benchmark.collect_queries') === true) {
                $array['queries'] = $checkpoint->getQueries();
            }

            return $array;
        });
    }
}