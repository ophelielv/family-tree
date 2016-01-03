<?php

namespace Oph\FamilytreeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OphFamilytreeBundle:Default:index.html.twig', array('name' => $name));
    }
}
