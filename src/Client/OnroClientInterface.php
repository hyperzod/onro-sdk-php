<?php

namespace Hyperzod\OnroSdkPhp\Client;

/**
 * Interface for a Onro client.
 */
interface OnroClientInterface extends BaseOnroClientInterface
{
   /**
    * Sends a request to Onro's API.
    *
    * @param string $method the HTTP method
    * @param string $path the path of the request
    * @param array $params the parameters of the request
    */
   public function request($method, $path, $params);
}
