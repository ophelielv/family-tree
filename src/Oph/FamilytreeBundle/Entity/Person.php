<?php

namespace Oph\FamilytreeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Oph\FamilytreeBundle\Entity\Image;


/**
 * Person
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Oph\FamilytreeBundle\Entity\PersonRepository")
 */
class Person
{
    /**
     * @ORM\ManyToMany(targetEntity="Oph\FamilytreeBundle\Entity\Photo", cascade={"persist"})
     */
    private $listPhotos;


    private $uploadedFiles;
    
    private $uploadedPictures;
    /**
	 * @ORM\OneToOne(targetEntity="Oph\FamilytreeBundle\Entity\Image",  cascade={"persist"})
	 */
	private $image;
	
	/**
	 * @ORM\OneToOne(targetEntity="Oph\FamilytreeBundle\Entity\Img",  cascade={"persist", "remove"})
	 */
	private $img;
	
	/**
     * @ORM\ManyToMany(targetEntity="Oph\FamilytreeBundle\Entity\Picture", cascade={"persist", "remove"})
     */
    private $gallery; //array de Picture
	
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *@ORM\Column(name="anitasFamily", type="boolean", nullable=true, options={"default":false})
     */
    private $anitasFamily;
    
    
    /**
     *@ORM\Column(name="georgesFamily", type="boolean", nullable=true, options={"default":false})
     */
    private $georgesFamily;
    
     /**
      * @ORM\Column(name="gender", type="string", length=1)
      * @Assert\Choice(choices = {"M", "F"}, message = "gender")
      */
    private $gender;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfBirth", type="datetime", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="placeOfBirth", type="string", length=255, nullable=true)
     */
    private $placeOfBirth;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfDeath", type="datetime", nullable=true)
     */
    private $dateOfDeath;

    /**
     * @var string
     *
     * @ORM\Column(name="placeOfDeath", type="string", length=255, nullable=true)
     */
    private $placeOfDeath;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string",nullable=true, length=255, nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->listPhotos = new ArrayCollection();
        $this->gallery = new ArrayCollection();
    }

    public function addPhoto(Photo $photo)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->listPhotos[] = $photo;
        return $this;
    }
    
    public function removePhoto(Photo $photo)
    {
    // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la photo en argument
    $this->listPhotos->removeElement($photo);
    }
    
    // Notez le pluriel, on récupère une liste de photos ici !
    public function getlistPhotos()
    {
    return $this->listPhotos;
    }

    public function addPicture(Picture $picture)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->gallery[] = $picture;
        return $this;
    }
    
    public function removePicture(Picture $picture)
    {
    // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la photo en argument
        $this->gallery->removeElement($picture);
    }
    
 /*   public function getPictureFromGallery($idPicture)
    {
    // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la photo en argument
        $picture = $this->gallery.first();//get($idPicture); //array_search ( mixed $needle , array $haystack [, bool $strict = false ] )
        return $picture;
    }
*/
    //on récupère une liste de photos ici !
    public function getGallery()
    {
        return $this->gallery;
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
     * @return Person
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

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Person
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     * @return Person
     */
    public function setDateOfBirth($dateOfBirth)
    {
       $this->dateOfBirth = $dateOfBirth;
       return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime 
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set placeOfBirth
     *
     * @param string $placeOfBirth
     * @return Person
     */
    public function setPlaceOfBirth($placeOfBirth = NULL)
    {
        $this->placeOfBirth = $placeOfBirth;

        return $this;
    }

    /**
     * Get placeOfBirth
     *
     * @return string 
     */
    public function getPlaceOfBirth()
    {
        return $this->placeOfBirth;
    }

    /**
     * Set dateOfDeath
     *
     * @param \DateTime $dateOfDeath
     * @return Person
     */
    public function setDateOfDeath($dateOfDeath = NULL)
    {
        $this->dateOfDeath = $dateOfDeath;

        return $this;
    }

    /**
     * Get dateOfDeath
     *
     * @return \DateTime 
     */
    public function getDateOfDeath()
    {
        return $this->dateOfDeath;
    }

    /**
     * Set placeOfDeath
     *
     * @param string $placeOfDeath
     * @return Person
     */
    public function setPlaceOfDeath($placeOfDeath)
    {
        $this->placeOfDeath = $placeOfDeath;

        return $this;
    }

    /**
     * Get placeOfDeath
     *
     * @return string 
     */
    public function getPlaceOfDeath()
    {
        return $this->placeOfDeath;
    }

    
    /**
     * Set anitasFamily
     *
     * @param boolean $anitasFamily
     * @return Person
     */
    public function setAnitasFamily($anitasFamily)
    {
        $this->anitasFamily = $anitasFamily;

        return $this;
    }

    /**
     * Get anitasFamily
     *
     * @return boolean 
     */
    public function getAnitasFamily()
    {
        return $this->anitasFamily;
    }

    /**
     * Set georgesFamily
     *
     * @param boolean $georgesFamily
     * @return Person
     */
    public function setGeorgesFamily($georgesFamily)
    {
        $this->georgesFamily = $georgesFamily;

        return $this;
    }

    /**
     * Get georgesFamily
     *
     * @return boolean 
     */
    public function getGeorgesFamily()
    {
        return $this->georgesFamily;
    }

    /**
     * Set image
     *
     * @param \Oph\FamilytreeBundle\Entity\Image $image
     * @return Person
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
     * Set img
     *
     * @param \Oph\FamilytreeBundle\Entity\Img $img
     * @return Person
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
     * Affichage d'une entité Film avec echo
     * @return string Représentation du film
     */
    public function __toString()
    {
        $completeName = $this->fistName . ' ' . $this->name;
        return $completeName;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Person
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
     * Add listPhotos
     *
     * @param \Oph\FamilytreeBundle\Entity\Photo $listPhotos
     * @return Person
     */
    public function addListPhoto(\Oph\FamilytreeBundle\Entity\Photo $listPhotos)
    {
        $this->listPhotos[] = $listPhotos;

        return $this;
    }

    /**
     * Remove listPhotos
     *
     * @param \Oph\FamilytreeBundle\Entity\Photo $listPhotos
     */
    public function removeListPhoto(\Oph\FamilytreeBundle\Entity\Photo $listPhotos)
    {
        $this->listPhotos->removeElement($listPhotos);
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return Person
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    public function addUploadedFile(Photo $photo)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->uploadedFiles[] = $photo;
        return $this;
    }
    
    public function removeUploadedFile(Photo $photo)
    {
    // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la photo en argument
    $this->uploadedFiles->removeElement($photo);
    }
    
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }
    
    public function setFile(UploadedFile $file = null)
    {
        $this->uploadedFiles = $files;
    }
    
    //////////////////////////////////
    public function getUploadedPictures()
    {
        return $this->uploadedPictures;
    }
    
    public function setUploadedPictures(array $files = null)
    {
        $this->uploadedPictures = $files;
    }////

     
     /**
     * @ORM\PreFlush()
     */
    public function uploadPhotos()
    {
        if (null === $this->uploadedFiles) 
        {
            return;
        }
        foreach($this->uploadedFiles as $uploadedFile)
        {
            $uploadedFile->getImage()->upload(); 
            $this->addPhoto($uploadedFile);
    
            unset($uploadedFile);
        }
    }
    
     /**
     * @ORM\PreFlush()
     */
    public function uploadPictures()
    {
        if (null === $this->uploadedPictures) 
        {
            return;
        }
        foreach($this->uploadedPictures as $uP)
        {

            $this->addPicture($uP);
    
            unset($uploadedPictures);
        }
    }
}
