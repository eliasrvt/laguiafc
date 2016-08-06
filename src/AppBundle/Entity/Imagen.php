<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Imagen
 *
 * @ORM\Table(name="imagen")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImagenRepository")
 */
class Imagen
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
     * @ORM\Column(name="name", type="string", length=255)
     * 
     */
    public $name;

    /**
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    public $path;

    /**
     *
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity="Galeria", inversedBy="imagenes")
     * @ORM\JoinColumn(name="galeria_id", referencedColumnName="id")
     */
    private $galeria;

    /**
     * Sets imageFile.
     *
     */
    public function setImageFile($imageFile = null)
    {
        $this->imageFile = $imageFile;
    }

    /**
     * Get imageFile.
     *
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }


    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    public function getUploadRootDir()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__.'/../../../web'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return '/uploads/Galerias/';
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Imagen
     */
    public function setPath($file_name, $name_gal)
    {
        $this->path = $this->generarNombreAlbum($name_gal).'/'.$file_name;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }



    /**
     * Set album
     *
     * @param \AppBundle\Entity\Album $album
     * @return Foto
     */
    public function setAlbum(\AppBundle\Entity\Album $album = null)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return \AppBundle\Entity\Album 
     */
    public function getAlbum()
    {
        return $this->album;
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Imagen
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    
    public function generarNombreAlbum($name_gal)
    {
        return $name_gal;
    }

    public function generarRuta($name_gal)
    {
        return $this->getUploadRootDir().'/'.$this->generarNombreAlbum($name_gal);
    }

    public function removeFile()
    {
        $file_path = $this->getUploadRootDir().'/'.$this->getPath();
        if (file_exists($file_path)) {
            unlink($file_path);
            return true;
        }
        else{ 
            return false;
        }
    }


    /**
     * Set galeria
     *
     * @param \AppBundle\Entity\Galeria $galeria
     *
     * @return Imagen
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
}
