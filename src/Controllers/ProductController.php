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

            
                
                $sort_choice = $_GET['sort'] ??'default';

                $page_number = $_GET['page']??1;

                $per_page = $_GET['per_page']??10;

                $offset = ($page_number -1) * $per_page;

                
    


                $sort_options = [

                'price_asc' => 'price ASC ',
            
                'price_desc' => 'price DESC',

                'newest '  => 'created_at DESC',

                'default' => 'created_at ASC' 


                ];


                $selected_sort_option = $sort_options[$sort_choice]?? $sort_options['default'];

                
                $total_records  = $this->db->query("SELECT COUNT(*) FROM products ")->fetchColumn();          

                $products =  $this->db->query("SELECT * FROM products ORDER BY $selected_sort_option LIMIT $per_page OFFSET $offset")->fetchAll();

                foreach($products as &$product){


                if(isset($product['specs']) && is_string($product['specs']) ) {

            
                    $product['specs']  =  json_decode($product['specs'],true);


            }


                 unset($product);

                
                    



                }


                return $this->return_json(['data'=>$products,'meta' =>[ 'total_records' =>(int)$total_records,'current_page'=>$page_number,'per_page'=>$per_page,'total_page' =>ceil($total_records /$per_page )  ]],200,'aaya re aaaya rre dekho kaun');



                


    }




    public function search() {

        $user_search_input = $_GET['search'] ?? '';

        $search_query = "SELECT product_id,name from products WHERE name LIKE ? ";

        $formatted_search_input = "%". $user_search_input ."%";
    
        try {



        $result = $this->db->query($search_query,[$formatted_search_input])->fetchAll();

        if($result){

        
            $this->return_json($result,201,"search query success!");

        }else {


            $this->error_response("not any related data found",404);


        }


        } catch (\Exception $e) {

            
            
            
            $this->error_response($e->getMessage(),500);



            
        }


}






    public function show($id){

        $query = null;

        $result = null;


        try {

        $query = "SELECT * FROM products where product_id = ?";

        $product = $this->db->query($query,[$id])->fetch();


            if(!$product){
            
                
            return $this->error_response("error while retrieving product ",500);

    
            }

            if($product && isset($product['specs']) && is_string($product['specs']) ) {


                
                $product['specs'] = json_decode($product['specs'],true);


            }
        
            return $this->return_json(['data'=>$product],200,'retrivel success');
        
            
        } catch (\Exception $e) {

            return $this->error_response($e->getMessage(),404);

            
        }

        
        




    }

  public function addProduct() {


        $specs = $_POST['specs'];
    
        $name  = $_POST['name'];

        $product_id = $_POST['product_id'];

        $brand = $_POST['brand'];

        $slug = $_POST['slug'];


        $category = $_POST['category'];

        $price = $_POST['product_price']??null;

        $image_path=null;
    
        if(isset($_FILES['product_image']['name'])  && !empty($_FILES['product_image']['name'])){

        
        
               $upload_directory  = __DIR__.'/../../public/uploads/'; 
                    
             $file_name = time(). '_' . $_FILES['product_image'] ['name'];

                $file_with_path = $upload_directory . $file_name;

     try{

                if(move_uploaded_file($_FILES['product_image']['tmp_name'],$file_with_path )) {


                    $image_path = '/uploads/'.$file_name;

                    error_clear_last();

                    error_log('image path found '.$image_path);



            }else {

                    error_log("IMAGE UPLOAD LOGIC FAILED");


                    error_log('image path found after upload fail '.$image_path);
            $this->error_response('uploading image failed !!!',500);

    }

}catch(\Exception $e){


        $this->error_response($e->getMessage(),500);


}


try {


                
    $add_product_query = " INSERT INTO products(name,product_id,brand,slug,category,image_url,price,specs) VALUES (?,?,?,?,?,?,?,?) ";
    
    $this->db->query($add_product_query,[$name,$product_id,$brand,$slug,$category,$image_path, $price , $specs ]);
 
                $this->return_json(['product_id'=>$product_id],201,'product addition success');


   
} catch (\Exception $e) {


$this->error_response($e->getMessage(),500);


}

}else{


$this->error_response("image url is not set",500);



}

    }




    function add_to_cart() {


        if(session_status()==PHP_SESSION_NONE) {

            session_start();
        }




        $user_id = $_SESSION['user_id'];

        $is_logged_in = $_SESSION['is_loggedin'];


    $query_add_to_cart = 'INSERT INTO cart_products (cart_id,product_id,quantity) values (?,?,?) ON DUPLICATE KEY UPDATE quantity = values(quantity)';

        $query_total_items_in_cart  = 'SELECT SUM(quantity) as total_items_in_cart FROM cart_products WHERE cart_id = ?';


        if(!$user_id || !$is_logged_in ) {


            $this->error_response('UNAUTHORISED ACCESS',401);

            /* exit; */

        }


       try {$raw_json = file_get_contents('php://input');


        error_log("RAW JSON IS ".$raw_json);


            $decoded_data = json_decode($raw_json,true,512,JSON_THROW_ON_ERROR); 
        
        }catch(\JsonException $e){


        $this->error_response('at line '.$e->getLine(),500);


        }

        if(empty($decoded_data)){

            $this->return_json(['decoded'=>$decoded_data],200,'decode success');
        }



        $product_id = $decoded_data['product_id']??null;

        $quantity = $decoded_data['quantity']??null;
        /**/
        /* if(!$product_id || !$quantity){ */
        /**/
        /*     $this->error_response('NULL  PRODUCT AND quantity',500); */
        /**/
        /**/
        /* } */


         if(!$product_id){


            $this->error_response(" NULL product id",500);


        }


        if(!$quantity){


            $this->error_response(" NULL quantiy",500);


        }







        // check if cart already exist
        

        $existing_cart_query = "SELECT id FROM carts WHERE user_id = ?";

        $cart_existing = $this->db->query($existing_cart_query,[$user_id])->fetch();

        if($cart_existing){

            // CART EXISTS
            
            $cart_id = $cart_existing['id'];


            
            $this->db->query($query_add_to_cart,[$cart_id,$product_id,$quantity]);

            $res =$this->db->query($query_total_items_in_cart,[$cart_id])->fetch();

            $cart_size = $res?(int)$res['total_items_in_cart']:0;
            



            $this->return_json(['product_id'=>$product_id,'cart_length'=>$cart_size,'cart_id'=>$cart_id],201,'product is added to cart');




        }else {

            //CREATE NEW CART 
            
            $query_new_cart = "INSERT INTO carts (user_id) VALUES (?)";

            $this->db->query($query_new_cart,[$user_id]);

            $cart_id = $this->db->get_id_of_last_entry();


            $this->db->query($query_add_to_cart,[$cart_id,$product_id,$quantity]);


            $res =$this->db->query($query_total_items_in_cart,[$cart_id])->fetch();

            $cart_size = $res?(int)$res['total_items_in_cart']:0;

            $new_cart_id =$this->db->get_id_of_last_entry();
            
            $this->return_json(['product_id'=>$product_id,'cart_length'=>$cart_size,'cart_id' =>$new_cart_id ],201,'product is added to cart');




        }

        



    }


    function cart_info(){


        if(session_status()==PHP_SESSION_NONE) {

            session_start();
        }




        $user_id = $_SESSION['user_id'];

        $is_logged_in = $_SESSION['is_loggedin'];

        
        if(!$user_id || $is_logged_in==false){

                
            $this->return_json(['cart_length'=>0],201,'usr is null so cart will be empty');


        }



        $query = "SELECT SUM(quantity) as total,cart_id FROM  user_cart_info WHERE id = ?";

        $count = $this->db->query($query,[$user_id])->fetch();

        $items = $count? (int)$count['total']:0;

        $cart_id = $count? (int)$count['cart_id']:null;
        $this->return_json(['cart_id'=>$cart_id, 'cart_length'=>$items ],201,'carts items are there');



    }




    function cart($id) {



         if(session_status()==PHP_SESSION_NONE) {

            session_start();
        }


            
        $user_id = $_SESSION['user_id'];

        $is_logged_in = $_SESSION['is_loggedin'];

        
        if(!$user_id || $is_logged_in==false){

                
                $this->error_response('page not found ',404);


        }


        $query = "SELECT * FROM cart_data WHERE cart_id = ?";

        $cart_items = $this->db->query($query,[$id])->fetchAll();

        if($cart_items){

            $this->return_json(['cart_items'=>$cart_items],200,'cart fetch success');

        }else {

            $this->error_response('not  valid cart found ',404);


        }






    }


    function remove_from_cart($cart_id,$product_id){


        $query_exist_cart_check = "SELECT *  FROM cart_products WHERE cart_id = ?  AND product_id = ?";

        $total_items = $this->db->query($query_exist_cart_check,[$cart_id,$product_id])->fetch();


        if($total_items){


        $query = "DELETE FROM cart_products WHERE cart_id = ? AND product_id = ?";

        $rows_affected  = $this->db->query($query,[$cart_id,$product_id])->rowCount();

        if($rows_affected>0){


            $this->return_json(['rows_affected'=>$rows_affected],200,'remove from cart sucess');


        }else{

        
            $this->error_response('failed to remove product ',500);



    
        }


        
         





        }else {


            $this->error_response('non existent product ',400);


        }



    }



    function place_order() {


         if(session_status()==PHP_SESSION_NONE) {

            session_start();
        }


            
        $user_id = $_SESSION['user_id'];

        $is_logged_in = $_SESSION['is_loggedin'];

        
        if(!$user_id || $is_logged_in==false){

                
                $this->error_response('page not found ',404);


        }

        /* QUERY 1 :CHECK CART EXISTS  */

        $query_cart_exists = "SELECT id FROM carts WHERE user_id = ?";


        $result =  $this->db->query($query_cart_exists,[$user_id])->fetch();

        $cart_id = $result? (int) $result['id']:null;

        if(!$result || !$cart_id) {

            $this->error_response(`not cart found for user {$cart_id}`,401);

        }

        //cart exist

        /* QUERY-2: FETCH CART ITEMS   */

        $query_get_from_carts = "SELECT cp.product_id,cp.quantity ,p.price 
        
        FROM cart_products cp 

        INNER JOIN products p on cp.product_id = p.product_id 
        
        WHERE cp.cart_id = ?";

        
        $cart_items = $this->db->query($query_get_from_carts,[$cart_id])->fetchAll();

        if(empty($cart_id)){

            $this->error_response('CART  IS EMPTY ORDER COULD NOT PLACED',400);

         }

        if(!$cart_items){

            
                $this->error_response('CART ITEMS NOT FOUND ',400);


        }


       

        




         /* CREATE ORDER AMOUNT */
        
        $order_amount = array_reduce($cart_items,function ($carry, $item) {

            
            return $carry +$item['price'];            

        

        });


        /* CREATE ORDER  */
        

        $time_token  = substr(time(),-6);

        $order_id = $user_id.$time_token;

        $query_create_order = "INSERT INTO orders (order_id,user_id,amount) VALUES (?,?,?)";


        $rows_added = $this->db->query($query_create_order,[$order_id,$user_id,$order_amount])->rowCount();

        
        if($rows_added>0){

            $recent_id_generated = $this->db->get_id_of_last_entry();

            /* WE HAVE PRODUCT ID ,QUANTITY AND PRICE IN CART ITEMS  */


            $order_product_id  = substr(time(),-6);



            $query_place_order = "INSERT INTO order_product (order_product_id,order_id,product_id,quantity_purchased,product_price) 


            VALUES (?,?,?,?,?)
        
            ";
            
            foreach ($cart_items as $cart_item) {
            
            $this->db->query($query_place_order,[$order_product_id,$order_id,$cart_item['product_id'],$cart_item['quantity'],$cart_item['price']]);


            }; 


            // finally remove from cart 

            $query_remove_from_cart = "DELETE FROM carts WHERE user_id = ?";

            $cart_removal= $this->db->query($query_remove_from_cart,[$user_id])->rowCount();


            if($cart_removal>0) {


            $this->return_json([$cart_item],201,'order placed succesfull');
            
            }else{


                    $this->error_response('COULD NOT PLACE ORDER',500);

            

            }



           



        }else {


            

            $this->error_response('could not place order ',500);
            
                

        }
        

        

}



