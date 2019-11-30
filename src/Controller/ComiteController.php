<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Pvce;
use App\Form\ContactFormType;
use App\Repository\ArticleRepository;
use App\Repository\EventRepository;
use App\Repository\PartnerRepository;
use App\Repository\PvceRepository;
use App\Repository\TicketRepository;
use App\Repository\ToolRepository;
use App\Repository\UserRepository;
use App\Service\CartService;
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
    public function index(ArticleRepository $articleRepos,PvceRepository $pvceRepository,CartService $cart)
    {

        $pvce=$pvceRepository->findOneBy([], ['id' => 'desc']);
        $articles=$articleRepos->findIsPublish();
        return $this->render('comite/index.html.twig', [
            'articles' => $articles,
            'items'=>  $cart->showItems(),
            'pvce' => $pvce
        ]);
    }

    /**
     * @Route("/articles/{slug}",name="app_articles")
     */
    public function show(Article $article, CartService $cart)
    {
        return $this->render('comite/show.html.twig', [
                'article' => $article,
            'items'=>  $cart->showItems(),
            ]);
    }


    /**
     * @Route("/PvCSE/{id}",name="app_PvCSE")
     */
    public function PvCE(Pvce $pvce,PvceRepository $pvceRepository,CartService $cart)
    {

        $pvces=$pvceRepository->findAll();
        return $this->render('comite/PvCSE.html.twig', [
            'pvce'=> $pvce,
            'pvces'=>$pvces,
            'items'=>  $cart->showItems(),
        ]);
    }
    /**
     * @Route("/partenaires",name="app_partenaire")
     */
    public function partenaire (PartnerRepository $repository,CartService $cart)

    {
        $partners=$repository->findAll();
        return $this->render('comite/partner.html.twig', [
            'title' => 'Nos Partenaires',
            'partners'=> $partners,
            'items'=>  $cart->showItems(),
        ]);
    }
    /**
     * @Route("/outillage",name="app_tool")
     */
    public function tool (ToolRepository $repository, CartService $cart)
    {
        $tools=$repository->findAll();
        return $this->render('comite/tool.html.twig', [
            'title'=> 'Location de Materiel',
            'tools' => $tools,
            'items'=>  $cart->showItems(),

        ]);
    }
    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact(Request $request,\Swift_Mailer $swift_Mailer,CartService $cart)
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
            'title'=>'Contact',
            'items'=>  $cart->showItems(),

        ]);
    }
    /**
     * @Route("/team", name="app_team")
     */
    public function team(UserRepository $userRepository,CartService $cart)
    {
        $cgtUsers=$userRepository->findby(['isComite'=>1,'syndicat'=>'cgt'], ['forname' => 'asc']);
        $cfeUsers=$userRepository->findby(['isComite'=>1,'syndicat'=>'cfe'], ['forname' => 'asc']);
        $sansUsers=$userRepository->findby(['isComite'=>1,'syndicat'=>'sans'], ['forname' => 'asc']);

        return $this->render('comite/team.html.twig', [
            'cgtUsers' => $cgtUsers,
            'cfeUsers' => $cfeUsers,
            'sansUsers' => $sansUsers,


            'title'=> 'Notre Equipe',
            'items'=>  $cart->showItems(),
        ]);
    }
    /**
     * @Route("/evenements",name="app_event")
     */
    public function Event(EventRepository $repository,CartService $cart)
    {
        $events= $repository->findAll();
        return $this->render('comite/event.html.twig', [
            'title' => 'Evénements Battants',
            'events'=> $events ,
            'items'=>  $cart->showItems(),
            ]);
    }
}


