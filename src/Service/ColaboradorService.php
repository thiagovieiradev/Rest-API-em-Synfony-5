<?php

namespace App\Service;

use App\Entity\Colaborador;
use App\Entity\Competencia;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ColaboradorRepository;
use App\Repository\CompetenciaRepository;
use App\Repository\CategoriaRepository;
use App\Repository\SenioridadeRepository;
use App\Helper\Helper;
use Exception;
use App\Service\UploadService;

class ColaboradorService
{
    
    private $getManager;

    private $doctrine;

    private $colaboradorRepository;

    private $competenciaRepository;

    private $senioridadeRepository;

    private $helper;

    private $uploadService;

    public function __construct(Helper $helper, ManagerRegistry $doctrine, CategoriaRepository $categoriaRepository, ColaboradorRepository $colaboradorRepository, CompetenciaRepository $competenciaRepository, SenioridadeRepository $senioridadeRepository, UploadService $uploadService)
    {        
        $this->getManager = $doctrine->getManager();
        $this->colaborador = new Colaborador();
        $this->competencia = new Competencia();
        $this->doctrine = $doctrine;
        $this->colaboradorRepository = $colaboradorRepository;
        $this->categoriaRepository = $categoriaRepository;
        $this->competenciaRepository = $competenciaRepository;
        $this->senioridadeRepository = $senioridadeRepository;
        $this->helper = $helper;
        $this->uploadService = $uploadService;
    }

    public function salvar($request): Colaborador
    {                       
        $dados = $request->request;
        $competencias_id = $dados->get('competencias');
        if(count($competencias_id) == 0)
            throw new Exception("Error Processing Request", 1);
            
        $competencias = $this->competenciaRepository->buscarCompetenciasArray($competencias_id); 
        $this->colaborador->addCompetencias($competencias);

        //Senioridade
        $senioridade_id = $dados->get('senioridade');
        $senioridade = $this->senioridadeRepository->buscarSenioridade($senioridade_id);  
        if(!$senioridade)
            throw new Exception("A senioridade selecionada não é válida!", 1);
        $this->colaborador->setSenioridade($senioridade);     

        $this->colaborador->setNome($dados->get('nome'));            
        $this->colaborador->setSobrenome($dados->get('sobrenome'));            
        $this->colaborador->setEmail($dados->get('email')); 

        //CPF
        $cpf = $this->helper->somenteNumeros($dados->get('cpf'));  
        $this->colaborador->setCpf($cpf);                   
        // Data de nascimento
        $data_nascimento = $this->helper->parseData($dados->get('data_nascimento'));        
        $this->colaborador->setDataNascimento($data_nascimento);
        // Data de admissão                
        $data_admissao = $this->helper->parseData($dados->get('data_admissao'));        
        $this->colaborador->setDataAdmissao($data_admissao);

        $this->getManager->persist($this->colaborador);        
        $this->getManager->flush(); 

        foreach($competencias as $c){
            $competencia = $this->competenciaRepository->buscarCompetencia($c->getId());
            if(!$competencia)
                throw new Exception("Uma das competências selecionadas não são válidas!", 1);

            $competencia->addColaborador($this->colaborador);                        
        }
        $this->getManager->flush(); 
            
        // Upload da foto
        $foto = $request->files->get('foto');
        $upload = $this->uploadService->upload($foto);
        $colaborador = $this->colaboradorRepository->buscarColaborador($this->colaborador->getId());
        $colaborador->setFoto($upload);
        $this->getManager->persist($colaborador); 
        $this->getManager->flush();  

        return $this->colaborador;
    }

    public function atualizar($request): Colaborador
    {                  
        $dados = $request->request;
        $this->colaborador = $this->colaboradorRepository->buscarColaborador($dados->get('id'));

        $competencias_id = $dados->get('competencias');        
        if(count($competencias_id) == 0)
            throw new Exception("Error Processing Request", 1);
            
        $competencias = $this->competenciaRepository->buscarCompetenciasArray($competencias_id); 
        $this->colaborador->addCompetencias($competencias);

        //Senioridade
        $senioridade_id = $dados->get('senioridade');
        $senioridade = $this->senioridadeRepository->buscarSenioridade($senioridade_id);  
        if(!$senioridade)
            throw new Exception("A senioridade selecionada não é válida!", 1);
        $this->colaborador->setSenioridade($senioridade);     

        $this->colaborador->setNome($dados->get('nome'));            
        $this->colaborador->setSobrenome($dados->get('sobrenome'));            
        $this->colaborador->setEmail($dados->get('email')); 

        //CPF
        $cpf = $this->helper->somenteNumeros($dados->get('cpf'));  
        $this->colaborador->setCpf($cpf);                   
        // Data de nascimento
        $data_nascimento = $this->helper->parseData($dados->get('data_nascimento'));        
        $this->colaborador->setDataNascimento($data_nascimento);
        // Data de admissão                
        $data_admissao = $this->helper->parseData($dados->get('data_admissao'));        
        $this->colaborador->setDataAdmissao($data_admissao);

        //$this->getManager->persist($this->colaborador);        
        $this->getManager->flush(); 

        foreach($competencias as $c){
            $competencia = $this->competenciaRepository->buscarCompetencia($c->getId());
            if(!$competencia)
                throw new Exception("Uma das competências selecionadas não são válidas!", 1);

            $competencia->addColaborador($this->colaborador);                        
        }
        $this->getManager->flush(); 
            
        // Upload da foto
        $foto = $request->files->get('foto');
        $upload = $this->uploadService->upload($foto);
        $colaborador = $this->colaboradorRepository->buscarColaborador($this->colaborador->getId());
        $colaborador->setFoto($upload);
        //$this->getManager->persist($colaborador); 
        $this->getManager->flush();  

        return $this->colaborador;
    }

    public function excluir($id): Colaborador
    {       
        $colaborador = $this->colaboradorRepository->buscarColaborador($id);
        if (!$colaborador) {
            throw new Exception("Colaborador não encontrada!", 1);
        }           
        $colaborador->setDeletedAt(new \DateTime());
        $this->getManager->flush(); 
        return $colaborador;
    }
}
?>