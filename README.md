# Laravel-Benchmark

Adds a Benchmark helper to your project, to get
 * exact time
 * elapsed time
 * RAM usage
 * queries ran by Eloquent

between checkpoints.

## Installation

```bash
composer require janiaje/benchmark
```

> If Laravel version < 5.5, you have to manually include the following line in your config/app.php:
```php
Janiaje\Benchmark\Provider::class,
```

## Usage (basics)

### Use the 'benchmark()' helper to easily access to the Benchmark class
```php
benchmark()
```

### Add checkpoints:
```php
benchmark()->checkpoint();
```

### Get the elapsed time between the first and the last checkpoint
```php
$elapsedTime = benchmark()->getElapsedTime();
```

### Get the maximum amount RAM (in bytes) allocated by PHP in the checkpoints
```php
$ramUsage = benchmark()->getPeakRamUsage();
```

### Get the checkpoints
```php
$checkpoints = benchmark()->getCheckpoints();
```

### Dump the checkpoints
```php
benchmark()->dump();
```

### DD the checkpoints:
```php
benchmark()->dd();
```

### Set the output format for the checkpoints:
```php
benchmark()->setOutputFormat(ArrayFormat::class);
benchmark()->setOutputFormat(JsonFormat::class);
```
The 2 options mentioned above are available by default.
You can override them or make your own,
but make sure it implements the `\Janiaje\Benchmark\OutputFormats\OutputFormat` interface.

Example of `ArrayFormat::class` output: 
```text
Collection {#275 ▼
  #items: array:2 [▼
    0 => array:6 [▼
      "id" => "#1"
      "name" => null
      "group" => null
      "time" => Carbon @1521101210 {#272 ▶}
      "timeDifference" => null
      "ram" => 6291456
      "queries" => []
    ]
    1 => array:6 [▼
      "id" => "#2"
      "name" => null
      "group" => null
      "time" => Carbon @1521101211 {#270 ▶}
      "timeDifference" => DateInterval {#277 ▶}
      "ram" => 6291456
      "queries" => array:1 [▼
        0 => {#279 ▼
          +"query": "SELECT * FROM users WHERE email = ?"
          +"bindings": array:1 [▼
            0 => "janiaje@gmail.com"
          ]
          +"time": 1.15
        }
      ]
    ]
  ]
}
```

## Usage (additional options)

### Naming checkpoints
This name will show up in the results so it will be easier to find a specific checkpoint.
```php
benchmark()->checkpoint("After expensive calculation");
```

```text
    1 => array:6 [▼
      "id" => "#2"
      "name" => "After expensive calculation"
      "group" => null
      "time" => Carbon @1521101211 {#270 ▶}
```

### Grouping checkpoints
Creating a checkpoint with a group:
```php
benchmark()->checkpointWithGroup("File generation");
```

This group will show up in the results:
```text
    1 => array:6 [▼
      "id" => "#2"
      "name" => "After expensive calculation"
      "group" => "File generation"
      "time" => Carbon @1521101211 {#270 ▶}
```

You can filter the results by groups:
```php
benchmark()->getCheckpointsByGroup("File generation");
```

### Deleting checkpoints

Delete all checkpoints:
```php
benchmark()->deleteAllCheckpoints();
```

Delete checkoints by group:
```php
benchmark()->deleteCheckpoints("File generation");
```

Delete checkoints by id:
```php
$checkpoint = benchmark()->checkpoint();
benchmark()->deleteCheckpoint($checkpoint->getId();
```

If you dont want to delete them,
you can always create your own `Benchmark` instances and collect everythign sepearately:
```php
$benchmark1 = new Benchmark;
$benchmark1->checkpoint();
$benchmark1->getAllCheckpoints();

$benchmark2 = new Benchmark;
$benchmark2->checkpoint();
$benchmark2->getAllCheckpoints();
```