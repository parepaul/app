<?php

namespace NAC\JobBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Job
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="NAC\JobBundle\Entity\JobRepository")
 */
class Job
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
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="experience", type="text")
     */
    private $experience;

    /**
     * @var string
     *
     * @ORM\Column(name="mode", type="text")
     */
    private $mode;
	
	/**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
	
	/**
     * @var string
     *
     * @ORM\Column(name="composition", type="text")
     */
    private $composition;

    /**
     * @var string
     *
     * @ORM\Column(name="localite", type="string", length=255)
     */
    private $localite;

    /**
     * @var string
     *
     * @ORM\Column(name="postule", type="text")
     */
    private $postule;
	
	/**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;
	
	 /**
	  * @ORM\ManyToMany(targetEntity="NAC\JobBundle\Entity\JobCategory", cascade={"persist"})
	  */
	 private $jobcategories;


    public function __construct()
	{
		// Par défaut, la date de l'annonce est la date d'aujourd'hui
		$this->date = new \Datetime();
		$this->jobcategories = new ArrayCollection();
	}
	
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
     * @return Job
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
     * @return Job
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
     * @return Job
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
     * @return Job
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
     * Set experience
     *
     * @param string $experience
     * @return Job
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return string 
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set mode
     *
     * @param string $mode
     * @return Job
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get mode
     *
     * @return string 
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set localite
     *
     * @param string $localite
     * @return Job
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

    /**
     * Set postule
     *
     * @param string $postule
     * @return Job
     */
    public function setPostule($postule)
    {
        $this->postule = $postule;

        return $this;
    }

    /**
     * Get postule
     *
     * @return string 
     */
    public function getPostule()
    {
        return $this->postule;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return Job
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



    /**
     * Add jobcategories
     *
     * @param \NAC\JobBundle\Entity\JobCategory $jobcategories
     * @return Job
     */
    public function addJobcategory(\NAC\JobBundle\Entity\JobCategory $jobcategories)
    {
        $this->jobcategories[] = $jobcategories;

        return $this;
    }

    /**
     * Remove jobcategories
     *
     * @param \NAC\JobBundle\Entity\JobCategory $jobcategories
     */
    public function removeJobcategory(\NAC\JobBundle\Entity\JobCategory $jobcategories)
    {
        $this->jobcategories->removeElement($jobcategories);
    }

    /**
     * Get jobcategories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJobcategories()
    {
        return $this->jobcategories;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Job
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set composition
     *
     * @param string $composition
     * @return Job
     */
    public function setComposition($composition)
    {
        $this->composition = $composition;

        return $this;
    }

    /**
     * Get composition
     *
     * @return string 
     */
    public function getComposition()
    {
        return $this->composition;
    }
}
