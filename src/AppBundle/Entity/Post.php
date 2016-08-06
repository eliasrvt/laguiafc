<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
* @Vich\Uploadable
 */
class Post
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
     * @ORM\Column(name="titulo", type="string", length=255).
     */
    private $titulo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creado_en", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $creadoEn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="actualizado_en", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $actualizadoEn;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="text")
     */
    private $contenido;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Comentario", mappedBy="post")
     */
    private $comentarios;


    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Categoria", inversedBy="posts")
     * @ORM\JoinTable(name="post_categoria")
     */
    private $categorias;

     /**
     * @var Usuario
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="creado_por", referencedColumnName="id")
     */
    private $creadoPor;


    /**
     * @var Usuario
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="actualizado_por", referencedColumnName="id")
    */
    private $actualizadoPor;

    /**
    * @Gedmo\Slug(fields={"id", "titulo"})
    * @ORM\Column(length=128, unique=true)
    */
    private $slug;


    /**
     *   
     * @ORM\OneToOne(targetEntity="Galeria", mappedBy="post", cascade={"persist"})
     */
    private $galeria;


    //VichUploader properties------------

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="imagePortadaName")
     * 
     * @var File
     */
    private $imagePortadaFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imagePortadaName;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $img_updatedAt;

    //End VichUploader Properties --------------

    /**
     * @ORM\Column(type="string", name="facebook")
     *
     * @var string
     */
    private $facebook;


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
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Post
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     *
     * @return Post
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
     * Constructor
     */
    public function __construct()
    {
        $this->comentarios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add comentario
     *
     * @param \AppBundle\Entity\Comentario $comentario
     *
     * @return Post
     */
    public function addComentario(\AppBundle\Entity\Comentario $comentario)
    {
        $this->comentarios[] = $comentario;

        return $this;
    }

    /**
     * Remove comentario
     *
     * @param \AppBundle\Entity\Comentario $comentario
     */
    public function removeComentario(\AppBundle\Entity\Comentario $comentario)
    {
        $this->comentarios->removeElement($comentario);
    }

    /**
     * Get comentarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Add categoria
     *
     * @param \AppBundle\Entity\Categoria $categoria
     *
     * @return Post
     */
    public function addCategoria(\AppBundle\Entity\Categoria $categoria)
    {
        $this->categorias[] = $categoria;

        return $this;
    }

    /**
     * Remove categoria
     *
     * @param \AppBundle\Entity\Categoria $categoria
     */
    public function removeCategoria(\AppBundle\Entity\Categoria $categoria)
    {
        $this->categorias->removeElement($categoria);
    }

    /**
     * Get categorias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategorias()
    {
        return $this->categorias;
    }

    /**
     * Set creadoEn
     *
     * @param \DateTime $creadoEn
     *
     * @return Post
     */
    public function setCreadoEn($creadoEn)
    {
        $this->creadoEn = $creadoEn;

        return $this;
    }

    /**
     * Get creadoEn
     *
     * @return \DateTime
     */
    public function getCreadoEn()
    {
        return $this->creadoEn;
    }

    /**
     * Set actualizadoEn
     *
     * @param \DateTime $actualizadoEn
     *
     * @return Post
     */
    public function setActualizadoEn($actualizadoEn)
    {
        $this->actualizadoEn = $actualizadoEn;

        return $this;
    }

    /**
     * Get actualizadoEn
     *
     * @return \DateTime
     */
    public function getActualizadoEn()
    {
        return $this->actualizadoEn;
    }

    /**
     * Set creadoPor
     *
     * @param integer $creadoPor
     *
     * @return Post
     */
    public function setCreadoPor($creadoPor)
    {
        $this->creadoPor = $creadoPor;

        return $this;
    }

    /**
     * Get creadoPor
     *
     * @return integer
     */
    public function getCreadoPor()
    {
        return $this->creadoPor;
    }

    /**
     * Set actualizadoPor
     *
     * @param integer $actualizadoPor
     *
     * @return Post
     */
    public function setActualizadoPor($actualizadoPor)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Get actualizadoPor
     *
     * @return integer
     */
    public function getActualizadoPor()
    {
        return $this->actualizadoPor;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function esAutor(Usuario $usuario){
        return  $usuario->getId() ==  $this->creadoPor->getId();
    }


    /**
     * Add imagene
     *
     * @param \AppBundle\Entity\Imagen $imagene
     *
     * @return Post
     */
    public function addImagene(\AppBundle\Entity\Imagen $imagene)
    {
        $this->imagenes[] = $imagene;

        return $this;
    }

    /**
     * Remove imagene
     *
     * @param \AppBundle\Entity\Imagen $imagene
     */
    public function removeImagene(\AppBundle\Entity\Imagen $imagene)
    {
        $this->imagenes->removeElement($imagene);
    }

    /**
     * Get imagenes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImagenes()
    {
        return $this->imagenes;
    }

    public function __toString()
    {
        return $this->titulo;
    }

    /**
     * Set galeria
     *
     * @param \AppBundle\Entity\Galeria $galeria
     *
     * @return Post
     */
    public function setGaleria(\AppBundle\Entity\Galeria $galeria = null)
    {
        $this->galeria = $galeria;

        return $this;
    }

    /**
     * Get galeria
     *
     * @return \AppBundle\Entity\Galeria
     */
    public function getGaleria()
    {
        return $this->galeria;
    }


    //Methos VichUploader--------------
    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Post
     */
    public function setImagePortadaFile(File $image = null)
    {
        $this->imagePortadaFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->img_updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getImagePortadaFile()
    {
        return $this->imagePortadaFile;
    }

    /**
     * @param string $imageName
     *
     * @return Product
     */
    public function setImagePortadaName($imageName)
    {
        $this->imagePortadaName = $imageName;

        return $this;
    }

    /**
     * @return string
     */
    public function getImagePortadaName()
    {
        return $this->imagePortadaName;
    }

    //End methods VichUploader-------------
}
