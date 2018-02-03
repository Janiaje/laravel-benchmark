<?php

namespace Janiaje\Benchmark;

trait BenchmarkAliases
{
    /**
     * Alias for the Benchmark's 'checkpoint' method.
     *
     * @param null|string $name
     */
    public function addCheckpoint($name = null)
    {
        $this->checkpoint($name);
    }

    /**
     * Alias for the Benchmark's 'getCheckpoints' method.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->getCheckpoints();
    }
}