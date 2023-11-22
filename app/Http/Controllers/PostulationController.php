<?php

namespace App\Http\Controllers;

use App\Models\Postulation;
use App\Models\Rate;
use App\Models\Service;
use App\Models\voucher;
use App\Models\Worker;
use Illuminate\Http\Request;
use JWTAuth;

class PostulationController extends Controller
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

    public function postulate(Service $service, Request $request)
    {

        if ($service->status != 1) {
            return response("forbidden", 403);
        }
        $worker = $this->user->worker;

        $existingPostulation = Postulation::where('worker_id', $worker->id)
            ->where('service_id', $service->id)
            ->first();

        if ($existingPostulation) {
            // Ya existe una postulación para este trabajador en este servicio
            return response("forbidden", 403);
        }

        if ($worker != null && $worker->id != $service->client_id) {
            /* $service->Workers()->attach($worker->id); */
            Postulation::create([
                "worker_id" => $worker->id,
                "service_id" => $service->id,
                "price" => $request->price

            ]);
        }
        return response($service, 200);
    }
    public function getApplicants(Service $service)
    {
        // Obtener todos los aplicantes (postulaciones) para el servicio dado, ordenados por price de manera ascendente
        /*  $applicants = Postulation::where('service_id', $service->id)
             ->orderBy('price', 'asc')
             ->get(); */
        $applicants = Postulation::where('service_id', $service->id)
            ->join('users', 'users.id', '=', 'postulations.worker_id')
            ->select('users.id', 'users.name', 'postulations.price')
            ->orderBy('postulations.price', 'asc')
            ->get();

        return response($applicants);
    }

    public function acceptApplicant(Service $service, Request $request)
    {
        $worker = Worker::findOrFail($request->worker_id);

        // Obtener todos los aplicantes (postulaciones) para el servicio dado, ordenados por price de manera ascendente
        $existingPostulation = Postulation::where('worker_id', $worker->id)
            ->where('service_id', $service->id)
            ->first();
        if (!$existingPostulation) {
            // El trabajador no se encuentra en la lista de postulados
            return response("forbidden", 403);
        }


        /* Postulation::where('service_id', $service->id)->delete(); */

        $service->update(['status' => 2, 'worker_id' => $worker->id]);

        voucher::create([
            "transaction_number" => null,
            "price" => 50000,
            "service_id" => $service->id
        ]);
        Rate::create([
            'rate_client'=>0,
            'rate_worker'=>0,
            'comment_client'=>'',
            'comment_worker'=>'',
            'service_id'=> $service->id,
            'worker_id'=> $service->worker_id,
            'client_id'=> $service->client_id
        ]);

        return response($service, 200);
    }

}
