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
use App\Repository\RequestRepository;
use App\Repository\TicketRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\IntegerType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Intl\DateFormatter\DateFormat\MonthTransformer;


class CartService extends AbstractController
{
    const SESSION_CART = 'panier';

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
    /**
     * @var RequestRepository
     */
    private $requestRepository;

    public function __construct(\Swift_Mailer $swift_Mailer, ObjectManager $manager, SessionInterface $session,TicketRepository $ticketRepository,RequestRepository $requestRepository)
    {

        $this->swift_Mailer = $swift_Mailer;
        $this->manager = $manager;
        $this->session = $session;

        $this->ticketRepository = $ticketRepository;

        $this->requestRepository = $requestRepository;
    }

    public function save(array $items,$total,User $user)
    {

   $panier=$this->getCurrentCart();
//  données de mon panier premier screen dd($panier);
//  je récupére mes données de mon panier pour créer ma commande
    $order=new Request();
    $order->setPrice($total);
    $order->setUser($user);
    $order->setCreatedAt(new \DateTime());

   $this->manager->persist($order);
   $this->manager->flush();
// Die aprés l'enregistrement qui est bien créé dd($order);


//je récupére les différentes lignes de mon panier pour les passer à ma commande
    foreach ($panier->getLigneRequest() as $ligneRequest)
    {

       //pour décomposer j'ai fait  une methode pour enregistrer les lignes de ma commande
        $ligne=$this->saveLigneRequest($ligneRequest->getTicket(),$ligneRequest->getNbTicket(),$order);
        $this->manager->persist($ligne);
        $order->addLigneRequest($ligne);


    }
        $this->manager->persist($order);
//dd($order);
$this->manager->flush();





        return $order;
    }
public function saveLigneRequest(Ticket $id,int $nbTicket,$order) :LigneRequest
{

    $ligne= new LigneRequest();
    $ticket=$this->manager->getRepository(Ticket::class)->findOneBy(
        ['id' => $id->getId()]
    );

    $ligne->setNbTicket($nbTicket);
    $ligne->setTicket($ticket);
    $ligne->setRequest($order);
    $ligne->setPrice($nbTicket * $id->getPriceCE());
    $this->manager->persist($ligne);
    $this->manager->flush();
//     die troisieme captrue dd($ligne);

    return $ligne;

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
     * @return Request
     */
    public function getCurrentCart()
    { $this->initializeCart();
        $cart = $this->session->get(CartService::SESSION_CART);
        if(!$cart instanceof Request){
            throw new NotFoundHttpException();
        }
        return $cart;
    }

    /**
     * @return Request
     */
    public function initializeCart()
    {
        if(!$this->session->get(CartService::SESSION_CART)){
        $cart = new Request();
        $this->session->set(CartService::SESSION_CART, $cart);
        return $cart;}

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
    public function addItem(Ticket $id,int $nbTicket) :LigneRequest
    {

        $ligne= new LigneRequest();
        $ligne->setNbTicket($nbTicket);
        $ligne->setTicket($id);

        $ligne->setPrice($nbTicket * $id->getPriceCE());
        $this->manager->persist($ligne);
//        Si j'enregistre ici j'arrive à mettre ma ligne avec le ticket
//        $this->manager->flush();
//        dd($ligne);

        return $ligne;


    }
    public function removeItem(int $id){

        $panier=$this->getCurrentCart();
        $ligne=$panier->getLigneRequest()[$id];


        if(!empty($panier->getLigneRequest()[$id])){
            unset($panier->getLigneRequest()[$id]);
            $this->session->set('panier',$panier);
        }

    }
    public function showItems(){
        $panier=$this->getCurrentCart();
        $panierData=[];

        foreach ($panier->getLigneRequest()  as $key => $item){
            $panierData[]=[
                'ticket'=>$item->getTicket(),
                'quantity'=>$item->getNbTicket(),
                'key'=>$key
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