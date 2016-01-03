<?php

namespace Oph\FamilytreeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Oph\FamilytreeBundle\Entity\DocumentRepository")
 */
class Document
{
    /**
	 * @ORM\OneToOne(targetEntity="Oph\FamilytreeBundle\Entity\Image", cascade={"persist"})
	 */
	private $image;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDocument", type="datetime", nullable=true)
     */
    private $dateDocument;


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
     * Set title
     *
     * @param string $title
     * @return Document
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Document
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateDocument
     *
     * @param \DateTime $dateDocument
     * @return Document
     */
    public function setDateDocument($dateDocument)
    {
        $this->dateDocument = $dateDocument;
        return $this;
    }

    /**
     * Get dateDocument
     *
     * @return \DateTime 
     */
    public function getDateDocument()
    {
        return $this->dateDocument;
    }

    /**
     * Set image
     *
     * @param \Oph\FamilytreeBundle\Entity\Image $image
     * @return Document
     */
    public function setImage(\Oph\FamilytreeBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Oph\FamilytreeBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Affichage d'une entité Image avec echo
     * @return string Représentation de l'image
    */
    public function __toString()
    {
        return $this->image->getAlt();
    }
   
}
