<?php 

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UploadService
{
    
    private $slugger;
    
    private $validator;

    private $params;

    public function __construct(SluggerInterface $slugger, ValidatorInterface $validator, ParameterBagInterface $params)
    {
        $this->slugger = $slugger;        
        $this->validator = $validator;
        $this->params = $params;
    }

    public function upload($file)
    {
        $errors = $this->validator->validate($file, new Image([
            'maxSize' => "10M",
            'minWidth' => 200,
            'maxWidth' => 5000,
            'minHeight' => 200,
            'maxHeight' => 5000,
            'mimeTypes' => [
                "image/jpeg",
                "image/jpg",
                "image/png",
                "image/gif",
            ],
        ]));

        if (count($errors)) {
            throw new ValidationException($errors);
        }

        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);            
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        }

        try {
            $file->move($this->getTargetDirectory(), $newFilename);
        } catch (FileException $e) {
            
        }
        return $newFilename;
    }

    public function getTargetDirectory()
    {
        return $this->params->get('colaboradores_directory');
    }

}


?>