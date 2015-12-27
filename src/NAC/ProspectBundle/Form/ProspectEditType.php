<?php

namespace NAC\ProspectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProspectEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->remove('date');
  }

  public function getName()
  {
    return 'nac_prospectbundle_prospect_edit';
  }

  public function getParent()
  {
    return new ProspectType();
  }
}
