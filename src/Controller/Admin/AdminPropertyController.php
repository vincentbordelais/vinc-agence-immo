<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPropertyController extends AbstractController
{
    protected $propertyRepository;
    protected $em;
    public function __construct(PropertyRepository $propertyRepository, EntityManagerInterface $em)
    {
        $this->propertyRepository = $propertyRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/property", name="adminProperty_index")
     * Récupère tous les biens
     */
    public function index()
    {
        $properties = $this->propertyRepository->findAll();
        return $this->render("admin/property/show.html.twig", [
            'properties' => $properties
        ]);
    }

    /**
     * @Route("/admin/property/create", name="adminProperty_create")
     */
    public function create(Request $request)
    {
        $property = new Property;
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', "Le bien '{$property->getTitle()}' a bien été créé.");
            return $this->redirectToRoute('adminProperty_index');
        }

        return $this->render("admin/property/create.html.twig", [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/property/edit/{id}", name="adminProperty_edit", methods="GET|POST")
     */
    public function edit(Property $property, Request $request)
    {
        // $property = $this->propertyRepository->find($id);

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', "Le bien '{$property->getTitle()}' a bien été édité.");
            return $this->redirectToRoute('adminProperty_index');
        }

        return $this->render("admin/property/edit.html.twig", [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/property/delete/{id}", name="adminProperty_delete", requirements={"id":"\d+"})
     */
    public function delete($id, Property $property)
    {
        // $property = $this->propertyRepository->find($id);
        if (!$property) {
            throw $this->createNotFoundException("Le bien {$id} n'existe pas et ne peut pas être supprimé");
        }
        $this->em->remove($property);
        $this->em->flush();
        $this->addFlash('success', "Le bien '{$property->getTitle()}' a bien été supprimé.");
        return $this->redirectToRoute('adminProperty_index');
    }
}
