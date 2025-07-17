<?php

namespace App\Entity;

class Meteo{
    /**
     * MeteoDay[] $days
     */
    private array $days = [];


    /**
     * Get the value of days
     *
     * @return array
     */
    public function getDays(): array
    {
        return $this->days;
    }

    /**
     * Set the value of days
     *
     * @param array $days
     *
     * @return self
     */
    public function setDays(array $days): self
    {
        $this->days = $days;

        return $this;
    }

    public function addDay(MeteoDay $meteoDay) 
    {
        //vérifier que la météo n'est pas déjà dans les jours
        if (!in_array($meteoDay, $this->days)) {
            $this->days[] = $meteoDay;
        }
    }
}

