<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StatusRepository;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\StatusService;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Status;
use Doctrine\Persistence\ManagerRegistry;

class StatusController extends AbstractController
{
    private $statusRepository;

    private $entity;

    private $doctrine;

    private $serializer;

    private $statusService;

    private $status;

    public function __construct(StatusService $statusService, SerializerInterface $serializer, StatusRepository $statusRepository, ManagerRegistry $doctrine
    ) {
        $this->statusRepository = $statusRepository;
        $this->entity = $doctrine->getManager();
        $this->doctrine = $doctrine;
        $this->serializer = $serializer;
        $this->status = new Status();
        $this->statusService = $statusService;
    }

    /**
     * @Route("/status", name="app_categoria",  methods={"GET"})
     */
    public function listar(): Response
    {      
        try {
            $status = $this->statusRepository->buscarStatuss();
            if(!$status)
                return $this->json("Nenhum status encontrada!", 200);   
            $json = $this->serializer->serialize($status, 'json',
                ['groups' => ['mostrar_status']]
            );
            return $this->json(json_decode($json), 200);        
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
    }

    /**
     * @Route("/status/{id}", name="listar-status",  methods={"GET"})
     */
    public function mostrar(int $id): Response
    {      
        try {
            $status = $this->statusRepository->buscarStatus($id);
            if(!$status)
                return $this->json('Status não encontrado!', 200); 

            $json = $this->serializer->serialize($status, 'json',
                ['groups' => ['mostrar_status']]
            );
            return $this->json(json_decode($json), 200);                
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
        
    }

    /**
     * @Route("/status", name="criar-status", methods={"POST"})
     */
    public function criar(Request $request):Response {
        
        try {
            if($content = $request->getContent()){
                $dados = json_decode($content);                
                $status = $this->statusService->salvar($dados);
                return $this->json($status->getId(), 200);            
            }
            return $this->json('Ocorreu um erro ao cadastrar o status!', 200);       
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);       
        }
        
    }

    /**
     * @Route("/status", name="alterar-status", methods={"PUT"})
     */
    public function alterar(Request $request):Response 
    {                 
        try {
            if($content = $request->getContent()){
                $dados = json_decode($content);                  
                $status = $this->statusService->atualizar($dados);                
                return $this->json($status, 200);
            } 
            return $this->json('Status não encontrado!', 200);      
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);
        }       
        
    }

    /**
     * @Route("/status/{id}", name="excluir-status", methods={"DELETE"})
    */
    public function excluir(int $id):Response 
    {
        try {            
            $status = $this->statusService->excluir($id);                  
            return $this->json($status, 200);  
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
        
    }
}
