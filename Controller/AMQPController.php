<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use P\AdminBundle\Entity\Notification;
use P\AdminBundle\Form\NotificationType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Notification controller.
 *
 */

class AMQPController extends Controller
{
    public function indexAction(Request $request)
    {
        $amqp = $this->get('p.amqp');

        $exchangeForm = $this->createFormBuilder()
            ->add('exchange')
            ->add('type', ChoiceType::class, array('label' => 'type', 'choices' => array(
                'AMQP_EX_TYPE_DIRECT' => AMQP_EX_TYPE_DIRECT,
                'AMQP_EX_TYPE_FANOUT' => AMQP_EX_TYPE_FANOUT,
                'AMQP_EX_TYPE_TOPIC' => AMQP_EX_TYPE_TOPIC,
                'AMQP_EX_TYPE_HEADERS' => AMQP_EX_TYPE_HEADERS,
            )))
            ->add('flags', ChoiceType::class, array('label' => 'flags', 'choices' => array(
                'AMQP_DURABLE' => AMQP_DURABLE,
                'AMQP_PASSIVE' => AMQP_PASSIVE,
            )))
            ->add('submit', SubmitType::class, array('label' => 'create', 'attr' => array('class' => 'btn btn-primary')))
            ->setMethod('POST')
            ->getForm()
            ;

        $queueForm = $this->createFormBuilder()
            ->add('queue')
            ->add('flags', ChoiceType::class, array('label' => 'flags', 'choices' => array(
                'AMQP_DURABLE' => AMQP_DURABLE,
                'AMQP_PASSIVE' => AMQP_PASSIVE,
                'AMQP_EXCLUSIVE' => AMQP_EXCLUSIVE,
                'AMQP_AUTODELETE' => AMQP_AUTODELETE
            )))
            ->add('submit', SubmitType::class, array('label' => 'create', 'attr' => array('class' => 'btn btn-primary')))
            ->setMethod('POST')
            ->getForm()
            ;

        $exchangeForm->handleRequest($request);
        if($exchangeForm->isValid()) {
            $exchange = $exchangeForm->getData();
            $exchangeName = $exchange['exchange'];
            $type = $exchange['type'];
            $flags = $exchange['flags'];

            $amqp->declareExchange($exchangeName, $type, $flags);
            return $this->redirect($this->generateUrl('p_amqp'));
        }

        $queueForm->handleRequest($request);
        if($queueForm->isValid()) {
            $queue = $queueForm->getData();
            $queueName = $queue['queue'];
            $flags = $queue['flags'];

            $amqp->declareQueue($queueName, $flags);
            return $this->redirect($this->generateUrl('p_amqp'));
        }

        $exchanges = $amqp->listExchange();
        $queues = $amqp->listQueue();

        return $this->render('PAdminBundle:AMQP:index.html.twig', array(
            'exchanges' => $exchanges,
            'queues' => $queues,
            'exchangeForm' => $exchangeForm->createView(),
            'queueForm' => $queueForm->createView(),
        ));
    }

    public function exchangeDeleteAction($name)
    {
        $amqp = $this->get('p.amqp');
        $amqp->deleteExchange($name);
        return $this->redirect($this->generateUrl('p_amqp'));
    }

    public function queueDeleteAction($name)
    {
        $amqp = $this->get('p.amqp');
        $amqp->deleteQueue($name);
        return $this->redirect($this->generateUrl('p_amqp'));
    }

}
