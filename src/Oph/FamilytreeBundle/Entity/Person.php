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
 * @ORM\HasLifecycleCallbacks
 */
class Person
{
    private $uploadedPictures;
    private $uploadedDocuments;
    private $tempMother;
    private $tempFather;
    private $tempChild;

	/**
	 * @ORM\OneToOne(targetEntity="Oph\FamilytreeBundle\Entity\Img",  cascade={"persist", "remove"})
	 * @Assert\Valid()
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
     * @var float
     *
     * @ORM\Column(name="latitudeBirth", type="float", nullable=true)
     */
    private $latitudeBirth;
    
    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", nullable=true)
     */
    private $longitudeBirth;

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
    
    private $completeName;
    /******************************************************************************************/
    /************************Parent***********************************************/
    /*******************Mother****************************************************/
     /**
     * @ORM\OneToMany(targetEntity="Oph\FamilytreeBundle\Entity\Person", mappedBy="mother", cascade={"persist", "remove"})
     */
    private $childrenOfMother;

    /**
     * @ORM\ManyToOne(targetEntity="Oph\FamilytreeBundle\Entity\Person", inversedBy="childrenOfMother", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="mother_id", referencedColumnName="id", nullable=true)
     */
    private $mother;

    /******mother**********/
    /**
     * Set mother
     *
     * @param \Oph\FamilytreeBundle\Entity\Person $mother
     * @return Person
     */
    public function setMother(\Oph\FamilytreeBundle\Entity\Person $mother = null)
    {
        $this->mother = $mother;

        return $this;
    }

    /**
     * Get mother
     *
     * @return \Oph\FamilytreeBundle\Entity\Person 
     */
    public function getMother()
    {
        return $this->mother;
    }
    

    public function getMotherId()
    {
        if($this->mother==null){
            return -1;
        }
        return $this->mother->getId();
    }
    
    public function getFatherId()
    {
        if($this->father==null){
            return -1;
        }
        return $this->father->getId();
    }
    
    /*******childrenOfMother*********/
    /**
     * Get childrenOfMother
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildrenOfMother()
    {
        return $this->childrenOfMother;
    }

    public function addChildOfMother(Person $child)
    {
        $this->childrenOfMother[] = $child;
        $child->setMother($this);
        return $this;
    }
    
    public function removeChildrenOfMother(Person $child)
    {
        $this->childrenOfMother->removeElement($child);
        $child->setMother(null);
    }


/*******************Father****************************************************/
     /**
     * @ORM\OneToMany(targetEntity="Oph\FamilytreeBundle\Entity\Person", mappedBy="father", cascade={"persist", "remove"})
     */
    private $childrenOfFather;

    /**
     * @ORM\ManyToOne(targetEntity="Oph\FamilytreeBundle\Entity\Person", inversedBy="childrenOfFather", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="father_id", referencedColumnName="id", nullable=true)
     */
    private $father;

    /******father**********/
    /**
     * Set father
     *
     * @param \Oph\FamilytreeBundle\Entity\Person $father
     * @return Person
     */
    public function setFather(\Oph\FamilytreeBundle\Entity\Person $father = null)
    {
        $this->father = $father;

        return $this;
    }

    /**
     * Get father
     *
     * @return \Oph\FamilytreeBundle\Entity\Person 
     */
    public function getFather()
    {
        return $this->father;
    }
    
    /*******childrenOfFather*********/

    /**
     * Get childrenOfFather
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildrenOfFather()
    {
        return $this->childrenOfFather;
    }

     public function addChildOfFather(Person $child)
    {
        $this->childrenOfFather[] = $child;
        $child->setFather($this);
        return $this;
    }
    
    public function removeChildrenOfFather(Person $child)
    {
        $this->childrenOfFather->removeElement($child);
        $child->setFather(null);
    }

    /************************UPLOAD MOTHER FATHER CHILD*********************/
    /**
     * @ORM\PreFlush()
     */
    public function uploadMother()
    {
        if (null === $this->tempMother) {
            return;
        }
        $this->setMother($this->tempMother);
    }

    /**
     * @ORM\PreFlush()
     */
    public function uploadFather()
    {
        if (null === $this->tempFather) {
            return;
        }
        $this->setFather($this->tempFather);
    }
    
    /**
     * @ORM\PreFlush()
     */
    public function uploadChild()
    {
        if (null === $this->tempChild) {
            return;
        }
        if($this->gender == 'M'){
            $this->addChildOfMother($this->tempChild);
        }
        else if($this->gender == 'F'){
            $this->addChildOfFather($this->tempChild);
        }
    }

/************************ Constructeur ***************************************************/

