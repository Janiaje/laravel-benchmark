<?php

namespace Janiaje\Benchmark;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Janiaje\Benchmark\OutputFormats\OutputFormat;

class Benchmark
{
    use BenchmarkAliases;

    /**
     * @var Collection
     */
    private $checkpoints;

    /**
     * @var OutputFormat
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
        $this->checkpoints = collect();
        $this->outputFormat = config('benchmark.output_format');

        if (config('benchmark.collect_queries') === true) {
            DB::enableQueryLog();
        }
    }

    /**
     * Saves a checkpoint.
     *
     * @param null|string $name
     * @param null|string $group
     *
     * @return Checkpoint
     */
    public function checkpoint($name = null, $group = null)
    {
        $id = $this->checkpoints->count() + 1;

        $checkpoint = new Checkpoint($id, $name, $group);

        $this->checkpoints->push($checkpoint);

        $this->peakRamUsage = memory_get_peak_usage(config('benchmark.memory_real_usage'));

        return $checkpoint;
    }

    /**
     * Saves a checkpoint.
     *
     * @param null|string $group
     *
     * @return Checkpoint
     */
    public function checkpointWithGroup($group = null)
    {
        return $this->checkpoint(null, $group);
    }

    /**
     * Returns the checkpoints.
     *
     * @param null|string $group Group name specified for the checkpoints or null to return all of them.
     *
     * @return mixed
     */
    public function getCheckpoints($group = null)
    {
        if ($group !== null) {
            $checkpoints = $this->checkpoints
                ->filter(function ($checkpoint, $key) use ($group) {
                    /** @var Checkpoint $checkpoint */
                    return $checkpoint->getGroup() === $group;
                });
        } else {
            $checkpoints = $this->checkpoints;
        }

        return $this->formatCheckpoints($checkpoints);
    }

    /**
     * Returns all the checkpoints.
     * (Alias for the "getCheckpoints()" function)
     *
     * @return mixed
     */
    public function getAllCheckpoints()
    {
        return $this->getAllCheckpoints(null);
    }

    /**
     * Returns checkpoints having the specified group.
     * (Alias for the "getCheckpoints()" function)
     *
     * @param string $group
     *
     * @return mixed
     */
    public function getCheckpointsByGroup($group)
    {
        return $this->getCheckpoints($group);
    }

    /**
     * Delete checkpoint having a specific ID.
     *
     * @param int $id Id of the checkopint to clear.
     */
    public function deleteCheckpoint($id)
    {
        $this->checkpoints = $this->checkpoints
            ->filter(function ($checkpoint, $key) use ($id) {
                /** @var Checkpoint $checkpoint */
                return $checkpoint->getId() !== $id;
            });
    }

    /**
     * Delete checkpoints having a specific group.
     *
     * @param null|string $group Group name specified for the checkpoints or null to return all of them.
     */
    public function deleteCheckpoints($group)
    {
        $this->checkpoints = $this->checkpoints
            ->filter(function ($checkpoint, $key) use ($group) {
                /** @var Checkpoint $checkpoint */
                return $checkpoint->getGroup() !== $group;
            });
    }

    /**
     * Delete all checkpoints.
     */
    public function deleteAllCheckpoints()
    {
        $this->checkpoints = collect();
    }

    /**
     * dump() the checkpoints.
     *
     * @param null|string $group Group name specified for the checkpoints or null to return all of them.
     */
    public function dump($group = null)
    {
        dump($this->getCheckpoints($group));
    }

    /**
     * dd() the checkpoints.
     *
     * @param null|string $group Group name specified for the checkpoints or null to return all of them.
     */
    public function dd($group = null)
    {
        dd($this->getCheckpoints($group));
    }

    /**
     * Returns the time difference between the first and the last checkpoint.
     *
     * @return \DateInterval
     */
    public function getElapsedTime()
    {
        /** @var Checkpoint $firstCheckpoint */
        $firstCheckpoint = $this->checkpoints->first();
        $min = $firstCheckpoint->getTime();

        /** @var Checkpoint $lastCheckpoint */
        $lastCheckpoint = $this->checkpoints->first();
        $max = $lastCheckpoint->getTime();

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

        if (config('benchmark.format_ram_usage')) {
            $ramUsage = self::formatBytes($ramUsage);
        }

        return $ramUsage;
    }

    /**
     * Sets the output format.
     *
     * @param string $outputFormat Qualifier to a class which implemtens the \Janiaje\Benchmark\OutputFormats\OutputFormat interface
     */
    public function setOutputFormat($outputFormat)
    {
        $this->outputFormat = $outputFormat;
    }

    /**
     * Returns the checkpoints in the given format.
     *
     * @param Collection $checkpoints
     *
     * @return mixed
     */
    private function formatCheckpoints($checkpoints)
    {
        $this->enhanceCheckpoints($checkpoints);

        return call_user_func($this->outputFormat . '::get', $checkpoints);
    }

    /**
     * Enhance checkpoints.
     *
     * @param Collection $checkpoints
     */
    private function enhanceCheckpoints($checkpoints)
    {
        $previousCheckpoint = null;

        foreach ($checkpoints as $checkpoint) {

            if ($previousCheckpoint === null) {
                $previousCheckpoint = $checkpoint;

                continue;
            }

            $checkpoint->setTimeDifference($previousCheckpoint->getTime());

            if (config('benchmark.collect_queries')) {
                $checkpoint->setQueries($previousCheckpoint->getQueries());
            }

            $previousCheckpoint = $checkpoint;
        }
    }

    /**
     * Converts the bytes to the highest unit, where they reach at least 1.
     *
     * @param int $bytes
     *
     * @return string
     */
    public static function formatBytes($bytes)
    {
        $postfixes = [
            'B',
            'kB',
            'MB',
            'GB',
            'TB',
        ];

        for (
            $i = 0, $prefixesLength = count($postfixes);
            $i < $prefixesLength && $bytes > 1024;
            $i++
        ) {
            $bytes /= 1024;
        }

        return $bytes . $postfixes[$i];
    }
}
