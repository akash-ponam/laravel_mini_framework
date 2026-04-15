<?php 


namespace App\Container;


class LaravelContainer{

    protected $enteries = [];

    protected $shared_instance = [];


    // FRESH INSTANCES 
    public function bind($key, callable $instance_creation_logic){
            
        $this->enteries[$key] =  [ 
            
        'instance_creation_logic' => $instance_creation_logic,
        'singleton' =>false

        ];


        }

    //MAKING SINGLETON INSTANCES
    public function singleton($key,callable $instance_creation_logic){

           $this->enteries[$key] =  [ 
            
        'instance_creation_logic' => $instance_creation_logic,
        'singleton' =>true

        ];

}




public function get($key){

    // check if exist in shared_instance return it 
    

        if(isset($this->shared_instance[$key])){

                
            return $this->shared_instance[$key];

        }

    
    // if we dont have enteries for given key 
    
        if(!isset($this->enteries[$key])){
            

            if(class_exists($key)){

                        
                return new $key();
            }


            throw new \Exception(" no new class {$key} found ");
        }


        // we have entry for given key 
        //

        $method = $this->enteries[$key]['instance_creation_logic'];
        
        $instance = $method($this);

    
            // if we made singleton instance then we save it for later usee 
       if($this->enteries[$key]['singleton']){
        
    
                $this->shared_instance[$key] = $instance;
                

        }

        return $instance;







}


 public function has($key) {

    return isset($this->enteries[$key]) || isset($this->shared_instance[$key]);
}






    
    
   
      



    
}

