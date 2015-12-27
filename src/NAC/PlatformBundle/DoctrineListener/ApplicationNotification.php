<?php
// src/NAC/PlatformBundle/DoctrineListener/ApplicationNotification.php

namespace NAC\PlatformBundle\DoctrineListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use NAC\PlatformBundle\Entity\Application;


class ApplicationNotification
{
  private $mailer;

  public function __construct(\Swift_Mailer $mailer)
  {
    $this->mailer = $mailer;
  }

  public function postPersist(LifecycleEventArgs $args)
  {
    $entity = $args->getEntity();

    // On veut envoyer un email que pour les entités Application
    if (!$entity instanceof Application) {
      return;
    }

    $message = new \Swift_Message(
      'Nouvelle candidature',
      'Vous avez reçu une nouvelle candidature.'
    );
    
    $message
      ->addTo($entity->getAdvert()->getUrl()) // le contenu de l'attribut url est une adresse email
      ->addFrom('info@netafrica-consult.com')
    ;

    $this->mailer->send($message);
  }
}