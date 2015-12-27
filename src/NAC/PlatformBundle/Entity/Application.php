<?php
// src/NAC/PlatformBundle/Entity/Application.php

namespace NAC\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="NAC\PlatformBundle\Entity\ApplicationRepository")
 */
class Application
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="author", type="string", length=255)
   */
  private $author;
  
  /**
   * @ORM\Column(name="prenom", type="string", length=255)
   */
  private $prenom;
          
  /**
   * @ORM\Column(name="tel", type="string", length=255)
   */
  private $tel;
    
  /**
   * @ORM\Column(name="email", type="string", length=255)
   */
  private $email;

  /**
   * @ORM\Column(name="content", type="text")
   */
  private $content;

  /**
   * @ORM\Column(name="date", type="datetime")
   */
  private $date;
  
  /**
   * @ORM\ManyToOne(targetEntity="NAC\PlatformBundle\Entity\Advert", inversedBy="applications")
   * @ORM\JoinColumn(nullable=false)
   */
  private $advert;
  
  public function __construct()
  {
    $this->date = new \Datetime();
  }

  public function getId()
  {
    return $this->id;
  }

  public function setAuthor($author)
  {
    $this->author = $author;

    return $this;
  }

  public function getAuthor()
  {
    return $this->author;
  }

  public function setContent($content)
  {
    $this->content = $content;

    return $this;
  }

  public function getContent()
  {
    return $this->content;
  }

  public function setDate($date)
  {
    $this->date = $date;

    return $this;
  }

  public function getDate()
  {
    return $this->date;
  }

    /**
     * Set advert
     *
     * @param \NAC\PlatformBundle\Entity\Advert $advert
     * @return Application
     */
    public function setAdvert(\NAC\PlatformBundle\Entity\Advert $advert)
    {
        $this->advert = $advert;

        return $this;
    }

    /**
     * Get advert
     *
     * @return \NAC\PlatformBundle\Entity\Advert 
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Application
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Application
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
     * @return Application
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
}
