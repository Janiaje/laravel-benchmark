<?php

namespace Janiaje\Benchmark;

use Carbon\Carbon;

class Checkpoint
{
    /**
     * @var null|string
     */
    private $name;

    /**
     * @var Carbon
     */
    private $time;

    /**
     * Allocated memory in bytes.
     *
     * @var int
     */
    private $ram;

    /**
     * Checkpoint constructor.
     *
     * @param null $name
     */
    public function __construct($name)
    {
        $this->ram = memory_get_usage(config('benchmark.memory_real_usage'));

        $this->time = new Carbon;

        $this->name = $name;
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
}