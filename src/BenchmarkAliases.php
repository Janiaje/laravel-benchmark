<?php

namespace Janiaje\Benchmark;

trait BenchmarkAliases
{
    /**
     * Alias for the Benchmark's 'checkpoint' method.
     *
     * @param null|string $name
     *
     * @return Checkpoint
     */
    public function addCheckpoint($name = null)
    {
        return $this->checkpoint($name);
    }

    /**
     * Alias for the Benchmark's 'checkpointWithGroup' method.
     *
     * @param null|string $group
     *
     * @return Checkpoint
     */
    public function addCheckpointWithGroup($group = null)
    {
        return $this->checkpointWithGroup($group);
    }

    /**
     * Alias for the Benchmark's 'getCheckpoints' method.
     *
     * @param null|string $group Group name specified for the checkpoints or null to return all of them.
     *
     * @return mixed
     */
    public function get($group = null)
    {
        return $this->getCheckpoints($group);
    }
}