<?php

namespace Janiaje\Benchmark\OutputFormats;

use Illuminate\Support\Collection;

class JsonFormat implements OutputFormat
{
    /**
     * @param Collection $checkpoints
     *
     * @return string
     */
    public static function get(Collection $checkpoints)
    {
        $array = ArrayFormat::get($checkpoints);

        return json_encode($array);
    }
}