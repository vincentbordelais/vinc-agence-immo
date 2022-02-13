<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropertyController extends AbstractController
{
    protected $em;
    protected $propertyRepository;
    public function __construct(EntityManagerInterface $em, PropertyRepository $propertyRepository)
    {
        $this->em = $em;
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * @Route("/biens", name="property_index")
     * Affiche tous mes biens
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        // $property = new Property();
        // $property
        //     ->setTitle('Mon premier bien')
        //     ->setDescription('Une petite description')
        //     ->setSurface(60)
        //     ->setRooms(4)
        //     ->setBedrooms(3)
        //     ->setFloor(4)
        //     ->setPrice(200000)
        //     ->setHeat(1)
        //     ->setCity('Montpellier')
        //     ->setAddress('15 boulevard Gambetta')
        //     ->setPostalCode('34000');
        // // ->setSold() // pré-rempli
        // // ->setCreatedAt() // pré-remplie

        // $this->em->persist($property);
        // $this->em->flush($property);

        // $property = $this->propertyRepository->findAllVisible();
        // $property[0]->setSold(true);
        // $this->em->flush($property);
        // dd($property);
        // Mais va utiliser les fixtures :
        // $properties = $this->propertyRepository->findAllVisibleQuery();

        $search = new PropertySearch;
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $this->propertyRepository->findAllVisibleQuery($search), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );
        return $this->render("property/index.html.twig", [
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property_show", requirements={"slug": "[a-z0-9\-]*"})
     * Affiche un bien
     *
     * @param Property $property
     */
    public function show(Property $property, $slug)
    {
        if ($property->getSlug() !== $slug) {
            return $this->redirectToRoute("property_show", [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301); // status 301 parce que c'est une redirection permanente
        }
        // $property = $this->propertyRepository->find($id);
        return $this->render("property/show.html.twig", [
            'property' => $property
        ]);
    }
}
