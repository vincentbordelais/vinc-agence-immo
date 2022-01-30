<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home_index")
     */
    public function index(PropertyRepository $propertyRepository)
    {
        $properties = $propertyRepository->findLatest();
        return $this->render("pages/home.html.twig", [
            'properties' => $properties
        ]);
    }
}
