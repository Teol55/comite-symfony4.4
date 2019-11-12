<?php

namespace App\Controller;

use App\Entity\Tool;
use App\Entity\User;
use App\Form\ToolFormType;
use App\Form\UserFormType;
use App\Repository\ToolRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ToolAdminController extends AbstractController
{
    /**
     * @Route("/tool/new", name="admin_tool_new")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $em,Request $request)
    {
        $form=$this->createForm(ToolFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var Tool $tool */
            $tool=$form->getData();
            $em->persist($tool);
            $em->flush($tool);
            $this->addFlash('success','L\'outils a bien été ajouté.');
            return $this->redirectToRoute('admin_tool_list');
        }

        return $this->render('tool_admin/new.html.twig', [
            'toolForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tool/list",name="admin_tool_list")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function list(ToolRepository $toolRepo){
        $tools=$toolRepo->findAll();
        return $this->render('tool_admin/list.html.twig',[
            'tools'=> $tools
        ]);



    }

    /**
     * @Route("/admin/tool/{id}/edit", name="admin_tool_edit")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function edit(Tool $tool, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ToolFormType::class,$tool);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Tool $tool */
            $tool = $form->getData();
            $em->persist($tool);
            $em->flush();
            $this->addFlash('success', 'Le matériel a bien été modifié');
            return $this->redirectToRoute('admin_tool_list', [
                'id' => $tool->getId(),
            ]);
        }

        return $this->render('tool_admin/edit.html.twig', [
            'toolForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/admin/tool/delete/{id}", name="admin_tool_delete")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function delete(Tool $tool, Request $request, EntityManagerInterface $em)
    {
        /** @var Tool $tool*/
        $em->remove($tool);
        $em->flush();
        $this->addFlash('success', 'L\'a bien été supprimé');
        return $this->redirectToRoute('admin_tool_list', [

        ]);

    }
}
