<?php

namespace Aip\ProfilageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use  Aip\ProfilageBundle\Entity\Cycle;
class CycleDeVieCompetenceType extends AbstractType
{
	
	private $defcycle;
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
	/**
	 * Constructor.
	 *
	 * @param Cycle[] $defcycle
	 */
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titrecycle', 'text', array('required' => false));
        
        
        $builder->add(
        		'encours',
        		'checkbox',
        		array(
        				'required' => false,
        				'attr' => array('class' => 'visible-chk')
        		)
        );
        $builder->add(
        		'acquistheorique',
        		'checkbox',
        		array(
        				'required' => false,
        				'attr' => array('class' => 'visible-chk')
        		)
        );
        $builder->add(
        		'acquispratique',
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
            'translation_domain' => 'cycledeviecompetence'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cycledeviecompetence_form';
    }
}
