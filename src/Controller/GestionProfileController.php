<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utilisateurs;
use DateTime;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\UserProfileFormType;
class GestionProfileController extends AbstractController
{

    #[Route('/profile', name: 'profile')]
    public function index(Security $security): Response
    {
        if ($security->getUser() !== null) {
            // Assuming you have logic to retrieve the user from your database
            $user = $this->getUser();

            return $this->render('GestionUser/profile.html.twig', [
                'user' => $user,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
    private function isPasswordStrong(string $password): bool
        {
            // Password must contain at least one uppercase letter, one lowercase letter, one number,
            // and one special character, and must be at least 8 characters long
            return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}$/', $password);
        }

    #[Route('/update', name: 'profile_update')]
    public function update(Request $request, ValidatorInterface $validator, SessionInterface $session): Response
    {
        // Retrieve the logged-in user
        $user = $this->getUser();

        // Retrieve form data from the request
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $email = $request->request->get('email');
        $motDePasse = $request->request->get('motDePasse');
        $ageString = $request->request->get('age');
        $imageFile = $request->files->get('coverImage');
          // Check if any required field is empty
    if (empty($nom) || empty($prenom) || empty($email) || empty($ageString) ) {
        // Add flash error message
        $session->getFlashBag()->add('error', 'All fields are required.');

        // Redirect back to the profile page
        return $this->redirectToRoute('profile');
    }
        // Validate password strength
        if (!empty($motDePasse)) {
            // Check if the password meets strength requirements
            if (!$this->isPasswordStrong($motDePasse)) {
                // Add a flash message to inform the user about the weak password
                $this->addFlash('error', 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character, and must be at least 8 characters long.');

                // Redirect back to the profile page
                return $this->redirectToRoute('profile');
            }
        }

        // Update user profile information
        $entityManager = $this->getDoctrine()->getManager();
        // Check if the provided email is already in use
        $existingUser = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneByEmail($email);
        if ($existingUser && $existingUser->getId() !== $user->getId()) {
            // Add flash error message
            $session->getFlashBag()->add('error', 'Email is already in use.');
            return $this->redirectToRoute('profile');
        }

        // Update nom
        $user->setNom($nom);

        // Update prenom
        $user->setPrenom($prenom);

        // Update email
        $user->setEmail($email);

        // Hash and update password if provided
        if (!empty($motDePasse)) {
            $hashedPassword = password_hash($motDePasse, PASSWORD_DEFAULT);
            $user->setMotDePasse($hashedPassword);
        }

        // Update age
        $age = new DateTime($ageString);
        $today = new DateTime();
        if ($age > $today) {
            // Add flash error message
            $session->getFlashBag()->add('error', 'Date of birth cannot be in the future.');

            // Redirect back to the profile page
            return $this->redirectToRoute('profile');
        }
        $user->setAge($age);

        // Handle file upload for the profile image
       

        if ($imageFile) {

            // Generate a unique filename using the original file extension
            $originalFilename = $imageFile->getClientOriginalName();
            $extension = $imageFile->getClientOriginalExtension();
            $newFilename = uniqid() . '.' . $extension;

            try {
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads', // Specify the directory where you want to store the uploaded images
                    $newFilename
                );

                // Set the real path of the uploaded image in the User entity
                $imagePath = '/uploads/' . $newFilename; // Relative path from the public directory
                $user->setUrl($imagePath);
            } catch (FileException $e) {
                // Handle file upload exception if needed
            }
        }

        // Persist changes to the database
        $entityManager->flush();

        // Redirect back to the profile page after updating
        return $this->redirectToRoute('profile');
    }


    #[Route('/update2', name: 'prupdate')]
    public function update2(Request $request): Response
    {
        // Retrieve the logged-in user
        $user = $this->getUser();

        // Create the form instance using UserProfileFormType
        $form = $this->createForm(UserProfileFormType::class, $user);

        // Handle form submission
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Update user profile information
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect back to the profile page after updating
            return $this->redirectToRoute('profile');
        }

        // Render the form template
        return $this->render('updati.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}