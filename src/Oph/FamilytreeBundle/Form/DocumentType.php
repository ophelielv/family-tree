<?php

namespace Oph\FamilytreeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('img',            new ImgType(),   array('label' => 'Choisir un fichier','required' => false))
            ->add('title',          'text',     array('label'=>'Titre') ) 
            ->add('description',    'textarea', array( 'attr' => array('cols' => '100', 'rows' => '8') ,'label'=>'Description','required' => false))
            ->add('dateDocument',   'birthday', array('label'=>'Date',  'years' => range(1800, date('Y')), 'empty_value' => '--', 'required' => false))
            ;
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Oph\FamilytreeBundle\Entity\Document'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oph_familytreebundle_document';
    }
}
