<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use JWTAuth;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $user;
    public function __construct(Request $request)
    {
        $token = $request->header('Authorization');
        if ($token != '')
            $this->user = JWTAuth::parseToken()->authenticate();
    }
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $worker = Worker::create(
            [
                "id" => $this->user->id,
                "user_id" => $this->user->id
            ]
        );


        $worker->Categories()->attach($request->category);

        return response("worker", 200);
    }


    public function getRate(User $user)
    {
        $rates = Rate::where('worker_id', $user->id)->where('rate_client','>',0)->get();

        $cantidadCalificaciones = $rates->count();
        if ($cantidadCalificaciones === 0) {
            return response()->json([
                'calificación' => 0,
                'servicios' => 0
            ]);
        }
        $sumaRates = $rates->sum('rate_client');
        $promedioRates = $sumaRates / $cantidadCalificaciones;
        return response()->json([
            'calificación' => $promedioRates,
            'servicios' => $cantidadCalificaciones
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Worker $worker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Worker $worker)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Worker $worker)
    {
        //
    }
}
