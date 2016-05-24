<?php

namespace Orgabat\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\OneToMany(targetEntity="Orgabat\GameBundle\Entity\HistoryRealisation", mappedBy="exercises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $historyRealisation;


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
    public function setCategory(\Orgabat\GameBundle\Entity\Category $category)
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
        $this->historyRealisation = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add historyRealisation
     *
     * @param \Orgabat\GameBundle\Entity\HistoryRealisation $historyRealisation
     *
     * @return Exercise
     */
    public function addHistoryRealisation(\Orgabat\GameBundle\Entity\HistoryRealisation $historyRealisation)
    {
        $this->historyRealisation[] = $historyRealisation;

        return $this;
    }

    /**
     * Remove historyRealisation
     *
     * @param \Orgabat\GameBundle\Entity\HistoryRealisation $historyRealisation
     */
    public function removeHistoryRealisation(\Orgabat\GameBundle\Entity\HistoryRealisation $historyRealisation)
    {
        $this->historyRealisation->removeElement($historyRealisation);
    }

    /**
     * Get historyRealisation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoryRealisation()
    {
        return $this->historyRealisation;
    }
}
