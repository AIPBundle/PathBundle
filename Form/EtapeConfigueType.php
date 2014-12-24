<?php

namespace Aip\ProfilageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EtapeConfigueType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
    	$cycle=$options['data'];
    	$cycleid=$cycle->getCycleid();
    	$cycleconditionid=$cycle->getCycleconditionid();
    	$builder->add('cycledevie','entity',array(
    			'class'=>'AipProfilageBundle:CycleVie',
    			'property' => 'Nom',
    			 
    			'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($cycleid)
    			{
    				 
    				return $er->createQueryBuilder('r')->where('r.cycle = :id')->setParameter('id',$cycleid);
    			},
    			 
    	));
    	$builder->add('conditionusager','entity',array(
    			'class'=>'AipProfilageBundle:CycleVie',
    			'property' => 'Nom',
    			'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($cycleconditionid)
    			{
    					
    				return $er->createQueryBuilder('r')->where('r.cycle = :id')->setParameter('id',$cycleconditionid);
    			},
    			
    	
    	));
    	 
    	
    	
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aip\ProfilageBundle\Entity\EtapeConfigue',
        		'translation_domain' => 'parcours'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'form_etapeconfigue';
    }
}
