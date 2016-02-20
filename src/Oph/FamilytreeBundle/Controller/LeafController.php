<?php

namespace Oph\FamilytreeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;


use Oph\FamilytreeBundle\Entity\Document;
use Oph\FamilytreeBundle\Entity\Person;
use Oph\FamilytreeBundle\Form\PersonType;
use Oph\FamilytreeBundle\Form\DocumentType;
use Oph\FamilytreeBundle\Form\DocumentEditOneType;
use Oph\FamilytreeBundle\Form\PersonAddGalleryType;
use Oph\FamilytreeBundle\Form\PersonAddDocumentsType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Oph\FamilytreeBundle\Model\PersonModel;

class LeafController extends Controller
{
    public function addAction(Request $request)
    {
        $person = new Person();
        
        // On crée le FormBuilder grâce au service form factory
        $form = $this->createForm(new PersonType(), $person);
        
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);
        if ($form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($person);
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('notice', 'Personne enregistrée.');
            return $this->redirect($this->generateUrl('oph_familytree_view', array('id' => $person->getId())));
        }

        // À ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        return $this->render('OphFamilytreeBundle:Leaf:add.html.twig', array('form' => $form->createView(),   ));
           
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
    
public function deleteDocumentAction($id, $idDocument, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('OphFamilytreeBundle:Person');
        
        $person = $repository->find($id);
        if (null === $person) 
        {
            throw new NotFoundHttpException("La personne d'id ".$id." est introuvable.");
        }
        
        $repositoryPicture = $em->getRepository('OphFamilytreeBundle:Document');
        $document = $repositoryPicture->findOneById($idDocument);
        if (null === $document) 
        {
            throw new NotFoundHttpException("La photo d'id ".$document." est introuvable.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->createFormBuilder()->getForm();
        
        if ($form->handleRequest($request)->isValid()) 
        {
          $person->removeDocument($document);
          $em->flush(); 
        
          $request->getSession()->getFlashBag()->add('info', "Le document a bien été supprimé.");
        
          return $this->redirect($this->generateUrl('oph_familytree_edit_documents', array('id' => $person->getId() )));
        }
        
        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render(   'OphFamilytreeBundle:Leaf:deletedocument.html.twig', 
                                array(  'person' => $person, 
                                        'document' => $document, 
                                        'form'   => $form->createView() 
                                ) );
    }

    public function deletePhotoAction($id, $idPicture, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('OphFamilytreeBundle:Person');
        
        $person = $repository->find($id);
        if (null === $person) 
        {
            throw new NotFoundHttpException("La personne d'id ".$id." est introuvable.");
        }
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
                                ) );
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
         //   $person->uploadFather();//setFather($person->getTempFather());
         //  $person->uploadMother();//setMother($person->getTempMother());
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
    
    public function editDocumentsAction($id, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('OphFamilytreeBundle:Person');
        $person = $repository->find($id);
        if (null === $person) {
            throw new NotFoundHttpException("La personne d'id ".$id." est introuvable.");
        }
        
        $form = $this->createForm(new PersonAddDocumentsType(), $person);

        $form->handleRequest($request);
        if ($form->isValid()) 
        {
           // $person->uploadDocuments();
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('notice', 'Documents modifiées.');
            return $this->redirect($this->generateUrl('oph_familytree_view', array('id' => $person->getId())));
        }

        return $this->render('OphFamilytreeBundle:Leaf:editdocuments.html.twig', array(
          'person' => $person,
          'form' => $form->createView(),
        ));
    }
    
    public function editOneDocumentAction($id, $idDocument, Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('OphFamilytreeBundle:Person');
        $person = $repository->find($id);
        if (null === $person) {
            throw new NotFoundHttpException("La personne d'id ".$id." est introuvable.");
        }
        
        $repositoryPicture = $em->getRepository('OphFamilytreeBundle:Document');
        $document = $repositoryPicture->findOneById($idDocument);
        if (null === $document) 
        {
            throw new NotFoundHttpException("Le document d'id ".$idDocument." est introuvable.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->createForm(new DocumentEditOneType(), $document);
        
        $form->handleRequest($request);
        if ($form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($document);
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('notice', 'Document modifié.');
            return $this->redirect($this->generateUrl('oph_familytree_view', array('id' => $person->getId())));
        }

        return $this->render('OphFamilytreeBundle:Leaf:editonedocument.html.twig', array(
          'document' => $document,
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
        //    $person->uploadPictures();
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
   
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $persons = $em->getRepository('OphFamilytreeBundle:Person')->findAll();
        return $this->render('OphFamilytreeBundle:Leaf:index.html.twig', array('persons' => $persons,   ));
    }
    
    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $persons = $em->getRepository('OphFamilytreeBundle:Person')->findAll();
        return $this->render('OphFamilytreeBundle:Leaf:menu.html.twig', array('persons' => $persons,   ));
    }

    public function mapAction()
    {
        $em = $this->getDoctrine()->getManager();
        $persons = $em->getRepository('OphFamilytreeBundle:Person')->findAll();
        return $this->render('OphFamilytreeBundle:Leaf:map.html.twig', array('persons' => $persons,   ));
    }
    
    
   public function treeAction()
    {
        $personService = $this->container->get('oph_familytree.personservice');
        $personModel = $personService->getPersonModelById(/*4*/1);    
        
        return new JsonResponse($personModel);
    }
     
    
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('OphFamilytreeBundle:Person');
        $person = $repository->find($id);
        if (null === $person) {
            throw new NotFoundHttpException("La personne d'id ".$id." est introuvable.");
        }
        return $this->render('OphFamilytreeBundle:Leaf:view.html.twig', 
                       array(   'person' => $person,));
    }
    
}
