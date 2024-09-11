<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/notes')] // Préfixe pour les routes du controller
class NoteController extends AbstractController
{
    #[Route('/', name: 'app_note_all', methods: ['GET'])]
    public function all(NoteRepository $nr): Response
    {
        $allNotes = $nr->findBy(
            ['is_public' => true], ['created_at' =>'DESC']); 

        return $this->render('note/all.html.twig', [
            'allNotes' => $allNotes,
        ]);
    }

    #[Route('/{slug}', name: 'app_note_show', methods: ['GET'])]
    public function show(string $slug, NoteRepository $nr): Response
    {
        return $this->render('note/show.html.twig', [
            'note' => $nr->findOneBySlug($slug),
        ]);
    }

    #[Route('/{username}', name: 'app_note_user', methods: ['GET'])]
    public function userNotes(
        string $username, 
        UserRepository $user,
        ): Response
    {
        $creator = $user->findOneByUsername($username);  // Récupère l'utilisateur en cours
        return $this->render('note/show.html.twig', [
            'creator' => $creator,  // Envoie l'utilisateur en cours à la vue Twig
            'note' => $creator->getNotes(),  // Récupère les notes de l'utilisateur en cours
        ]);
    }

    #[Route('/new', name: 'app_note_new', methods: ['GET', 'POST'])]
    public function new(): Response
    {
        return $this->render('note/new.html.twig', [
        ]);
    }

    #[Route('/edit/{slug}', name: 'app_note_edit', methods: ['GET', 'POST'])]
    public function edit(string $slug, NoteRepository $nr): Response
    {
        $note = $nr->findOneBySLug($slug); // Recherche de la note à modifier
        return $this->render('note/edit.html.twig', [
        ]);
    }

    #[Route('/delete/{slug}', name: 'app_note_delete', methods: ['POST'])]
    public function delete(string $slug, NoteRepository $nr): Response
    {
        $note = $nr->findOneBySLug($slug); // Recherche de la note à supprimer
        $this->addFlash('success', 'Your code note has been deleted.'); // Affiche un message de succès
        return $this->redirectToRoute(('app_note_user'));
    }
}
