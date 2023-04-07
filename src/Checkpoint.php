<?php

namespace Janiaje\Benchmark;

use Carbon\Carbon;
use DB;

class Checkpoint
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var null|string
     */
    private $name;

    /**
     * @var null|string
     */
    private $group;

    /**
     * @var Carbon
     */
    private $time;

    /**
     * Time difference between this and the previous Checkpoint.
     *
     * @var null|\DateInterval
     */
    private $timeDifference;

    /**
     * Allocated memory in bytes.
     *
     * @var int
     */
    private $ram;

    /**
     * Queries ran between this and the previous checkpoint.
     *
     * @var array
     */
    private $queries;

    /**
     * Checkpoint constructor.
     *
     * @param int  $id
     * @param null $name
     */
    public function __construct($id, $name, $group)
    {
        $this->ram = memory_get_usage(config('benchmark.memory_real_usage'));

        $this->id = '#' . $id;

        $this->name = $name;

        $this->group = $group;

        $this->time = new Carbon;

        $this->queries = DB::getQueryLog();
    }

    /**
     * @param Carbon\Carbon $time The previous checkpoint's timestamp.
     */
    public function setTimeDifference(Carbon $time)
    {
        $this->timeDifference = $this->time->diff($time);
    }

    /**
     * @param array $queries Previous checkpoint's quereries.
     */
    public function setQueries(array $queries)
    {
        // Convert arrays to be able to use 'array_diff'
        $previousQueries = array_map(function ($query) {
            return json_encode($query);
        }, $queries);

        $queries = array_map(function ($query) {
            return json_encode($query);
        }, $this->queries);

        // Get only the queries ran between the checkpoints
        $queries = array_diff($queries, $previousQueries);

        // Conert back the array
        $queries = array_map(function ($query) {
            return json_decode($query);
        }, $queries);

        $this->queries = $queries;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return Carbon
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return int|string
     */
    public function getRam()
    {
        $ram = $this->ram;

        if(config('benchmark.format_ram_usage')) {
            $ram = Benchmark::formatBytes($ram);
        }

        return $ram;
    }

    /**
     * @return null|\DateInterval
     */
    public function getTimeDifference()
    {
        return $this->timeDifference;
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }
}