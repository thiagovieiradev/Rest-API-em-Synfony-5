<?php 
namespace App\Service;

use App\Entity\Turma;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\TurmaRepository;
use App\Repository\StatusRepository;
use App\Repository\DisciplinaRepository;
use App\Repository\ColaboradorRepository;
use App\Helper\Helper;
use Exception;

class TurmaService
{
    private $turma;
    
    private $getManager;

    private $doctrine;

    private $helper;

    private $turmaRepository;

    private $statusRepository;

    private $disciplinaRepository;

    private $colaboradorRepository;

    public function __construct(Helper $helper, ManagerRegistry $doctrine, TurmaRepository $turmaRepository, StatusRepository $statusRepository, DisciplinaRepository $disciplinaRepository, ColaboradorRepository $colaboradorRepository)
    {        
        $this->getManager = $doctrine->getManager();
        $this->turma = new Turma();
        $this->doctrine = $doctrine;
        $this->helper = $helper;
        $this->turmaRepository = $turmaRepository;
        $this->statusRepository = $statusRepository;
        $this->disciplinaRepository = $disciplinaRepository;
        $this->colaboradorRepository = $colaboradorRepository;
    }

    public function salvar($dados): Turma
    {                
        
        $this->turma->setNome($dados->nome);            
        $this->turma->setDescricao($dados->descricao);

        $data_inicio = $this->helper->parseData($dados->data_inicio);        
        $this->turma->setDataInicio($data_inicio);

        $data_termino = $this->helper->parseData($dados->data_termino);        
        $this->turma->setDataTermino($data_termino);
    
        $status_id = $dados->status;
        $status = $this->statusRepository->buscarStatus($status_id);  
        if(!$status)
            throw new Exception("Selecione um status válido para continuar!", 1);
        $this->turma->setStatus($status);

        $disciplinas_id = $dados->disciplinas;
        $this->validarAtributo($disciplinas_id, "disciplina", $this->disciplinaRepository, $this->turma);        
        $colaboradores_id = $dados->colaboradores;
        $this->validarAtributo($colaboradores_id, "colaborador", $this->colaboradorRepository, $this->turma);
        
        $this->getManager->persist($this->turma);
        $this->getManager->flush();
        return $this->turma;
    }

    public function atualizar($dados): Turma
    {           
        
        $turma = $this->turmaRepository->buscarTurma($dados->id);
        if (!$turma) {
            throw new Exception("Turma não encontrada!", 1);            
        }
        
        $turma->setNome($dados->nome);            
        $turma->setDescricao($dados->descricao);
        
        $data_inicio = $this->helper->parseData($dados->data_inicio);        
        $turma->setDataInicio($data_inicio);
        $data_termino = $this->helper->parseData($dados->data_termino);        
        $turma->setDataTermino($data_termino);
        
        $status_id = $dados->status;
        $status = $this->statusRepository->buscarStatus($status_id);  
        if(!$status)
            throw new Exception("Selecione um status válido para continuar!", 1);
        $turma->setStatus($status);    

        foreach ($turma->getDisciplina() as $disciplina) {
            $turma->removeDisciplina($disciplina);
        }                
        $disciplinas_id = $dados->disciplinas;
        $this->validarAtributo($disciplinas_id, "disciplina", $this->disciplinaRepository, $this->turma);               
        
        foreach ($turma->getColaborador() as $colaborador) {
            $turma->removeColaborador($colaborador);
        }
        $colaboradores_id = $dados->colaboradores;
        $this->validarAtributo($disciplinas_id, "disciplina", $this->disciplinaRepository, $this->turma);  
        
        $this->getManager->flush();
        return $turma;
    }

    public function excluir($id): Turma
    {       
        $turma = $this->turmaRepository->buscarTurma($id);
        if (!$turma) {
            throw new Exception("Turma não encontrada!", 1);
        }           
        $turma->setDeletedAt(new \DateTime());
        $this->getManager->flush(); 
        return $turma;
    }

    public function validarAtributo($atributo, $nomeCampo, $repository, $contexto){        
        if(count($atributo) == 0)
            throw new Exception("Selecione ao menos um ".$nomeCampo, 1);
        foreach($atributo as $item){
            if(strcmp($nomeCampo, "colaborador") == 0)
                $elemento = $repository->buscarColaborador($item); 
            elseif(strcmp($nomeCampo, "disciplina") == 0)                
                $elemento = $repository->buscarDisciplina($item); 
            if(!$elemento)
                throw new Exception("Selecione ao menos um ".$nomeCampo, 1);
            if(strcmp($nomeCampo, "colaborador") == 0)
                $contexto->addColaborador($elemento);    
            elseif(strcmp($nomeCampo, "disciplina") == 0)                
                $contexto->addDisciplina($elemento);    
        }        

    }

}

?>