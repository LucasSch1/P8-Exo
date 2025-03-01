<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


final class VoituresController extends AbstractController
{

    public function __construct(
        private VoitureRepository $voitureRepository,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
    )
    {

    }



    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $voitures = $this->voitureRepository->findAll();
        return $this->render('index.html.twig', [
            'voitures' => $voitures,
        ]);
    }

    #[Route('/voiture/ajouter', name: 'app_car_add')]
    public function ajouterVoiture(Request $request): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $error = $this->validator->validate($form);
            if (count($error) > 0) {
                return new Response('Le formulaire n\'est pas valide', Response::HTTP_BAD_REQUEST);
            }else
            {
                $this->entityManager->persist($voiture);
                $this->entityManager->flush();
                return $this->redirectToRoute('app_home');
            };

        };



        return $this->render('nouvelle-voiture.html.twig', [
            'form' => $form,
        ]);

    }


    #[Route('/voiture/{id}', name: 'app_car')]
    public function voiture(int $id): Response
    {
        $voiture = $this->voitureRepository->find($id);

        if (!$voiture) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('voiture.html.twig', [
            'voiture' => $voiture,
        ]);

    }

    #[Route('/voiture/{id}/supprimer', name: 'app_car_delete')]
    public function supprimerVoiture(int $id): Response
    {
        $voiture = $this->voitureRepository->find($id);
        if (!$voiture) {
            return $this->redirectToRoute('app_home');
        }

        $this->entityManager->remove($voiture);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_home');
    }





}
