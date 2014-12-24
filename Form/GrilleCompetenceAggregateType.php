<?php

namespace Aip\ProfilageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GrilleCompetenceAggregateType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('resourceNode')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aip\ProfilageBundle\Entity\GrilleCompetenceAggregate'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aip_profilagebundle_grillecompetenceaggregate';
    }
}
