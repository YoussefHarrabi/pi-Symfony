<?php

namespace App\Controller;

use App\Entity\Work;
use App\Form\AjoutFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Utilisation de la bonne classe Request
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\WorkRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormError;

class WorkController extends AbstractController
{
    #[Route('/work', name: 'app_work')]
    public function index(WorkRepository $workRepository): Response
    {
        $works = $workRepository->findAll();
        return $this->render('work/index.html.twig', [
            'works' => $works,
        ]);
    }

    #[Route('/work/ajouter', name: 'work_new')]
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        $work = new Work();
        $form = $this->createForm(AjoutFormType::class, $work);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Validation with ValidatorInterface
            $errors = $validator->validate($work);

            if (count($errors) > 0) {
                // If there are validation errors, add them to the form
                foreach ($errors as $error) {
                    // Add each validation error to the form
                    $form->addError(new FormError($error->getMessage()));
                }

                // Render the form again with validation errors
                return $this->render('work/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // If no additional errors from manual validation, proceed to save
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($work);
            $entityManager->flush();

            // Redirect to a different route after successful save
            return $this->redirectToRoute('app_work'); // Replace 'app_work' with your desired route
        }

        // Render the form initially or with submitted data and validation errors
        return $this->render('work/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'work_update')]
    public function update(Request $request, Work $work): Response
    {
        if ($request->isMethod('POST')) {
            // Update the work entity with data from the request
            $work->setLocation($request->request->get('location'));
            $work->setStartdate(new \DateTime($request->request->get('startdate')));
            $work->setEnddate(new \DateTime($request->request->get('enddate')));
            $work->setDescription($request->request->get('description'));
            $work->setIsactive($request->request->get('isactive'));

            // Save the changes to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // Redirect to the index page
            return $this->redirectToRoute('app_work');
        }

        // Render the update form
        return $this->render('edit.html.twig', [

            'work' => $work,
        ]);
    }
    #[Route('/work/{id}/delete', name: 'work_delete', methods: ['POST'])]
    public function delete(Request $request, Work $work): Response
    {
        if ($this->isCsrfTokenValid('delete' . $work->getWorkid(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($work);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_work');
    }
}
