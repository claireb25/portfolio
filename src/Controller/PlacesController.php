<?php

namespace App\Controller;

use App\Entity\Places;
use App\Form\PlacesType;
use App\Repository\PlacesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/places")
 */
class PlacesController extends Controller
{
    /**
     * @Route("/", name="places_index", methods="GET")
     */
    public function index(PlacesRepository $placesRepository): Response
    {
        return $this->render('places/index.html.twig', ['places' => $placesRepository->findAll()]);
    }

    /**
     * @Route("/new", name="places_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $place = new Places();
        $form = $this->createForm(PlacesType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($place);
            $em->flush();

            return $this->redirectToRoute('places_index');
        }

        return $this->render('places/new.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="places_show", methods="GET")
     */
    public function show(Places $place): Response
    {
        return $this->render('places/show.html.twig', ['place' => $place]);
    }

    /**
     * @Route("/{id}/edit", name="places_edit", methods="GET|POST")
     */
    public function edit(Request $request, Places $place): Response
    {
        $form = $this->createForm(PlacesType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('places_show', ['id' => $place->getId()]);
        }

        return $this->render('places/edit.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="places_delete", methods="DELETE")
     */
    public function delete(Request $request, Places $place): Response
    {
        if ($this->isCsrfTokenValid('delete'.$place->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($place);
            $em->flush();
        }

        return $this->redirectToRoute('places_index');
    }
}
