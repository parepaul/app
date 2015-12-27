<?php

// src/NAC/PlatformBundle/Controller/AdvertController.php

namespace NAC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use NAC\PlatformBundle\Entity\Advert;
use NAC\PlatformBundle\Entity\Category;
use NAC\PlatformBundle\Entity\Image;
use NAC\PlatformBundle\Entity\Application;
use NAC\PlatformBundle\Entity\Contact;
use NAC\PlatformBundle\Form\AdvertType;
use NAC\PlatformBundle\Form\ApplicationType;
use NAC\PlatformBundle\Form\AdvertEditType;
use NAC\PlatformBundle\Form\CategoryType;
use NAC\PlatformBundle\Form\ContactType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
// N'oubliez pas ce use pour l'annotation
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdvertController extends Controller
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
    $listAdverts = $this->getDoctrine()
      ->getManager()
      ->getRepository('NACPlatformBundle:Advert')
      ->getAdverts($page, $nbPerPage)
    ;

    // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
    $nbPages = ceil(count($listAdverts)/$nbPerPage);

    // Si la page n'existe pas, on retourne une 404
    if ($page > $nbPages) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

    // On donne toutes les informations nécessaires à la vue
    return $this->render('NACPlatformBundle:Advert:index.html.twig', array(
      'listAdverts' => $listAdverts,
      'nbPages'     => $nbPages,
      'page'        => $page
    ));
  }

  public function viewAction($id)
  {
    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // Pour récupérer une annonce unique : on utilise find()
    $advert = $em->getRepository('NACPlatformBundle:Advert')->find($id);

    // On vérifie que l'annonce avec cet id existe bien
    if ($advert === null) {
      throw $this->createNotFoundException("L'annonce d'id ".$id." n'existe pas.");
    }
    
	
	// On récupère la liste des candidatures de cette annonce
    $listApplications = $em
      ->getRepository('NACPlatformBundle:Application')
      ->findBy(array('advert' => $advert))
    ;
	
    // On récupère la liste des advertSkill pour l'annonce $advert
    $listAdvertSkills = $em->getRepository('NACPlatformBundle:AdvertSkill')->findByAdvert($advert);
    
    // Puis modifiez la ligne du render comme ceci, pour prendre en compte les variables :
    return $this->render('NACPlatformBundle:Advert:view.html.twig', array(
      'advert'           => $advert,
	  'listApplications' => $listApplications,
      'listAdvertSkills' => $listAdvertSkills,
    ));
  }
  
  
  public function addAction(Request $request)
  {
    
	$advert = new Advert();
    $form = $this->createForm(new AdvertType(), $advert);

    if ($form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      return $this->redirect($this->generateUrl('nac_platform_view', array('id' => $advert->getId())));
    }

    // À ce stade :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau
    return $this->render('NACPlatformBundle:Advert:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em->getRepository('NACPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    $form = $this->createForm(new AdvertEditType(), $advert);

    if ($form->handleRequest($request)->isValid()) {
      // Inutile de persister ici, Doctrine connait déjà notre annonce
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      return $this->redirect($this->generateUrl('nac_platform_view', array('id' => $advert->getId())));
    }

    return $this->render('NACPlatformBundle:Advert:edit.html.twig', array(
      'form'   => $form->createView(),
      'advert' => $advert // Je passe également l'annonce à la vue si jamais elle veut l'afficher
    ));
  }

  public function deleteAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em->getRepository('NACPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'annonce contre cette faille
    $form = $this->createFormBuilder()->getForm();

    if ($form->handleRequest($request)->isValid()) {
      $em->remove($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

      return $this->redirect($this->generateUrl('nac_platform_home'));
    }

    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
    return $this->render('NACPlatformBundle:Advert:delete.html.twig', array(
      'advert' => $advert,
      'form'   => $form->createView()
    ));
  }
  
  public function menuAction($limit = 3)
  {
    $listAdverts = $this->getDoctrine()
      ->getManager()
      ->getRepository('NACPlatformBundle:Advert')
      ->findBy(
        array(),                 // Pas de critère
        array('date' => 'desc'), // On trie par date décroissante
        $limit,                  // On sélectionne $limit annonces
        0                        // À partir du premier
    );

    return $this->render('NACPlatformBundle:Advert:menu.html.twig', array(
      'listAdverts' => $listAdverts
    ));
  }
   
  public function appAction($id, Request $request)
  {
    // On récupère l'annonce $id
	$em = $this->getDoctrine()->getManager();
    $advert = $em->getRepository('NACPlatformBundle:Advert')->find($id);
	
	$application = new Application();
    $form = $this->createForm(new ApplicationType(), $application);

    if ($form->handleRequest($request)->isValid()) {
      $em_app = $this->getDoctrine()->getManager();
      
	  // On lie les candidatures à l'annonce
	  $application->setAdvert($advert);
	  
	  // Étape 1 : On « persiste » l'entité advert
	  $em_app->persist($advert);
	  
	   // Étape 2 : On « persiste » l'entité application
	  $em_app->persist($application);
	  
      $em_app->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Félicitation! Votre inscription à été validée.');
	
	// Ici le mail de confirmation de l'annonceur
	  $message = \Swift_Message::newInstance()
            ->setSubject("Nouvelle inscription à votre formation")
			->setFrom(array('netafricaconsult@gmail.com' => "Net Africa Consult"))
            ->setTo($application->getAdvert()->getUrl())
			->setCharset('utf-8')
            ->setContentType('text/html')
			->setBody($this->renderView('NACPlatformBundle:Advert:validation.html.twig', array('advert' => $advert)));

        $this->get('mailer')->send($message);
	
	// Ici le mail de confirmation de l'annonceur
	  $message_stagiaire = \Swift_Message::newInstance()
            ->setSubject("Inscription à une formation")
			->setFrom(array('netafricaconsult@gmail.com' => "Net Africa Consult"))
            ->setTo($application->getEmail())
			->setCharset('utf-8')
            ->setContentType('text/html')
			->setBody($this->renderView('NACPlatformBundle:Advert:inscription.html.twig', array('advert' => $advert, 'application' => $application)));

        $this->get('mailer')->send($message_stagiaire);
		
      return $this->redirect($this->generateUrl('nac_platform_view', array('id' => $advert->getId())));
	  
    }

    // À ce stade :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau
    return $this->render('NACPlatformBundle:Advert:app.html.twig', array(
      'form' => $form->createView(),
	  'advert' => $advert,
    ));
  }
  
   public function addcategorieAction(Request $request)
  {
    
	$category = new Category();
    $form = $this->createForm(new CategoryType(), $category);

    if ($form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($category);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Categorie bien enregistrée.');

      return $this->redirect($this->generateUrl('nac_platform_home'));
    }

    // À ce stade :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau
    return $this->render('NACPlatformBundle:Advert:addcategory.html.twig', array(
      'form' => $form->createView(),
    ));
  }
  
   public function contactAction()
   {
		$contact = new Contact();
		$form = $this->createForm(new ContactType(), $contact);
        
		//$mail = $contact->getEmail();
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->handleRequest($request);

			if ($form->isValid()) {
				// Perform some action, such as sending an email
                $message = \Swift_Message::newInstance()
				->setSubject($contact->getSubject())
				//->setFrom(array('associationappat@gmail.com' => "Net Africa Consult"))
				->setFrom(array($contact->getEmail() => $contact->getName()))
				//->setFrom($mail)
				->setTo('info@netafrica-consult.com')
				->setBody($this->renderView('NACPlatformBundle:Advert:contactEmail.txt.twig', array('contact' => $contact)));
			$this->get('mailer')->send($message);
			
			// Redirect - This is important to prevent users re-posting
				// the form if they refresh the page
				return $this->redirect($this->generateUrl('nac_platform_home'));
				
			}
		}

		return $this->render('NACPlatformBundle:Advert:contact.html.twig', array(
			'contact'           => $contact,
			'form' => $form->createView()
		));
	}
  
}