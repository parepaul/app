<?php

// src/NAC/ProspectBundle/Controller/ProspectController.php

namespace NAC\ProspectBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use NAC\ProspectBundle\Entity\Prospect;
use NAC\ProspectBundle\Entity\Domaine;
use NAC\ProspectBundle\Form\ProspectType;
use NAC\ProspectBundle\Form\ProspectEditType;
use NAC\ProspectBundle\Form\DomaineType;
use NAC\ProspectBundle\Entity\Enquiry;
use NAC\ProspectBundle\Form\EnquiryType;


class ProspectController extends Controller
{
  public function indexAction($page)
  {
    if ($page < 1) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

    // Ici je fixe le nombre d'annonces par page à 3
    // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
    $nbPerPage = 15;

    // On récupère notre objet Paginator
    $listProspects = $this->getDoctrine()
      ->getManager()
      ->getRepository('NACProspectBundle:Prospect')
      ->getProspects($page, $nbPerPage)
    ;

    // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
    $nbPages = ceil(count($listProspects)/$nbPerPage);

    // Si la page n'existe pas, on retourne une 404
    if ($page > $nbPages) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

    // On donne toutes les informations nécessaires à la vue
    return $this->render('NACProspectBundle:Prospect:index.html.twig', array(
      'listProspects' => $listProspects,
      'nbPages'     => $nbPages,
      'page'        => $page
    ));
  }

  public function viewAction($id)
  {
    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // Pour récupérer une annonce unique : on utilise find()
    $prospect = $em->getRepository('NACProspectBundle:Prospect')->find($id);
	
	$mail = $prospect->getEmail();

    // On vérifie que l'annonce avec cet id existe bien
    if ($prospect === null) {
      throw $this->createNotFoundException("L'annonce d'id ".$id." n'existe pas.");
    }

        $enquiry = new Enquiry();
		$form = $this->createForm(new EnquiryType(), $enquiry);
        
		//$subject = $enquiry->getSubject();
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->handleRequest($request);
            
			if ($form->isValid()) {
				// Perform some action, such as sending an email
                $message = \Swift_Message::newInstance()
				->setSubject($enquiry->getSubject())
				//->setFrom(array('netafricaconsult@gmail.com' => "Net Africa Consult"))
				->setFrom(array($enquiry->getEmail() => $enquiry->getName()))
				->setTo($mail)
				->setBody($this->renderView('NACProspectBundle:Prospect:contactEmail.txt.twig', array('enquiry' => $enquiry)));
			$this->get('mailer')->send($message);

			$this->get('session')->getFlashBag()->Add('notice', 'Votre demande de contact a bien été envoyé. Merci!');
			
				// Redirect - This is important to prevent users re-posting
				// the form if they refresh the page
				//return $this->redirect($this->generateUrl('nac_prospect_view'));
				return $this->redirect($this->generateUrl('nac_prospect_view', array('id' => $prospect->getId())));
			}
		}

	 
    // Puis modifiez la ligne du render comme ceci, pour prendre en compte les variables :
    return $this->render('NACProspectBundle:Prospect:view.html.twig', array(
      'prospect'           => $prospect,
	  'form' => $form->createView()
    ));
  }

 public function addAction(Request $request)
  {
    $prospect = new Prospect();
    $form = $this->createForm(new ProspectType(), $prospect);

    if ($form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($prospect);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Prospect bien enregistrée.');

      return $this->redirect($this->generateUrl('nac_prospect_view', array('id' => $prospect->getId())));
    }

    // À ce stade :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau
    return $this->render('NACProspectBundle:Prospect:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $prospect = $em->getRepository('NACProspectBundle:Prospect')->find($id);

    if (null === $prospect) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    $form = $this->createForm(new ProspectEditType(), $prospect);

    if ($form->handleRequest($request)->isValid()) {
      // Inutile de persister ici, Doctrine connait déjà notre annonce
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      return $this->redirect($this->generateUrl('nac_prospect_view', array('id' => $prospect->getId())));
    }
    
	
    return $this->render('NACProspectBundle:Prospect:edit.html.twig', array(
      'form'   => $form->createView(),
      'prospect' => $prospect // Je passe également l'annonce à la vue si jamais elle veut l'afficher
    ));
  }

  public function deleteAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $prospect = $em->getRepository('NACProspectBundle:Prospect')->find($id);

    if (null === $prospect) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'annonce contre cette faille
    $form = $this->createFormBuilder()->getForm();

    if ($form->handleRequest($request)->isValid()) {
      $em->remove($prospect);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

      return $this->redirect($this->generateUrl('nac_prospect_home'));
    }

    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
    return $this->render('NACProspectBundle:Prospect:delete.html.twig', array(
      'prospect' => $prospect,
      'form'   => $form->createView()
    ));
  }
    
 public function menuprospectAction($limite = 11)
  {
    $listProspects = $this->getDoctrine()
      ->getManager()
      ->getRepository('NACProspectBundle:Prospect')
      ->findBy(
        array(),                 // Pas de critère
        array('date' => 'desc'), // On trie par date décroissante
        $limite,                  // On sélectionne $limit annonces
        0                        // À partir du premier
    );

    return $this->render('NACProspectBundle:Prospect:menuprospect.html.twig', array(
      'listProspects' => $listProspects
    ));
  }
  
    public function adddomaineAction(Request $request)
  {
    
	$domaine = new Domaine();
    $form = $this->createForm(new DomaineType(), $domaine);

    if ($form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($domaine);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Domaine bien enregistrée.');

      return $this->redirect($this->generateUrl('nac_prospect_home'));
    }

    // À ce stade :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau
    return $this->render('NACProspectBundle:Prospect:adddomaine.html.twig', array(
      'form' => $form->createView(),
    ));
  }
  
  
}