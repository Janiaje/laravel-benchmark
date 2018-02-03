<?php

namespace Janiaje\Benchmark;

use Carbon\Carbon;
use DB;
use Janiaje\Benchmark\OutputFormats\OutputFormat;

class Benchmark
{
    use BenchmarkAliases;

    /**
     * @var Illuminate\Support\Collection
     */
    private $checkpoints;

    /**
     * @var \Janiaje\Benchmark\OutputFormats\OutputFormat
     */
    private $outputFormat;

    /**
     * Benchmark constructor.
     */
    public function __construct()
    {
        $this->checkpoints  = collect();
        $this->outputFormat = config('benchmark.output_format');

        if (config('benchmark.log_queries') === true) {
            DB::enableQueryLog();
        }
    }

    /**
     * Adds a checkpoint.
     *
     * @param null|string $name
     */
    public function checkpoint($name = null)
    {
        $id = $this->checkpoints->count() + 1;

        $this->checkpoints->push(new Checkpoint($id, $name));
    }

    /**
     * Returns the checkpoints in the given format.
     *
     * @return mixed
     */
    public function getCheckpoints()
    {
        $this->enhanceCheckpoints();

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
        $min = $this->checkpoints->first()->getTime();

        /** @var Carbon $max */
        $max = $this->checkpoints->last()->getTime();

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
        $ramUsage = $this->checkpoints->map(function (Checkpoint $checkpoint) {
            return $checkpoint->getRam();
        });

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

    /**
     * Enhance checkpoints.
     */
    private function enhanceCheckpoints()
    {
        $previousCheckpoint = null;

        foreach ($this->checkpoints as $checkpoint) {

            if ($previousCheckpoint === null) {
                $previousCheckpoint = $checkpoint;

                continue;
            }

            $checkpoint->setTimeDifference($previousCheckpoint->getTime());

            if (config('benchmark.log_queries')) {
                $checkpoint->setQueries($previousCheckpoint->getQueries());
            }

            $previousCheckpoint = $checkpoint;
        }
    }
}