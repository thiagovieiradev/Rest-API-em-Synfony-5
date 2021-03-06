<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SenioridadeRepository;
use App\Entity\Senioridade;
use App\Service\SenioridadeService;
use App\Helper\Helper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class SenioridadeController extends AbstractController
{
    private $senioridadeRepository;

    private $entity;

    private $doctrine;

    private $serializer;

    private $senioridadeService;

    private $senioridade;

    private $helper;

    public function __construct(Helper $helper, SenioridadeService $senioridadeService, SerializerInterface $serializer, SenioridadeRepository $senioridadeRepository, ManagerRegistry $doctrine
    ) {
        $this->senioridadeRepository = $senioridadeRepository;
        $this->entity = $doctrine->getManager();
        $this->doctrine = $doctrine;
        $this->serializer = $serializer;
        $this->senioridade = new Senioridade();
        $this->senioridadeService = $senioridadeService;
        $this->helper = $helper;
    }

    /**
     * @Route("/senioridades", name="senioridades",  methods={"GET"})
     */
    public function listar(Request $request): Response
    {      

        $buscar = $request->query->get('buscar'); 
        $senioridades = $this->helper->resolverBuscar($buscar, $this->senioridadeRepository);        
        if(!$senioridades)
            return $this->json("Nenhuma senioridade encontrada!", 200);        
        
        $json = $this->serializer->serialize($senioridades, 'json',
            ['groups' => ['mostrar_senioridades']]
        );
        $resposta = json_decode($json);

        $totalRows = count($this->helper->resolverBuscar($buscar, $this->senioridadeRepository, true));
        return $this->json(
            ['senioridades' => $resposta, 'totalRecords' => $totalRows], 200
        );
    }

    /**
     * @Route("/senioridades/{id}", name="listar-senioridades",  methods={"GET"})
     */
    public function mostrar(int $id): Response
    {      
        try {
            $senioridade = $this->senioridadeRepository->buscarSenioridade($id);
            if(!$senioridade)
                return $this->json('Senioridade n??o encontrada!', 200); 

            $json = $this->serializer->serialize($senioridade, 'json',
                ['groups' => ['mostrar_senioridades']]
            );
            return $this->json(json_decode($json), 200);                
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
        
    }

    /**
     * @Route("/senioridades", name="criar-senioridades", methods={"POST"})
     */
    public function criar(Request $request):Response {
        
        try {
            if($content = $request->getContent()){
                $dados = json_decode($content);                
                $senioridade = $this->senioridadeService->salvar($dados);
                return $this->json($senioridade, 200);            
            }
            return $this->json('Ocorreu um erro ao cadastrar a senioridade!', 200);       
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);       
        }
        
    }

    /**
     * @Route("/senioridades", name="alterar-senioridades", methods={"PUT"})
     */
    public function alterar(Request $request):Response 
    {                 
        try {
            if($content = $request->getContent()){
                $dados = json_decode($content);                  
                $senioridade = $this->senioridadeService->atualizar($dados);                
                return $this->json($senioridade, 200);
            } 
            return $this->json('Senioridade n??o encontrada!', 200);      
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200);
        }       
        
    }

    /**
     * @Route("/senioridades/{id}", name="excluir-senioridades", methods={"DELETE"})
    */
    public function excluir(int $id):Response 
    {
        try {            
            $senioridade = $this->senioridadeService->excluir($id);                  
            return $this->json($senioridade, 200);  
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 200); 
        }
        
    }

}
