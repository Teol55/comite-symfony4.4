<?php

namespace App\Controller;

use App\Entity\Partner;

use App\Form\PartnerFormType;


use App\Repository\PartnerRepository;
use App\Repository\TicketRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PartnerAdminController extends AbstractController
{
    /**
     * @Route("/partner/new", name="admin_partner_new")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $em,Request $request,UploaderHelper $uploaderHelper)
    {

        $form = $this->createForm(PartnerFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Partner $partner */
            $partner=$form->getData();

            /** @var UploadedFile $uploadFile */
            $uploadedFile=$form['imageFile']->getData();

            if($uploadedFile) {
                $newFilename=$uploaderHelper->uploadPartnerImage($uploadedFile,$partner->getPathImage());

                $partner->setPathImage($newFilename);
            }

            $em->persist($partner);
            $em->flush();

            $this->addFlash('success','le partenaire a bien été enregistré');
            return $this->redirectToRoute('admin_partner_list', [
                'id' => $partner->getId(),
            ]);

        }

        return $this->render('partner_admin/new.html.twig', [
            'partnerForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/partner/list", name="admin_partner_list")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function list(PartnerRepository $partnerRepository)
    {
        $partners = $partnerRepository->findAll();

        return $this->render('partner_admin/list.html.twig', [
            'partners' => $partners,
        ]);
    }


    /**
     * @Route("/admin/partner/{id}/edit", name="admin_partner_edit")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function edit(Partner $partner, Request $request, EntityManagerInterface $em,UploaderHelper $uploaderHelper)
    {
        $form = $this->createForm(PartnerFormType::class,$partner);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Partner $partner */
            $partner = $form->getData();

            /** @var UploadedFile $uploadFile */
            $uploadedFile=$form['imageFile']->getData();

            if($uploadedFile) {
                $newFilename=$uploaderHelper->uploadPartnerImage($uploadedFile,$partner->getPathImage());

                $partner->setPathImage($newFilename);
            }

            $em->persist($partner);
            $em->flush();
            $this->addFlash('success', 'Le partenaire a bien été modifié');
            return $this->redirectToRoute('admin_partner_edit', [
                'id' => $partner->getId(),
            ]);
        }

        return $this->render('partner_admin/edit.html.twig', [
            'partnerForm' => $form->createView(),
            'partner' => $partner,
        ]);
    }

    /**
     * @Route("/admin/partner/delete/{id}", name="admin_partner_delete")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function delete(Partner $partner, Request $request, EntityManagerInterface $em)
    {
        /** @var Partner $partner*/
        $em->remove($partner);
        $em->flush();
        $this->addFlash('success', 'Le partenaire a bien été supprimé');
        return $this->redirectToRoute('admin_partner_list', [

        ]);

    }
}

