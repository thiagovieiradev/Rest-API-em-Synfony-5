<?php 

namespace App\Helper;

class Helper
{
    
    public function __contruct(){

    }

    public static function parseData(string $data): \DateTime{        
        $data = str_replace('/', '-', $data);
        $data = date('Y-m-d', strtotime($data));                
        return new \DateTime($data);
    }

    public static function somenteNumeros(string $string): int{
        return preg_replace('/\D/', '', $string);
    }

    public function traduzFiltros($match, $campo){
        $filtros = [
            "startsWith" => "LIKE '".$campo."%'",
            "contains" => "LIKE '%".$campo."%'",
            "notContains" => "NOT LIKE '%".$campo."%'",
            "endsWith" => "LIKE '%".$campo."'",
            "equals" => "LIKE '".$campo."'",
            "notEquals" => "NOT LIKE '".$campo."'"
        ];
        return $filtros[$match];
    }
    
    public function resolverBuscar($buscar, $repository, $total = false)
    {

        $buscar = json_decode($buscar, TRUE);

        $objeto = $repository->createQueryBuilder('c')->andWhere('c.deleted_at is NULL');
        if(!$total){     
            $objeto = $objeto->setMaxResults($buscar["rows"]);
            $objeto = $objeto->setFirstResult($buscar["first"]);
        }
        if(isset($buscar["sortField"])){
            $order = $buscar["sortOrder"] == 1 ? "ASC" : "DESC";
            $objeto = $objeto->orderBy("c.".$buscar["sortField"], $order);
        }        
        if(isset($buscar) && isset($buscar["filters"])){
            foreach($buscar["filters"] as $key => $filter)                                
                if($filter["value"] != null){
                    $comparacao = $this->traduzFiltros($filter["matchMode"], $filter["value"]);
                    $objeto = $objeto->andWhere("c.".$key." ".$comparacao);
                }
        }
        $objeto = $objeto->getQuery()->getResult(); 

        return $objeto;
                
    }

}


?>