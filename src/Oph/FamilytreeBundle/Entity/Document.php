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
	 * @ORM\OneToOne(targetEntity="Oph\FamilytreeBundle\Entity\Img", cascade={"persist", "remove"})
	 */
	private $img;
	
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
     * Set img
     *
     * @param \Oph\FamilytreeBundle\Entity\Img $img
     * @return Document
     */
    public function setImg(\Oph\FamilytreeBundle\Entity\Img $img = null)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return \Oph\FamilytreeBundle\Entity\Img 
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Affichage d'une entité Img avec echo
     * @return string Représentation de l'img
    */
    public function __toString()
    {
        return $this->img->getAlt();
    }
   
}
