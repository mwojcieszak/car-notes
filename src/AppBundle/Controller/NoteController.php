<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Note;
use AppBundle\Form\NoteType;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class NoteController extends Controller
{
    /**
     * @Route("/notes", name="notes")
     */
    public function indexAction(Request $request)
    {
        return $this->render('AppBundle:Note:index.html.twig');
    }

    /**
     * @Route("/notes/{model}", name="model-notes")
     * @Method({"GET"})
     */
    public function getAllAction(Request $request, $model)
    {
        $notes = $this->getDoctrine()->getRepository('AppBundle:Note')->getByModel($model);

        $serializer = $this->get('jms_serializer');

        return new Response($serializer->serialize($notes, 'json', SerializationContext::create()), 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/notes/create", name="create-note")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new NoteType(), $note = new Note());

        $form->handleRequest($request);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            $serializer = $this->get('jms_serializer');

            return new Response($serializer->serialize($note, 'json', SerializationContext::create()), 201, ['Content-Type' => 'application/json']);
        } else {
            return new JsonResponse(array('errors' => $this->getErrorsAsArray($form)), 400);
        }
    }

    /**
     * @Route("/notes/{id}", name="update-note")
     * @Method({"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Note')->find($id);

        if (!$entity) {
            return new JsonResponse(null, 404);
        }

        $form = $this->createForm(new NoteType(), $entity);

        $form->handleRequest($request);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $serializer = $this->get('jms_serializer');

            return new Response($serializer->serialize($entity, 'json'), 200);
        } else {
            return new JsonResponse(array('errors' => $this->getErrorsAsArray($form)), 400);
        }
    }

    /**
     * @Route("/notes/{id}/model/{models}", name="unbuckle-note")
     * @Method({"DELETE"})
     */
    public function unbuckleModelAction(Request $request, $id, $models)
    {
        $em = $this->getDoctrine()->getManager();
        $note = $em->getRepository('AppBundle:Note')->find($id);

        $models = explode(',', $models);

        if (!$note) {
            return new JsonResponse(null, 404);
        }

        foreach ($note->getModels() as $model) {
            if (in_array($model->getId(), $models)) {
                $note->removeModel($model);
            }
        }

        $em->persist($note);
        $em->flush();

        return new JsonResponse();
    }

    protected function getErrorsAsArray($form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $key => $child) {
            if ($err = $this->getErrorsAsArray($child)) {
                $errors[$key] = array_unique($err);
            }
        }

        return $errors;
    }
}
