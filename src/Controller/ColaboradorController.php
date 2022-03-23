<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ColaboradorRepository;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\ColaboradorService;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Colaborador;
use App\Helper\Helper;
use Doctrine\Persistence\ManagerRegistry;

class ColaboradorController extends AbstractController
{
    private $uploadService;
    private $colaboradorRepository;
    private $getManager;
    private $helper;

    public function __construct(Helper $helper, ManagerRegistry $doctrine, ColaboradorService $colaboradorService, ColaboradorRepository $colaboradorRepository, SerializerInterface $serializer)
    {
        $this->colaboradorRepository = $colaboradorRepository;        
        $this->serializer = $serializer;
        $this->colaboradorService = $colaboradorService;
        $this->getManager = $doctrine->getManager();
        $this->helper = $helper;
    }
    
    /**
     * @Route("/colaboradores", name="listar-colaboradores",  methods={"GET"})
     */
    public function listar(Request $request): Response
    {      

        $buscar = $request->query->get('buscar'); 
        $colaboradores = $this->helper->resolverBuscar($buscar, $this->colaboradorRepository);        
        if(!$colaboradores)
            return $this->json("Nenhum colaborador encontrado!", 200);        
        
        $json = $this->serializer->serialize($colaboradores, 'json',
            ['groups' => ['mostrar_colaborador', 'mostrar_senioridades']]
        );
        $resposta = json_decode($json);

        $totalRows = count($this->helper->resolverBuscar($buscar, $this->colaboradorRepository, true));
        return $this->json(
            ['colaboradores' => $resposta, 'totalRecords' => $totalRows], 200
        );
            
    }

    /**
     * @Route("/colaboradores/{id}", name="mostrar-colaboradores",  methods={"GET"})
     */
    public function mostrar(int $id): Response
    {      
        try {
            $colaborador = $this->colaboradorRepository->buscarColaborador($id);
            if(!$colaborador)
                return $this->json('Colaborador não encontrada!', 200); 

            $json = $this->serializer->serialize($colaborador, 'json',
                ['groups' => ['mostrar_colaborador']]
            );
            return $this->json(json_decode($json), 200);                
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
        
    }

    /**
     * @Route("/colaboradores", name="criar-colaboradores", methods={"POST"})
     */
    public function criar(Request $request): Response
    {            
                
        try {            
            if($request){                                
                $colaborador = $this->colaboradorService->salvar($request);
                return $this->json($colaborador->getId(), 200);            
            }
            return $this->json('Ocorreu um erro ao cadastrar o colaborador!', 200);       
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);       
        }
        
    }

    /**
     * @Route("/colaboradores", name="alterar-colaboradores", methods={"PUT"})
     */
    public function alterar(Request $request):Response 
    {                 
        
        try {
            if($request){                              
                $colaborador = $this->colaboradorService->atualizar($request);                
                return $this->json($colaborador->getId(), 200);
            } 
            return $this->json('Categoria não encontrada!', 200);      
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);
        }       
    }

    /**
     * @Route("/colaboradores/{id}", name="excluir-colaboradores", methods={"DELETE"})
    */
    public function excluir(int $id):Response 
    {
        try {            
            $colaborador = $this->colaboradorService->excluir($id);                  
            return $this->json($colaborador, 200);  
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
        
    }
}
