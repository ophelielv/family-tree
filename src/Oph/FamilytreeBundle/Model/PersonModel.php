<?php

namespace Oph\FamilytreeBundle\Model;

/*use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Oph\FamilytreeBundle\Entity\Image;
*/
use Oph\FamilytreeBundle\Entity\Person;

class PersonModel
{
    public $id;
    public $name;
    public $urlImage;
    public $altImage;
    public $_parents;

    public function setUrlImage($urlImage)
    {
        $this->urlImage = $urlImage;

        return $this;
    }
    
    public function setAltImage($altImage)
    {
        $this->altImage = $altImage;

        return $this;
    }
     
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    
    public function addParent (PersonModel $parent)
    {
        $this->_parents[] = $parent;

        return $this;
    }
    
    /*public function getFamilyTreeBySon(Person $son)
    {
        $pM = new PersonModel($son->getId(), $son->getCompleteName());
        
        if(null != $son->getMother()){
            $mother = $son->getMother();
            $motherModel = new PersonModel($mother->getId(),$mother->getCompleteName());
            $this->getFamilyTreeBySon($mother);
            $pM->addParent($motherModel);
        }
        
        if(null != $son->getFather()){
            $father = $son->getFather();
            $fatherModel = new PersonModel($father->getId(),$father->getCompleteName());
            $this->getFamilyTreeBySon($father);
            $pM->addParent($fatherModel);
        }
        $this->personModels[] =  $pM;
        return $this->personModels;
    }*/


}