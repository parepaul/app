<?php

namespace NAC\JobBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * JobRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class JobRepository extends EntityRepository
{
  public function getJobs($page, $nbPerPage)
  {
    $query = $this->createQueryBuilder('a')
      // Jointure sur l'attribut categories
      ->leftJoin('a.jobcategories', 'c')
      ->addSelect('c')
      ->orderBy('a.date', 'DESC')
      ->getQuery()
    ;
    $query
      // On définit l'annonce à partir de laquelle commencer la liste
      ->setFirstResult(($page-1) * $nbPerPage)
      // Ainsi que le nombre d'annonce à afficher sur une page
      ->setMaxResults($nbPerPage)
    ;

    // Enfin, on retourne l'objet Paginator correspondant à la requête construite
    // (n'oubliez pas le use correspondant en début de fichier)
    return new Paginator($query, true);
  }
}