<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Singleton instance
    |--------------------------------------------------------------------------
    |
    | If set to true the Benchmark class will be bound to the $app
    | as a singleton. If set to false, you must save the instance
    | and work with the saved instance.
    |
    */
    'singleton' => env('BENCHMARK_SINGLETON', true),

    /*
    |--------------------------------------------------------------------------
    | Default output format
    |--------------------------------------------------------------------------
    |
    | Sets the default output format.
    |
    | Options provided by default:
    |   - \Janiaje\Benchmark\OutputFormats\ArrayFormat::class
    |   - \Janiaje\Benchmark\OutputFormats\JsonFormat::class
    |
    | You can make your own OutputFormat, but make sure,
    | it implements the '\Janiaje\Benchmark\OutputFormats\OutputFormat' interface.
    |
    */
    'output_format' => env('BENCHMARK_OUTPUT_FORMAT', \Janiaje\Benchmark\OutputFormats\ArrayFormat::class),

    /*
    |--------------------------------------------------------------------------
    | Memory real usage
    |--------------------------------------------------------------------------
    |
    | This config will be used as a parameter for
    | the PHP's 'memory_get_usage' function.
    |
    | Set this to true to get the real size of memory allocated from system.
    | If set to false only the memory used by emalloc() is reported.
    |
    */
    'memory_real_usage' => env('BENCHMARK_MEMORY_REAL_USAGE', true),

    /*
    |--------------------------------------------------------------------------
    | Log / collect queries
    |--------------------------------------------------------------------------
    |
    | Set this to true to log / collect queries ran between the checkpoints.
    |
    */
    'collect_queries' => env('BENCHMARK_COLLECT_QUERIES', true),

    /*
    |--------------------------------------------------------------------------
    | Format RAM usage
    |--------------------------------------------------------------------------
    |
    | False if you want to get the number of bytes as the RAM usage.
    |
    */
    'format_ram_usage' => env('BENCHMARK_FORMAT_RAM_USAGE', true),
];