<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ChatBotController extends AbstractController
{
    #[Route('/chatbot', name: 'app_chat_bot')]
    public function index(Request $request,Security $security): Response
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        // Get the submitted message from the textarea
        $userMessage = $request->request->get('message');
    
        // If a message is submitted, send it to the chat bot API
        $response = null;
        if ($userMessage) {
            // Check if the message contains bad content
            if ($this->isBadContent($userMessage)) {
                // If bad content is detected, return a warning message
                $response = "Warning: Your message contains inappropriate language. Please refrain from using bad words.";
            } else {
                // If no bad content is detected, proceed with sending the message to the chatbot API
                $response = $this->sendMessageToChatBot($userMessage,$security);
            }
        }
    
        return $this->render('chat_bot/index.html.twig', [
            'controller_name' => 'ChatBotController',
            'response' => $response,
        ]);
    }

    
    private function sendMessageToChatBot(string $message,Security $security): string
    {
        if ($security->getUser() === null) {
            // Redirect anonymous users to the login page
            return $this->redirectToRoute('app_login');
        }
        // Make the API request
        $client = new Client([
            'verify' => false, // Disable SSL verification
            'headers' =>  [
                'X-RapidAPI-Host' => 'open-ai21.p.rapidapi.com',
                'X-RapidAPI-Key' => '2863f566dfmsh8cabef06f5e9852p145b49jsncc50a6d8a39c',
                'content-type' => 'application/json',
            ],
        ]);
        
        $response = $client->request('POST', 'https://open-ai21.p.rapidapi.com/chatgpt', [
            'body' => '{
            "messages": [
                {
                    "role": "user",
                    "content": "' . $message . '"
                }
            ],
            "web_access": false
        }',
            'headers' =>  [
                'X-RapidAPI-Host' => 'open-ai21.p.rapidapi.com',
                'X-RapidAPI-Key' => '2863f566dfmsh8cabef06f5e9852p145b49jsncc50a6d8a39c',
                'content-type' => 'application/json',
            ],
        ]);
        
        // Decode the JSON response
        $responseData = json_decode($response->getBody(), true);
    
        
        // Extract text from the response
        $text = isset($responseData['result']) ? $responseData['result'] : '';
        
        
        // Return the extracted text
        return $text;
    }
    
        private function isBadContent($content)
    {
        $client = new Client([
            'verify' => false,
            'headers' => [
                
                'X-RapidAPI-Host' => 'neutrinoapi-bad-word-filter.p.rapidapi.com',
                'X-RapidAPI-Key' => '2863f566dfmsh8cabef06f5e9852p145b49jsncc50a6d8a39c',
                'content-type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        $response = $client->request('POST', 'https://neutrinoapi-bad-word-filter.p.rapidapi.com/bad-word-filter', [
            'form_params' => [
                'content' => $content,
                'censor-character' => '*'
            ]
        ]);

        $body = json_decode($response->getBody(), true);

        // Check if the response indicates bad content
        return $body['is-bad'];
    }
    
    
}