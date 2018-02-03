<?php

namespace Janiaje\Benchmark;

use Carbon\Carbon;
use DB;

class Checkpoint
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var null|string
     */
    private $name;

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
     * Queries run.
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
    public function __construct($id, $name)
    {
        $this->ram = memory_get_usage(config('benchmark.memory_real_usage'));

        $this->id = '#' . $id;

        $this->name = $name;

        $this->time = new Carbon;

        $this->queries = DB::getQueryLog();
    }

    /**
     * @param int|Carbon\Carbon $timeDifference
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

        // Get only the queries run between the checkpoints
        $queries = array_diff($queries, $previousQueries);

        // Conert back the array
        $queries = array_map(function ($query) {
            return json_decode($query);
        }, $queries);

        $this->queries = $queries;
    }

    /**
     * @return string
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
     * @return Carbon
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return int
     */
    public function getRam()
    {
        return $this->ram;
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