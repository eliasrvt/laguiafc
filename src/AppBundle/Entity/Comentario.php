<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Comentario
 *
 * @ORM\Table(name="comentario")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ComentarioRepository")
 */
class Comentario
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="text")
     */
    private $contenido;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaHora", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $fechaHora;

    /**
     * @var Post 
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comentarios")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", nullable=false)
     */
    private $post;

    /**
     * @ORM\OneToMany(targetEntity="Comentario", mappedBy="respondeA")
     */
    private $respuestas;

    /**
     * @var Comentario
     *
     * @ORM\ManyToOne(targetEntity="Comentario", inversedBy="respuestas")
     * @ORM\JoinColumn(name="respondeA_id", referencedColumnName="id")
     */
    private $respondeA;


    /**
     * @var Usuario
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="creado_por", referencedColumnName="id")
     */
    private $comentadoPor;


    public function __construct() {
        $this->respuestas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     *
     * @return Comentario
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set fechaHora
     *
     * @param \DateTime $fechaHora
     *
     * @return Comentario
     */
    public function setFechaHora($fechaHora)
    {
        $this->fechaHora = $fechaHora;

        return $this;
    }

    /**
     * Get fechaHora
     *
     * @return \DateTime
     */
    public function getFechaHora()
    {
        return $this->fechaHora;
    }

    /**
     * Set post
     *
     * @param \AppBundle\Entity\Post $post
     *
     * @return Comentario
     */
    public function setPost(\AppBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \AppBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Add respuesta
     *
     * @param \AppBundle\Entity\Comentario $respuesta
     *
     * @return Comentario
     */
    public function addRespuesta(\AppBundle\Entity\Comentario $respuesta)
    {
        $this->respuestas[] = $respuesta;

        return $this;
    }

    /**
     * Remove respuesta
     *
     * @param \AppBundle\Entity\Comentario $respuesta
     */
    public function removeRespuesta(\AppBundle\Entity\Comentario $respuesta)
    {
        $this->respuestas->removeElement($respuesta);
    }

    /**
     * Get respuestas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRespuestas()
    {
        return $this->respuestas;
    }

    /**
     * Set respondeA
     *
     * @param \AppBundle\Entity\Comentario $respondeA
     *
     * @return Comentario
     */
    public function setRespondeA(\AppBundle\Entity\Comentario $respondeA = null)
    {
        $this->respondeA = $respondeA;

        return $this;
    }

    /**
     * Get respondeA
     *
     * @return \AppBundle\Entity\Comentario
     */
    public function getRespondeA()
    {
        return $this->respondeA;
    }

    /**
     * Set comentadoPor
     *
     * @param \AppBundle\Entity\Usuario $comentadoPor
     *
     * @return Comentario
     */
    public function setComentadoPor(\AppBundle\Entity\Usuario $comentadoPor = null)
    {
        $this->comentadoPor = $comentadoPor;

        return $this;
    }

    /**
     * Get comentadoPor
     *
     * @return \AppBundle\Entity\Usuario
     */
    public function getComentadoPor()
    {
        return $this->comentadoPor;
    }
}
