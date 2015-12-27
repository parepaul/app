<?php

// src/NAC/JobBundle/Controller/JobController.php

namespace NAC\JobBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use NAC\JobBundle\Entity\Job;
use NAC\JobBundle\Entity\JobCategory;
use NAC\JobBundle\Form\JobType;
use NAC\JobBundle\Form\JobEditType;
use NAC\JobBundle\Form\JobCategoryType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

// N'oubliez pas ce use pour l'annotation
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class JobController extends Controller
{
  public function indexAction($page)
  {
    if ($page < 1) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

    // Ici je fixe le nombre d'annonces par page à 15
    // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
    $nbPerPage = 15;

    // On récupère notre objet Paginator
    $listJobs = $this->getDoctrine()
      ->getManager()
      ->getRepository('NACJobBundle:Job')
      ->getJobs($page, $nbPerPage)
    ;

    // On calcule le nombre total de pages grâce au count($listJobs) qui retourne le nombre total d'annonces
    $nbPages = ceil(count($listJobs)/$nbPerPage);

    // Si la page n'existe pas, on retourne une 404
    if ($page > $nbPages) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

    // On donne toutes les informations nécessaires à la vue
    return $this->render('NACJobBundle:Job:index.html.twig', array(
      'listJobs' => $listJobs,
      'nbPages'     => $nbPages,
      'page'        => $page
    ));
  }
  
  public function viewAction($id)
  {
    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // Pour récupérer une annonce unique : on utilise find()
    $job = $em->getRepository('NACJobBundle:Job')->find($id);

    // On vérifie que l'annonce avec cet id existe bien
    if ($job === null) {
      throw $this->createNotFoundException("L'annonce d'id ".$id." n'existe pas.");
    }
    
    // Puis modifiez la ligne du render comme ceci, pour prendre en compte les variables :
    return $this->render('NACJobBundle:Job:view.html.twig', array(
      'job'           => $job,
    ));
  }
  
  public function addAction(Request $request)
  {
    
	$job = new Job();
    $form = $this->createForm(new JobType(), $job);

    if ($form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($job);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      return $this->redirect($this->generateUrl('job_jobplatform_view', array('id' => $job->getId())));
    }

    // À ce stade :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau
    return $this->render('NACJobBundle:Job:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }
  
  public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $job = $em->getRepository('NACJobBundle:Job')->find($id);

    if (null === $job) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    $form = $this->createForm(new JobEditType(), $job);

    if ($form->handleRequest($request)->isValid()) {
      // Inutile de persister ici, Doctrine connait déjà notre annonce
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      return $this->redirect($this->generateUrl('job_jobplatform_view', array('id' => $job->getId())));
    }

    return $this->render('NACJobBundle:Job:edit.html.twig', array(
      'form'   => $form->createView(),
      'job' => $job // Je passe également l'annonce à la vue si jamais elle veut l'afficher
    ));
  }
  
  public function deleteAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $job = $em->getRepository('NACJobBundle:Job')->find($id);

    if (null === $job) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'annonce contre cette faille
    $form = $this->createFormBuilder()->getForm();

    if ($form->handleRequest($request)->isValid()) {
      $em->remove($job);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

      return $this->redirect($this->generateUrl('job_jobplatform_home'));
    }

    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
    return $this->render('NACJobBundle:Job:delete.html.twig', array(
      'job' => $job,
      'form'   => $form->createView()
    ));
  }
  
  public function menuAction($limit = 7)
  {
    $listJobs = $this->getDoctrine()
      ->getManager()
      ->getRepository('NACJobBundle:Job')
      ->findBy(
        array(),                 // Pas de critère
        array('date' => 'desc'), // On trie par date décroissante
        $limit,                  // On sélectionne $limit annonces
        0                        // À partir du premier
    );

    return $this->render('NACJobBundle:Job:menu.html.twig', array(
      'listJobs' => $listJobs
    ));
  }
  
  public function addjobcategorieAction(Request $request)
  {
    
	$jobcategory = new JobCategory();
    $form = $this->createForm(new JobCategoryType(), $jobcategory);

    if ($form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($jobcategory);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Job Categorie bien enregistrée.');

      return $this->redirect($this->generateUrl('job_jobplatform_home'));
    }

    // À ce stade :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau
    return $this->render('NACJobBundle:Job:addjobcategory.html.twig', array(
      'form' => $form->createView(),
    ));
  }
  
 
  
}