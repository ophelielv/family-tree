<?php

namespace Oph\FamilytreeBundle\Model;

/*use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Oph\FamilytreeBundle\Entity\Image;
*/
use Oph\FamilytreeBundle\Entity\Person;
use Oph\FamilytreeBundle\Model\PersonModel;

class TreeModel
{
    public $personModel;
    
    public function getFamilyTreeBySon(Person $son)
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
    }
    
   /*
    public function __construct()
    {
        $this->_parent = null;
    }*/

}