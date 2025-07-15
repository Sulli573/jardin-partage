<?php

namespace App\Entity;

class MeteoDay{
    private \DateTime $day;
    private float $temperatureMax;
    private float $temperatureMin;
    private float $precipitation;



    /**
     * Get the value of day
     *
     * @return \DateTime
     */
    public function getDay(): \DateTime
    {
        return $this->day;
    }

    /**
     * Set the value of day
     *
     * @param \DateTime $day
     *
     * @return self
     */
    public function setDay(\DateTime $day): self
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get the value of temperatureMax
     *
     * @return float
     */
    public function getTemperatureMax(): float
    {
        return $this->temperatureMax;
    }

    /**
     * Set the value of temperatureMax
     *
     * @param float $temperatureMax
     *
     * @return self
     */
    public function setTemperatureMax(float $temperatureMax): self
    {
        $this->temperatureMax = $temperatureMax;
        
        return $this;
    }

    /**
     * Get the value of temperatureMin
     *
     * @return float
     */
    public function getTemperatureMin(): float
    {
        return $this->temperatureMin;
    }

    /**
     * Set the value of temperatureMin
     *
     * @param float $temperatureMin
     *
     * @return self
     */
    public function setTemperatureMin(float $temperatureMin): self
    {
        $this->temperatureMin = $temperatureMin;

        return $this;
    }

    /**
     * Get the value of precipitation
     *
     * @return float
     */
    public function getPrecipitation(): float
    {
        return $this->precipitation;
    }

    /**
     * Set the value of precipitation
     *
     * @param float $precipitation
     *
     * @return self
     */
    public function setPrecipitation(float $precipitation): self
    {
        $this->precipitation = $precipitation;

        return $this;
    }
}

