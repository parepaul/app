<?php
// src/NAC/PlatformBundle/Form/AdvertType.php

namespace NAC\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdvertType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('date',      'date')
      ->add('title',     'text')
      ->add('author',    'text')
	  ->add('url',    'text')
	  ->add('reference',    'text')
	  ->add('tarif',    'text')
	  ->add('duree',    'text')
      ->add('content',   'textarea')
	  ->add('pourqui',   'textarea')
	  ->add('prerequis',   'textarea')
	  ->add('objectif',   'textarea')
	  ->add('planning',   'textarea')
	  ->add('lieu',   'textarea')
	  ->add('compte',   'text')
	  ->add('ville',   'text')
      ->add('image',      new ImageType())
      ->add('categories', 'entity', array(
        'class'    => 'NACPlatformBundle:Category',
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
      'data_class' => 'NAC\PlatformBundle\Entity\Advert'
    ));
  }

  /**
   * @return string
   */
  public function getName()
  {
    return 'nac_platformbundle_advert';
  }
}