<?php 


class Test {
    


    public function __construct( public string $info="akash aged 32" , public ?string $profile = null ,public int $status = 301)
    {




    }


 

    public function update_profile( $bio ) { $this->profile = $this ;}


    public function getProfile(){


        return $this;
        

    }



}

/* $test = new Test(); */
/**/
/* $data = $test?->getProfile()?->info; */
/**/
/* echo "data is {$data}"; */
/**/
/**/
/* $value = match($test->status) { */
/**/
/* 200,301 =>'good value', */
/**/
/* default =>'unknown value' */
/**/
/* }; */
/**/
/**/
/* echo "matched value is {$value}"; */

$nums = [ 1 ,2 ,3];

/* $res =  array_reduce( $nums , fn($c,$i) => $c*$i  ,1); */
/**/
/**/
/* echo " result is ${res}"; */


$nums[]= 4;

print_r($nums);




?>







