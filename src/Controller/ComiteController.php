<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Pvce;
use App\Form\ContactFormType;
use App\Repository\ArticleRepository;
use App\Repository\PartnerRepository;
use App\Repository\PvceRepository;
use App\Repository\TicketRepository;
use App\Repository\ToolRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ComiteController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class ComiteController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(ArticleRepository $articleRepos,PvceRepository $pvceRepository)
    {
        $pvce=$pvceRepository->findOneBy([], ['id' => 'desc']);
        $articles=$articleRepos->findIsPublish();
        return $this->render('comite/index.html.twig', [
            'articles' => $articles,
            'pvce' => $pvce
        ]);
    }

    /**
     * @Route("/articles/{slug}",name="app_articles")
     */
    public function show(Article $article)
    {
        return $this->render('comite/show.html.twig', [
                'article' => $article,]);
    }
    /**
     * @Route("/billetterie",name="app_billetterie")
     */
    public function Ticket(TicketRepository $repository)
    {
        $tickets= $repository->findAll();
        return $this->render('comite/ticket.html.twig', [
            'title' => 'Billetterie Battants',
            'tickets'=> $tickets ,]);
    }

    /**
     * @Route("/PvCSE/{id}",name="app_PvCSE")
     */
    public function PvCE(Pvce $pvce,PvceRepository $pvceRepository)
    {

        $pvces=$pvceRepository->findAll();
        return $this->render('comite/PvCSE.html.twig', [
            'pvce'=> $pvce,
            'pvces'=>$pvces,
        ]);
    }
    /**
     * @Route("/partenaires",name="app_partenaire")
     */
    public function partenaire (PartnerRepository $repository)

    {
        $partners=$repository->findAll();
        return $this->render('comite/partner.html.twig', [
            'title' => 'Nos Partenaires',
            'partners'=> $partners,
        ]);
    }
    /**
     * @Route("/outillage",name="app_tool")
     */
    public function tool (ToolRepository $repository)
    {
        $tools=$repository->findAll();
        return $this->render('comite/tool.html.twig', [
            'title'=> 'Location de Materiel',
            'tools' => $tools
        ]);
    }
    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact(Request $request,\Swift_Mailer $swift_Mailer)
    {


        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formMessage=$form->getData();


                $message = (new \Swift_Message('Confirmation de Commande'))
                    ->SetFrom($formMessage["emailContact"])
                    ->setTo('m.ch5500@gmail.com')
                    ->setBody($this->renderView('comite/emailContact.html.twig',
                        ['message' => $formMessage['messageContact'],
                            'nom'=>$formMessage['nameContact'],
                            'email'=>$formMessage['emailContact']
                        ]), 'text/html');
                $swift_Mailer->send($message);



            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('app_homepage',[

            ]);
        }

        return $this->render('comite/contact.html.twig', [
            'contactForm' => $form->createView(),
            'title'=>'Contact'

        ]);
    }

}


