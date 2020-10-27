<?php

namespace App\Services;

use App\Models\Car;
use App\Models\User;

class RentACarService
{
    public function rentCarAsUser(User $user, Car $car): void
    {
        if($car->qty <= 0) {
            throw new \UnderflowException("Car is out of stock");
        }

        $car->qty -= 1;
        $car->save(); // yes, this should be done in repository ;)

        $user->car = $car->car;
        $user->save(); // this too ;)
    }
}