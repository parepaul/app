<?php
// src/NAC/JobBundle/Form/JobEditType.php

namespace NAC\JobBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class JobEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->remove('date');
  }

  public function getName()
  {
    return 'job_jobplatform_edit';
  }

  public function getParent()
  {
    return new JobType();
  }
}