<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Worker;
use App\Models\Client;
use JWTAuth;

use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected $user;
    public function __construct(Request $request)
    {
        $token = $request->header('Authorization');
        if ($token != '')
            $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request)
    {

        $category = Category::create(
            [
                "name" => $request->name
            ]
        );

        return response($category);
    }

}
