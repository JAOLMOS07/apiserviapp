<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Worker;
use App\Models\Client;
use JWTAuth;


class ServiceController extends Controller
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


    public function indexClient()
    {

        $client = $this->user->Client;

        return response($client);
    }
    public function indexWorker()
    {

    }


    public function getOffers(Request $request)
    {
        $worker = $this->user->Worker;
        $workerId = $worker->id;
        $workerUserId = $worker->user_id;

        $services = Service::whereHas('categories', function ($query) use ($workerId) {
            $query->whereIn('categories.id', function ($subQuery) use ($workerId) {
                $subQuery->select('categories.id')
                    ->from('categories')
                    ->join('category_worker', 'categories.id', '=', 'category_worker.category_id')
                    ->where('category_worker.worker_id', $workerId);
            });
        })
            ->where('active', true)
            ->where('client_id', '!=', $workerId)
            ->get();

        return response($services);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Define las reglas de validación para cada campo
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'date' => 'required|date',

        ];

        // Valida el request según las reglas definidas
        $validator = Validator::make($request->all(), $rules);

        // Verifica si la validación falla
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $service = null;
        $client = $this->user->Client;

        if ($client != null) {
            $service = Service::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'Date' => $request->date,
                'client_id' => $client->id,
                'calification' => 5,
                'active' => true
            ]);
        }

        $service->Categories()->attach($request->category);
        return response($service);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return response($service);
    }
    public function postulate(Service $service)
    {
        if ($service->active == false) {
            return response("forbidden", 403);
        }
        $worker = $this->user->worker;

        foreach ($service->Workers as $workerItem) {
            // Aquí puedes aplicar tu condición a cada $worker
            if ($workerItem->id == $worker->id) {
                return response("forbidden", 403);
            }
        }
        if ($worker != null && $worker->id != $service->client_id) {
            $service->Workers()->attach($worker->id);
        }
        return response($service->Workers, 200);
    }

    public function aplicants(Service $service)
    {
        if ($service->Client->id != $this->user->id) {
            return response("forbidden", 403);
        }
        return response($service->Workers, 200);
    }
    public function acceptAplicant(Request $request, Service $service)
    {

        if ($service->active == false) {
            return response("forbidden", 403);
        }
        //cambiamos el status
        $service->active = false;

        //quitamos id repetidos
        $workersUniques = array_unique($request->worker);

        //quitamos el id del usuario por si acaso
        $workers = array_diff($workersUniques, [$this->user->id]);

        //verificamso que existan id's
        if (count($workers) === 0) {
            return response("Debe proporcionar al menos un trabajador.", 400);
        }

        foreach ($workers as $workerid) {
            $worker = Worker::findOrFail($workerid);
        }

        $service->Workers()->sync( $workers );
        $service->save();

        return response($service->Workers, 200);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }
}
