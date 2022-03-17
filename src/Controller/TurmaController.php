<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TurmaRepository;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\TurmaService;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Turma;
use Doctrine\Persistence\ManagerRegistry;

class TurmaController extends AbstractController
{
    
    private $turmaRepository;

    private $entity;

    private $doctrine;

    private $serializer;

    private $turmaService;

    private $turma;

    public function __construct(TurmaService $turmaService, SerializerInterface $serializer, TurmaRepository $turmaRepository, ManagerRegistry $doctrine
    ) {
        $this->turmaRepository = $turmaRepository;
        $this->entity = $doctrine->getManager();
        $this->doctrine = $doctrine;
        $this->serializer = $serializer;
        $this->turma = new Turma();
        $this->turmaService = $turmaService;
    }

    /**
     * @Route("/turmas", name="listar-turmas",  methods={"GET"})
     */
    public function listar(): Response
    {      
        try {
            $turma = $this->turmaRepository->buscarTurmas();
            if(!$turma)
                return $this->json("Nenhuma turma encontrada!", 200);   
            $json = $this->serializer->serialize($turma, 'json',
                ['groups' => ['mostrar_turma']]
            );
            return $this->json(json_decode($json), 200);        
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
    }

    /**
     * @Route("/turmas/{id}", name="listar-turma",  methods={"GET"})
     */
    public function mostrar(int $id): Response
    {      
        try {
            $turma = $this->turmaRepository->buscarTurma($id);
            if(!$turma)
                return $this->json('Turma não encontrada!', 200); 

            $json = $this->serializer->serialize($turma, 'json',
                ['groups' => ['mostrar_turma']]
            );
            return $this->json(json_decode($json), 200);                
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
        
    }

    /**
     * @Route("/turmas", name="criar-turma", methods={"POST"})
     */
    public function criar(Request $request):Response {
        
        try {
            if($content = $request->getContent()){
                $dados = json_decode($content);                
                $turma = $this->turmaService->salvar($dados);
                return $this->json($turma->getId(), 200);            
            }
            return $this->json('Ocorreu um erro ao cadastrar a turma!', 200);       
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);       
        }
        
    }

    /**
     * @Route("/turmas", name="alterar-turma", methods={"PUT"})
     */
    public function alterar(Request $request):Response 
    {                 
        try {
            if($content = $request->getContent()){
                $dados = json_decode($content);                  
                $turma = $this->turmaService->atualizar($dados);                
                return $this->json($turma->getNome(), 200);
            } 
            return $this->json('Turma não encontrado!', 200);      
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);
        }       
        
    }

    /**
     * @Route("/turmas/{id}", name="excluir-turma", methods={"DELETE"})
    */
    public function excluir(int $id):Response 
    {
        try {            
            $turma = $this->turmaService->excluir($id);                  
            return $this->json($turma->getId(), 200);  
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
        
    }

}
