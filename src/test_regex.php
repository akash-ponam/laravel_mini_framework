<?php 


$input_url = '/products/{id}' ;



function to_regex($url) {


    $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/','([a-zA-Z0-9_-]+)',$url);

    $full_regex = "#^" . $pattern . "$#";

    return $full_regex;


}


function test_input($input_url) {

$pattern_format =  to_regex($input_url);

echo " INPUT ".$input_url ."\n";

echo " REGEX FORMAT OF INPUT URL " . $pattern_format ."\n";



if(preg_match($pattern_format,'/products/2',$matches)){

echo " GIVEN URL MATCHES THE PATTERN \n ";



echo " BEFORE SHIFT MATCHES ARE \n" ;

print_r($matches);



$item  = array_shift($matches);


echo "  ITEM FROM ARRAY FETCH LOGIC IS ".$item ."\n"  ;


echo " AFTER SHIFT MATCHES ARE \n";


print_r($matches);

}else {


    echo " PATTERN WAS NOT MATCHED WITH GIVEN URL \n";

}




}


test_input($input_url);




    
