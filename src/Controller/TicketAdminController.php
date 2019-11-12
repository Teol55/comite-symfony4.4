<?php

namespace App\Controller;

use App\Entity\Ticket;

use App\Form\ArticleFormType;
use App\Form\TicketFormType;
use App\Repository\ArticleRepository;
use App\Repository\TicketRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TicketAdminController extends AbstractController
{
    /**
     * @Route("/ticket/new", name="admin_ticket_new")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $em,Request $request,UploaderHelper $uploaderHelper)
    {

        $form = $this->createForm(TicketFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Ticket $ticket */
            $ticket=$form->getData();

            /** @var UploadedFile $uploadFile */
            $uploadedFile=$form['imageFile']->getData();

            if($uploadedFile) {
                $newFilename=$uploaderHelper->uploadTicketImage($uploadedFile,$ticket->getImage());

                $ticket->setImage($newFilename);
            }

            $em->persist($ticket);
            $em->flush();

            $this->addFlash('success','le billet a bien été enregistré');
            return $this->redirectToRoute('admin_ticket_list', [
                'id' => $ticket->getId(),
            ]);

        }

        return $this->render('ticket_admin/new.html.twig', [
            'ticketForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/ticket/list", name="admin_ticket_list")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function list(TicketRepository $ticketRepository)
    {
        $tickets = $ticketRepository->findAll();

        return $this->render('ticket_admin/list.html.twig', [
            'tickets' => $tickets,
        ]);
    }


    /**
     * @Route("/admin/ticket/{id}/edit", name="admin_ticket_edit")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function edit(Ticket $ticket, Request $request, EntityManagerInterface $em,UploaderHelper $uploaderHelper)
    {
        $form = $this->createForm(TicketFormType::class,$ticket);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Ticket $ticket */
            $ticket = $form->getData();

            /** @var UploadedFile $uploadFile */
            $uploadedFile=$form['imageFile']->getData();

            if($uploadedFile) {
                $newFilename=$uploaderHelper->uploadTicketImage($uploadedFile,$ticket->getImage());

                $ticket->setImage($newFilename);
            }

            $em->persist($ticket);
            $em->flush();
            $this->addFlash('success', 'Le billet a bien été modifié');
            return $this->redirectToRoute('admin_ticket_edit', [
                'id' => $ticket->getId(),
            ]);
        }

        return $this->render('ticket_admin/edit.html.twig', [
            'ticketForm' => $form->createView(),
            'ticket' => $ticket,
        ]);
    }

    /**
     * @Route("/admin/ticket/delete/{id}", name="admin_ticket_delete")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function delete(Ticket $ticket, Request $request, EntityManagerInterface $em)
    {
        /** @var Ticket $ticket*/
        $em->remove($ticket);
        $em->flush();
        $this->addFlash('success', 'Le billet a bien été supprimé');
        return $this->redirectToRoute('admin_ticket_list', [

        ]);

    }
}

