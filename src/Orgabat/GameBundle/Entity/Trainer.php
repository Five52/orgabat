<?php

namespace Orgabat\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Orgabat\GameBundle\Repository\TrainerRepository")
 * @ORM\Table(name="trainer")
 * @UniqueEntity(fields = "username", targetClass = "Orgabat\GameBundle\Entity\User", message="fos_user.username.already_used")
 */
class Trainer extends User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Orgabat\GameBundle\Entity\Section", inversedBy="trainers", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $sections;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getData()
    {
        $tab = [];
        $tab[1] = $this->getFirstName();
        $tab[2] = $this->getLastName();
        $tab[3] = $this->getEmail();
        $tab[4] = $this->getPassword();
        foreach ($this->getSections() as $section) {
            $tab[] = $section->getName();
        }
        return $tab;
    }


    /**
     * Add section
     *
     * @param \Orgabat\GameBundle\Entity\Section $section
     *
     * @return Trainer
     */
    public function addSection(\Orgabat\GameBundle\Entity\Section $section)
    {
        $this->sections[] = $section;
        $section->addTrainer($this);

        return $this;
    }

    /**
     * Remove section
     *
     * @param \Orgabat\GameBundle\Entity\Section $section
     */
    public function removeSection(\Orgabat\GameBundle\Entity\Section $section)
    {
        $this->sections->removeElement($section);
        $section->removeTrainer($this);
    }

    /**
     * Get sections
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSections()
    {
        return $this->sections;
    }
}
