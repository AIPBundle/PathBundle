<?php

namespace Aip\ProfilageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EnsembleCompetenceType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        $builder->add('titreens', 'text', array('required' => false));
    	$builder->add('defens', 'text', array('required' => false));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
             'translation_domain' => 'ensemblecompetence'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ensemblecompetence_form';
    }
}
