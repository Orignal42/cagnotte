<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\Campaign;
use App\Form\PaymentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use DateTime;

/**
 * @Route("/payment")
 */
class PaymentController extends AbstractController
{
   
    /**
     * @Route("/new/{id}", name="payment_new", methods={"GET","POST"})
     */
    public function new(Campaign $campaign , Request $request): Response
    {

        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);
        // dd($request->request->get('amount'));
        $amount = $request->request->get('amount');
        
        if ($form->isSubmitted() && $form->isValid()) {
            \Stripe\Stripe::setApiKey('sk_test_51IudYJE6zq9JtjMeKaLVqVeD5DU44TdEw2kFMuak62VLwymNNoUTQpvqJEgaHZCAzh10DAo6f6P9O4bJsLc5qzSY00IVms15NF');
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $payment->getAmount()*100,
                'currency' => 'eur'
            ]);
            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];
            

            $payment->setCreatedAt(new DateTime());
            $payment->setUpdatedAt(new DateTime());
            $payment->getParticipant()->setCampaignId($campaign);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('campaign_show',[
                'id'=>$campaign->getId(),
                
            ]);
        }

        return $this->render('payment/new.html.twig', [
            'payment' => $payment,
            'campaign'=>$campaign,
            'form' => $form->createView(),
            'title'=>$campaign->getTitle(),
            'name'=>$campaign->getName(),
            'amount' => $amount
        ]);
    }
}
