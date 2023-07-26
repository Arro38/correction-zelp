<?php

namespace App\Controller;

use App\Entity\RestaurantPicture;
use App\Form\RestaurantPictureType;
use App\Repository\RestaurantPictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/restaurant/picture')]
class RestaurantPictureController extends AbstractController
{
    #[Route('/', name: 'app_restaurant_picture_index', methods: ['GET'])]
    public function index(RestaurantPictureRepository $restaurantPictureRepository): Response
    {
        return $this->render('restaurant_picture/index.html.twig', [
            'restaurant_pictures' => $restaurantPictureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_restaurant_picture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $restaurantPicture = new RestaurantPicture();
        $form = $this->createForm(RestaurantPictureType::class, $restaurantPicture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($restaurantPicture);
            $entityManager->flush();

            return $this->redirectToRoute('app_restaurant_picture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('restaurant_picture/new.html.twig', [
            'restaurant_picture' => $restaurantPicture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_restaurant_picture_show', methods: ['GET'])]
    public function show(RestaurantPicture $restaurantPicture): Response
    {
        return $this->render('restaurant_picture/show.html.twig', [
            'restaurant_picture' => $restaurantPicture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_restaurant_picture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RestaurantPicture $restaurantPicture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RestaurantPictureType::class, $restaurantPicture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_restaurant_picture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('restaurant_picture/edit.html.twig', [
            'restaurant_picture' => $restaurantPicture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_restaurant_picture_delete', methods: ['POST'])]
    public function delete(Request $request, RestaurantPicture $restaurantPicture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurantPicture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($restaurantPicture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_restaurant_picture_index', [], Response::HTTP_SEE_OTHER);
    }
}
