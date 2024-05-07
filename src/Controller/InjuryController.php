<?php

namespace App\Controller;

use App\Entity\Injury;
use App\Form\InjuryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\InjuryRestCalculator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;

#[Route('/injury')]
class InjuryController extends AbstractController
{
    #[Route('/', name: 'app_injury_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request, Security $security): Response
    {
        // Redirect anonymous users to the login page
        if ($security->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
    
        // Fetch all injuries from the repository
        $query = $entityManager->getRepository(Injury::class)->createQueryBuilder('i');
    
        // Search functionality
        $search = $request->query->get('search');
        if (!empty($search)) {
            $query->andWhere('i.type LIKE :search')
                  ->setParameter('search', '%'.$search.'%');
        }
    
        $injuries = $query->getQuery()->getResult();
    
        // Paginate the injuries
        $injuries = $paginator->paginate(
            $injuries, /* query NOT result */
            $request->query->getInt('page', 1),
            3 // Number of items per page
        );
    
        return $this->render('injury/index.html.twig', [
            'injuries' => $injuries,
        ]);
    }
    
    #[Route('/2/', name: 'app_injury_index2', methods: ['GET'])]
    public function index2(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request, Security $security): Response
    {
        // Redirect anonymous users to the login page
        if ($security->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
    
        // Fetch all injuries from the repository
        $query = $entityManager->getRepository(Injury::class)->createQueryBuilder('i');
    
        // Search functionality
        $search = $request->query->get('search');
        if (!empty($search)) {
            $query->andWhere('i.type LIKE :search')
                  ->setParameter('search', '%'.$search.'%');
        }
    
        $injuries = $query->getQuery()->getResult();
    
        // Paginate the injuries
        $injuries = $paginator->paginate(
            $injuries, /* query NOT result */
            $request->query->getInt('page', 1),
            3 // Number of items per page
        );
    
        return $this->render('injury/indexback.html.twig', [
            'injuries' => $injuries,
        ]);
    }


    #[Route('/new', name: 'app_injury_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $injury = new Injury();
        $form = $this->createForm(InjuryType::class, $injury);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($injury);
            $entityManager->flush();

            return $this->redirectToRoute('app_injury_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('injury/new.html.twig', [
            'injury' => $injury,
            'form' => $form,
        ]);
    }

    #[Route('/new2', name: 'app_injury_new2', methods: ['GET', 'POST'])]
    public function new2(Request $request, EntityManagerInterface $entityManager,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $injury = new Injury();
        $form = $this->createForm(InjuryType::class, $injury);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($injury);
            $entityManager->flush();

            return $this->redirectToRoute('app_injury_index2', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('injury/newback.html.twig', [
            'injury' => $injury,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_injury_show', methods: ['GET'])]
    public function show(Injury $injury,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        return $this->render('injury/show.html.twig', [
            'injury' => $injury,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_injury_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Injury $injury, EntityManagerInterface $entityManager,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(InjuryType::class, $injury);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_injury_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('injury/edit.html.twig', [
            'injury' => $injury,
            'form' => $form,
        ]);
    }
    #[Route('/edit2/{id}', name: 'app_injury_edit2', methods: ['GET', 'POST'])]
    public function edit2(Request $request, Injury $injury, EntityManagerInterface $entityManager,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(InjuryType::class, $injury);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_injury_index2', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('injury/editback.html.twig', [
            'injury' => $injury,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_injury_delete', methods: ['POST'])]
    public function delete(Request $request, Injury $injury, EntityManagerInterface $entityManager,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$injury->getId(), $request->request->get('_token'))) {
            $entityManager->remove($injury);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_injury_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete2/{id}', name: 'app_injury_delete2', methods: ['POST'])]
    public function delete2(Request $request, Injury $injury, EntityManagerInterface $entityManager,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$injury->getId(), $request->request->get('_token'))) {
            $entityManager->remove($injury);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_injury_index2', [], Response::HTTP_SEE_OTHER);
    }



}
