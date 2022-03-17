<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsuarioRepository;

class UsuarioController extends AbstractController
{

    public function __construct(UsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * @Route("/usuario", name="app_usuario")
     */
    public function index(): Response
    {
        
        return $this->json([
            $this->usuarioRepository->findAll()
        ]);
    }
}
