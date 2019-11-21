<?php

namespace App\Controller;

use App\Entity\Event;


use App\Form\EventFormType;
use App\Repository\EventRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventAdminController extends AbstractController
{
    /**
     * @Route("/event/new", name="admin_event_new")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $em,Request $request,UploaderHelper $uploaderHelper)
    {

        $form = $this->createForm(EventFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Event $event */
            $event=$form->getData();

            /** @var UploadedFile $uploadFile */
            $uploadedFile=$form['imageFile']->getData();

            if($uploadedFile) {
                $newFilename=$uploaderHelper->uploadEventImage($uploadedFile,$event->getImage());

                $event->setImage($newFilename);
            }

            $em->persist($event);
            $em->flush();

            $this->addFlash('success','le billet a bien été enregistré');
            return $this->redirectToRoute('admin_event_list', [
                'id' => $event->getId(),
            ]);

        }

        return $this->render('event_admin/new.html.twig', [
            'eventForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/event/list", name="admin_event_list")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function list(EventRepository $eventRepository)
    {
        $events = $eventRepository->findAll();

        return $this->render('event_admin/list.html.twig', [
            'events' => $events,
        ]);
    }


    /**
     * @Route("/admin/event/{id}/edit", name="admin_event_edit")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function edit(Event $event, Request $request, EntityManagerInterface $em,UploaderHelper $uploaderHelper)
    {
        $form = $this->createForm(EventFormType::class,$event);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Event $event */
            $event = $form->getData();

            /** @var UploadedFile $uploadFile */
            $uploadedFile=$form['imageFile']->getData();

            if($uploadedFile) {
                $newFilename=$uploaderHelper->uploadEventImage($uploadedFile,$event->getImage());

                $event->setImage($newFilename);
            }

            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Le billet a bien été modifié');
            return $this->redirectToRoute('admin_event_edit', [
                'id' => $event->getId(),
            ]);
        }

        return $this->render('event_admin/edit.html.twig', [
            'eventForm' => $form->createView(),
            'event' => $event,
        ]);
    }

    /**
     * @Route("/admin/event/delete/{id}", name="admin_event_delete")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function delete(Event $event, Request $request, EntityManagerInterface $em)
    {
        /** @var Event $event*/
        $em->remove($event);
        $em->flush();
        $this->addFlash('success', 'L\'événement a bien été supprimé');
        return $this->redirectToRoute('admin_event_list', [

        ]);

    }
}

