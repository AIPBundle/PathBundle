<?php

namespace Aip\ProfilageBundle\Form;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\SecurityContext;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Aip\ProfilageBundle\Entity\UserParcours;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class EtapeType extends AbstractType
{
	

	
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('nom', 'text', array('required' => false));
    	$builder->add('description', 'text', array('required' => false));
    	$etape=$options['data'];
    	$user=$etape->getCreator();
 
   
        $builder->add('ressource','entity',array(
    			'class'=>'ClarolineCoreBundle:Resource\ResourceNode',
    			'property' => 'Name',
    			
        		'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($user)
        		{
        			
        			return $er->createQueryBuilder('r')->where('r.creator = :id')->setParameter('id',$user->getId());
        		},
    	
    	));
    	
    	$builder->add('competence','entity',array(
    			'class'=>'AipProfilageBundle:GrilleCompetence',
    			'property' => 'Titre',
    			'mapped' => false,
    			'mapped' => false,
    			'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($user)
    			{
    				 
    				return $er->createQueryBuilder('r')->where('r.creator = :id')->setParameter('id',$user->getId());
    			},
    			
    	
    	));
    	$builder->add('conditionvisibilite','entity',array(
    			'class'=>'AipProfilageBundle:GrilleCompetence',
    			'property' => 'Titre',
    			'mapped' => false,
    			'mapped' => false,
    			'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($user)
    			{
    					
    				return $er->createQueryBuilder('r')->where('r.creator = :id')->setParameter('id',$user->getId());
    			},
    			 
    			 
    	));
    	 
    	$builder->add(
    			'initial',
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
    			'translation_domain' => 'parcours'
    	));
    }
    
    /**
     * @return string
     */
    public function getName()
    {
    	return 'parcours_form';
    }
}
