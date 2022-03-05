<?php

namespace App\Services;

use League\Fractal\Manager;
use League\Fractal\ScopeFactoryInterface;
use League\Fractal\Serializer\SerializerAbstract;

class CustomManager extends Manager
{

    /**
     * @param ScopeFactoryInterface|null $scopeFactory
     */
    public function __construct(ScopeFactoryInterface $scopeFactory = null)
    {
        parent::__construct($scopeFactory);
    }

    /**
     * Get Serializer.
     *
     * @return SerializerAbstract
     */
    public function getSerializer(): SerializerAbstract
    {
        if (! $this->serializer) {
            $this->setSerializer(new CustomSerializer());
        }

        return $this->serializer;
    }
}
