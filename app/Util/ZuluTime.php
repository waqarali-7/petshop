<?php namespace App\Util;

use Carbon\Carbon;

class ZuluTime
{
    public const ISO8601_ZULU_TIME = 'Y-m-d\TH:i:s\Z';
    private Carbon $dateTime;

    public function __construct(Carbon $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function __toString()
    {
        return $this->dateTime->format(static::ISO8601_ZULU_TIME);
    }

}
