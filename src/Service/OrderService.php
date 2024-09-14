<?php

namespace Hyperzod\OnroSdkPhp\Service;

use Hyperzod\OnroSdkPhp\Enums\HttpMethodEnum;

class OrderService extends AbstractService
{
   /**
    * Create a job on Onro
    *
    * @param array $params
    *
    * @throws \Hyperzod\OnroSdkPhp\Exception\ApiErrorException if the request fails
    *
    */
   public function create(array $params)
   {
      return $this->request(HttpMethodEnum::POST, '/order/ondemand', $params);
   }

   /**
    * Get a job on Onro
    *
    * @param array $params
    *
    * @throws \Hyperzod\OnroSdkPhp\Exception\ApiErrorException if the request fails
    *
    */

   public function get(array $params)
   {
      return $this->request(HttpMethodEnum::GET, '/order/' . $params['orderId'] . '?customerId=' . $params['customerId'], $params);
   }
}
