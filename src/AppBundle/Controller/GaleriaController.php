<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Galeria;
use AppBundle\Entity\Imagen;
use AppBundle\Form\GaleriaType;

/**
 * Galeria controller.
 *
 * @Route("/admin/galeria")
 */
class GaleriaController extends Controller
{
    /**
     * Lists all Galeria entities.
     *
     * @Route("/", name="admin_galeria_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $galerias = $em->getRepository('AppBundle:Galeria')->findAll();

        return $this->render('galeria/index.html.twig', array(
            'galerias' => $galerias,
        ));
    }

    /**
     * Creates a new Galeria entity.
     *
     * @Route("/new", name="admin_galeria_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $imagen = new Imagen;
        $galerium = new Galeria();
        $galerium->addImagen($imagen);

        $form = $this->createForm('AppBundle\Form\GaleriaType', $galerium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($galerium->getImagenes() as $imagen) {
                $file = $imagen->getImageFile();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $imagen->setGaleria($galerium);
                $imagen->setName($fileName);
                $imagen->setPath($fileName,$galerium->getNombre() );
                $ruta = $imagen->generarRuta($galerium->getNombre());

                $file->move(
                    $ruta,
                    $fileName
                );
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($galerium);
            $em->flush();

            return $this->redirectToRoute('admin_galeria_show', array('id' => $galerium->getId()));
        }

        return $this->render('galeria/new.html.twig', array(
            'galerium' => $galerium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Galeria entity.
     *
     * @Route("/{id}", name="admin_galeria_show")
     * @Method("GET")
     */
    public function showAction(Galeria $galerium)
    {
        $deleteForm = $this->createDeleteForm($galerium);

        return $this->render('galeria/show.html.twig', array(
            'galerium' => $galerium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Galeria entity.
     *
     * @Route("/{id}/edit", name="admin_galeria_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Galeria $galerium)
    {
        $deleteForm = $this->createDeleteForm($galerium);
        $editForm = $this->createForm('AppBundle\Form\GaleriaType', $galerium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($galerium);
            $em->flush();

            return $this->redirectToRoute('admin_galeria_edit', array('id' => $galerium->getId()));
        }

        return $this->render('galeria/edit.html.twig', array(
            'galerium' => $galerium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Galeria entity.
     *
     * @Route("/{id}", name="admin_galeria_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Galeria $galerium)
    {
        $form = $this->createDeleteForm($galerium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($galerium);
            $em->flush();
        }

        return $this->redirectToRoute('admin_galeria_index');
    }

    /**
     * Creates a form to delete a Galeria entity.
     *
     * @param Galeria $galerium The Galeria entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Galeria $galerium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_galeria_delete', array('id' => $galerium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
