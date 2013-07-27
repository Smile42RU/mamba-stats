<?php

// лимит май: 83591
// лимит конец июля: 86354

set_time_limit(0);

$success = 0;

for($i = 0; $i < 86354; $i++){

    $file = __DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $i . '.jpg';

    if(!file_exists($file)){
        $image = @file_get_contents('http://www.corp.mamba.ru/test/widget.phtml?id='.$i);

        if($image){
            file_put_contents($file, $image);
            $success++;
        }
    }
}

var_dump($i);
var_dump($success);