<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DisciplinaRepository;
use App\Entity\Disciplina;
use App\Service\DisciplinaService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class DisciplinaController extends AbstractController
{
    
    private $disciplinaRepository;

    private $entity;

    private $doctrine;

    private $serializer;

    private $disciplinaService;

    private $disciplina;

    public function __construct(DisciplinaService $disciplinaService, SerializerInterface $serializer, DisciplinaRepository $disciplinaRepository, ManagerRegistry $doctrine
    ) {
        $this->disciplinaRepository = $disciplinaRepository;
        $this->entity = $doctrine->getManager();
        $this->doctrine = $doctrine;
        $this->serializer = $serializer;
        $this->disciplina = new Disciplina();
        $this->disciplinaService = $disciplinaService;
    }

    /**
     * @Route("/disciplinas", name="listar-disciplinas",  methods={"GET"})
     */
    public function listar(): Response
    {      
        try {
            $disciplinas = $this->disciplinaRepository->buscarDisciplinas();
            if(!$disciplinas)
                return $this->json("Nenhuma disciplina encontrada!", 200);   
            $json = $this->serializer->serialize($disciplinas, 'json',
                ['groups' => ['mostrar_disciplina']]
            );
            return $this->json(json_decode($json), 200);        
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
    }

    /**
     * @Route("/disciplinas/{id}", name="mostrar-disciplina",  methods={"GET"})
     */
    public function mostrar(int $id): Response
    {      
        try {
            $disciplina = $this->disciplinaRepository->buscarDisciplina($id);
            if(!$disciplina)
                return $this->json('Disciplina não encontrada!', 200); 

            $json = $this->serializer->serialize($disciplina, 'json',
                ['groups' => ['mostrar_disciplina']]
            );
            return $this->json(json_decode($json), 200);                
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
        
    }

    /**
     * @Route("/disciplinas", name="criar-disciplinas", methods={"POST"})
     */
    public function criar(Request $request):Response {
        
        try {
            if($content = $request->getContent()){
                $dados = json_decode($content);                
                $disciplina = $this->disciplinaService->salvar($dados);
                return $this->json($disciplina->getId(), 200);            
            }
            return $this->json('Ocorreu um erro ao cadastrar o disciplina!', 200);       
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);       
        }
        
    }

    /**
     * @Route("/disciplinas", name="alterar-disciplinas", methods={"PUT"})
     */
    public function alterar(Request $request):Response 
    {                 
        try {
            if($content = $request->getContent()){
                $dados = json_decode($content);                  
                $disciplinas = $this->disciplinaService->atualizar($dados);                
                return $this->json($disciplinas, 200);
            } 
            return $this->json('Disciplinas não encontrado!', 200);      
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);
        }       
        
    }

    /**
     * @Route("/disciplinas/{id}", name="excluir-disciplinas", methods={"DELETE"})
    */
    public function excluir(int $id):Response 
    {
        try {            
            $disciplina = $this->disciplinaService->excluir($id);                  
            return $this->json($disciplina, 200);  
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
        
    }

}
