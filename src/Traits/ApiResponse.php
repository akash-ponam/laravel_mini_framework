<?php 
            
namespace App\Traits;

trait ApiResponse {


protected function return_json(array $data,int $code=200,string $message) {


http_response_code($code);

header('Content-Type: application/json');

$response = ['status' => ($code >=200 && $code <300)?'success':'failed' ,'message' =>$message ,'data' =>$data];

echo json_encode($response);

exit;

}


protected function error_response(string $message,$code=400){

    $this->return_json([],$code,$message);

}

}

?>
