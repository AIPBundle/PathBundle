<?php

namespace Aip\ProfilageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PreferenceType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
          $builder->add('titre', 'text', array('required' => false));
        
       
        $builder->add(
        		'pratique',
        		'checkbox',
        		array(
        				'required' => false,
        				'attr' => array('class' => 'visible-chk')
        		)
        );
        $builder->add(
        		'theorique',
        		'checkbox',
        		array(
        				'required' => false,
        				'attr' => array('class' => 'visible-chk')
        		)
        );
        $builder->add(
        		'videos',
        		'checkbox',
        		array(
        				'required' => false,
        				'attr' => array('class' => 'visible-chk')
        		)
        );
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aip\ProfilageBundle\Entity\Preference',
        	'translation_domain' => 'preference'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'preference_form';
    }
}
