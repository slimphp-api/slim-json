# slim-json

[![Coverage Status](https://coveralls.io/repos/slimphp-api/slim-json/badge.svg?branch=master&service=github)](https://coveralls.io/github/slimphp-api/slim-json?branch=master)
[![Code Climate](https://codeclimate.com/github/slimphp-api/slim-json/badges/gpa.svg)](https://codeclimate.com/github/slimphp-api/slim-json)
[![Build Status](https://travis-ci.org/slimphp-api/slim-json.svg?branch=master)](https://travis-ci.org/slimphp-api/slim-json)


JSON middleware that enforces a json response wherever possible. Based on Accept header

# installation

`composer require slimphp-api/slim-json`

# usage
Add middleware in the usual way. For slim:

```
use Slim\App;
$app = new App();
$app->add(new SlimApi\Json\Json);
$app->run();
```
