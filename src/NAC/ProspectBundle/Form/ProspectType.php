<?php

namespace NAC\ProspectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProspectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder	
			->add('date',      'date')
			->add('titre',     'text')
			->add('localite',     'text')
			->add('tel',    'text')
			->add('email',    'text')
			->add('resume',    'textarea')
			->add('content',   'textarea')
			->add('domaines', 'entity', array(
				  'class'    => 'NACProspectBundle:Domaine',
				  'property' => 'name',
				  'multiple' => true
				))
			->add('save',      'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NAC\ProspectBundle\Entity\Prospect'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'nac_prospectbundle_prospect';
    }
}
