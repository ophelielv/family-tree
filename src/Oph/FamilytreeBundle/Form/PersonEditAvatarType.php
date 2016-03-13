<?php

namespace Oph\FamilytreeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonEditAvatarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('img',            new ImgType(),   array('label' => 'Choisir un fichier','required' => false))
                ->add('Enregistrer',    'submit', array('label'=>'Enregistrer',
                                                    'attr' =>array('class' =>'btn btn-warning')));;
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
        return 'oph_familytreebundle_img';
    }
}
