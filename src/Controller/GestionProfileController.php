<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GestionProfileController extends AbstractController
{
    
    #[Route('/profile/3', name: 'profile')]
    public function index(Security $security): Response
    {
        if ($security->getUser() !== null) {
        // Assuming you have logic to retrieve the user from your database
        $user = $this->getUser();

        return $this->render('GestionUser/profile.html.twig', [
            'user' => $user,
        ]);}
        else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/update', name: 'profile_update')]
    public function update(Request $request,ValidatorInterface $validator): Response
    {
        // Retrieve the logged-in user
        $user = $this->getUser();
    
        // Retrieve form data from the request
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $email = $request->request->get('email');
        $motDePasse = $request->request->get('motDePasse');
        $ageString = $request->request->get('age');
    
        // Update user profile information
        $entityManager = $this->getDoctrine()->getManager();
    
        // Update nom
        $user->setNom($nom);
    
        // Update prenom
        $user->setPrenom($prenom);
    
        // Update email
        $user->setEmail($email);
        $errors = $validator->validate($user);

        // Check for validation errors
        if (count($errors) > 0) {
            // Collect error messages
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            // Add flash messages for error messages
            foreach ($errorMessages as $errorMessage) {
                $this->addFlash('error', $errorMessage);
            }

            // Redirect back to the list page
            return $this->redirectToRoute('profile');
        }
    
        // Hash and update password if provided
        if (!empty($motDePasse)) {
            $hashedPassword = password_hash($motDePasse, PASSWORD_DEFAULT);
            $user->setMotDePasse($hashedPassword);
        }

        // Update age
        $age = new DateTime($ageString);
        $user->setAge($age);
    
        // Handle file upload for the profile image
        $imageFile = $request->files->get('coverImage');
       
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
}    