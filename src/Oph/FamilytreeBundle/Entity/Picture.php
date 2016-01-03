<?php

namespace Oph\FamilytreeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Picture
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Oph\FamilytreeBundle\Entity\PictureRepository")
 */
class Picture
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
	 * @ORM\OneToOne(targetEntity="Oph\FamilytreeBundle\Entity\Img", cascade={"persist", "remove"})
	 */
	private $img;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePicture", type="date", nullable=true)
     */
    private $datePicture;


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
     * Set datePicture
     *
     * @param \DateTime $datePicture
     * @return Picture
     */
    public function setDatePicture($datePicture)
    {
        $this->datePicture = $datePicture;

        return $this;
    }

    /**
     * Get datePicture
     *
     * @return \DateTime 
     */
    public function getDatePicture()
    {
        return $this->datePicture;
    }

    /**
     * Set img
     *
     * @param \Oph\FamilytreeBundle\Entity\Img $img
     * @return Picture
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
}
