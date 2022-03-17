<?php 

namespace App\Helper;

class Helper
{
    
    public function __contruct(){

    }

    public function parseData(string $data): \DateTime{        
        $data = str_replace('/', '-', $data);
        $data = date('Y-m-d', strtotime($data));                
        return new \DateTime($data);
    }

    public function somenteNumeros(string $string): int{
        return preg_replace('/\D/', '', $string);
    }

}


?>