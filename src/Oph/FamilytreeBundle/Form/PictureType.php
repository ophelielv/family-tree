<?php

namespace Oph\FamilytreeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PictureType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('img',  new ImgType(),  array('label'=>'Charger une image'))
            ->add('datePicture', 'birthday', array('label'=>'Date', 'years' => range(1800, date('Y')),'required' => false, 'empty_value' => '--'))
            //->add('image') généré auto
            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Oph\FamilytreeBundle\Entity\Picture'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oph_familytreebundle_picture';
    }
}
