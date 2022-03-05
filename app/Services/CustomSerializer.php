<?php

namespace App\Services;

use League\Fractal\Serializer\ArraySerializer;

class CustomSerializer extends ArraySerializer
{
    /**
     * @param $resourceKey
     * @param array $data
     * @return array
     */
    public function collection($resourceKey, array $data): array
    {
    if ($resourceKey) {
        return [$resourceKey => $data];
    }

    return $data;
    }

    /**
     * @param $resourceKey
     * @param array $data
     * @return array
     */
    public function item($resourceKey, array $data): array
    {
        if ($resourceKey) {
        return [$resourceKey => $data];
        }
        return $data;
    }
}
