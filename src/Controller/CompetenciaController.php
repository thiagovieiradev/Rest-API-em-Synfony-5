<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoriaRepository;
use App\Repository\CompetenciaRepository;
use App\Entity\Competencia;
use App\Helper\Helper;
use App\Entity\Categoria;
use App\Service\CompetenciaService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class CompetenciaController extends AbstractController
{

    private $competenciaRepository;

    private $categoriaRepository;

    private $entity;

    private $doctrine;

    private $serializer;

    private $competenciaService;
    
    public function __construct(Helper $helper, CompetenciaService $competenciaService, SerializerInterface $serializer, CompetenciaRepository $competenciaRepository, CategoriaRepository $categoriaRepository, ManagerRegistry $doctrine)
    {
        $this->competenciaRepository = $competenciaRepository;
        $this->categoriaRepository = $categoriaRepository;
        $this->entity = $doctrine->getManager();
        $this->doctrine = $doctrine;
        $this->serializer = $serializer;
        $this->competenciaService = $competenciaService;
        $this->helper = $helper;
    }

    /**
     * @Route("/competencias", name="listar-competencias",  methods={"GET"})
     */
    public function listar(Request $request): Response
    {       
        try {
            $buscar = $request->query->get('buscar'); 
            $competencias = $this->helper->resolverBuscar($buscar, $this->competenciaRepository);
            //$competencias = $this->competenciaRepository->buscarCompetencias();
            if(!$competencias)
                return $this->json("Nenhuma competência encontrada!", 200);        
            
            $json = $this->serializer->serialize($competencias, 'json',
                ['groups' => ['mostrar_competencia', 'mostrar_categorias']]
            );
            $resposta = json_decode($json);

            $totalRows = count($this->helper->resolverBuscar($buscar, $this->competenciaRepository, true));
            return $this->json(
                ['competencias' => $resposta, 'totalRecords' => $totalRows], 200
            );
            
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);                  
        }         
    }

    /**
     * @Route("/competencias/{id}", name="mostrar-competencia",  methods={"GET"})
     */
    public function mostrar(int $id): Response
    {       
        try {
            $competencia = $this->competenciaRepository->buscarCompetencia($id);
            if(!$competencia)
                return $this->json('Competência não encontrada!', 200);

            $json = $this->serializer->serialize($competencia, 'json',
                ['groups' => ['mostrar_competencia', 'mostrar_categorias']]
            );
            return $this->json(json_decode($json), 200);                      
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);                  
        }         
    }

    /**
     * @Route("/competencias", name="criar-competencia", methods={"POST"})
     */
    public function criar(Request $request):Response 
    {     
        try {
            if($content = $request->getContent()){
                $dados = json_decode($content);                
                $competencia = $this->competenciaService->salvar($dados);                
                $json = $this->serializer->serialize($competencia, 'json',
                    ['groups' => ['mostrar_competencia', 'mostrar_categorias']]
                );
                return $this->json(json_decode($json), 200);                      
            }
            return $this->json(['Ocorreu um erro ao cadastrar a competência!'], 200);       
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);       
        } 
    }

    /**
     * @Route("/competencias", name="alterar-competencia", methods={"PUT"})
     */
    public function alterar(Request $request):Response 
    {
        try {
            if($content = $request->getContent()){
                $dados = json_decode($content);
                $competencia = $this->competenciaService->atualizar($dados);                
                //return $this->json($competencia, 200);
                $json = $this->serializer->serialize($competencia, 'json',
                    ['groups' => ['mostrar_competencia', 'mostrar_categorias']]
                );
                return $this->json(json_decode($json), 200);  
            } 
            return $this->json("Competência não encontrada!", 200); 
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);
        }            
    }

    /**
     * @Route("/competencias/{id}", name="excluir-competencia", methods={"DELETE"})
    */
    public function excluir(Request $request, int $id):Response 
    {

        try {            
            $competencia = $this->competenciaService->excluir($id);                  
            return $this->json(['codigo' => Response::HTTP_OK, 'message' => $competencia->getId()], 200);  
        } catch (\Exception $e) {
            return $this->json(['codigo' => Response::HTTP_NOT_FOUND, 'message' => $e->getMessage()], 200); 
        }
        
    }
    
    
}
