<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CapteursRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Capteurs;
use App\Form\CapteursType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;




class CapteursController extends AbstractController
{
    #[Route('/capteurs', name: 'app_capteurs')]
    public function index(): Response
    {
        return $this->render('capteurs/index.html.twig', [
            'controller_name' => 'CapteursController',
        ]);
    }
    
    #[Route('/capteurs/Affiche', name: 'app_Affiche')]
    public function Affiche(CapteursRepository $repository)
    {
        $capteurs =$repository->findAll(); //select * 
        return $this->render('capteurs/Affiche.html.twig', [
            'capteurs' => $capteurs,
        ]);
    }

    #[Route('/ajouterCapteur', name: 'app_ajouterCapteur')]
public function ajouterCapteur(Request $request): Response
{
    $capteur = new Capteurs();
    
    $form = $this->createForm(CapteursType::class, $capteur);
    $form->add('Ajouter', SubmitType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();

        // Vérifier l'unicité du nom de capteur
        $existingCapteur = $entityManager->getRepository(Capteurs::class)->findOneBy(['nom' => $capteur->getNom()]);
        if ($existingCapteur) {
            $this->addFlash('error', 'Ce nom de capteur est déjà utilisé. Veuillez choisir un autre nom.');
            // Réinitialiser les données du formulaire avec les valeurs du capteur saisi précédemment
            $form = $this->createForm(CapteursType::class, $capteur);
            $form->add('Ajouter', SubmitType::class);
            // Passer également le formulaire avec le message d'erreur à afficher
            return $this->render('Capteurs/ajouterCapteur.html.twig', [
                'f' => $form->createView(),
                'erreur' => true, // Indique qu'il y a une erreur
            ]);
        }
        else {
            // Si le nom est unique, persister le capteur
            $entityManager->persist($capteur);
            $entityManager->flush();

            $this->addFlash('success', 'Capteur ajouté avec succès.');

            return $this->redirectToRoute('app_Affiche');
        }
    }

    return $this->render('Capteurs/ajouterCapteur.html.twig', [
        'f' => $form->createView(),
        'erreur' => false, // Indique qu'il n'y a pas d'erreur lors du premier affichage
    ]);
}

    
#[Route('/modifierCapteur/{id}', name: 'app_modifierCapteur')]
public function modifierCapteur(Request $request, CapteursRepository $repository, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $capteur = $repository->find($id);

    if (!$capteur) {
        throw $this->createNotFoundException('Capteur non trouvé avec l\'id : ' . $id);
    }

    $form = $this->createForm(CapteursType::class, $capteur);
    $form->add('Modifier', SubmitType::class);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifier l'unicité du nom de capteur, à l'exception du capteur actuel
        $existingCapteur = $entityManager->getRepository(Capteurs::class)->findOneBy(['nom' => $capteur->getNom()]);
        if ($existingCapteur && $existingCapteur->getId() !== $id) {
            $this->addFlash('error', 'Ce nom de capteur est déjà utilisé. Veuillez choisir un autre nom.');
            return $this->redirectToRoute('app_modifierCapteur', ['id' => $id]);
        }

        // Si le nom est unique, persister les modifications du capteur
        $entityManager->flush();

        $this->addFlash('success', 'Capteur modifié avec succès.');

        return $this->redirectToRoute('app_Affiche');
    }

    return $this->render('Capteurs/modifierCapteur.html.twig', [
        'f' => $form->createView(),
        'capteur' => $capteur, // Passer le capteur au template
    ]);
}


#[Route('/supprimerCapteur/{id}', name: 'app_supprimerCapteur')]
public function supprimerCapteur(Request $request, CapteursRepository $repository, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $capteur = $repository->find($id);

    if (!$capteur) {
        throw $this->createNotFoundException('Capteur non trouvé avec l\'id : ' . $id);
    }

    // Supprimer le capteur de la base de données
    $entityManager->remove($capteur);
    $entityManager->flush();

    $this->addFlash('success', 'Capteur supprimé avec succès.');

    return $this->redirectToRoute('app_Affiche');
}



}
