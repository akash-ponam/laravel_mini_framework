<?php 

namespace App\Controllers;

use App\Http\Request;

class ProductController {


    public function index(Request $request){

                return " <h1>  you requested {$request->url()} </h1> ";


    }

    public function show(){

        
                return "<h1> Single product info </h1>";


    }



}
