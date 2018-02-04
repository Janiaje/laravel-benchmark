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
     * @var int
     */
    private $peakRamUsage;

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

        $this->peakRamUsage = memory_get_peak_usage(config('benchmark.memory_real_usage'));
    }

    /**
     * Returns the checkpoints in the given format.
     *
     * @return mixed
     */
    public function getCheckpoints()
    {
        $this->enhanceCheckpoints();

        return call_user_func($this->outputFormat . '::get', $this->checkpoints);
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
        /** @var Carbon\Carbon $min */
        $min = $this->checkpoints->first()->getTime();

        /** @var Carbon\Carbon $max */
        $max = $this->checkpoints->last()->getTime();

        return $min->diff($max);
    }

    /**
     * Returns the maximum amount of RAM,
     * that PHP allocated before the last checkpoint.
     *
     * @return int|string
     */
    public function getPeakRamUsage()
    {
        $ramUsage = $this->peakRamUsage;

        if(config('benchmark.format_ram_usage')) {
            $ramUsage = self::formatBytes($ramUsage);
        }

        return $ramUsage;
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

    /**
     * Formats bytes into the given format.
     *
     * @param int $bytes
     *
     * @return string
     */
    public static function formatBytes($bytes)
    {
        $prefixes = [
            'B',
            'kB',
            'MB',
            'GB',
            'TB',
        ];

        for (
            $i = 0, $prefixesLength = count($prefixes);
            $i < $prefixesLength && $bytes > 1024;
            $i++
        ) {
            $bytes /= 1024;
        }

        return $bytes . $prefixes[$i];
    }
}