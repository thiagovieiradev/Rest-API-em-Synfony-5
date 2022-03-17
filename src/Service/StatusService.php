<?php 
namespace App\Service;

use App\Entity\Status;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\StatusRepository;
use Exception;

class StatusService
{
    private $status;
    
    private $getManager;

    private $doctrine;

    private $statusRepository;

    public function __construct(ManagerRegistry $doctrine, StatusRepository $statusRepository)
    {        
        $this->getManager = $doctrine->getManager();
        $this->status = new Status();
        $this->doctrine = $doctrine;
        $this->statusRepository = $statusRepository;
    }

    public function salvar($dados): Status
    {                
        $this->status->setNome($dados->nome);                    
        $this->getManager->persist($this->status);
        $this->getManager->flush();
        return $this->status;
    }

    public function atualizar($dados): Status
    {                
        $id = $dados->id;
        $status = $this->statusRepository->buscarStatus($id);
        if (!$status) {
            throw new Exception("Status não encontrada!", 1);            
        }            

        $status->setNome($dados->nome);
        $this->getManager->flush(); 
        return $status;
    }

    public function excluir($id): Status
    {       
        $status = $this->statusRepository->buscarStatus($id);
        if (!$status) {
            throw new Exception("Status não encontrada!", 1);
        }           
        $status->setDeletedAt(new \DateTime());
        $this->getManager->flush(); 
        return $status;
    }

}

?>