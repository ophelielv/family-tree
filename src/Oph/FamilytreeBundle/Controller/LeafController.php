<?php

namespace Oph\FamilytreeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use Oph\FamilytreeBundle\Entity\Image;
use Oph\FamilytreeBundle\Entity\Document;
use Oph\FamilytreeBundle\Entity\Photo;
use Oph\FamilytreeBundle\Entity\Person;
use Oph\FamilytreeBundle\Form\PersonType;
use Oph\FamilytreeBundle\Form\PersonAddPhotosType;
use Oph\FamilytreeBundle\Form\PersonAddGalleryType;

class LeafController extends Controller
{
    public function addAction(Request $request){
        //requête en post, création et gestion du formulaire, puis redirige vers page de visualisation de l’annonce. add.html.twig
        
        $person = new Person();
        
        // On crée le FormBuilder grâce au service form factory
        $form = $this->createForm(new PersonType(), $person);
        
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);
        if ($form->isValid()) 
        {
          //****il parait que ça se fait dans l'entité avec doctrine maintenant  $person->getImage()->upload();
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('notice', 'Personne enregistrée.');
            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirect($this->generateUrl('oph_familytree_view', array('id' => $person->getId())));
        }

        // À ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        return $this->render('OphFamilytreeBundle:Leaf:add.html.twig', array('form' => $form->createView(),   ));
           
    }
    
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('OphFamilytreeBundle:Person');
        $person = $repository->find($id);
        if (null === $person) {
            throw new NotFoundHttpException("La personne d'id ".$id." est introuvable.");
        }
        
        $form = $this->createForm(new PersonType(), $person);

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);
        if ($form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('notice', 'Fiche éditée.');
            return $this->redirect($this->generateUrl('oph_familytree_view', array('id' => $person->getId())));
        }

        // À ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        return $this->render('OphFamilytreeBundle:Leaf:edit.html.twig', array(
          'person' => $person,
          'form' => $form->createView(),
        ));
           
    }
 
    public function editPhotosAction($id, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('OphFamilytreeBundle:Person');
        $person = $repository->find($id);
        if (null === $person) {
            throw new NotFoundHttpException("La personne d'id ".$id." est introuvable.");
        }
        
        $form = $this->createForm(new PersonAddGalleryType(), $person);

        $form->handleRequest($request);
        if ($form->isValid()) 
        {
            $person->uploadPictures();
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('notice', 'Galerie éditée.');
            return $this->redirect($this->generateUrl('oph_familytree_view', array('id' => $person->getId())));
        }

        return $this->render('OphFamilytreeBundle:Leaf:editgaleriephotos.html.twig', array(
          'person' => $person,
          'form' => $form->createView(),
        ));
           
    }
    

    public function deleteAction($id, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('OphFamilytreeBundle:Person');
        $person = $repository->find($id);
        if (null === $person) 
        {
            throw new NotFoundHttpException("La personne d'id ".$id." est introuvable.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->createFormBuilder()->getForm();
        
        if ($form->handleRequest($request)->isValid()) {
          $em->remove($person);
          $em->flush();
        
          $request->getSession()->getFlashBag()->add('info', "La fiche a bien été supprimée.");
        
          return $this->redirect($this->generateUrl('oph_familytree_homepage'));
        }
        
        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('OphFamilytreeBundle:Leaf:delete.html.twig', array(
          'person' => $person,
          'form'   => $form->createView() ));
    }
    
    //////////////////////////////////
    public function deletePhotoAction($id, $idPicture, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('OphFamilytreeBundle:Person');
        
        $person = $repository->find($id);
        if (null === $person) 
        {
            throw new NotFoundHttpException("La personne d'id ".$id." est introuvable.");
        }
        
        //$picture = $person->getPictureFromGallery($idPicture);//$repository->find($idPicture);
        $repositoryPicture = $em->getRepository('OphFamilytreeBundle:Picture');
        $picture = $repositoryPicture->findOneById($idPicture);
        if (null === $picture) 
        {
            throw new NotFoundHttpException("La photo d'id ".$idPicture." est introuvable.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->createFormBuilder()->getForm();
        
        if ($form->handleRequest($request)->isValid()) 
        {
          $person->removePicture($picture);
          $em->flush(); 
        
          $request->getSession()->getFlashBag()->add('info', "La photo a bien été supprimée.");
        
          return $this->redirect($this->generateUrl('oph_familytree_edit_photos', array('id' => $person->getId() )));
        }
        
        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render(   'OphFamilytreeBundle:Leaf:deletephotos.html.twig', 
                                array(  'person' => $person, 
                                        'picture' => $picture, 
                                        'form'   => $form->createView() 
                                )
                            );


    }//////////////////////////////////////////////////////////////////////
    
    public function indexAction()
    {
        // L'index affiche:
        // - une photo de Anita et Georges
        // - texte pour choisir la famille de Anita ou Georges
        // - portfolio + affichage en jquery
        // - après je ne sais plus
     /*   $listPersons = VOIR REPOSITORY*/
        $em = $this->getDoctrine()->getManager();
        $persons = $em->getRepository('OphFamilytreeBundle:Person')->findAll();
        return $this->render('OphFamilytreeBundle:Leaf:index.html.twig', array('persons' => $persons,   ));
       // return $this->render('OphFamilytreeBundle:Leaf:index.html.twig', array(/*'listPerons' => listPersons;*/));
    }
    
    public function viewAction($id)
    {
        // La vue affiche une personne
        // - header : photo + firstName + name 
        // - résumé :   firstName, name 
        //              dateOfBirth, placeOfBirth 
        //              dateOfDeath, placeOfDeath et deadAtAge 
        
        // À faire ENSUITE 
        // - Mariage (personne, date, lieu, divorces, enfants)
        // - Photos (array de photos (url, alt, légende, date}
        // - DOCUMENTS (array de docs (url, alt, date, légende)
        
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('OphFamilytreeBundle:Person');
        $person = $repository->find($id);
        if (null === $person) {
            throw new NotFoundHttpException("La personne d'id ".$id." est introuvable.");
        }
       /* 
        //récup portrait
        $portrait = $person->getId();
        //récup liste docs
        
        $listDocuments = $em->getRepository('OphFamilytreeBundle:Document')->findBy(array('person'=>$person));
        $listPhotos = $em->getRepository('OphFamilytreeBundle:Photo')->findBy(array('person'=>$person));
*/
        return $this->render('OphFamilytreeBundle:Leaf:view.html.twig', 
                       array(   'person' => $person,/*
                                'portrait' => $portrait,
                                'listDocuments' => $listDocuments,
                                'listPhotos' => $listPhotos*/));
    }
}
