<?php 
namespace App\Service;

use App\Entity\Categoria;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\CategoriaRepository;
use App\Repository\CompetenciaRepository;
use Exception;

class CategoriaService
{
    private $categoria;
    
    private $getManager;

    private $doctrine;

    private $categoriaRepository;

    private $competenciaRepository;

    public function __construct(ManagerRegistry $doctrine, CategoriaRepository $categoriaRepository, CompetenciaRepository $competenciaRepository)
    {        
        $this->getManager = $doctrine->getManager();
        $this->categoria = new Categoria();
        $this->doctrine = $doctrine;
        $this->categoriaRepository = $categoriaRepository;
        $this->competenciaRepository = $competenciaRepository;
    }

    public function salvar($dados): Categoria
    {   
        
        try {
            $this->getManager->beginTransaction();
            $this->categoria->setNome($dados->nome);            
            $this->getManager->persist($this->categoria);
            $this->getManager->flush();
            $this->getManager->commit();
            return $this->categoria;
        } catch (\Exception $e) {
            $this->getManager->rollback();
            return $e->getMessage();
        }
    }

    public function atualizar($dados): Categoria
    {                
        $id = $dados->id;
        $categoria = $this->categoriaRepository->buscarCategoria($id);
        if (!$categoria) {
            throw new Exception("Categoria não encontrada!", 1);            
        }            

        $categoria->setNome($dados->nome);
        $this->getManager->flush(); 
        return $categoria;
    }

    public function excluir($id): Categoria
    {       

        $categoria = $this->categoriaRepository->buscarCategoria($id);
        if (!$categoria) {
            throw new Exception("Categoria não encontrada!", 1);
        }
        
        $categoriaDefault = $this->categoriaRepository->buscarCategoria(35);                
        $competencias = $this->competenciaRepository->buscarCompetenciasPorCategoria($id);        
        foreach($competencias as $competencia){            
            $competencia = $this->competenciaRepository->buscarCompetencia($competencia["id"]);        
            $competencia->setCategoria($categoriaDefault);
        }
                           
        $categoria->setDeletedAt(new \DateTime());
        $this->getManager->flush(); 
        return $categoria;
    }

}

?>