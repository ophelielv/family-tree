<?php


namespace Oph\FamilytreeBundle\Service;

use Oph\FamilytreeBundle\Model\PersonModel;
use Doctrine\Common\Persistence\ObjectManager;

class PersonService
{
    
    protected $em;
    
    public function setEntityManager(ObjectManager $em)
    {
        $this->em = $em;
    }
    /*
    public function someOtherFunction()
    {
        $this->em->getRepository('...')
    }*/
    
    public function getPersonModelById($id)
    {
    //    $em = $this->getDoctrine()->getManager();
        $repository = $this->em->getRepository('OphFamilytreeBundle:Person');
        $person = $repository->find($id);
        if (null === $person) {
            return null;
        }
        
        $pM = new PersonModel();
        $pM->setId($person->getId());
        $pM->setName($person->getCompleteName());
        
        
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
        return $pM;
    }

}