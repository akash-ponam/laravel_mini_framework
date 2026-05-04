<?php 

namespace App\Controllers;

use App\Data\Database;

use App\Traits\ApiResponse;

class UserController {

    use ApiResponse;

    public function __construct( protected Database $db)
    {
    }

    public function getUser(){


            $id = $_GET['id' ] ?? null;

            if(!$id) {

                http_response_code(400);
                return ['error'=>' user id  required'];  

                          
        
            }


            $user = $this->db->query('SELECT email ,profile_url FROM users WHERE id = ? ',[$id])->fetch();


            if(!$user){

                    $this->error_response('invalid user');               

        }   

            
            $this->return_json([$user['email'],$user['profile_url']],201,'here is user info');
    }



    public function register(){

            $email  =  $_POST['email'] ?? null;

            $password  = $_POST ['password'] ??  null; 

            $hashed_password = password_hash($password,PASSWORD_BCRYPT);


            $image_path=null;

        
            if(isset($_FILES['profile_url']['name'])  && !empty($_FILES['profile_url']['name'])){


                error_log('fk is happening ');



               $upload_directory  = __DIR__.'/../../public/uploads/'; 



                $condition_a  = file_exists($upload_directory)?"valid directory true":"valid directory false";

                $condition_b = $_FILES['profile_url']['error'] ===UPLOAD_ERR_OK?"no upload issue but tmp folder":"upload issue";

                $condition_c =  isset($_FILES['profile_url']['tmp_name'])?'temp reference exist':'temp reference oes not exist';


                $condition_d  = file_exists($_FILES['profile_url']['tmp_name'])?'file exists as in tmp ':'file does not exist in temp';


                error_log($condition_a);
                error_log($condition_b);
                error_log($condition_c);
                error_log($condition_d);    


                $file_name = time(). '_' . $_FILES['profile_url'] ['name'] ;

                $file_with_path = $upload_directory . $file_name;

                error_log("PROPER FILE IS ".$file_with_path);




                try{

                if(move_uploaded_file($_FILES['profile_url']['tmp_name'],$file_with_path )) {


                $image_path = '/uploads/'.$file_name;



            }else {

            $this->error_response('uploading image failed !!!',500);

    }

}catch(\Exception $e){


        $this->error_response($e->getMessage(),500);


}

 } 
 
            $register_query = " INSERT INTO users (email ,password, profile_url)  VALUES (?, ? ,?)";


            try {
                
                $this->db->query($register_query,[$email,$hashed_password,$image_path]);

                $this->return_json(['email' => $email, 'profile_url' => $image_path],201,'user was created successfully');

                

             } catch (\Exception $e ) {

                    $this->error_response($e->getMessage(),500);
            }


            

            




    
}


                public function login()  {

                            
                        
                    $data =array();
                    if(session_status() === PHP_SESSION_NONE){
                
                        session_start();


                    }
                    
                     $data['email']  = $_POST['email']??null;

                     $data['password']  = $_POST['password']??null;





               
                    if(!isset($data['email'],$data['password'] )){

                        $this->error_response('all fields required',422);

                    }

                
                    $user = $this->db->query("SELECT * FROM users where email = ?",[ $data['email'] ])->fetch();

                    if($user && password_verify($data['password'],$user['password'])){

                        
                            $_SESSION['user_id']  =  $user ['id'];
                            
                            $_SESSION['is_loggedin']  =  $user ['email'];   

                            $_SESSION['profile_url']  = $user['profile_url'];




                        $this->return_json(['email'=>$user['email'],'profile_url' =>$user['profile_url']],201,'login success');

                }else {

        
                    
                $this->error_response('unathorised access',401);
            
    
            }













}


function logout() {


if(session_status() === PHP_SESSION_NONE){
    
session_start();
    
}

session_unset();
session_destroy();

return $this->return_json([],200,'logout success');





}








}
