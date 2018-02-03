<?php

namespace Janiaje\Benchmark\OutputFormats;

class JsonFormat implements OutputFormat
{
    public static function get(array $checkpoints)
    {
        $array = ArrayFormat::get($checkpoints);

        return json_encode($array);
    }
}