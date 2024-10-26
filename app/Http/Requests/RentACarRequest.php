<?php

namespace App\Http\Requests;

use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class RentACarRequest extends FormRequest
{

    const MATURE_YEARS = 18;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->canUserRentCar();
    }

    private function canUserRentCar(): bool
    {
        return $this->isMature() && !$this->hasRentedCar();
    }

    private function isMature(): bool
    {
        return $this->user()->birthday < Carbon::now()->subYears(self::MATURE_YEARS)->format('Y-m-d');
    }

    private function hasRentedCar(): bool
    {
        return $this->user()->car !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    public function getCar(): Car
    {
        return Car::where('car', $this->get('car'))->firstOrFail();
    }
}
