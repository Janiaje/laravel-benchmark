<?php

namespace Janiaje\Benchmark\OutputFormats;

interface OutputFormat
{
    /**
     * @param array $checkpoints Array of 'Checkpoint's
     *
     * @return mixed
     */
    public static function get(array $checkpoints);
}