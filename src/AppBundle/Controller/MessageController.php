<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MessageController extends Controller
{
    /**
     * @Route("/message", name="message")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $messages = $this->getDoctrine()
            ->getRepository('AppBundle:Message')
            ->findBy([], ['id' => 'ASC']);
        // replace this example code with whatever you need
        return $this->render('message/index.html.twig', [
            'messages' => $messages
        ]);
    }

    /**
     * @Route("/message/{message_id}/", name="message_view", requirements={"message_id"="\d+"})
     * @ParamConverter("message", class="AppBundle:Message", options={"id" = "message_id"})
     *
     * @param Message $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Message $message)
    {
        // replace this example code with whatever you need
        return $this->render('message/view.html.twig', [
            'message' => $message
        ]);
    }

    /**
     * @Route("/message/create", name="message_create")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $message = new Message();

        $form = $this->createFormBuilder($message, ['attr' => ['data-form' => true]])
            ->add('message', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Message'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setAuthor($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return new JsonResponse(array('message' => 'Success'), 201);
        }

        $response = new JsonResponse(
            array(
                'form' => $this->renderView('message/create.html.twig',
                    array(
                        'form' => $form->createView(),
                    ))), 200);

        return $response;
    }

    /**
     * @Route("/message/{message_id}/edit", name="message_edit")
     * @ParamConverter("message", class="AppBundle:Message", options={"id" = "message_id"})
     *
     * @param Request $request
     * @param Message $message
     * @return JsonResponse
     */
    public function editAction(Request $request, Message $message)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        if ($message->getAuthor()->getId() != $user->getId()) {
            throw new AccessDeniedException(
                'This user does not have access to edit this message.'
            );
        }


        $form = $this->createFormBuilder($message, ['attr' => ['data-form' => true]])
            ->add('message', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Edit Message'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return new JsonResponse(array('message' => 'Success'), 201);
        }

        $response = new JsonResponse(
            array(
                'form' => $this->renderView('message/edit.html.twig',
                    array(
                        'form' => $form->createView(),
                    ))), 200);

        return $response;
    }

    /**
     * @Route("/message/{message_id}/delete/", name="message_delete", requirements={"message_id"="\d+"})
     * @ParamConverter("message", class="AppBundle:Message", options={"id" = "message_id"})
     *
     * @param Message $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Message $message)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        if ($message->getAuthor()->getId() != $user->getId()) {
            throw new AccessDeniedException(
                'This user does not have access to edit this message.'
            );
        }


        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute('message');
    }
}
