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



                


    }

    public function show(){

        
                return "<h1> Single product info </h1>";


    }



    public function addProduct() {



            
    


    
        $name  = $_POST['name'];

        $product_id = $_POST['product_id'];

        $brand = $_POST['brand'];

        $slug = $_POST['slug'];


        $category = $_POST['category'];

        $image_path=null;
        
        error_log('name is '.$name);

        error_log('product id is '.$product_id);

        error_log('barnd is '.$brand);

        error_log('slug is '.$slug);

        error_log('category id is '.$category);


        






        if(isset($_FILES['product_image']['name'])  && !empty($_FILES['product_image']['name'])){

        
        
               $upload_directory  = __DIR__.'/../../public/uploads/'; 
                    
             $file_name = time(). '_' . $_FILES['product_image'] ['name'];

                $file_with_path = $upload_directory . $file_name;

     try{

                if(move_uploaded_file($_FILES['product_image']['tmp_name'],$file_with_path )) {


                $image_path = '/uploads/'.$file_name;



            }else {

            error_log("IMAGE UPLOAD LOGIC FAILED");
            $this->error_response('uploading image failed !!!',500);

    }

}catch(\Exception $e){


        $this->error_response($e->getMessage(),500);


}


try {


                
    $add_product_query = " INSERT INTO products(name,product_id,brand,slug,category,image_url) VALUES (?,?,?,?,?,?) ";
    
    $this->db->query($add_product_query,[$name,$product_id,$brand,$slug,$category,$image_path]);
 
    $this->return_json(['product_id'=>$product_id],201,'product addition success');






    
} catch (\Exception $e) {


$this->error_response($e->getMessage(),500);


}








}else{


$this->error_response("image url is not set",500);



}

}


}
