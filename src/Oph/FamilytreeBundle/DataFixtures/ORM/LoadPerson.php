<?php 
// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Oph\FamilytreeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oph\FamilytreeBundle\Entity\Person;

class LoadPerson implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        $andre = new Person();
        $andre->setFirstName('André');
        $andre->setName('Salleyrettes');
        $andre->setDateOfBirth('1891-05-12 00:00:01');//12 mai 1891 à Dijon // YYYY-MM-DD HH:MM:SS 
        $andre->setPlaceOfBirth('Dijon (Côte d\'or)');
        $andre->setDateOfDeath('1914-08-19 00:00:01');// 19 aout 1914 à Hersbach (Alsace()
        $andre->setPlaceOfDeath('Hersbach (Alsace)');
        $andre->setGeorgesFamily(1);
        $andre->setMan(1);
        
        $daniel = new Person();
        $daniel->setFirstName('Daniel');
        $daniel->setName('Ponce Guillen');
        $daniel->setDateOfBirth('1903-06-06');// 6 juin 1903 Sax (alicante) // YYYY-MM-DD HH:MM:SS 
        $daniel->setPlaceOfBirth('Sax (Alicante, Espagne)');
        $daniel->setDateOfDeath('1975-03-08');// 8 mars 1975 Salon
        $daniel->setPlaceOfDeath('Salon de Provence (Bouches-du-Rhône)');
        $daniel->setAnitasFamily(1);
        $daniel->setMan(1);

        $manager->persist($andre);
        $manager->persist($daniel);
        $manager->flush();
   } 
}
/* openclassroom :
  public function load(ObjectManager $manager)
  {
    // Liste des noms de catégorie à ajouter
    $names = array(
      'Développement web',
      'Développement mobile',
      'Graphisme',
      'Intégration',
      'Réseau'
    );

    foreach ($names as $name) {
      // On crée la catégorie
      $category = new Category();
      $category->setName($name);

      // On la persiste
      $manager->persist($category);
    }

    // On déclenche l'enregistrement de toutes les catégories
    $manager->flush();*/