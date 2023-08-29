<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\Contract;
use App\Models\Service;
use Illuminate\Http\Request;

class ContractController extends Controller
{

    protected $user;
    public function __construct(Request $request)
    {
        $token = $request->header('Authorization');
        if ($token != '')
            //En caso de que requiera autentifiación la ruta obtenemos el usuario y lo almacenamos en una variable, nosotros no lo utilizaremos.
            $this->user = JWTAuth::parseToken()->authenticate();
    }


    public function getContract(Service $service)
    {
        $worker = $this->user->worker;
        if ($worker == null || $worker->id != $service->workers[0]->id) {
            return response("Invalid worker", 400);
        }

        $contract = Contract::where('service_id', $service->id)
            ->where('worker_id', $worker->id)
            ->get();

        return response($contract);


    }

    public function store(Request $request, Service $service)
    {
        $client = $this->user->client;

        if ($client == null || $client->id != $service->Client->id) {
            return response("Invalid client", 400);
        }

        $contract = Contract::create([
            'name' => $request->name,
            'date_init' => $request->date_init,
            'hours_worked' => $request->hours_worked,
            'description' => $request->description,
            'date_end' => $request->date_end,
            'service_id' => $service->id,
            'worker_id' => $service->workers[0]->id,
            'signed' => null
        ]);


        return response($contract);
    }

    public function AcceptContract(Request $request,Contract $contract)
    {

        $worker = $this->user->worker;
        if($worker == null || $worker->id != $contract->worker->id || $contract->signed  != null){
            return response("Invalid worker",400);
        }


        $contract->signed = $request->signed;
        $contract->save();

        return response($contract);
    }


}
