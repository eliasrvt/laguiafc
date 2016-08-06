<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Imagen;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('post_index');
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/test", name="test")
     * @Method({"GET", "POST"})
     */
    public function testAction(Request $request)
    {
        $imagen = new Imagen();
        $form = $this->createForm('AppBundle\Form\ImagenType', $imagen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($imagen);
            $em->flush();

            return $this->redirectToRoute('test');
        }

        return $this->render('test.html.twig', array(
            'imagen' => $imagen,
            'form' => $form->createView(),
        ));
    }
}
