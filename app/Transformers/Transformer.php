<?php

namespace App\Transformers;

use App\Util\ZuluTime;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Str;

/**
 * Class Transformer
 * @package App\Transformers
 */
abstract class Transformer extends TransformerAbstract
{
    /**
     * The accepted names to include this transformer
     */
    protected $includeNames = [];

    /**
     * If The Transformer is An included resource
     */
    protected $isAnInclude = false;

    /**
     * Apply filters to the transformed object
     */
    public function filter(array $mapping, $record = null)
    {
        $mapping = $this->convertCarbonDatesToZulu($mapping);

        $mapping = $this->limitTransformedFields($mapping);

        $mapping = $this->appendExtraFields($mapping, $record);

        return $mapping;
    }

    /**
     * Add extra fields to the main transformed object, doesn't apply to the list of includes
     */
    private function appendExtraFields($data, $record)
    {
        if ($this->isAnInclude || !is_object($record) || !\Request::has('append')) {
            return $data;
        }

        $append_fields = explode(',', \Request::input('append'));

        foreach ($append_fields as $field) {
            $data[$field] = $record->$field ?? null;
        }

        return $data;
    }

    private function limitTransformedFields($data)
    {
        $limit_fields = array_filter(explode(',', \Request::input('fields', '')));

        if ($this->isAnInclude) {
            $limit_fields = $this->extractLimitedFields($limit_fields);
        }

        if (!empty($limit_fields)) {
            return Arr::only($data, $limit_fields);
        }

        return $data;
    }

    private function extractLimitedFields($fields)
    {
        $includes = array_intersect($this->includeNames, explode(',', \Request::input('with')));
        $limit_fields = [];
        foreach ($includes as $include) {
            foreach ($fields as $field) {
                if (Str::startsWith($field, $include . '.')) {
                    $limit_fields[] = explode('.', $field)[1];
                }
            }
        }

        return $limit_fields;
    }

    private function convertCarbonDatesToZulu($data, $isObject = false)
    {
        foreach ($data as $key => $value) {
            if ($this->isTraversable($value)) {
                $data[$key] = $this->convertCarbonDatesToZulu((array)$value, is_object($value));
            }

            if ($this->isCarbon($value)) {
                $data[$key] = (string)new ZuluTime($value);
            }
        }

        return $isObject ? (object)$data : $data;
    }

    private function isTraversable($data)
    {
        return (is_array($data) || is_object($data)) && !$this->isCarbon($data);
    }

    private function isCarbon(&$value)
    {
        if ($value instanceof Carbon || $this->isConvertableToCarbon($value)) {
            return true;
        }

        return false;
    }

    private function isConvertableToCarbon(&$data)
    {
        if ($this->isDateTimeString($data)) {
            $data = Carbon::createFromFormat('Y-m-d H:i:s', $data);
            return true;
        }

        if ($this->isCarbonObject($data)) {
            $data = new Carbon($data->date, $data->timezone);
            return true;
        }

        return false;
    }

    private function isDateTimeString($data)
    {
        return is_string($data) && preg_match('/^(\d{4})-(\d{2})-(\d{2})\s(\d{2}):(\d{2}):(\d{2})$/', $data);
    }

    private function isCarbonObject($obj)
    {
        return isset($obj->date) && $obj->date &&
            isset($obj->timezone_type) && $obj->timezone_type &&
            isset($obj->timezone) && $obj->timezone;
    }

    public function morph($type, $model)
    {
        $transformer = app('dingo.api.transformer')->getTransformerBindings()[$type];
        return $this->item($model, app($transformer));
    }

    public function map($items, $transformer)
    {
        $data = [];
        foreach ($items as $item) {
            $data[] = $transformer->transform($item);
        }
        return $data;
    }

    /**
     * Create a new item resource object
     */
    protected function item($data, $transformer, $resourceKey = null)
    {
        $transformer->isAnInclude = true;

        return parent::item($data, $transformer, $resourceKey);
    }

    /**
     * Create a new collection resource object
     */
    protected function collection($data, $transformer, $resourceKey = null)
    {
        $transformer->isAnInclude = true;

        return parent::collection($data, $transformer, $resourceKey);
    }

}
