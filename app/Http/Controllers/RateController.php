<?php

namespace App\Http\Controllers;
use JWTAuth;
use App\Models\service;
use App\Models\Rate;



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
            $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function Rate(Service $service)
    {
        $rate = Rate::where("service_id",$service->id)->get()[0];

        if($rate == null)
        {
            return response("error rate",404);
        }


        return response($rate);
    }

    public function store(Service $service)
    {
        $client = $this->user->client;

        if ($client == null || $client->id != $service->Client->id) {
            return response("Invalid client", 400);
        }

        $rate = Rate::create([
            'rate_client'=>null,
            'rate_worker'=>null,
            'comment_client'=>null,
            'comment_worker'=>null,
            'service_id'=> $service->id,
            'worker_id' => $service->workers[0]->id,
            'client_id'=> $client->id,

        ]);

        return response($rate);
    }
    public function rateWorker(Request $request,Service $service)
    {
        $client = $this->user->client;

        if ($client == null || $client->id != $service->Client->id) {
            return response("Invalid client", 400);
        }

        $rate = Rate::where("service_id",$service->id)->get()[0];

        if($rate == null)
        {
            return response("error rate",404);
        }


        $rate->rate_client = $request->rate;
        $rate->comment_client = $request->comment;
        $rate->save();
        return response($rate);
    }
    public function rateCLient(Request $request,Service $service)
    {
        $worker = $this->user->worker;

        if ($worker == null || $worker->id != $service->workers[0]->id) {
            return response("Invalid worker", 400);
        }

        $rate = Rate::where("service_id",$service->id)->get()[0];

        if($rate == null)
        {
            return response("error rate",404);
        }


        $rate->rate_client = $request->rate;
        $rate->comment_client = $request->comment;
        $rate->save();
        return response($rate);
    }


}
