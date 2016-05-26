<?php

namespace Orgabat\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Exercise
 *
 * @ORM\Table(name="exercise")
 * @ORM\Entity(repositoryClass="Orgabat\GameBundle\Repository\ExerciseRepository")
 */
class Exercise
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Orgabat\GameBundle\Entity\Category", inversedBy="exercises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var int
     *
     * @ORM\Column(name="healthMaxNote", type="integer")
     * @Assert\Range(
     *     min = 0,
     *     minMessage= "La note de peut pas être inférieure à {{ limit }}."
     * )
     */
    private $healthMaxNote;

    /**
     * @var int
     *
     * @ORM\Column(name="organizationMaxNote", type="integer")
     * @Assert\Range(
     *     min = 0,
     *     minMessage= "La note de peut pas être inférieure à {{ limit }}."
     * )
     */
    private $organizationMaxNote;

    /**
     * @var int
     *
     * @ORM\Column(name="businessNotorietyMaxNote", type="integer")
     * @Assert\Range(
     *     min = 0,
     *     minMessage= "La note de peut pas être inférieure à {{ limit }}."
     * )
     */
    private $businessNotorietyMaxNote;

    /**
     * @ORM\OneToMany(targetEntity="Orgabat\GameBundle\Entity\HistoryRealisation", mappedBy="exercise")
     * @ORM\JoinColumn(nullable=false)
     */
    private $historyRealisations;


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
     * @return Exercise
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

    /**
     * Set category
     *
     * @param \Orgabat\GameBundle\Entity\Category $category
     *
     * @return Exercise
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Orgabat\GameBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->historyRealisations = new ArrayCollection();
    }

    /**
     * Set healthMaxNote
     *
     * @param integer $healthMaxNote
     *
     * @return HistoryRealisation
     */
    public function setHealthMaxNote($healthMaxNote)
    {
        $this->healthMaxNote = $healthMaxNote;

        return $this;
    }

    /**
     * Get healthMaxNote
     *
     * @return integer
     */
    public function getHealthMaxNote()
    {
        return $this->healthMaxNote;
    }

    /**
     * Set organizationMaxNote
     *
     * @param integer $organizationMaxNote
     *
     * @return HistoryRealisation
     */
    public function setOrganizationMaxNote($organizationMaxNote)
    {
        $this->organizationMaxNote = $organizationMaxNote;

        return $this;
    }

    /**
     * Get organizationMaxNote
     *
     * @return integer
     */
    public function getOrganizationMaxNote()
    {
        return $this->organizationMaxNote;
    }

    /**
     * Set businessNotorietyMaxNote
     *
     * @param integer $businessNotorietyMaxNote
     *
     * @return HistoryRealisation
     */
    public function setBusinessNotorietyMaxNote($businessNotorietyMaxNote)
    {
        $this->businessNotorietyMaxNote = $businessNotorietyMaxNote;

        return $this;
    }

    /**
     * Get businessNotorietyMaxNote
     *
     * @return integer
     */
    public function getBusinessNotorietyMaxNote()
    {
        return $this->businessNotorietyMaxNote;
    }

    /**
     * Add historyRealisation
     *
     * @param \Orgabat\GameBundle\Entity\HistoryRealisation $historyRealisation
     *
     * @return Exercise
     */
    public function addHistoryRealisation(HistoryRealisation $historyRealisation)
    {
        $this->historyRealisations[] = $historyRealisation;

        return $this;
    }

    /**
     * Remove historyRealisation
     *
     * @param \Orgabat\GameBundle\Entity\HistoryRealisation $historyRealisation
     */
    public function removeHistoryRealisation(HistoryRealisation $historyRealisation)
    {
        $this->historyRealisations->removeElement($historyRealisation);
    }

    /**
     * Get historyRealisation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoryRealisation()
    {
        return $this->historyRealisations;
    }

    /**
     * Get historyRealisations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoryRealisations()
    {
        return $this->historyRealisations;
    }
}
