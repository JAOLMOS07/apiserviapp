<?php

namespace App\Http\Controllers;
use JWAuth;
use App\Models\Rate;
use App\Models\service;

use Illuminate\Http\Request;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $user;
    public function __construct(Request $request)
    {
        $token = $request->header('Authorization');
        if ($token != '')
            //En caso de que requiera autentifiación la ruta obtenemos el usuario y lo almacenamos en una variable, nosotros no lo utilizaremos.
            $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Service $service)
    {
        $worker = $this->user->worker;
        if($worker == null)
        {
            return response("Invalid worker",400);
        }

        $rate = Rate::create([
            'rate_service'=>null,
            'rate_worker'=>null,
            'service_id'=> $service->id,
            'worker_id' => $worker->id
        ]);


        return response($rate);
    }
    public function rateWorker(Request $request,Service $service)
    {
        $worker = $this->user->worker;
        if($worker == null || $worker->id != $service->worker->id)
        {
            return response("Invalid worker",400);
        }

        $rate = $service->rate;

        if($rate == null)
        {
            return response("error rate",404);
        }

        $rate->rate_worker = $request->rate_worker;
        $rate->save();
        return response($rate);
    }
    public function rateService(Request $request,Service $service)
    {
        $client = $this->user->client;

        if($client == null || $client->id != $service->Client->id)
        {
            return response("Invalid client",400);
        }

        $rate = $service->rate;

        if($rate == null)
        {
            return response("error rate",404);
        }

        $rate->rate_client = $request->rate_client;
        $rate->save();
        return response($rate);
    }
    /**
     * Display the specified resource.
     */
    public function show(Rate $rate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rate $rate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rate $rate)
    {
        //
    }
}
