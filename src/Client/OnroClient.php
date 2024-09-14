<?php

namespace Hyperzod\OnroSdkPhp\Client;

use Hyperzod\OnroSdkPhp\Service\CoreServiceFactory;

class OnroClient extends BaseOnroClient
{
    /**
     * @var CoreServiceFactory
     */
    private $coreServiceFactory;

    public function __get($name)
    {
        if (null === $this->coreServiceFactory) {
            $this->coreServiceFactory = new CoreServiceFactory($this);
        }

        return $this->coreServiceFactory->__get($name);
    }
}
