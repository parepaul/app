<?php
// src/NAC/JobBundle/Form/JobType.php

namespace NAC\JobBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class JobType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',      'date')
            ->add('title',     'text')
            ->add('author',    'text')
            ->add('content',   'textarea')
            ->add('experience',   'textarea')
            ->add('mode',   'textarea')
			->add('description',   'textarea')
			->add('composition',   'textarea')
            ->add('localite',    'text')
            ->add('postule',   'textarea')
            ->add('jobcategories', 'entity', array(
				'class'    => 'NACJobBundle:JobCategory',
				'property' => 'name',
				'multiple' => true,
				'expanded' => false
			  ))
			->add('Soumettre',      'submit')
        ;
     
		 // On ajoute une fonction qui va écouter l'évènement PRE_SET_DATA
		$builder->addEventListener(
		  FormEvents::PRE_SET_DATA,
		  function(FormEvent $event) {
			// On récupère notre objet Advert sous-jacent
			$advert = $event->getData();

			if (null === $advert) {
			  return;
			}

			if (!$advert->getPublished() || null === $advert->getId()) {
			  $event->getForm()->add('published', 'checkbox', array('required' => false));
			} else {
			  $event->getForm()->remove('published');
			}
		  }
		);
    }
	
	/**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NAC\JobBundle\Entity\Job'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'nac_jobbundle_job';
    }
}
