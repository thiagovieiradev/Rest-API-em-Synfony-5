<?php 
namespace App\Service;

use App\Entity\Senioridade;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\SenioridadeRepository;
use Exception;

class SenioridadeService
{
    private $senioridade;
    
    private $getManager;

    private $doctrine;

    private $senioridadeRepository;

    public function __construct(ManagerRegistry $doctrine, SenioridadeRepository $senioridadeRepository)
    {        
        $this->getManager = $doctrine->getManager();
        $this->senioridade = new Senioridade();
        $this->doctrine = $doctrine;
        $this->senioridadeRepository = $senioridadeRepository;
    }

    public function salvar($dados): Senioridade
    {                
        $this->senioridade->setNome($dados->nome);            
        $this->getManager->persist($this->senioridade);
        $this->getManager->flush();
        return $this->senioridade;
    }

    public function atualizar($dados): Senioridade
    {                
        $id = $dados->id;
        $senioridade = $this->senioridadeRepository->buscarSenioridade($id);
        if (!$senioridade) {
            throw new Exception("Senioridade não encontrada!", 1);            
        }            

        $this->senioridade->setNome($dados->nome);
        $this->getManager->flush(); 
        return $this->senioridade;
    }

    public function excluir($id): Senioridade
    {       
        $senioridade = $this->senioridadeRepository->buscarSenioridade($id);
        if (!$senioridade) {
            throw new Exception("Senioridade não encontrada!", 1);
        }           
        $senioridade->setDeletedAt(new \DateTime());
        $this->getManager->flush(); 
        return $senioridade;
    }

}

?>