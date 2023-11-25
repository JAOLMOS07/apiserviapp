<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Rate;
use App\Models\User;

use Illuminate\Http\Request;
use JWTAuth;

class ClientController extends Controller
{
    /* *
     * Display a listing of the resource.
     */
    protected $user;
    public function __construct(Request $request)
    {
        $token = $request->header('Authorization');
        if($token != '')
            $this->user = JWTAuth::parseToken()->authenticate();
    }


    public function store(Request $request)
    {
        $client = Client::create(
        [
            "id" => $this->user->id,
            "user_id" => $this->user->id
        ]
        );

        return response("client", 200);
    }
    public function getRate(User $user)
    {
        $rates = Rate::where('client_id', $user->id)->where('rate_worker','>',0)->get();

        $cantidadCalificaciones = $rates->count();
        if ($cantidadCalificaciones === 0) {
            return response()->json([
                'calificación' => 0,
                'servicios' => 0
            ]);
        }
        $sumaRates = $rates->sum('rate_worker');
        $promedioRates = $sumaRates / $cantidadCalificaciones;
        return response()->json([
            'calificación' => $promedioRates,
            'servicios' => $cantidadCalificaciones
        ]);

    }

    public function show(Client $client)
    {
        //
    }


    public function update(Request $request, Client $client)
    {
        //
    }


}
