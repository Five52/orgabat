<?php

namespace Orgabat\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Section
 *
 * @ORM\Table(name="section")
 * @ORM\Entity(repositoryClass="Orgabat\GameBundle\Repository\SectionRepository")
 * @UniqueEntity(fields="name", message="Une classe existe déjà avec ce nom.")
 */
class Section
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le champ ne doit pas être vide")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Orgabat\GameBundle\Entity\Apprentice", mappedBy="section")
     */
    private $apprentices;

    /**
     * @ORM\ManyToMany(targetEntity="Orgabat\GameBundle\Entity\Trainer", mappedBy="sections")
     */
    private $trainers;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Section
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getData()
    {
        $tab = [];
        $tab[0] = $this->getName();
        return $tab;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apprentices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->trainers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add apprentice
     *
     * @param \Orgabat\GameBundle\Entity\Apprentice $apprentice
     *
     * @return Section
     */
    public function addApprentice(\Orgabat\GameBundle\Entity\Apprentice $apprentice)
    {
        $this->apprentices[] = $apprentice;

        return $this;
    }

    /**
     * Remove apprentice
     *
     * @param \Orgabat\GameBundle\Entity\Apprentice $apprentice
     */
    public function removeApprentice(\Orgabat\GameBundle\Entity\Apprentice $apprentice)
    {
        $this->apprentices->removeElement($apprentice);
    }

    /**
     * Get apprentices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApprentices()
    {
        return $this->apprentices;
    }

    /**
     * Add trainer
     *
     * @param \Orgabat\GameBundle\Entity\Trainer $trainer
     *
     * @return Section
     */
    public function addTrainer(\Orgabat\GameBundle\Entity\Trainer $trainer)
    {
        $this->trainers[] = $trainer;

        return $this;
    }

    /**
     * Remove trainer
     *
     * @param \Orgabat\GameBundle\Entity\Trainer $trainer
     */
    public function removeTrainer(\Orgabat\GameBundle\Entity\Trainer $trainer)
    {
        $this->trainers->removeElement($trainer);
    }

    /**
     * Get trainers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrainers()
    {
        return $this->trainers;
    }
}
