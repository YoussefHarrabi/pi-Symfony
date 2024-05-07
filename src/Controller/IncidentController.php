<?php

namespace App\Controller;

use App\Entity\Incident;
use App\Form\IncidentType;
use App\Repository\IncidentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use SebastianBergmann\Environment\Console;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Security\Core\Security;


#[Route('/incident')]
class IncidentController extends AbstractController
{
   
    
    #[Route('/', name: 'app_incident_index')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request, Security $security): Response
{
    // Redirect anonymous users to the login page
    if ($security->getUser() === null) {
        return $this->redirectToRoute('app_login');
    }

    // Fetch all incidents from the repository
    $query = $entityManager->getRepository(Incident::class)->createQueryBuilder('i');

    // Search functionality
    $search = $request->query->get('search');
    if (!empty($search)) {
        $query->andWhere('i.type LIKE :search')
              ->setParameter('search', '%'.$search.'%');
    }

    $incidents = $query->getQuery()->getResult();

    // Count incidents for each hour
    $hourlyCounts = array_fill(0, 24, 0);
    foreach ($incidents as $incident) {
        $hour = $incident->getHour();
        if ($hour instanceof \DateTimeInterface) {
            $hour = (int) $hour->format('H');
            $hourlyCounts[$hour]++;
        }
    }

    // Find the hour(s) with the highest frequency
    $maxCount = max($hourlyCounts);
    $rushHours = array_keys($hourlyCounts, $maxCount);

    // Format the rush hour(s)
    $formattedRushHours = [];
    foreach ($rushHours as $rushHour) {
        $formattedRushHours[] = "{$rushHour}:00 - " . ($rushHour + 1) . ":00 ({$maxCount} incidents)";
    }
    $rushHourText = implode(", ", $formattedRushHours);

    // Paginate the incidents
    $incidents = $paginator->paginate(
        $incidents, /* query NOT result */
        $request->query->getInt('page', 1),
        10 // Number of items per page
    );

    return $this->render('incident/index.html.twig', [
        'rushHour' => $rushHourText,
        'incidents' => $incidents,
    ]);
}
    
#[Route('/2/', name: 'app_incident_index2', methods: ['GET'])]
public function index2(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request, Security $security): Response
{
    // Redirect anonymous users to the login page
    if ($security->getUser() === null) {
        return $this->redirectToRoute('app_login');
    }

    // Fetch all incidents from the repository
    $query = $entityManager->getRepository(Incident::class)->createQueryBuilder('i');

    // Search functionality
    $search = $request->query->get('search');
    if (!empty($search)) {
        $query->andWhere('i.type LIKE :search')
              ->setParameter('search', '%'.$search.'%');
    }

    $incidents = $query->getQuery()->getResult();

    // Count incidents for each hour
    $hourlyCounts = array_fill(0, 24, 0);
    foreach ($incidents as $incident) {
        $hour = $incident->getHour();
        if ($hour instanceof \DateTimeInterface) {
            $hour = (int) $hour->format('H');
            $hourlyCounts[$hour]++;
        }
    }

    // Find the hour(s) with the highest frequency
    $maxCount = max($hourlyCounts);
    $rushHours = array_keys($hourlyCounts, $maxCount);

    // Format the rush hour(s)
    $formattedRushHours = [];
    foreach ($rushHours as $rushHour) {
        $formattedRushHours[] = "{$rushHour}:00 - " . ($rushHour + 1) . ":00 ({$maxCount} incidents)";
    }
    $rushHourText = implode(", ", $formattedRushHours);

    // Paginate the incidents
    $incidents = $paginator->paginate(
        $incidents, /* query NOT result */
        $request->query->getInt('page', 1),
        10 // Number of items per page
    );

    return $this->render('incident/indexback.html.twig', [
        'rushHour' => $rushHourText,
        'incidents' => $incidents,
    ]);
}

    
    #[Route('/new', name: 'app_incident_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,FlashyNotifier $flashy,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $incident = new Incident();

        // Set the date of the incident to today's date
        $incident->setDate(new \DateTime());
    
        $form = $this->createForm(IncidentType::class, $incident);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($incident);
            $entityManager->flush();
    
