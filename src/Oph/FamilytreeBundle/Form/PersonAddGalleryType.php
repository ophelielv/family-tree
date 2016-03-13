<?php

namespace Oph\FamilytreeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonAddGalleryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('uploadedPictures', 'collection', array(  'type'=> new PictureType(), 
                                                            'label'=>'Galerie',
                                                            'allow_add'=> true, 
                                                            'allow_delete' => true,
                                                            'attr' => array('multiple' => 'multiple')))
                          ->add('Enregistrer',    'submit', array('label'=>'Envoyer et enregistrer',
                                                            'attr' =>array('class' =>'btn btn-warning')));
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
