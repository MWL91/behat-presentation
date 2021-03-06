<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentACarRequest;
use App\Models\Car;
use App\Services\RentACarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RentACarController extends Controller
{
    private RentACarService $rentACarService;

    /**
     * RentACarController constructor.
     * @param RentACarService $rentACarService
     */
    public function __construct(RentACarService $rentACarService)
    {
        $this->rentACarService = $rentACarService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * @param RentACarRequest $request
     * @return JsonResponse
     */
    public function store(RentACarRequest $request): JsonResponse
    {
        $car = Car::where('car', $request->car)->first();
        $this->rentACarService->rentCarAsUser($request->user(), $car);

        return new JsonResponse(['status' => true, 'message' => 'Booking has been created'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
