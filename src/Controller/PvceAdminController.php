<?php

namespace App\Controller;

use App\Entity\Pvce;

use App\Form\PvceFormType;
use App\Repository\PvceRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Tests\Compiler\NotWireable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PvceAdminController extends AbstractController
{
    /**
     * @Route("/pvcse/new", name="admin_pvcse_new")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $em,Request $request,UploaderHelper $uploaderHelper)
    {

        $form = $this->createForm(PvceFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Pvce $pvce */
            $pvce=$form->getData();
            $pvce->setPublishedAt(NEW \DateTime());

            /** @var UploadedFile $uploadFile */
            $uploadedFile=$form['pdfFile']->getData();

            if($uploadedFile) {
                $newFilename=$uploaderHelper->uploadPvcePdf($uploadedFile,$pvce->getPdfFilename());

                $pvce->setPdfFilename($newFilename);
            }

            $em->persist($pvce);
            $em->flush();

            $this->addFlash('success','le document a bien été enregistré');
            return $this->redirectToRoute('admin_pvcse_edit', [
                'id' => $pvce->getId(),
            ]);

        }

        return $this->render('pvce_admin/new.html.twig', [
            'pvceForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/admin/pvcse", name="admin_pvcse_list")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function list(PvceRepository $pvceRepository)
    {
        $pvcses = $pvceRepository->findAll();

        return $this->render('pvce_admin/list.html.twig', [
            'pvcses' => $pvcses,
        ]);
    }


    /**
     * @Route("/admin/pvcse/{id}/edit", name="admin_pvcse_edit")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function edit(Pvce $pvce, Request $request, EntityManagerInterface $em,UploaderHelper $uploaderHelper)
    {
        $form = $this->createForm(PvceFormType::class,$pvce);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Pvce $pvce */
            $pvce= $form->getData();

            /** @var UploadedFile $uploadFile */
            $uploadedFile=$form['pdfFile']->getData();

            if($uploadedFile) {
                $newFilename=$uploaderHelper->uploadPvcePdf($uploadedFile,$pvce->getPdfFilename());

                $pvce->setPdfFilename($newFilename);
            }

            $em->persist($pvce);
            $em->flush();
            $this->addFlash('success', 'Le PV a bien été modifié');
            return $this->redirectToRoute('admin_pvcse_edit', [
                'id' => $pvce->getId(),
            ]);
        }

        return $this->render('pvce_admin/edit.html.twig', [
            'pvceForm' => $form->createView(),
            'pvce' => $pvce,
        ]);
    }

    /**
     * @Route("/admin/pvcse/delete/{id}", name="admin_pvcse_delete")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function delete(Pvce $pvce, Request $request, EntityManagerInterface $em)
    {
        /** @var Pvce $pvce */
        $em->remove($pvce);
        $em->flush();
        $this->addFlash('success', 'Le PV a bien été supprimé');
        return $this->redirectToRoute('admin_pvcse_list', [
            'id' => $pvce->getId(),
        ]);

    }
}