            // Flash success message
            $flashy->success('New incident is created!');

    
            return $this->redirectToRoute('app_incident_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('incident/new.html.twig', [
            'incident' => $incident,
            'form' => $form,
        ]);
    }

    #[Route('/new2', name: 'incident_new', methods: ['GET', 'POST'])]
    public function new2(Request $request, EntityManagerInterface $entityManager,FlashyNotifier $flashy,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        
        $incident = new Incident();
        $incident->setDate(new \DateTime());
        $form = $this->createForm(IncidentType::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($incident);
            $entityManager->flush();
            $flashy->success('New incident is created!');
            return $this->redirectToRoute('app_incident_index2', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('incident/newback.html.twig', [
            'incident' => $incident,
            'form' => $form,
        ]);
    }

    
    #[Route('/{id}', name: 'app_incident_show', methods: ['GET'])]
    public function show(Incident $incident, EntityManagerInterface $entityManager,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        return $this->render('incident/show.html.twig', [
            'incident' => $incident,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_incident_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Incident $incident, EntityManagerInterface $entityManager,int $id,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $incident = $entityManager->getRepository(Incident::class)->find($id);

            // Check if the incident exists
            if (!$incident) {
                throw $this->createNotFoundException('The incident does not exist');
            }
        $form = $this->createForm(IncidentType::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Flush the changes to the database
            $entityManager->flush();

            // Redirect back to the incident page
            return $this->redirectToRoute('app_incident_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('incident/edit.html.twig', [
            'incident' => $incident,
            'form' => $form,
        ]);
    }
    #[Route('/edit2/{id}', name: 'incident_edit', methods: ['GET', 'POST'])]
    public function edit2(Request $request, Incident $incident, EntityManagerInterface $entityManager,int $id,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        $incident = $entityManager->getRepository(Incident::class)->find($id);

            // Check if the incident exists
            if (!$incident) {
                throw $this->createNotFoundException('The incident does not exist');
            }
        $form = $this->createForm(IncidentType::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Flush the changes to the database
            $entityManager->flush();

            // Redirect back to the incident page
            return $this->redirectToRoute('app_incident_index2', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('incident/editback.html.twig', [
            'incident' => $incident,
            'form' => $form,
        ]);
    }
    #[Route('/delete/{id}', name: 'app_incident_delete', methods: ['POST'])]
    public function delete(Request $request, Incident $incident, EntityManagerInterface $entityManager,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$incident->getIncidentid(), $request->request->get('_token'))) {
            $entityManager->remove($incident);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_incident_index', [], Response::HTTP_SEE_OTHER);
    
    }
    #[Route('/delete2/{id}', name: 'incident_delete', methods: ['POST'])]
    public function delete2(Request $request, Incident $incident, EntityManagerInterface $entityManager,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$incident->getIncidentid(), $request->request->get('_token'))) {
            $entityManager->remove($incident);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_incident_index2', [], Response::HTTP_SEE_OTHER);
    
    }
    
    #[Route("/delete-incident/{id}", name: "delete_incident", methods: ["POST"])]
    public function deleteIncident(Incident $incident, EntityManagerInterface $entityManager,Security $security): JsonResponse
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        if (!$incident) {
            return new JsonResponse(['error' => 'Incident not found'], JsonResponse::HTTP_NOT_FOUND);
        }
    
        try {
            $entityManager->remove($incident);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to delete incident: ' . $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    
        return new JsonResponse(['message' => 'Incident deleted successfully'], JsonResponse::HTTP_OK);
        
    }

    #[Route('/generate-excel', name: 'generate_excel', methods: ['POST'])]
    public function generateExcel(IncidentRepository $incidentRepository,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        // Fetch all incidents from the database
        $incidents = $incidentRepository->findAll();
    
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
    
        // Set the properties for the Excel file
        $spreadsheet->getProperties()
            ->setTitle('Incident Report')
            ->setCreator('Your Name')
            ->setLastModifiedBy('Your Name')
            ->setDescription('Incident report generated by PHP');
    
        // Add a worksheet
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Incidents');
    
        // Add headers
        $sheet->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Type')
            ->setCellValue('C1', 'Description')
            ->setCellValue('D1', 'Place')
            ->setCellValue('E1', 'Hour')
            ->setCellValue('F1', 'Date');
    
        // Add data for each incident
        $row = 2; // Start from the second row
        foreach ($incidents as $incident) {
            $sheet->setCellValue('A' . $row, $incident->getincidentId())
                ->setCellValue('B' . $row, $incident->getType())
                ->setCellValue('C' . $row, $incident->getDescription())
                ->setCellValue('D' . $row, $incident->getPlace())
                ->setCellValue('E' . $row, $incident->getHour() ? $incident->getHour()->format('H:i') : '')
                ->setCellValue('F' . $row, $incident->getDate());

    $row++;
        }
    
        // Create a writer
        $writer = new Xlsx($spreadsheet);
    
        // Create a temporary file to save the spreadsheet
        $tempFile = tempnam(sys_get_temp_dir(), 'incident_report');
        $writer->save($tempFile);
    
        // Return the Excel file as a response
        return $this->file($tempFile, 'incident_report.xlsx', ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }


    
}