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
     * @ORM\ManyToOne(targetEntity="Orgabat\GameBundle\Entity\Category", inversedBy="exercises", cascade={"persist"})
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
     * @ORM\OneToMany(targetEntity="Orgabat\GameBundle\Entity\ExerciseHistory", mappedBy="exercise")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exerciseHistories;


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
        $category->addExercise($this);

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
     * Set healthMaxNote
     *
     * @param integer $healthMaxNote
     * @return Exercise
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
     * @return Exercise
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
     * @return Exercise
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
     * Constructor
     */
    public function __construct()
    {
        $this->exerciseHistories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add exerciseHistory
     *
     * @param \Orgabat\GameBundle\Entity\ExerciseHistory $exerciseHistory
     *
     * @return Exercise
     */
    public function addExerciseHistory(\Orgabat\GameBundle\Entity\ExerciseHistory $exerciseHistory)
    {
        $this->exerciseHistories[] = $exerciseHistory;

        return $this;
    }

    /**
     * Remove exerciseHistory
     *
     * @param \Orgabat\GameBundle\Entity\ExerciseHistory $exerciseHistory
     */
    public function removeExerciseHistory(\Orgabat\GameBundle\Entity\ExerciseHistory $exerciseHistory)
    {
        $this->exerciseHistories->removeElement($exerciseHistory);
    }

    /**
     * Get exerciseHistories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExerciseHistories()
    {
        return $this->exerciseHistories;
    }
}
