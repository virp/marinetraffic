<?php

declare(strict_types=1);

namespace Virp\Marinetraffic;

/**
 * Class Position
 */
class Position
{
    /**
     * @var float
     */
    public $latitude;

    /**
     * @var float
     */
    public $longitue;

    /**
     * Position constructor.
     * @param float $latitude
     * @param float $longitue
     */
    public function __construct(float $latitude, float $longitue)
    {
        $this->latitude = $latitude;
        $this->longitue = $longitue;
    }
}