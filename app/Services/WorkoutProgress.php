<?php 

namespace App\Service;

// I am using this so we could run outsoide of the controller
// Maybe in a nighly job
class WorkoutProgress {

    public $workouts;

    public function __construct($workouts)
    {
        $this->workouts = $workouts;
    }

    // I haven't done this with a specific date we pass in the collection
    // Then calculate so you could do different timeframes
    public function maxWeight()
    {
        // GROUP BY LIFT
        return $this->workouts->max('');
    }

    // I haven't done this with a specific date we pass in the collection
    // Then calculate so you could do different timeframes
    public function maxVolume()
    {
        // GROUP BY LIFT
        return $this->workouts->max('volume');
    }
}
