<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateurs;
use App\Form\UserRegistrationFormType;
use App\Form\ResetPasswordFormType;
use App\Form\LoginFormType;
use App\Form\verifcode;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\LogicException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;



class UserController extends AbstractController
{


    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security,Request $request,ValidatorInterface $validator)
    {

        // Check if the user is already authenticated
        if ($security->getUser() !== null) {
            // User is already authenticated, redirect based on their role
           
                return $this->redirectToRoute('Home');
     
            
        }
         // Create the login form
        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Manually validate the reCAPTCHA field
            $captchaData = $request->request->get('login')['recaptcha'];
            $errors = $validator->validate($captchaData, new RecaptchaTrue());
    
            // If reCAPTCHA validation fails, return an error
            if (count($errors) > 0) {
                $this->addFlash('error', 'reCAPTCHA validation failed. Please try again.');
                return $this->redirectToRoute('login'); // Redirect back to the login page
            }}


        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Render the login form
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'form' => $form->createView(), 
            'error' => $error,

        ]);}


    
    
    private $tokenGenerator;

    public function __construct(TokenGeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    
    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(Request $request, LogoutSuccessHandlerInterface $logoutSuccessHandler): Response
    {
        // Clear the user's session
        $this->get('session')->invalidate();

        // Optionally perform additional tasks (e.g., logging)

        // Redirect to a specific page after logout
        return $logoutSuccessHandler->onLogoutSuccess($request);
    }





    #[Route('/forgetpassword', name: 'forgot_password')]
    public function password2(Request $request, \Swift_Mailer $mailer, Security $security): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
         if ($this->isValidEmail($email)) {
    
            // Assuming you have logic to retrieve the user from your database by email
            $userRepository = $this->getDoctrine()->getRepository(Utilisateurs::class);
            $user = $userRepository->findOneBy(['email' => $email]);
    
            if ($user !== null) {
                // Generate a random verification code
                $verificationCode = $this->generateVerificationCode();
    
                // Store the verification code in the database
                $user->setVerificationCode($verificationCode);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
    
                // Send the verification code to the user via email
                $this->sendVerificationCodeByEmail($user, $mailer);
    
                return $this->redirectToRoute('verify_code', ['email' => $email]);
            } else {
                $error = 'User with this email does not exist.';
            }
        } else {
            $error = 'Invalid email format.';
        }
        }
    
        // Render the template normally
        return $this->render('security/forget.html.twig', [
            'error' => $error ?? null,
        ]);
    }

    private function isValidEmail(string $email): bool
    {
        // Regular expression for basic email format validation
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    
        // Perform regex match
        return preg_match($pattern, $email) === 1;
    }

    // Function to generate a random verification code
    private function generateVerificationCode(): string
    {
        $numericCode = '';

    // Generate 10 random digits
    for ($i = 0; $i < 10; $i++) {
        $numericCode .= mt_rand(0, 9);
    }

    return $numericCode;
}
    

    // Function to send the verification code to the user via email
    private function sendVerificationCodeByEmail(Utilisateurs $user, \Swift_Mailer $mailer): void
    {
        // Create and send an email containing the verification code
        $message = (new \Swift_Message('Password Reset Verification Code'))
            ->setFrom('Youssefharrabi99@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'security/verification_code.html.twig',
                    ['verificationCode' => $user->getVerificationCode()]
                ),
                'text/html'
            );

        $mailer->send($message);
    }

    #[Route('/verify-code/{email}', name: 'verify_code')]