function get_orders(){

         if(session_status()==PHP_SESSION_NONE) {

            session_start();
        }


            
        $user_id = $_SESSION['user_id'];

        $is_logged_in = $_SESSION['is_loggedin'];

        
        if(!$user_id || $is_logged_in==false){

                
                $this->error_response('page not found ',404);


        }

        $query = "SELECT created_at,order_id,amount from orders where user_id = ?";

        $result = $this->db->query($query,[$user_id])->fetchAll();

        if($result){

                
                $this->return_json($result,200,'orders retrivel success ');
            
                
        

        }else {

            
            $this->error_response("NO ORDERS FOUND",400);


        }






}



function order_details($order_id) {


      if(session_status()==PHP_SESSION_NONE) {

            session_start();
        }


            
        $user_id = $_SESSION['user_id'];

        $is_logged_in = $_SESSION['is_loggedin'];

        
        if(!$user_id || $is_logged_in==false){

                
                $this->error_response('page not found ',404);


        }


    $query = "SELECT  created_at, product_id,name,price,quantity_purchased,amount from info_order WHERE order_id = ?";

    $order_info =$this->db->query($query,[$order_id])->fetchAll();

    if($order_info){

        $this->return_json($order_info,200,'success');

    }else{
        $this->error_response("OULD NOT FETCH ORDERS",400);

        }
    

    





}






    
    











}
