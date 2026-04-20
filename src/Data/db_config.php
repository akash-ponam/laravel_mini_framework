
<?php 



return [

    'host' =>'localhost',
    'dbname' =>'dev',
    'user'=>'akash',
    'pass'=>'akash@mysql',
    'options' => [PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC ,PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false  ]

];







?>
