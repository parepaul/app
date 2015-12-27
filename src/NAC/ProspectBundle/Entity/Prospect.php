<?php

namespace NAC\ProspectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

 /**
 * Prospect
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="NAC\ProspectBundle\Entity\ProspectRepository")
 * @UniqueEntity(fields="titre", message="Une annonce existe déjà avec ce titre.")
 */
 class Prospect
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
     * @ORM\Column(name="titre", type="string", length=255, unique=true)
     * @Assert\Length(min=3)
	 */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(
     *     message = "'{{ value }}' n'est pas un email valide.",
     *     checkMX = true
     * )
	 */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="text", length=255, nullable=false)
	 */
    private $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;
	
	/**
     * @var string
     *
     * @ORM\Column(name="localite", type="string", length=255)
     */
    private $localite;
    

	/**
     * @ORM\ManyToMany(targetEntity="NAC\ProspectBundle\Entity\Domaine", cascade={"persist"})
     */
    private $domaines;
	
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
     * @return Prospect
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
     * Set titre
     *
     * @param string $titre
     * @return Prospect
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Prospect
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Prospect
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set resume
     *
     * @param string $resume
     * @return Prospect
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string 
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Prospect
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
	
	public function __construct()
	{
		// Par défaut, la date de l'annonce est la date d'aujourd'hui
		$this->date = new \Datetime();
		$this->domaines = new ArrayCollection();
	}
	

    /**
     * Add domaines
     *
     * @param \NAC\ProspectBundle\Entity\Domaine $domaines
     * @return Prospect
     */
    public function addDomaine(\NAC\ProspectBundle\Entity\Domaine $domaines)
    {
        $this->domaines[] = $domaines;

        return $this;
    }

    /**
     * Remove domaines
     *
     * @param \NAC\ProspectBundle\Entity\Domaine $domaines
     */
    public function removeDomaine(\NAC\ProspectBundle\Entity\Domaine $domaines)
    {
        $this->domaines->removeElement($domaines);
    }

    /**
     * Get domaines
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDomaines()
    {
        return $this->domaines;
    }

    /**
     * Set localite
     *
     * @param string $localite
     * @return Prospect
     */
    public function setLocalite($localite)
    {
        $this->localite = $localite;

        return $this;
    }

    /**
     * Get localite
     *
     * @return string 
     */
    public function getLocalite()
    {
        return $this->localite;
    }
}
