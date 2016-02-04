<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $makes = $this->getDoctrine()->getRepository('AppBundle:Make')->getMakes();

        return $this->render('AppBundle:Default:index.html.twig', ['makes' => $makes]);
    }
}
