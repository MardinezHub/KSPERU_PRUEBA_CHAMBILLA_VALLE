<?php

namespace App\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

final class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            // Obtener los datos enviados desde el formulario
            $dni = $request->request->get('dni');
            $contrasenia = $request->request->get('contrasenia');

            // Buscar el usuario en la base de datos
            $usuario = $entityManager->getRepository(Usuario::class)
                ->findOneBy(['dni' => $dni, 'contrasenia' => $contrasenia]);

            if ($usuario) {
                // Guardar la ID del usuario en la sesión
                $session->set('usuario_id', $usuario->getId());
                $session->set('usuario_nombre', $usuario->getNombre());
                
                // Validar el rol del usuario y redirigir
                $rolId = $usuario->getRol()->getId();

                if ($rolId === 1) {
                    return $this->redirectToRoute('app_curso_index');
                } elseif ($rolId === 2) {
                    return $this->redirectToRoute('app_matricula_index', [
                        'usuarioId' => $usuario->getId(),
                    ]);
                }
            }

            // Agregar un mensaje de error si las credenciales son incorrectas
            $this->addFlash('error', 'DNI o contraseña incorrectos.');
        }

        // Renderizar el formulario de login
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(SessionInterface $session): Response
    {
        // Limpiar los datos de la sesión
        $session->clear();

        // Redirigir al login
        return $this->redirectToRoute('app_login');
    }
}
