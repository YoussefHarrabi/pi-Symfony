<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DonneeshistoriquesRepository;
use App\Form\DonneesModifierType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Donneeshistoriques;
use App\Entity\Capteurs;
use App\Form\DonneeshistoriquesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Security;
use Knp\Component\Pager\PaginatorInterface;

class DonneesController extends AbstractController
{
    #[Route('/donnees', name: 'app_donnees')]
    public function index(Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        return $this->render('donnees/index.html.twig', [
            'controller_name' => 'DonneesController',
        ]);
    }

    #[Route('/donnees/afficheDonnees', name: 'app_afficheDonnees')] 
    public function afficheDonnees(DonneeshistoriquesRepository $repository,Security $security,PaginatorInterface $paginator, Request $request): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $idCapteur = $request->query->get('id_capteur', '');

    // Créer une requête pour obtenir les données historiques
    $queryBuilder = $repository->createQueryBuilder('d');

    // Filtrer par ID de capteur s'il est spécifié
    if (!empty($idCapteur)) {
        $queryBuilder->andWhere('d.idCapteur = :idCapteur')
                     ->setParameter('idCapteur', $idCapteur);
    }

    // Obtenir la requête finale
    $donneesQuery = $queryBuilder->getQuery();

    // Paginer les données avec un maximum de 10 éléments par page
    $donneesPagines = $paginator->paginate(
        $donneesQuery,
        $request->query->getInt('page', 1), // Numéro de page
        10 // Nombre d'éléments par page
    );

    return $this->render('donnees/afficheDonnees.html.twig', [
        'donnees' => $donneesPagines,
    ]);
    }

    #[Route('/ajouterDonnees', name: 'app_ajouterDonnees')]
public function ajouterDonnees(Request $request,Security $security): Response
{
    if ($security->getUser() === null) {
        // Redirect anonymous users to the login page
        return $this->redirectToRoute('app_login');
    }
    $donnees = new Donneeshistoriques();
    $form = $this->createForm(DonneeshistoriquesType::class, $donnees);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();

        // Affecter les données du formulaire à l'entité Donneeshistoriques
        $donnees->setIdCapteur($form->get('idCapteur')->getData());
        $donnees->setNiveauEmbouteillage($form->get('niveauEmbouteillage')->getData());
        $donnees->setAlerte($form->get('alerte')->getData());

        // Persister les données
        $entityManager->persist($donnees);
        $entityManager->flush();

        $this->addFlash('success', 'Données ajoutées avec succès.');

        return $this->redirectToRoute('app_afficheDonnees');
    }

    return $this->render('donnees/ajouterDonnees.html.twig', [
        'f' => $form->createView(),
    ]);
}

    #[Route('/modifierDonnees/{id}', name: 'app_modifierDonnees')]
public function modifierDonnees(Request $request, DonneeshistoriquesRepository $repository, int $id,Security $security): Response
{
    if ($security->getUser() === null) {
        // Redirect anonymous users to the login page
        return $this->redirectToRoute('app_login');
    }
    $entityManager = $this->getDoctrine()->getManager();
    $donnees = $repository->find($id);

    if (!$donnees) {
        throw $this->createNotFoundException('Données non trouvées avec l\'id : ' . $id);
    }

    $form = $this->createForm(DonneesModifierType::class, $donnees);
    $form->add('Modifier', SubmitType::class);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Persister les modifications des données
        $entityManager->flush();

        $this->addFlash('success', 'Données modifiées avec succès.');

        return $this->redirectToRoute('app_afficheDonnees');
    }

    return $this->render('donnees/modifierDonnees.html.twig', [
        'f' => $form->createView(),
        'donneeHistorique' => $donnees, // Passer l'instance de Donneeshistoriques à Twig
    ]);
}


    #[Route('/supprimerDonnees/{id}', name: 'app_supprimerDonnees')]
    public function supprimerDonnees(Request $request, DonneeshistoriquesRepository $repository, int $id,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $donnees = $repository->find($id);

        if (!$donnees) {
            throw $this->createNotFoundException('Données non trouvées avec l\'id : ' . $id);
        }

        // Supprimer les données de la base de données
        $entityManager->remove($donnees);
        $entityManager->flush();

        $this->addFlash('success', 'Données supprimées avec succès.');

        return $this->redirectToRoute('app_afficheDonnees');
    }
    #[Route('/map', name: 'map')]
    public function Mappp(Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $repository = $this->getDoctrine()->getRepository(Donneeshistoriques::class);
        $donneesHistoriques = $repository->findAll(); // Récupère toutes les données historiques
    
        $latitudes = [];
        $longitudes = [];
    
        foreach ($donneesHistoriques as $donnee) {
            $latitudes[] = $repository->findLatitudeByIdCapteur($donnee->getIdCapteur());
            $longitudes[] = $repository->findLongitudeByIdCapteur($donnee->getIdCapteur());
        }
    
        return $this->render('donnees/map.html.twig', [
            'donneesHistoriques' => $donneesHistoriques,
            'latitudes' => $latitudes,
            'longitudes' => $longitudes,
        ]);
    }

    #[Route('/stat', name: 'stat')]
    public function stat(EntityManagerInterface $entityManager,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        // Récupération des données historiques
        $repository = $entityManager->getRepository(Donneeshistoriques::class);
        $donneesHistoriques = $repository->findAll();
    
        // Tableau pour stocker les statistiques par heure
        $statsByHour = [];
    
        // Calcul des statistiques par heure
        foreach ($donneesHistoriques as $donnee) {
            // Convertir la chaîne de caractères en objet DateTime
            $timestamp = new \DateTime($donnee->getTimestamp());
            $heure = $timestamp->format('H'); // Extraire l'heure de la date
    
            if (!isset($statsByHour[$heure])) {
                $statsByHour[$heure] = 0;
            }
            $statsByHour[$heure]++;
        }
    
        return $this->render('donnees/stat.html.twig', [
            'statsByHour' => $statsByHour,
        ]);
    }
    
}
    
