<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 18/02/2019
 * Time: 14:22
 */

namespace App\Service;

use App\Controller\TicketController;
use App\Entity\LigneRequest;
use App\Entity\Request;
use App\Entity\Ticket;
use App\Entity\User;
use App\Repository\TicketRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\IntegerType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



class CartService extends AbstractController
{
    const SESSION_CART = 'cart';

    /**
     * @var \Swift_Mailer
     */
    private $swift_Mailer;
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var TicketRepository
     */
    private $ticketRepository;
    /**
     * @var UserInterface
     */
    private $user;

    public function __construct(\Swift_Mailer $swift_Mailer, ObjectManager $manager, SessionInterface $session,TicketRepository $ticketRepository)
    {

        $this->swift_Mailer = $swift_Mailer;
        $this->manager = $manager;
        $this->session = $session;

        $this->ticketRepository = $ticketRepository;

    }

    public function save(array $items,$total,User $user)
    {
//        if($ticket->getCustomer()->getId()){
//            $ticket->setCustomer($this->manager->merge($ticket->getCustomer()));
//        }
//
//        $ticket->setCreatedAt(new \DateTime());
//        $ticket->setReference($ticket->getCreatedAt()->getTimestamp() . $ticket->getCustomer()->getStripeCustomerId());

    $commande=new Request();
    foreach ($items as $item)
    {
        $ligne=new LigneRequest();
        $ligne->setTicket($item['ticket']);
        $ligne->setNbTicket($item['quantity']);
        $ligne->setPrice($item['ticket']->getPriceCe() * $item['quantity']);
        $this->manager->persist($ligne);
        $commande->addLigneRequest($ligne);
    }
    $commande->setPrice($total);
    $commande->setCreatedAt(new \DateTime());
$commande->setUser($user);

        $this->manager->persist($commande);
        $this->manager->flush();

        return $commande;
    }

    public function sendMessage(Request $commande)
    {
        $message = (new \Swift_Message('Confirmation de Commande'))
            ->SetFrom('m.ch@atipicwebdesign.fr')
            ->setTo($commande->getUser()->getEmail())
            ->setBody($this->renderView('comite/email.html.twig',
                ['commande' => $commande]), 'text/html');
        $this->swift_Mailer->send($message);

    }

    public function findCustomer($adresseEmail)
    {
//        return $this->customerRepository->findOneBy(['adresseEmail'=>$adresseEmail]);

    }

    /**
     * @return Ticket
     */
    public function getCurrentTicket()
    {
//        $ticket = $this->session->get(TicketManager::SESSION_TICKET);
//        if(!$ticket instanceof  Ticket){
//            throw new NotFoundHttpException();
//        }
//        return $ticket;
    }

    /**
     * @return Request
     */
    public function initializeTicket()
    {
        $cart = new Request();
        $this->session->set(CartService::SESSION_CART, $cart);
        return $cart;
    }
    public function closeCart()
    {
        return $this->session->clear();
    }
    public function contactMessage($form)
    {

        $message = (new \Swift_Message('Confirmation de Commande'))
            ->SetFrom($form["emailContact"])
            ->setTo('emaildulouvre@louvre.fr')
            ->setBody($this->renderView('emailContact.html.twig',
                ['message' => $form['messageContact'],
                    'nom'=>$form['nameContact'],
                    'email'=>$form['emailContact']
                ]), 'text/html');
        $this->swift_Mailer->send($message);

    }
    public function addItem(int $id,int $nbTicket)
    {
        $panier=$this->session->get('panier',[]);
        $panier[$id]=$nbTicket;
        $this->session->set('panier',$panier);

    }
    public function removeItem(int $id){
        $panier=$this->session->get('panier',[]);
        if(!empty($panier[$id])){
            unset($panier[$id]);
            $this->session->set('panier',$panier);
        }

    }
    public function showItems(){
        $panier=$this->session->get('panier',[]);
        $panierData=[];

        foreach ($panier as $id => $quantity){
            $panierData[]=[
                'ticket'=>$this->ticketRepository->find($id),
                'quantity'=>$quantity
            ];
        }
        return $panierData;

    }
    public function total():float {
        $total=0;
        foreach ($this->showItems() as $item){
            $total+=$item['ticket']->getPriceCe() * $item['quantity'];

        }
        return $total;
    }


}