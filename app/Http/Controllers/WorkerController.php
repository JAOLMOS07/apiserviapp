<?php

namespace App\Http\Controllers;

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
                "user_id" => $this->user->id,
                "calification" => 5
            ]
        );


        $worker->Categories()->attach($request->category);

        return response($worker);
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
