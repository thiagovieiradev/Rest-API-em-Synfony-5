<?php 
namespace App\Service;

use App\Entity\Competencia;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\CategoriaRepository;
use App\Repository\CompetenciaRepository;
use Exception;

class CompetenciaService
{
    private $categoria;
    
    private $getManager;

    private $doctrine;
    
    private $competenciaRepository;

    private $categoriaRepository;

    public function __construct(ManagerRegistry $doctrine, CompetenciaRepository $competenciaRepository, CategoriaRepository $categoriaRepository)
    {        
        $this->getManager = $doctrine->getManager();
        $this->competencia = new Competencia();
        $this->doctrine = $doctrine;
        $this->competenciaRepository = $competenciaRepository;
        $this->categoriaRepository = $categoriaRepository;
    }

    public function salvar($dados): Competencia
    {                     
        if(isset($dados->nome))
            $this->competencia->setNome($dados->nome);

        if(isset($dados->descricao))
            $this->competencia->setNome($dados->descricao);
        
        if(isset($dados->categoria)){
            $categoria = $this->categoriaRepository->buscarCategoria($dados->categoria);
            if (!$categoria)
                return $this->competencia;
               
            $this->competencia->setCategoria($categoria);            
        }else               
            return $this->competencia;
        
        $this->getManager->persist($this->competencia);
        $this->getManager->flush();
        return $this->competencia;   
    }

    public function atualizar($dados): Competencia
    {                  
        $id = $dados->id;
        $competencia = $this->competenciaRepository->buscarCompetencia($id);   
        
        if (!$competencia)
            throw new Exception("Competência não encontrada!", 1);                    
        
        if(isset($dados->nome))
            $competencia->setNome($dados->nome);

        if(isset($dados->descricao))
            $competencia->setDescricao($dados->descricao);

        $categoria = $this->categoriaRepository->buscarCategoria($dados->categoria);
        if (!$categoria)
            throw new Exception("Categoria não encontrada para atualização!", 1);  

        $competencia->setCategoria($categoria);
        $this->getManager->flush(); 
        return $competencia;
    }

    public function excluir($id): Competencia
    {                         
        $competencia = $this->competenciaRepository->buscarCompetencia($id);
        if (!$competencia) {
            throw new Exception("Competência não encontrada!", 1); 
        }            
        $competencia->setDeletedAt(new \DateTime());
        $this->getManager->flush();                        
        return $competencia;
    }

}

?>