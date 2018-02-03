<?php

namespace Janiaje\Benchmark;

use Carbon\Carbon;
use Janiaje\Benchmark\OutputFormats\OutputFormat;

class Benchmark
{
    use BenchmarkAliases;

    /**
     * @var array
     */
    private $checkpoints = [];

    /**
     * @var \Janiaje\Benchmark\OutputFormats\OutputFormat
     */
    private $outputFormat;

    /**
     * Benchmark constructor.
     */
    public function __construct()
    {
        $this->outputFormat = config('benchmark.output_format');
    }

    /**
     * Adds a checkpoint.
     *
     * @param null|string $name
     */
    public function checkpoint($name = null)
    {
        $this->checkpoints[] = new Checkpoint($name);
    }

    /**
     * Returns the checkpoints.
     *
     * @return mixed
     */
    public function getCheckpoints()
    {
        return $this->outputFormat::get($this->checkpoints);
    }

    /**
     * Returns the checkpoints AND dump() them.
     */
    public function dump()
    {
        dump($this->getCheckpoints());
    }

    /**
     * Returns the checkpoints AND dd() them.
     */
    public function dd()
    {
        dd($this->getCheckpoints());
    }

    /**
     * Returns the time difference between the first and the last checkpoint.
     *
     * @return \DateInterval
     */
    public function getElapsedTime()
    {
        /** @var Carbon $min */
        $min = array_first($this->checkpoints);

        /** @var Carbon $max */
        $max = array_last($this->checkpoints);

        return $min->diff($max);
    }

    /**
     * Returns the maximum amount of RAM,
     * that PHP allocated at the checkpoints.
     * (in bytes)
     *
     * @return int
     */
    public function getMaxRamUsage()
    {
        $ramUsage = array_map(function (Checkpoint $checkpoint) {
            return $checkpoint->getRam();
        }, $this->checkpoints);

        return max($ramUsage);
    }

    /**
     * Sets the output format.
     *
     * @param \Janiaje\Benchmark\OutputFormats\OutputFormat $outputFormat
     */
    public function setOutputFormat(OutputFormat $outputFormat)
    {
        $this->outputFormat = $outputFormat;
    }
}