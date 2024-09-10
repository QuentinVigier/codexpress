<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NoteController extends AbstractController
{
    // note
    // Route dédié à l'affichage d'une seule note
    #[Route('/note/{slug}', name: 'app_note')]
    public function show(NoteRepository $notes, string $slug): Response
    {
        return $this->render('note/note.html.twig', [
            'note'=>$notes->find(['slug'=>$slug]),
        ]);
    }
    // notes
    // Route dédié à l'affichage de toutes les notes
    #[Route('/notes', name: 'app_notes')]
    public function all(NoteRepository $notes): Response
    {
        return $this->render('note/all.html.twig', [
            'notes'=>$notes->findAll(),
        ]);
    }
    // notes
    // Route dédié à l'affichage des notes de l'utilisateur connecté
    #[Route('/my-notes', name: 'app_my_notes')]
    public function myNotes(): Response
    {
        return $this->render('note/my-notes.html.twig', [
        ]);
    }

}
