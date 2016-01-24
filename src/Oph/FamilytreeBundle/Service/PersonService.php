<?php


namespace Oph\FamilytreeBundle\Service;

use Oph\FamilytreeBundle\Model\PersonModel;

class PersonService
{
    public function getPersonModelById( $id)
    {
     /*   $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('OphFamilytreeBundle:Person');
        $person = $repository->find($id);
        if (null === $person) {
            throw new NotFoundHttpException("La personne d'id ".$id." est introuvable.");
        }
        */
        $pM = new PersonModel();
        
        
        /*
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
        $this->personModels[] =  $pM*/
        return $pM;
    }

}