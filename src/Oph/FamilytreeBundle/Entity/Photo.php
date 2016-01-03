<?php

namespace Oph\FamilytreeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Oph\FamilytreeBundle\Entity\PhotoRepository")
 */
class Photo
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
     * @var \DateTime
     *
     * @ORM\Column(name="datePhoto", type="datetime", nullable=true)
     */
    private $datePhoto;

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
     * Set datePhoto
     *
     * @param \DateTime $date
     * @return Photo
     */
    public function setDatePhoto($datePhoto)
    {
        $this->datePhoto = $datePhoto;
        return $this;
    }

    /**
     * Get datePhoto
     *
     * @return \DateTime 
     */
    public function getDatePhoto()
    {
        return $this->datePhoto;
    }

    /**
     * Set image
     *
     * @param \Oph\FamilytreeBundle\Entity\Image $image
     * @return Photo
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
