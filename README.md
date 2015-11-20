# slim-json
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
