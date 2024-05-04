# Slick file system watch package

[![Latest Version](https://img.shields.io/github/release/slickframework/fswatch.svg?style=flat-square)](https://github.com/slickframework/fswatch/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/github/actions/workflow/status/slickframework/fswatch/continuous-integration.yml?style=flat-square)](https://github.com/slickframework/fswatch/actions/workflows/continuous-integration.yml)
[![Quality Score](https://img.shields.io/scrutinizer/g/slickframework/fswatch/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/slickframework/fswatch?branch=master)
[![Contributor Covenant](https://img.shields.io/badge/Contributor%20Covenant-2.1-4baaaa.svg?style=flat-square)](CONDUCT.md)
[![Total Downloads](https://img.shields.io/packagist/dt/slick/fswatch.svg?style=flat-square)](https://packagist.org/packages/slick/fswatch)

``slick/fswatch`` is a simple library that sums the total size of all files in a given directory allowing you to verify if its contents have changed.

This package is compliant with PSR-2 code standards and PSR-4 autoload standards. It
also applies the [semantic version 2.0.0](http://semver.org) specification.

## Install

Via Composer

``` bash
composer require slick/fschange
```

## Usage
First you need to create a directory snapshot to be able to compare it with any other changes
later on:
```php
use Slick\FsWatch\Directory;
use Slick\FsWatch\Directory\Snapshot;

$dir = new Directory('/path/to/directory');
// can be stored in any cache or temporary memory to be checked later
$snapshot = new Snapshot($dir); 

```

Now you can verify if directory contents have changed:
```php
if ($dir->hasChanged($snapshot)) { //directory contents have changed
    // do your logic
}
```

#### Using as a watcher
Using the same principle above, you can have a daemon like script that will execute a given
callback whenever a file changes or is added to a given directory, recursively.
```php
use Slick\FsWatch\Directory;
use Slick\FsWatch\Watcher;

$dir = new Directory('/path/to/directory');

$watcher = new Watcher($dir, function (Directory $dir) => {
    // do your logic on file change
});

// This will run until Ctrl + C (SIGINT) is pressed.
// You can pass an expression of callable to determine the end of the execution
$watcher->watch(Watcher::SIGINT);

```

## Testing
``` bash
phpunit
``` 

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email slick.framework@gmail.com instead of using the issue tracker.

## Credits

- [Slick framework](https://github.com/slickframework)
- [All Contributors](https://github.com/slickframework/fswatch/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.