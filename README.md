# Laravel-Benchmark

Adds a Benchmark helper to your project.

## Installation

```bash
composer require janiaje/benchmark
```

> If Laravel version < 5.5, you have to manually include this line in your config/app.php:
```php
Janiaje\Benchmark\Provider::class,
```

## Usage

Use the 'benchmark()' helper to easily access to the Benchmark class.
```php
benchmark()
```

Add checkpoints:
```php
benchmark()->checkpoint();
```

Get the elapsed time between the first and the last checkpoint:
```php
$elapsedTime = benchmark()->getElapsedTime();
```

Get the maximum amount RAM (in bytes) allocated by PHP in the checkpoints:
```php
$ramUsage = benchmark()->getPeakRamUsage();
```

Get the checkpoints:
```php
$checkpoints = benchmark()->getCheckpoints();
```

Dump the checkpoints:
```php
benchmark()->dump();
```

DD the checkpoints:
```php
benchmark()->dd();
```

Set the output format for the checkpoints:
```php
benchmark()->setOutputFormat(ArrayFormat::class);
benchmark()->setOutputFormat(JsonFormat::class);
```
The 2 options mentioned above are available by default.
You can override them or make your own,
but make sure it implements the `\Janiaje\Benchmark\OutputFormats\OutputFormat` interface.

## Warning
This package is still in early in development use it at your own risk!