<?php
namespace SlimApi\Json;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Negotiation\Negotiator;

/**
 * json middleware, detects is accept header is json-y and attempts to json encode the output.
 */
class Json
{
    /**
     * Invoke middleware
     *
     * @param  RequestInterface  $request  PSR7 request object
     * @param  ResponseInterface $response PSR7 response object
     * @param  callable          $next     Next middleware callable
     *
     * @return ResponseInterface PSR7 response object
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        if ($next) {
            $response = $next($request, $response);
        }

        $negotiator = new \Negotiation\EncodingNegotiator();
        $mediaType  = $negotiator->getBest($request->getHeaderLine('accept'), ['application/json']);
        // accept header can take json
        if ($mediaType && $mediaType->getType()) {
            $negotiator = new \Negotiation\EncodingNegotiator();
            $mediaType  = $negotiator->getBest($response->getHeaderLine('Content-Type'), ['application/json']);

            // content-type header isn't json-y, so withJson hasn't already been called
            if (!$mediaType || !$mediaType->getType()) {
                $body = (string)$response->getBody();

                // might already be json-y, so attempt to decode it first
                $jsonDecodeBody = json_decode($body, true);
                if (JSON_ERROR_NONE === json_last_error()) {
                    $body = $jsonDecodeBody;
                }

                // make sure it can be encoded as json, otherwise return as is
                $jsonEncodeBody = json_encode($body);
                if (JSON_ERROR_NONE === json_last_error()) {
                    $response = $response->withJson($body);
                }
            }
        }

        return $response;
    }
}
