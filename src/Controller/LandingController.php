<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LandingController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('landing.html.twig');
    }

    #[Route('/features', name: 'features')]
    public function features(): Response
    {
        return $this->render('features.html.twig');
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(Request $request, SessionInterface $session): Response
    {
        $error = null;

        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $password = $request->request->get('password');

            $users = [
                'admin' => 'admin',
                'user' => 'user'
            ];

            if (isset($users[$username]) && $users[$username] === $password) {
                $session->set('user', $username);
                return $this->redirectToRoute('dashboard');
            } else {
                $error = "Invalid username or password";
            }
        }

        return $this->render('login.html.twig', [
            'error' => $error
        ]);
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(SessionInterface $session): Response
    {
        $user = $session->get('user');
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        return $this->render('dashboard.html.twig', ['user' => $user]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('home');
    }
}
