<?php
// src/NAC/PlatformBundle/Entity/AdvertSkill.php

namespace NAC\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="NAC\PlatformBundle\Entity\AdvertSkillRepository")
 */
class AdvertSkill
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="level", type="string", length=255)
   */
  private $level;

  /**
   * @ORM\ManyToOne(targetEntity="NAC\PlatformBundle\Entity\Advert")
   * @ORM\JoinColumn(nullable=false)
   */
  private $advert;

  /**
   * @ORM\ManyToOne(targetEntity="NAC\PlatformBundle\Entity\Skill")
   * @ORM\JoinColumn(nullable=false)
   */
  private $skill;
  
  // ... vous pouvez ajouter d'autres attributs bien sûr

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
     * Set level
     *
     * @param string $level
     * @return AdvertSkill
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set advert
     *
     * @param \NAC\PlatformBundle\Entity\Advert $advert
     * @return AdvertSkill
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
     * Set skill
     *
     * @param \NAC\PlatformBundle\Entity\Skill $skill
     * @return AdvertSkill
     */
    public function setSkill(\NAC\PlatformBundle\Entity\Skill $skill)
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * Get skill
     *
     * @return \NAC\PlatformBundle\Entity\Skill 
     */
    public function getSkill()
    {
        return $this->skill;
    }
}