public function verifyCode(Request $request, string $email): Response
{
    // Create an instance of the VerificationCodeFormType form
    $form = $this->createForm(verifcode::class);

    // Handle form submission
    $form->handleRequest($request);

    // Check if the form is submitted and valid
    if ($form->isSubmitted() && $form->isValid()) {
        // Get the data submitted in the form
        $formData = $form->getData();

        // Get the verification code entered by the user
        $enteredCode = $formData['verificationCode'];

        // Retrieve the user from the database using their email
        $userRepository = $this->getDoctrine()->getRepository(Utilisateurs::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        // Check if the entered verification code matches the one stored in the database
        if ($user && $user->getVerificationCode() === $enteredCode) {
            // If the verification code matches, redirect the user to the password reset page
            return $this->redirectToRoute('reset_password', ['email' => $email]);
        } else {
            // If the verification code does not match, render the template with an error message
            return $this->render('security/verifcode.html.twig', [
                'form' => $form->createView(),
                'error' => 'Invalid verification code. Please try again.',
            ]);
        }
    }

    // Render the template with the form
    return $this->render('security/verifcode.html.twig', [
        'form' => $form->createView(),
    ]);}

    #[Route('/reset-password/{email}', name: 'reset_password')]
public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, string $email): Response
{
    // Retrieve the user from the database using their email
    $userRepository = $this->getDoctrine()->getRepository(Utilisateurs::class);
    $user = $userRepository->findOneBy(['email' => $email]);

    // Check if the user exists
    if (!$user) {
        // Handle the scenario where the user with the provided email doesn't exist
        // You can display an error message or redirect the user to a registration page
        return $this->redirectToRoute('forgot_password', ['email' => $email]);
    }

    // Create a form for resetting the password
    $form = $this->createForm(ResetPasswordFormType::class);

    // Handle form submission
    $form->handleRequest($request);

    // Check if the form is submitted and valid
    if ($form->isSubmitted() && $form->isValid()) {
        // Get the data submitted in the form
        $formData = $form->getData();

        // Get the new password entered by the user
       // Get the new password entered by the user
        $newPassword = $formData['password'];

        // Get the confirmation password entered by the user
      
        // Check if the passwords match


        // Encode the new password
        $encodedPassword = $passwordEncoder->encodePassword($user, $newPassword);

        // Set the new password for the user
        $user->setMotDePasse($encodedPassword);

        // Clear the verification code as it's no longer needed
        $user->setVerificationCode(null);

        // Update the user in the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // Redirect the user to a success page or login page
        return $this->redirectToRoute('app_login');
    }

    // Render the template with the form
    return $this->render('security/reset_password.html.twig', [
        'form' => $form->createView(),
     
    ]);
}



    // #[Route('/login', name: 'app_login')]
    // public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    // {

    //     dump($request->request->all());

    //     // If user is already authenticated, redirect to homepage or other appropriate route
    //     if ($this->getUser()) {
    //         return $this->redirectToRoute('ListeUsers'); // Change 'dashboard' to the appropriate route name
    //     }

    //     // Create a form instance using your LoginFormType
    //     $form = $this->createForm(LoginFormType::class);

    //     // Handle form submission
    //     $form->handleRequest($request);


    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Retrieve the data from the form
    //         $formData = $form->getData();

    //         // Make sure the name matches the form field name
    //         $email = $formData['email']; // Ensure the email is retrieved correctly

    //         // You should check if $email is not null before proceeding
    //         if ($email !== null) {
    //             $userRepository = $this->getDoctrine()->getRepository(Utilisateurs::class);
    //             $user = $userRepository->findOneBy(['email' => $email]);

    //             if ($user) {
    //                 // Authentication logic
    //                 $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
    //                 $this->get('security.token_storage')->setToken($token);

    //                 // Dispatch the login event
    //                 $event = new InteractiveLoginEvent($request, $token);
    //                 $this->get('event_dispatcher')->dispatch($event);

    //                 // Redirect the user after successful authentication
    //                 return $this->redirectToRoute('ListeUsers');
    //             } else {
    //                 // Handle authentication failure, e.g., display error message
    //                 $this->addFlash('error', 'Invalid email or password.');
    //             }
    //         } else {
    //             // Handle the case where email is null
    //             $this->addFlash('error', 'Email field is required.');
    //         }
    //     }

    //     // Get the login error if there is one
    //     $error = $authenticationUtils->getLastAuthenticationError();

    //     // Last username entered by the user
    //     $lastUsername = $authenticationUtils->getLastUsername();

    //     return $this->render('GestionUser/login.html.twig', [
    //         'form' => $form->createView(),
    //         'last_username' => $lastUsername,
    //         'error' => $error,
    //     ]);
    // }        



    /**#[Route('/', name: 'login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils, UserPasswordEncoderInterface $passwordEncoder): Response
    {
          // Check if user is authenticated
          if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('Home'); // Redirect to dashboard or home page
        }
        // Create the login form
        $form = $this->createForm(LoginFormType::class);

        // Get the error (if any) from the authentication attempt
        $error = $authenticationUtils->getLastAuthenticationError();

        // Get the last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // If it's a POST request, handle form submission
        if ($request->isMethod('POST')) {
            // Handle form submission and authentication here
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Retrieve the submitted data
                $formData = $form->getData();

                // Get the email and password from the form data
                $email = $formData['email'];
                $password = $formData['password'];

                // Find the user by email in the database
                $user = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['email' => $email]);

                // If user found and password matches
                if ($user && password_verify($password, $user->getMotDePasse())) {
                    // Authentication successful, redirect to index page
                    // You may need to handle this part according to your application's logic
                    return $this->redirectToRoute('Home');
                } else {
                    // Authentication failed, add flash message or handle accordingly
                    $this->addFlash('error', 'Invalid email or password');
                }
            }
        }

        // Render the login form
        return $this->render('GestionUser/login.html.twig', [
            'form' => $form->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
      */

    #[Route('/register', name: 'user_register')]
    public function register(Request $request, Security $security): Response
    {
        if ($security->getUser() !== null) {
            // User is already authenticated, redirect based on their role
            $roles = $security->getUser()->getRoles();
            if (in_array('ROLE_ADMIN', $roles, true)) {
                return $this->redirectToRoute('Home');
            } else {
                return $this->redirectToRoute('Home2');
            }
        }
        $user = new Utilisateurs();
        $user->setRole('ROLE_USER'); // Set the role to 'admin'

        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $imageFile = $form->get('photoFile')->getData();
            
     
            $entityManager = $this->getDoctrine()->getManager();
            // Check if email already exists
            $existingUser = $entityManager->getRepository(Utilisateurs::class)->findOneByEmail($user->getEmail());
            if ($existingUser !== null) {
                // Email already exists, add error to the form
                $form->get('email')->addError(new FormError('This email is already registered.'));
                return $this->render('GestionUser/register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
          
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
            // Hash the password
            $hashedPassword = password_hash($user->getMotDePasse(), PASSWORD_DEFAULT);
            $user->setMotDePasse($hashedPassword);

            // Handle form submission, e.g., saving to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to a success page or any other action
            return $this->redirectToRoute('app_login');
        }

        return $this->render('GestionUser/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/reg', name: 'user_regi')]
    public function regi(Request $request, Security $security): Response
    {
        if ($security->getUser() !== null) {
            // User is already authenticated, redirect based on their role
            $roles = $security->getUser()->getRoles();
            if (in_array('ROLE_ADMIN', $roles, true)) {
                return $this->redirectToRoute('Home');
            } else {
                return $this->redirectToRoute('Home2');
            }
        }
        $user = new Utilisateurs();
        $user->setRole('ROLE_USER'); // Set the role to 'admin'

        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $imageFile = $form->get('photoFile')->getData();
            
           
            $entityManager = $this->getDoctrine()->getManager();
            // Check if email already exists
            $existingUser = $entityManager->getRepository(Utilisateurs::class)->findOneByEmail($user->getEmail());
            if ($existingUser !== null) {
                // Email already exists, add error to the form
                $form->get('email')->addError(new FormError('This email is already registered.'));
                return $this->render('testimage.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
          
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
            // Hash the password
            $hashedPassword = password_hash($user->getMotDePasse(), PASSWORD_DEFAULT);
            $user->setMotDePasse($hashedPassword);

            // Handle form submission, e.g., saving to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to a success page or any other action
            return $this->redirectToRoute('app_login');
        }

        return $this->render('testimage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/Liste', name: 'ListeUsers')]
    public function list(Request $request, Security $security): Response
    {

        // User is already authenticated, redirect based on their role
        $roles = $security->getUser()->getRoles();
        if (in_array('ROLE_USER', $roles, true)) {
            return $this->redirectToRoute('Home2');
        } else {
            // Fetch user data from the database
            $userRepository = $this->getDoctrine()->getRepository(Utilisateurs::class);
            $users = $userRepository->findAll();

            // Render the template with user data
            return $this->render('GestionUser/TableUsers.html.twig', [
                'users' => $users,
            ]);
        }

    }



    #[Route('/update/user/{id}', name: 'update_user', methods: ['POST'])]
    public function updateUser(Request $request, int $id, ValidatorInterface $validator, Security $security): Response
    {
        // Check if the user is authenticated
        if ($security->getUser() !== null) {
            // User is authenticated
            $roles = $security->getUser()->getRoles();

            // Check user role
            if (in_array('ROLE_USER', $roles, true)) {
                // Redirect authenticated users with ROLE_USER to HomeUser page
                return $this->redirectToRoute('HomeUser');
            } else {
                // Fetch user data from the database
                $entityManager = $this->getDoctrine()->getManager();
                $userRepository = $entityManager->getRepository(Utilisateurs::class);
                $user = $userRepository->find($id);

                // Update user data based on form submission
                $user->setNom($request->request->get('nom'));
                $user->setPrenom($request->request->get('prenom'));
                $newEmail = $request->request->get('email');

                // Check if the new email is different from the existing email
                if ($newEmail !== $user->getEmail()) {
                    // Email has been changed, check if it already exists
                    $existingUser = $userRepository->findOneBy(['email' => $newEmail]);
                    if ($existingUser !== null) {
                        // Email already exists, add flash message and redirect
                        $this->addFlash('error', 'This email is already registered.');
                        return $this->redirectToRoute('ListeUsers');
                    }
                }

                $user->setEmail($newEmail);
                // Convert string to DateTime for the 'age' property
                $ageString = $request->request->get('age');
                $age = new DateTime($ageString); // Create a DateTime object from the string
                $user->setAge($age);

                $user->setRole($request->request->get('role'));
                // Validate the user entity
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
                    return $this->redirectToRoute('ListeUsers');
                }

                // Persist the updated user entity
                $entityManager->persist($user);
                $entityManager->flush();

                // Redirect back to the list page
                return $this->redirectToRoute('ListeUsers');
            }
            } else {
                // If user is not authenticated, handle accordingly
                // You might want to redirect them to a login page or show an error message
                // For now, let's redirect them to the homepage
                return $this->redirectToRoute('app_login');
            }
    }
    #[Route('/delete/{id}', name: 'delete_user', methods: ['POST'])]
    public function deleteUser(Utilisateurs $user, Request $request, Security $security): Response
    {
        // Check if the user is authenticated
        if ($security->getUser() !== null) {
            // User is authenticated
            $roles = $security->getUser()->getRoles();

            // Check user role
            if (in_array('ROLE_USER', $roles, true)) {
                // Redirect authenticated users with ROLE_USER to HomeUser page
                return $this->redirectToRoute('HomeUser');
            } else {
                // Get entity manager
                $entityManager = $this->getDoctrine()->getManager();

                // Remove the user
                $entityManager->remove($user);
                $entityManager->flush();

                // Redirect after deletion
                return $this->redirectToRoute('ListeUsers');
            }
        } else {
            // If user is not authenticated, handle accordingly
            // You might want to redirect them to a login page or show an error message
            // For now, let's redirect them to the homepage
            return $this->redirectToRoute('app_login');
        }
    }

    private function getUsersByAge($entityManager)
{
    // Get current date
    $currentDate = new \DateTime();

    // Get users with birthdays above and below 18
    $above18Users = $entityManager->getRepository(Utilisateurs::class)->createQueryBuilder('u')
        ->select('COUNT(u)')
        ->where('DATE_DIFF(:currentDate, u.age) / 365 >= :age')
        ->setParameter('currentDate', $currentDate)
        ->setParameter('age', 18)
        ->getQuery()
        ->getSingleScalarResult();

    $below18Users = $entityManager->getRepository(Utilisateurs::class)->createQueryBuilder('u')
        ->select('COUNT(u)')
        ->where('DATE_DIFF(:currentDate, u.age) / 365 < :age')
        ->setParameter('currentDate', $currentDate)
        ->setParameter('age', 18)
        ->getQuery()
        ->getSingleScalarResult();

    return [
        'above_18' => $above18Users,
        'below_18' => $below18Users
    ];
}

#[Route('/stat', name: 'stats')]
public function showStats(Request $request): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    // Fetch statistics for users above and below 18
    $userStats = $this->getUsersByAge($entityManager);
 

    // Render the statistics page template
    return $this->render('GestionUser/stat.html.twig', [
        'userStats' => $userStats,
    ]);
}

}

