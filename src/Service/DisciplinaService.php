<?php 
namespace App\Service;

use App\Entity\Disciplina;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\DisciplinaRepository;
use Exception;

class DisciplinaService
{
    private $disciplina;
    
    private $getManager;

    private $doctrine;

    private $disciplinaRepository;

    public function __construct(ManagerRegistry $doctrine, DisciplinaRepository $disciplinaRepository)
    {        
        $this->getManager = $doctrine->getManager();
        $this->disciplina = new Disciplina();
        $this->doctrine = $doctrine;
        $this->disciplinaRepository = $disciplinaRepository;
    }

    public function salvar($dados): Disciplina
    {                
        $this->disciplina->setNome($dados->nome);            
        $this->getManager->persist($this->disciplina);
        $this->getManager->flush();
        return $this->disciplina;
    }

    public function atualizar($dados): Disciplina
    {                
        $id = $dados->id;
        $disciplina = $this->disciplinaRepository->buscarDisciplina($id);
        if (!$disciplina) {
            throw new Exception("Disciplina não encontrada!", 1);            
        }            

        $disciplina->setNome($dados->nome);
        $this->getManager->flush(); 
        return $disciplina;
    }

    public function excluir($id): Disciplina
    {       
        $disciplina = $this->disciplinaRepository->buscarDisciplina($id);
        if (!$disciplina) {
            throw new Exception("Disciplina não encontrada!", 1);
        }           
        $disciplina->setDeletedAt(new \DateTime());
        $this->getManager->flush(); 
        return $disciplina;
    }

}

?>