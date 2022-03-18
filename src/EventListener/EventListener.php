<?php 

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        
        $exception = $event->getThrowable();        
        $message = 'Erro: '.$exception->getMessage();
        
        $response = new Response(); 
        $response->setContent($message);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response = new JsonResponse(
            ['code' => $response->getStatusCode(), 'message' => $message], 
            $response->getStatusCode()
        );
        $event->setResponse($response);
    }
}

?>