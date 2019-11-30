<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use App\Service\CartService;
use phpDocumentor\Reflection\Types\Integer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TicketController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */

class TicketController extends AbstractController
{
    /**
     * @Route("/billetterie",name="app_billetterie")
     */
    public function Ticket(TicketRepository $repository,UserInterface $user,CartService $cart)
    {
        $tickets= $repository->findAll();
        return $this->render('comite/ticket.html.twig', [
            'title' => 'Billetterie Battants',
            'tickets'=> $tickets ,
            'items'=>  $cart->showItems(),
            'user'=> $user]);
    }
    /**
     * @Route("/cart/add/{id}",name="app_cart_add")
     */
    public function cartAdd($id,CartService $cart,Request $request)
    {
        $result=intval($request->get('nbTicket'));
        $cart->addItem($id,$result);
        return $this->redirectToRoute('app_billetterie');
    }
    /**
     * @Route("/cart/remove/{id}",name="app_cart_remove")
     */
    public function cartRemove($id,CartService $cart,Request $request)
    {

        $cart->removeItem($id);
        return $this->redirectToRoute('app_cart_index');
    }
    /**
     * @Route("/panier",name="app_cart_index")
     */
    public function cart( CartService $cart)
    {


        return $this->render('comite/cart.html.twig', [
            'title' => 'Votre Panier',
            'items'=>  $cart->showItems(),
                'total'=> $cart->total()

                ]
        );

    }
    /**
     * @Route("/cart/send",name="app_cart_send")
     */
    public function cartSend(CartService $cart,UserInterface $user)
    {
$paniers=$cart->showItems();
$total=$cart->total();
$commande=$cart->save($paniers,$total,$user);
$cart->sendMessage($commande);
$cart->closeCart();

//        $cart->removeItem($id);
        return $this->redirectToRoute('app_homepage');
    }

}
