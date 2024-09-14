<?php

namespace Hyperzod\OnroSdkPhp\Client;

use Exception;
use GuzzleHttp\Client;
use Hyperzod\OnroSdkPhp\Exception\InvalidArgumentException;

class BaseOnroClient implements OnroClientInterface
{

   /** @var array<string, mixed> */
   private $config;

   /**
    * Initializes a new instance of the {@link BaseOnroClient} class.
    *
    * The constructor takes two arguments.
    * @param string $client_id the Client ID of the client
    * @param string $api_base the base URL for Onro's API
    */

   public function __construct($customer_id, $client_id, $client_secret, $api_base)
   {
      $config = $this->validateConfig(array(
         "customer_id" => $customer_id,
         "client_id" => $client_id,
         "client_secret" => $client_secret,
         "api_base" => $api_base
      ));

      $this->config = $config;
   }


   /**
    * Gets the Customer ID used by the client to send requests.
    *
    * @return null|string the Customer ID used by the client to send requests
    */
   public function getCustomerID()
   {
      return $this->config['customer_id'];
   }

   /**
    * Gets the Client ID used by the client to send requests.
    *
    * @return null|string the Client ID used by the client to send requests
    */
   public function getClientID()
   {
      return $this->config['client_id'];
   }

   /**
    * Gets the Client Secret used by the client to send requests.
    *
    * @return null|string the Client Secret used by the client to send requests
    */
   public function getClientSecret()
   {
      return $this->config['client_secret'];
   }

   /**
    * Gets the base URL for Onro's API.
    *
    * @return string the base URL for Onro's API
    */
   public function getApiBase()
   {
      return $this->config['api_base'];
   }

   /**
    * Get Access Token
    *
    * @return string the access token
    */
   public function getAccessToken()
   {
      // Instantiate a Guzzle client
      $client = new Client();

      $response = $client->post($this->getApiBase() . "/auth/token", [
         'json' => [
            'clientId' => $this->getClientID(),
            'clientSecret' => $this->getClientSecret()
         ]
      ]);

      // Get the response body as a string
      $responseBody = $response->getBody()->getContents();

      // Decode the JSON response
      $result = json_decode($responseBody, true);
      return $result['data']['accessToken'];
   }


   /**
    * Sends a request to Onro's API.
    *
    * @param string $method the HTTP method
    * @param string $path the path of the request
    * @param array $params the parameters of the request
    */

   public function request($method, $path, $params)
   {
      $client = new Client([
         'headers' => [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $params['access_token']
         ]
      ]);

      unset($params['access_token']);

      $api = $this->getApiBase() . $path;

      $response = $client->request($method, $api, [
         'json' => $params
      ]);

      return $this->validateResponse($response);
   }

   /**
    * @param array<string, mixed> $config
    *
    * @throws InvalidArgumentException
    */
   private function validateConfig($config)
   {
      if (!is_string($config['customer_id'])) {
         throw new InvalidArgumentException('customer_id must be a string');
      }

      if ('' === $config['customer_id']) {
         throw new InvalidArgumentException('customer_id cannot be an empty string');
      }

      if (preg_match('/\s/', $config['customer_id'])) {
         throw new InvalidArgumentException('customer_id cannot contain whitespace');
      }

      // client_id
      if (!isset($config['client_id'])) {
         throw new InvalidArgumentException('client_id field is required');
      }

      if (!is_string($config['client_id'])) {
         throw new InvalidArgumentException('client_id must be a string');
      }

      if ('' === $config['client_id']) {
         throw new InvalidArgumentException('client_id cannot be an empty string');
      }

      if (preg_match('/\s/', $config['client_id'])) {
         throw new InvalidArgumentException('client_id cannot contain whitespace');
      }

      if (!isset($config['client_secret'])) {
         throw new InvalidArgumentException('client_secret field is required');
      }

      if (!is_string($config['client_secret'])) {
         throw new InvalidArgumentException('client_secret must be a string');
      }

      if ('' === $config['client_secret']) {
         throw new InvalidArgumentException('client_secret cannot be an empty string');
      }

      if (preg_match('/\s/', $config['client_secret'])) {
         throw new InvalidArgumentException('client_secret cannot contain whitespace');
      }

      if (!isset($config['client_secret'])) {
         throw new InvalidArgumentException('client_secret field is required');
      }

      if (!isset($config['api_base'])) {
         throw new InvalidArgumentException('api_base field is required');
      }

      if (!is_string($config['api_base'])) {
         throw new InvalidArgumentException('api_base must be a string');
      }

      if ('' === $config['api_base']) {
         throw new InvalidArgumentException('api_base cannot be an empty string');
      }

      return [
         "customer_id" => $config['customer_id'],
         "client_id" => $config['client_id'],
         "client_secret" => $config['client_secret'],
         "api_base" => $config['api_base'],
      ];
   }

   private function validateResponse($response)
   {
      $status_code = $response->getStatusCode();

      if ($status_code >= 200 && $status_code < 300) {
         $response = json_decode($response->getBody(), true);
         return $response;
      } else {
         $response = json_decode($response->getBody(), true);
         if (isset($response["errors"])) {
            throw new Exception($response["errors"][0]["message"]);
         }
         throw new Exception("Errors node not set in server response");
      }
   }
}
