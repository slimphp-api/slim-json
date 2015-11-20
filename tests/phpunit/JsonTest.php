<?php
namespace SlimApi\JsonTest;

use SlimApi\Json\Json;

use Slim\Route;
use Slim\Http\Body;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use Slim\Http\Uri;

/**
 *
 */
class JsonTest extends \PHPUnit_Framework_TestCase
{
    public function requestFactory($acceptType = 'application/json')
    {
        $env = Environment::mock(['HTTP_ACCEPT' => $acceptType]);

        $uri = Uri::createFromString('https://example.com:443/foo/bar?abc=123');
        $headers = Headers::createFromEnvironment($env);
        $serverParams = $env->all();
        $body = new RequestBody();
        $uploadedFiles = UploadedFile::createFromEnvironment($env);
        $request = new Request('GET', $uri, $headers, [], $serverParams, $body, $uploadedFiles);

        return $request;
    }

    public function testJsonAcceptReturnJson()
    {
        $json = new Json;
        $request = $this->requestFactory();
        $response = (new Response())->withHeader('Content-Type', 'text/html');
        $newResponse = $json($request, $response, function ($req, $res) {return $res->write(json_encode(['foo' => 'bar']));});
        $this->assertEquals('application/json;charset=utf-8', $newResponse->getHeaderLine('Content-Type'));
    }

    public function testNonJsonString()
    {
        $json = new Json;
        $request = $this->requestFactory();
        $response = (new Response())->withHeader('Content-Type', 'text/html');
        $newResponse = $json($request, $response, function ($req, $res) {return $res->write("Foo " . chr(163));});
        $this->assertEquals('text/html', $newResponse->getHeaderLine('Content-Type'));
    }

    public function testJsonWithJsonNoChanges()
    {
        $json = new Json;
        $request = $this->requestFactory();
        $response = (new Response())->withHeader('Content-Type', 'text/html');
        $newResponse = $json($request, $response, function ($req, $res) {return $res->withJson(['foo' => 'bar']);});
        $this->assertEquals('application/json;charset=utf-8', $newResponse->getHeaderLine('Content-Type'));
        $this->assertEquals('{"foo":"bar"}', ((string)$newResponse->getBody()));
    }
}
