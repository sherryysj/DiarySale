### Unit Testing
- Unit testing files are in /tests, and have file names like somethingTest.php
#### Prerequisites
- install PHPUnit in your working directory: `composer require --dev phpunit/phpunit ^8`
- edit composer.json, add any folders or files your testing code will be using in autoload->classmap
- `composer update` to update according to edited json file
#### Usage
- to execute all tests: `vendor/bin/phpunit tests`
- to execute a specific test file: `vendor/bin/phpunit tests/<file_name>`
