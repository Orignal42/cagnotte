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

        if ($form->isSubmitted() && $form->isValid()) {

            $payment->setCreatedAt(new DateTime());
            $payment->setUpdatedAt(new DateTime());
            $payment->getParticipant()->setCampaignId($campaign);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('campaign_show',[
                'id'=>$campaign->getId()
            ]);
        }

        return $this->render('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form->createView(),
        ]);
    }
}
