# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [v0.1.0] - 2024-05-07
### Added
- Directory class that maps a given directory and calculates content size recursively
- Directory snapshot that can create an hash of a given directory and is used to 
  identify directory changes.
- Directory watcher that emit changes on a given directory by calling a passed callback
- Changes collection that stores the changed file name and the change type: ADD, CHANGE
  and DELETE
- CHANGELOG.md file to log project changes.

## [v0.0.2] - 2024-05-04
### Added
- Scrutinizer integration for code analysis
### Changed
- PHP >= 8.2
- Github actions: Integration Workflow with most recent PHP actions and steps. Support
  for PHP 8.2 and 8.3 integration tests.
### Removed
- Support for PHP <= 8.1

## [v0.0.1] - 2024-05-04
### Added
- README.md file with basic install and usage instructions
- Project setup with composer
- PHPUnit framework setup and first test
- Contributing guide
- Code of conduct for the project
- Docker-compose definition file for development docker support 
- Github actions for continuous integration

[unreleased]: https://github.com/slickframework/fswatch/compare/v0.1.0...HEAD
[v0.1.0]: https://github.com/slickframework/fswatch/compare/v0.0.2...v0.1.0
[v0.0.2]: https://github.com/slickframework/fswatch/compare/v0.0.1...v0.0.2
[v0.0.1]: https://github.com/slickframework/fswatch/releases/tag/v0.0.1