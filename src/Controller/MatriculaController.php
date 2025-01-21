<?php

namespace App\Controller;

use App\Entity\Matricula;
use App\Entity\Usuario;
use App\Form\MatriculaType;
use App\Repository\MatriculaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/matricula')]
final class MatriculaController extends AbstractController
{
    #[Route(name: 'app_matricula_index', methods: ['GET'])]
    public function index(MatriculaRepository $matriculaRepository, Request $request, SessionInterface $session): Response
    {
        // Obtener el usuario logueado desde la sesión
        $usuarioId = $session->get('usuario_id');

        if (!$usuarioId) {
            $this->addFlash('error', 'Debes iniciar sesión para ver tus matrículas.');
            return $this->redirectToRoute('app_login');
        }

        // Filtrar las matrículas por usuario logueado
        $matriculas = $matriculaRepository->findBy(['Usuario' => $usuarioId]);

        return $this->render('matricula/index.html.twig', [
            'matriculas' => $matriculas,
        ]);
    }

    #[Route('/listamatricula', name: 'app_lista_index', methods: ['GET'])]
    public function lista(MatriculaRepository $matriculaRepository): Response
    {
        $matriculas = $matriculaRepository->findAllWithCurso();

        return $this->render('matricula/lista.html.twig', [
            'matriculas' => $matriculas,
        ]);
    }



    #[Route('/new', name: 'app_matricula_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Recuperar el ID del usuario logueado desde la sesión
        $usuarioId = $session->get('usuario_id');

        if (!$usuarioId) {
            $this->addFlash('error', 'Debes iniciar sesión para crear una matrícula.');
            return $this->redirectToRoute('app_login');
        }

        // Buscar al usuario en la base de datos
        $usuario = $entityManager->getRepository(Usuario::class)->find($usuarioId);

        if (!$usuario) {
            $this->addFlash('error', 'Usuario no encontrado.');
            return $this->redirectToRoute('app_login');
        }

        // Crear una nueva matrícula y asociarla al usuario
        $matricula = new Matricula();
        $matricula->setUsuario($usuario);
        $matricula->setFechaInscripcion(new \DateTime()); // Fecha y hora actuales

        $form = $this->createForm(MatriculaType::class, $matricula);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($matricula);
            $entityManager->flush();

            $this->addFlash('success', 'Matrícula creada exitosamente.');
            return $this->redirectToRoute('app_matricula_index');
        }

        return $this->render('matricula/new.html.twig', [
            'matricula' => $matricula,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_matricula_show', methods: ['GET'])]
    public function show(Matricula $matricula): Response
    {
        return $this->render('matricula/show.html.twig', [
            'matricula' => $matricula,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_matricula_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Matricula $matricula, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MatriculaType::class, $matricula);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_matricula_index');
        }

        return $this->render('matricula/edit.html.twig', [
            'matricula' => $matricula,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_matricula_delete', methods: ['POST'])]
    public function delete(Request $request, Matricula $matricula, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $matricula->getId(), $request->get('_token'))) {
            $entityManager->remove($matricula);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_matricula_index');
    }
}
