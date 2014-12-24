<?php

namespace Aip\ProfilageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GrilleCompetenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('titre', 'text', array('required' => false));
    	$builder->add('createurdegrille', 'text', array('required' => false));
    	$builder->add(
    			'description',
    			'textarea',
    			array(
    					'required' => true,
    					'attr' => array(
    							'class' => 'tinymce',
    							'data-theme' => 'medium'
    					)
    			)
    	);
    	$builder->add(
    			'visible',
    			'checkbox',
    			array(
    					'required' => false,
    					'attr' => array('class' => 'visible-chk')
    			)
    	);
    	

    	$attr = array();
    	$attr['class'] = 'datepicker input-small';
    	$attr['data-date-format'] = 'dd-mm-yyyy';
    	$attr['autocomplete'] = 'off';
    	
    	$builder->add(
    			'visible_from',
    			'date',
    			array(
    					'attr' => $attr,
    					'required' => false,
    					'format' => 'dd-MM-yyyy',
    					'widget' => 'single_text',
    					'input' => 'datetime'
    			)
    	);
    	$builder->add(
    			'visible_until',
    			'date',
    			array(
    					'required' => false,
    					'format' => 'dd-MM-yyyy',
    					'widget' => 'single_text',
    					'attr' => $attr,
    					'input' => 'datetime'
    			)
    	);
    	$grillecompetence=$options['data'];
    	$aggregate=$grillecompetence->getAggregate();
    	$builder->add('cycledevie','entity',array(
    			'class'=>'AipProfilageBundle:CycleDeVieCompetence',
    			'property' => 'titrecycle',
    			'mapped' => false,
    			'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($aggregate)
    			{
    			
    				return $er->createQueryBuilder('r')->where('r.aggregate = :id')->setParameter('id',$aggregate->getId());
    			},
    			 
    			 
    	));
    	$builder->add('ensembledef','entity',array(
    			'class'=>'AipProfilageBundle:EnsembleCompetence',
    			'property' => 'Titreens',
    			'mapped' => false,
    			'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($aggregate)
    			{
    				 
    				return $er->createQueryBuilder('r')->where('r.aggregate = :id')->setParameter('id',$aggregate->getId());
    			},
    	
    	
    	));
    	
        
    }

    public function getName()
    {
        return 'grillecompetence_form';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'grillecompetence'
            )
        );
    }
}