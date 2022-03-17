<?php 
namespace App\Service;

use App\Entity\Categoria;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\CategoriaRepository;
use Exception;

class CategoriaService
{
    private $categoria;
    
    private $getManager;

    private $doctrine;

    private $categoriaRepository;

    public function __construct(ManagerRegistry $doctrine, CategoriaRepository $categoriaRepository)
    {        
        $this->getManager = $doctrine->getManager();
        $this->categoria = new Categoria();
        $this->doctrine = $doctrine;
        $this->categoriaRepository = $categoriaRepository;
    }

    public function salvar($dados): Categoria
    {                
        $this->categoria->setNome($dados->nome);            
        $this->getManager->persist($this->categoria);
        $this->getManager->flush();
        return $this->categoria;
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
        $categoria->setDeletedAt(new \DateTime());
        $this->getManager->flush(); 
        return $categoria;
    }

}

?>