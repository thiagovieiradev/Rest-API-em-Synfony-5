<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoriaRepository;
use App\Entity\Categoria;
use App\Service\CategoriaService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class CategoriaController extends AbstractController
{

    private $categoriaRepository;

    private $entity;

    private $doctrine;

    private $serializer;

    private $categoriaService;

    private $categoria;

    public function __construct(CategoriaService $categoriaService, SerializerInterface $serializer, CategoriaRepository $categoriaRepository, ManagerRegistry $doctrine
    ) {
        $this->categoriaRepository = $categoriaRepository;
        $this->entity = $doctrine->getManager();
        $this->doctrine = $doctrine;
        $this->serializer = $serializer;
        $this->categoria = new Categoria();
        $this->categoriaService = $categoriaService;
    }

    /**
     * @Route("/categorias", name="listar-categorias",  methods={"GET"})
     */
    public function listar(): Response
    {      
        try {
            $categorias = $this->categoriaRepository->buscarCategorias();
            if(!$categorias)
                return $this->json("Nenhuma categoria encontrada!", 200);        
            $json = $this->serializer->serialize($categorias, 'json',
                ['groups' => ['mostrar_categorias']]
            );
            return $this->json(json_decode($json), 200);        
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
    }

    /**
     * @Route("/categorias/{id}", name="mostrar-categoria",  methods={"GET"})
     */
    public function mostrar(int $id): Response
    {      
        try {
            $categoria = $this->categoriaRepository->buscarCategoria($id);
            if(!$categoria)
                return $this->json('Categoria não encontrada!', 200); 

            $json = $this->serializer->serialize($categoria, 'json',
                ['groups' => ['mostrar_categorias']]
            );
            return $this->json(json_decode($json), 200);                
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
        
    }

    /**
     * @Route("/categorias", name="criar-categorias", methods={"POST"})
     */
    public function criar(Request $request):Response {
        
        try {
            if($content = $request->getContent()){
                $dados = json_decode($content);                
                $categoria = $this->categoriaService->salvar($dados);
                return $this->json($categoria, 200);            
            }
            return $this->json('Ocorreu um erro ao cadastrar a categoria!', 200);       
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);       
        }
        
    }

    /**
     * @Route("/categorias", name="alterar-categorias", methods={"PUT"})
     */
    public function alterar(Request $request):Response 
    {                 
        try {
            if($content = $request->getContent()){
                $dados = json_decode($content);                  
                $categoria = $this->categoriaService->atualizar($dados);                
                return $this->json($categoria, 200);
            } 
            return $this->json('Categoria não encontrada!', 200);      
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);
        }       
        
    }

    /**
     * @Route("/categorias/{id}", name="excluir-categorias", methods={"DELETE"})
    */
    public function excluir(int $id):Response 
    {
        try {            
            $categoria = $this->categoriaService->excluir($id);                  
            return $this->json($categoria, 200);  
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
        
    }

}
