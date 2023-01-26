<?php

namespace App\Http\Controllers;

use App\Models\ObatApotek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostPublicController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index(Request $request){
        $posts = ObatApotek::OrderBy("id", "DESC")->paginate(10)->toArray();

        $response = [
            "total_count" => $posts["total"],
            "limit" => $posts["per_page"],
            "pagination" => [
                "next_page" => $posts["next_page_url"],
                "current_page" => $posts["current_page"]
            ],
            "data" => $posts["data"],
        ];

        return response()->json($response, 200);
    }

    public function show($id, Request $request){
        $posts = ObatApotek::find($id);

        if(!$posts) {
            abort(404);
        }
        
        return response()->json($posts, 200);
    }

   
}
