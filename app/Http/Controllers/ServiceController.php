<?php

namespace App\Http\Controllers;

use App\Events\EventService;
use App\Models\Rate;
use App\Models\Service;
use App\Models\voucher;
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
            $this->user = JWTAuth::parseToken()->authenticate();
    }


    public function indexClient()
    {

        $client = $this->user->Client;
        $services = Service::where('client_id', $client->id)->where('status', 1)->get();

        return response($services);
    }
    public function indexClientAll()
    {

        $client = $this->user->Client;
        $services = Service::where('client_id', $client->id)->where('status', '>', 1)->get();

        return response($services);
    }
    public function indexWorker()
    {
        $worker = $this->user->Worker;
        $services = Service::where('worker_id', $worker->id)->where('status', '>', 1)->get();

        return response($services);

    }


    /*     public function getOffers2(Request $request)
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
                ->where('status', 1)
                ->where('client_id', '!=', $workerId)
                ->get();

            return response($services);
        } */
    /*     public function getOffers(Request $request)
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
                ->where('status', 1)
                ->where('client_id', '!=', $workerId)
                ->whereDoesntHave('workers', function ($subQuery) use ($workerId) {
                    $subQuery->where('worker_id', $workerId);
                })
                ->get();

            return response($services);
        } */

    public function getOffers(Request $request)
    {
        $worker = $this->user->Worker;
        $workerId = $worker->id;

        $services = Service::whereHas('categories', function ($query) use ($workerId) {
            $query->whereIn('categories.id', function ($subQuery) use ($workerId) {
                $subQuery->select('categories.id')
                    ->from('categories')
                    ->join('category_worker', 'categories.id', '=', 'category_worker.category_id')
                    ->where('category_worker.worker_id', $workerId);
            });
        })
            ->where('status', 1)
            ->where('client_id', '!=', $workerId)
            ->whereNotIn('id', function ($subQuery) use ($workerId) {
                $subQuery->select('service_id')
                    ->from('postulations')
                    ->where('worker_id', $workerId);
            })
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
            'price_min' => 'required|numeric',
            'price_max' => 'required|numeric',
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
                'price_min' => $request->price_min,
                'price_max' => $request->price_max,
                'Date' => $request->date,
                'client_id' => $client->id,
            ]);
        }

        $service->Categories()->attach($request->category);

        /*  event(new EventService($service)); */

        return response($service);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return response($service);
    }
    public function getRates(Request $request)
    {
        $userOnline = $this->user;

        if($request->type === 1){
            Rate::where('client_id',$userOnline->id)->get();
        }
        return response($request);
    }
    public function getRate(Service $service)
    {
        $rate= Rate::where('service_id',$service->id)->first();
        return response($rate);
    }
    public function getVouchers()
    {
        if($this->user->role_id === 3){
            $vouchers = Voucher::where('confirmed',false)->get();
            return response($vouchers);
        }
        return response()->json([
            'message' => 'no tienes esos permisos',
        ], 401);
    }
    public function getUserService(Service $service)
    {

        $userOnline = $this->user;
        if ($service->client_id === $userOnline->id) {
            $user = User::findOrFail($service->worker_id);
            return response($user);
        } else {
            $user = User::findOrFail($service->client_id);
            return response($user);

        }
    }
    public function rateService(Request $request,Rate $rate)
    {
        $service = Service::findOrFail($rate->service_id);

        $userOnline = $this->user;

        if ($service->client_id === $userOnline->id) {
            if($request->rate > 0){
                $rate->update([
                    "rate_client"=>$request->rate,
                    "comment_client"=>$request->comment
                ]);
            }


        } else {
            if($request->rate > 0){
                $rate->update([
                    "rate_worker"=>$request->rate,
                    "comment_worker"=>$request->comment
                ]);
            }


        }
        if($rate->rate_client > 0 && $rate->rate_worker > 0){
            $service->update([
                "status"=>4
            ]);
        }
        return response($rate);
    }
    public function getVoucher(Service $service)
    {
        $voucher = Voucher::where('service_id', $service->id)->first();

        return response($voucher);
    }
    /*     public function postulate(Service $service)
        {
            if ($service->status != 1) {
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
        } */

    /* public function aplicants(Service $service)
    {
        if ($service->Client->id != $this->user->id) {
            return response("forbidden", 403);
        }
        return response($service->Workers, 200);
    } */
    /*  public function acceptAplicant(Request $request, Service $service)
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

         $service->Workers()->sync($workers);
         $service->save();

         return response($service->Workers, 200);
     }
  */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //
    }
    public function toVerifyVoucher(Request $request, Voucher $voucher)
    {
        $voucher->update([
            "transaction_number" => $request->transaction_number
        ]);

        return response($voucher);
    }
    public function ValidateVoucher(Voucher $voucher)
    {

        $voucher->update([
            "confirmed" => true
        ]);

        $service = Service::findOrFail($voucher->service_id);
        $service->update([
            "status" => 3,
            "confirmed" => true
        ]);
        return response($service);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }
}
