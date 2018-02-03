<?php

namespace Janiaje\Benchmark\OutputFormats;

use Illuminate\Support\Collection;

interface OutputFormat
{
    /**
     * @param Illuminate\Support\Collection $checkpoints Array of 'Checkpoint's
     *
     * @return mixed
     */
    public static function get(Collection $checkpoints);
}