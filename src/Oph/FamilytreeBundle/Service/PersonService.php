<?php


namespace Oph\FamilytreeBundle\Service;

use Oph\FamilytreeBundle\Model\PersonModel;
use Doctrine\Common\Persistence\ObjectManager;
use Oph\FamilytreeBundle\Entity\Img;

class PersonService
{
    
    protected $em;
    
    public function setEntityManager(ObjectManager $em)
    {
        $this->em = $em;
    }

    
    public function getPersonModelById($id)
    {
        $repository = $this->em->getRepository('OphFamilytreeBundle:Person');
        $person = $repository->find($id);
        if (null === $person) {
            return null;
        }
        
        $pM = new PersonModel();
        $pM->setId($person->getId());
        $pM->setName($person->getCompleteName());
        $img=$person->getImg();
        if($img!=null){
            $pM->setUrlImage($img->getWebPath());
            $pM->setAltImage($img->getAlt());
        } else {
            $pM->setUrlImage('media/cache/my_thumb/bundles/ophfamilytree/images/pas-d-avatar.png');
            $pM->setAltImage($person->getCompleteName());
        }
        
        $motherId = $person->getMotherId();
        $fatherId = $person->getFatherId();

        if(null != $motherId && $motherId > 0){
            $motherModel = $this->getPersonModelById($motherId);
            if($motherModel != null){
                $pM->addParent($motherModel);
            }
        }
        
        if(null != $fatherId && $fatherId > 0){
            $fatherModel = $this->getPersonModelById($fatherId);
             if($fatherModel != null){
                $pM->addParent($fatherModel);
            }
        }
        
        //children
        
        return $pM;
    }

}