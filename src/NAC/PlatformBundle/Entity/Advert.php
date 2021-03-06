<?php

namespace NAC\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
// N'oubliez pas de rajouter ce « use », il définit le namespace pour les annotations de validation
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Advert
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="NAC\PlatformBundle\Entity\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 */

class Advert
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
	 * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
	 * @Assert\Length(min=10)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
	 * @Assert\Length(min=2)
     */
    private $author;
	
	/**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;
	
	/**
     * @var string
     *
     * @ORM\Column(name="tarif", type="string", length=255)
     */
    private $tarif;
	
	/**
     * @var string
     *
     * @ORM\Column(name="duree", type="string", length=255)
     */
    private $duree;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
	 * @Assert\NotBlank()
     */
    private $content;
	
	/**
     * @var string
     *
     * @ORM\Column(name="pourqui", type="text")
     */
    private $pourqui;
	
	/**
     * @var string
     *
     * @ORM\Column(name="prerequis", type="text")
     */
    private $prerequis;
	
	/**
     * @var string
     *
     * @ORM\Column(name="objectif", type="text")
     */
    private $objectif;

	/**
	 * @ORM\Column(name="published", type="boolean")
	 */
    private $published = true;

    /**
     * @var string
     *
     * @ORM\Column(name="planning", type="text")
     */
    private $planning;
	
	/**
     * @var string
     *
     * @ORM\Column(name="lieu", type="text")
     */
    private $lieu;
	
	/**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;
	
	/**
     * @var string
     *
     * @ORM\Column(name="compte", type="string", length=255)
     */
    private $compte;
	
	/**
     * @ORM\OneToOne(targetEntity="NAC\PlatformBundle\Entity\Image", cascade={"persist"})
	 * @Assert\Valid()
     */
    private $image;
	
	/**
     * @ORM\ManyToMany(targetEntity="NAC\PlatformBundle\Entity\Category", cascade={"persist"})
     */
    private $categories;
  
	/**
	 * @ORM\OneToMany(targetEntity="NAC\PlatformBundle\Entity\Application", mappedBy="advert")
	 */
    private $applications; // Notez le « s », une annonce est liée à plusieurs candidatures

	/**
	 * @ORM\Column(name="updated_at", type="datetime", nullable=true)
	 */
	private $updatedAt;

	/**
	 * @ORM\Column(name="nb_applications", type="integer")
	 */
	private $nbApplications = 0;
	
	/**
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;
	  
	
	/**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Advert
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }
	
		
	public function setImage(Image $image = null)
	{
		$this->image = $image;
	}

	public function getImage()
	{
		return $this->image;
	}
	
	public function __construct()
    {
		$this->date = new \Datetime();
		$this->categories = new ArrayCollection();
		$this->applications = new ArrayCollection();
    }

  // Notez le singulier, on ajoute une seule catégorie à la fois
    public function addCategory(Category $category)
	{
		// Ici, on utilise l'ArrayCollection vraiment comme un tableau
		$this->categories[] = $category;

		return $this;
	}

    public function removeCategory(Category $category)
	{
		// Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
		$this->categories->removeElement($category);
	}

  // Notez le pluriel, on récupère une liste de catégories ici !
    public function getCategories()
	{
		return $this->categories;
	}
  

    /**
     * Add applications
     *
     * @param \NAC\PlatformBundle\Entity\Application $applications
     * @return Advert
     */
    public function addApplication(\NAC\PlatformBundle\Entity\Application $applications)
    {
        $this->applications[] = $applications;
	// On lie l'annonce à la candidature
		$applications->setAdvert($this);
        
		return $this;
    }

    /**
     * Remove applications
     *
     * @param \NAC\PlatformBundle\Entity\Application $applications
     */
    public function removeApplication(\NAC\PlatformBundle\Entity\Application $applications)
    {
        $this->applications->removeElement($applications);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Advert
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
	
	/**
	 * @ORM\PreUpdate
	 */
	public function updateDate()
	{
		$this->setUpdatedAt(new \Datetime());
	}
	
	/**
     * @ORM\PrePersist
     */
	public function increaseApplication()
	{
		$this->nbApplications++;
	}
    
	/**
     * @ORM\PreRemove
     */
    public function decreaseApplication()
    {
		$this->nbApplications--;
	}

    /**
     * Set nbApplications
     *
     * @param integer $nbApplications
     * @return Advert
     */
    public function setNbApplications($nbApplications)
    {
        $this->nbApplications = $nbApplications;

        return $this;
    }

    /**
     * Get nbApplications
     *
     * @return integer 
     */
    public function getNbApplications()
    {
        return $this->nbApplications;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Advert
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set reference
     *
     * @param string $reference
     * @return Advert
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string 
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set tarif
     *
     * @param string $tarif
     * @return Advert
     */
    public function setTarif($tarif)
    {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * Get tarif
     *
     * @return string 
     */
    public function getTarif()
    {
        return $this->tarif;
    }

    /**
     * Set duree
     *
     * @param string $duree
     * @return Advert
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return string 
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set pourqui
     *
     * @param string $pourqui
     * @return Advert
     */
    public function setPourqui($pourqui)
    {
        $this->pourqui = $pourqui;

        return $this;
    }

    /**
     * Get pourqui
     *
     * @return string 
     */
    public function getPourqui()
    {
        return $this->pourqui;
    }

    /**
     * Set prerequis
     *
     * @param string $prerequis
     * @return Advert
     */
    public function setPrerequis($prerequis)
    {
        $this->prerequis = $prerequis;

        return $this;
    }

    /**
     * Get prerequis
     *
     * @return string 
     */
    public function getPrerequis()
    {
        return $this->prerequis;
    }

    /**
     * Set objectif
     *
     * @param string $objectif
     * @return Advert
     */
    public function setObjectif($objectif)
    {
        $this->objectif = $objectif;

        return $this;
    }

    /**
     * Get objectif
     *
     * @return string 
     */
    public function getObjectif()
    {
        return $this->objectif;
    }

    /**
     * Set planning
     *
     * @param string $planning
     * @return Advert
     */
    public function setPlanning($planning)
    {
        $this->planning = $planning;

        return $this;
    }

    /**
     * Get planning
     *
     * @return string 
     */
    public function getPlanning()
    {
        return $this->planning;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Advert
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set compte
     *
     * @param string $compte
     * @return Advert
     */
    public function setCompte($compte)
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * Get compte
     *
     * @return string 
     */
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     * @return Advert
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string 
     */
    public function getLieu()
    {
        return $this->lieu;
    }
}
