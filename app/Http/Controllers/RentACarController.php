<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentACarRequest;
use App\Models\Car;
use App\Services\RentACarService;
use App\Services\RentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RentACarController extends Controller
{
    public function __construct(
        private readonly RentService $rentACarService
    )
    {
    }

    /**
     * @param RentACarRequest $request
     * @return JsonResponse
     */
    public function store(RentACarRequest $request): JsonResponse
    {
        $this->rentACarService->rentCarAsUser(
            $request->user(),
            $request->getCar()
        );

        return new JsonResponse(['status' => true, 'message' => 'Booking has been created'], 201);
    }
}
