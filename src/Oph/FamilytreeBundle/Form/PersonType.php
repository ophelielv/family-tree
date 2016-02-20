<?php

namespace Oph\FamilytreeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',      'text',     array('label'=>'Prénom'))
            ->add('name',           'text',     array('label'=>'Nom'))
            ->add('gender',         'choice',   array('label'=>'Genre','choices'  => array('M' => 'Homme', 'F' => 'Femme')) )
            ->add('dateOfBirth',    'birthday', array('label'=>'Date de naissance', 'years' => range(1800, date('Y')), 'empty_value' => '--', 'required' => false))
            ->add('placeOfBirth',   'text',     array('label'=>'à', 'required' => false))
            ->add('latitudeBirth',  'number',   array('label'=>'Latitude', 'required' => false))
            ->add('longitudeBirth', 'number',   array('label'=>'Longitude', 'required' => false))
            ->add('dateOfDeath',    'birthday', array('label'=>'Date de décès', 'years' => range(1800, date('Y')), 'empty_value' => '--','empty_data'  => null, 'required' => false))
            ->add('placeOfDeath',   'text',     array('label'=>'à', 'required' => false))
            ->add('tempMother',     'entity',   array('class'=>'OphFamilytreeBundle:Person', 'required'  => false, 'property' => 'completeName',  'empty_value' => '--',    'empty_data'  => null, 'expanded' => false, 'multiple' => false))
            ->add('tempFather',     'entity',   array('class'=>'OphFamilytreeBundle:Person', 'required'  => false, 'property' => 'completeName',  'empty_value' => '--',    'empty_data'  => null, 'expanded' => false, 'multiple' => false))
            ->add('tempChild',      'entity',   array('class'=>'OphFamilytreeBundle:Person', 'required'  => false, 'property' => 'completeName',  'empty_value' => '--',    'empty_data'  => null, 'expanded' => false, 'multiple' => false))
            ->add('img',    new ImgType(),      array('label' => 'Choisir un fichier','required' => false))
            ->add('Enregistrer',    'submit');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Oph\FamilytreeBundle\Entity\Person'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oph_familytreebundle_person';
    }
}
