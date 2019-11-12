<?php

namespace App\Controller;

use App\Entity\Article;

use App\Entity\ArticleReference;
use App\Form\ArticleFormType;
use App\Repository\ArticleReferenceRepository;
use App\Repository\ArticleRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/article/new", name="admin_article_new")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $em,Request $request,UploaderHelper $uploaderHelper)
    {

        $form = $this->createForm(ArticleFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Article $article */
            $article=$form->getData();

            /** @var UploadedFile $uploadFile */
            $uploadedFile=$form['imageFile']->getData();

            if($uploadedFile) {
                $newFilename=$uploaderHelper->uploadArticleImage($uploadedFile,$article->getImageFilename());

                $article->setImageFilename($newFilename);
            }

            $em->persist($article);
            $em->flush();

            $this->addFlash('success','le document a bien été enregistré,vous pouvez ajouter des documents');
            return $this->redirectToRoute('admin_article_edit', [
                'id' => $article->getId(),
            ]);

        }

        return $this->render('article_admin/new.html.twig', [
            'articleForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/admin/article", name="admin_article_list")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function list(ArticleRepository $articleRepo)
    {



        $articles = $articleRepo->findAll();

        return $this->render('article_admin/list.html.twig', [
            'articles' => $articles,
        ]);
    }


    /**
     * @Route("/admin/article/{id}/edit", name="admin_article_edit")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $em,UploaderHelper $uploaderHelper)
    {
        $form = $this->createForm(ArticleFormType::class,$article);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Article $article */
            $article = $form->getData();

            /** @var UploadedFile $uploadFile */
            $uploadedFile=$form['imageFile']->getData();

            if($uploadedFile) {
                $newFilename=$uploaderHelper->uploadArticleImage($uploadedFile,$article->getImageFilename());

                $article->setImageFilename($newFilename);
            }

            $em->persist($article);
            $em->flush();
            $this->addFlash('success', 'L\'article a bien été modifié');
            return $this->redirectToRoute('admin_article_edit', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('article_admin/edit.html.twig', [
            'articleForm' => $form->createView(),
            'article' => $article,
        ]);
    }
        /**
         * @Route("/admin/upload/test", name="upload_test")
         */
        public function tempoaryUploadAction(Request $request)
        {
            /** @var UploadedFile $uploadFile */
                $uploadedFile=$request->files->get('image');
                $destination=$this->getParameter('kernel.project_dir').'/public/uploads/articles';

            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move($destination,
                    $newFilename);
        }

    /**
     * @Route("/admin/article/delete/{id}", name="admin_article_delete")
     * IsGranted("ROLE_ADMIN_ARTICLE")
     * @throws \Exception
     */
    public function delete(Article $article, Request $request, EntityManagerInterface $em,UploaderHelper $uploaderHelper)
    {
        /** @var Article $article */
//        $references=$repoReference->findBy(['article'=>$article->getId()]);
//$references=$article->getArticleReferences();

        foreach ($article->getArticleReferences()as $reference){

            /** @var ArticleReference $reference */
            $this->denyAccessUnlessGranted('ROLE_ADMIN_ARTICLE', $article);
            $em->remove($reference);
            $em->flush();

            $uploaderHelper->deleteFile($reference->getFilePath(),false);

        }


            $em->remove($article);
            $em->flush();
            $uploaderHelper->deleteFile($article->getImagePath(),true);
            $this->addFlash('success', 'L\'article a bien été supprimé');
            return $this->redirectToRoute('admin_article_list', [
                'id' => $article->getId(),
            ]);

    }
}