    public function __construct() {
        $this->childrenOfMother = new ArrayCollection();
        $this->childrenOfFather = new ArrayCollection();

        $this->gallery = new ArrayCollection();
        $this->listOfDocuments = new ArrayCollection();
    }


/****************** ManyToMany Documents************************************************/

        
    /**
     * @ORM\ManyToMany(targetEntity="Oph\FamilytreeBundle\Entity\Document", cascade={"persist", "remove"})
     */
    private $listOfDocuments; //array de Document
    
    public function addDocument(Document $document)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->listOfDocuments[] = $document;
        return $this;
    }
    
    public function removeDocument(Document $document)
    {
    // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la photo en argument
        $this->listOfDocuments->removeElement($document);
    }

    //on récupère une liste de photos ici !
    public function getListOfDocuments()
    {
        return $this->listOfDocuments;
    }
/*************************************************************************************/

/****************** ManyToMany Picture************************************************/
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

    //on récupère une liste de photos ici !
    public function getGallery()
    {
        return $this->gallery;
    }

/*************************************************************************************/
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
     * 
     * @return string Représentation de Person
     */
    public function __toString()
    {
        $completeName = $this->fistName . ' ' . $this->name;
        return $completeName;
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

    public function getUploadedPictures()
    {
        return $this->uploadedPictures;
    }
    
    public function setUploadedPictures(array $files = null)
    {
        $this->uploadedPictures = $files;
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
    
   /**
     * @ORM\PreFlush()
     */
    public function getUploadedDocuments()
    {
        return $this->uploadedDocuments;
    }
    
    public function setUploadedDocuments(array $files = null)
    {
        $this->uploadedDocuments = $files;
    }
    
    /**
     * @ORM\PreFlush()
     */
    public function uploadDocuments()
    {
        if (null === $this->uploadedDocuments) 
        {
            return;
        }
        foreach($this->uploadedDocuments as $uD)
        {

            $this->addDocument($uD);
    
            unset($uploadedDocuments);
        }
    }


    /**
     * Add gallery
     *
     * @param \Oph\FamilytreeBundle\Entity\Picture $gallery
     * @return Person
     */
    public function addGallery(\Oph\FamilytreeBundle\Entity\Picture $gallery)
    {
        $this->gallery[] = $gallery;

        return $this;
    }

    /**
     * Remove gallery
     *
     * @param \Oph\FamilytreeBundle\Entity\Picture $gallery
     */
    public function removeGallery(\Oph\FamilytreeBundle\Entity\Picture $gallery)
    {
        $this->gallery->removeElement($gallery);
    }

    /**
     * Add listOfDocuments
     *
     * @param \Oph\FamilytreeBundle\Entity\Document $listOfDocuments
     * @return Person
     */
    public function addListOfDocument(\Oph\FamilytreeBundle\Entity\Document $listOfDocuments)
    {
        $this->listOfDocuments[] = $listOfDocuments;

        return $this;
    }

    /**
     * Remove listOfDocuments
     *
     * @param \Oph\FamilytreeBundle\Entity\Document $listOfDocuments
     */
    public function removeListOfDocument(\Oph\FamilytreeBundle\Entity\Document $listOfDocuments)
    {
        $this->listOfDocuments->removeElement($listOfDocuments);
    }

    public function getcompleteName()
    {
        $this->completeName = $this->firstName.' '.$this->name;
        return $this->completeName;
    }

    public function getTempMother()
    {
        return $this->tempMother;
    }
    
    public function setTempMother(Person $par)
    {
        $this->tempMother = $par;
    }
    
    public function getTempFather()
    {
        return $this->tempFather;
    }
    
    public function setTempFather(Person $par)
    {
        $this->tempFather = $par;
       // return $this->linkedParent;
    }
///////////////
    public function getTempChild()
    {
        return $this->tempChild;
    }
    
    public function setTempChild(Person $child)
    {
        $this->tempChild = $child;
    }

//////////////

    /**
     * Set latitudeBirth
     *
     * @param float $latitudeBirth
     * @return Person
     */
    public function setLatitudeBirth($latitudeBirth)
    {
        $this->latitudeBirth = $latitudeBirth;

        return $this;
    }

    /**
     * Get latitudeBirth
     *
     * @return float 
     */
    public function getLatitudeBirth()
    {
        return $this->latitudeBirth;
    }

    /**
     * Set longitudeBirth
     *
     * @param float $longitudeBirth
     * @return Person
     */
    public function setLongitudeBirth($longitudeBirth)
    {
        $this->longitudeBirth = $longitudeBirth;

        return $this;
    }

    /**
     * Get longitudeBirth
     *
     * @return float 
     */
    public function getLongitudeBirth()
    {
        return $this->longitudeBirth;
    }
}
