<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\ComentarioType;
use AppBundle\Form\PostType;
use AppBundle\Entity\Comentario;
use AppBundle\Entity\Imagen;
use AppBundle\Entity\Post;
use AppBundle\Entity\Galeria;

/**
 * Post controller.
 *
 * @Route("/post")
 */
class PostController extends Controller
{
    /**
     * Lists all Post entities.
     *
     * @Route("/", name="post_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dql   = "SELECT p FROM AppBundle:Post p";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $posts = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            3/*limit per page*/
        );

        //$posts = $em->getRepository('AppBundle:Post')->findAll();

        return $this->render('post/index.html.twig', array(
            'posts' => $posts,
        ));
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/new", name="post_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $galery = new Galeria();
        $img = new Imagen();

        $galery->addImagen($img);
        $post->setGaleria($galery);

        $form = $this->createForm('AppBundle\Form\PostType', $post);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //Procesar Galeria-Imagen
            foreach ($galery->getImagenes() as $imagen) {
               
               $file = $imagen->getImageFile();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $imagen->setGaleria($galery);
                $imagen->setName($fileName);
                $imagen->setPath($fileName,$galery->getNombre() );
                $ruta = $imagen->generarRuta($galery->getNombre());

                $file->move(
                    $ruta,
                    $fileName
                );
                
            }
            //fin
            $galery->setPost($post);

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_show', array('slug' => $post->getSlug()));
        }

        return $this->render('post/new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Post entity.
     * 
     * @Route("/{slug}", name="post_show")
     * @ParamConverter("post", options={"mapping": {"slug": "slug"} })
     * @Method("GET")
     */
    public function showAction(Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);
        $comentarioForm = $this->createForm('AppBundle\Form\ComentarioType' , new Comentario());

        return $this->render('post/show.html.twig', array(
            'post' => $post,
            'delete_form' => $deleteForm->createView(),
            'comentario_form' => $comentarioForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Post entity.
     *
     * @Route("/{id}/edit", name="post_edit")
     * @Security("has_role('ROLE_ADMIN') || post.esAutor(user)")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm('AppBundle\Form\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_edit', array('id' => $post->getId()));
        }

        return $this->render('post/edit.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Post entity.
     *
     * @Route("/{id}", name="post_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Post $post)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
        }

        return $this->redirectToRoute('post_index');
    }

    /**
     * Creates a form to delete a Post entity.
     *
     * @param Post $post The Post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     *
     * @Route("/{id}/comentar/{comentario_id}", name="post_comentar", defaults={"comentario_id" = null})
     * @ParamConverter("respondeA", class="AppBundle:Comentario", options={"id" = "comentario_id"}, isOptional=true)
     * @Method("POST")
     */
    public function comentarAction(Request $request, Post $post, Comentario $respondeA = null)
    {
        $comentario = new Comentario();
        $comentario->setPost($post);
        
        if ($respondeA) {
            $comentario->setRespondeA($respondeA);
        }

        $formComentario = $this->createForm('AppBundle\Form\ComentarioType', $comentario);
        $formComentario->handleRequest($request);
       
       if ($formComentario->isSubmitted() && $formComentario->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comentario);
            $em->flush();

            return $this->redirectToRoute('post_show', array('slug' => $post->getSlug()));
        }
    }

    /**
     * Lista de todos los post de un usuario.
     *
     * @Route("/", name="post_index_usuario")
     * @Method("GET")
     */
    public function indexMisPostsAction()
    {
        return $this->render('post/misPosts.html.twig');
    }

}
