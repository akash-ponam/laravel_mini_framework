<?php 

namespace App\Controllers;

use App\Data\Database;
use App\Http\Request;

use App\Traits\ApiResponse;

class ProductController {

    use ApiResponse;
    
    public function __construct( protected Database $db)
    {
    
    }
    


    public function index(Request $request){

                $result =  $this->db->query("SELECT * FROM products")->fetchAll();

                return $this->return_json($result,200,'aaya re aaaya rre dekho kaun');

                /* echo json_encode(['data'=>$result,'status' =>404]); */


                


    }

    public function show(){

        
                return "<h1> Single product info </h1>";


    }



}
