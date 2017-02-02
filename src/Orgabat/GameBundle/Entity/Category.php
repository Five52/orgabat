<?php

namespace Orgabat\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Orgabat\GameBundle\Repository\CategoryRepository")
 * @UniqueEntity(fields="name", message="Une catégorie existe déjà avec ce nom.")
 */
class Category
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
     * @Assert\NotBlank(message="Le message ne doit pas être vide")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Orgabat\GameBundle\Entity\Exercise", mappedBy="category")
     */
    private $exercises;

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
     * @return Category
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
     * Constructor
     */
    public function __construct()
    {
        $this->exercises = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add exercise
     *
     * @param \Orgabat\GameBundle\Entity\Exercise $exercise
     *
     * @return Category
     */
    public function addExercise(\Orgabat\GameBundle\Entity\Exercise $exercise)
    {
        $this->exercises[] = $exercise;

        return $this;
    }

    /**
     * Remove exercise
     *
     * @param \Orgabat\GameBundle\Entity\Exercise $exercise
     */
    public function removeExercise(\Orgabat\GameBundle\Entity\Exercise $exercise)
    {
        $this->exercises->removeElement($exercise);
    }


    /**
     * Get exercises
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExercises()
    {
        return $this->exercises;
    }

    /**
     * Get the sum of all best exercises for a category
     *
     * @return array : results of all exercises for a category
     */
    public function getDataFromAllBestExercises() {
        $results = ["timer" => 0, "healthNote" => 0, "organizationNote" => 0, "businessNotorietyNote" => 0];
        foreach ($this->exercises as $exercise) {
            $results["timer"] += $exercise->getBestExerciseHistory()->getTimer();
            $results["healthNote"] += $exercise->getBestExerciseHistory()->getHealthNote();
            $results["organizationNote"] += $exercise->getBestExerciseHistory()->getOrganizationNote();
            $results["businessNotorietyNote"] += $exercise->getBestExerciseHistory()->getBusinessNotorietyNote();
        }
        $results["timer"] /= count($this->exercises);
        return $results;
    }

    public function getMaxResults() {
        $results = ["healthMaxNote" => 0, "organizationMaxNote" => 0, "businessNotorietyMaxNote" => 0];
        foreach ($this->exercises as $exercise) {
            $results["healthMaxNote"] += $exercise->getHealthMaxNote();
            $results["organizationMaxNote"] += $exercise->getOrganizationMaxNote();
            $results["businessNotorietyMaxNote"] += $exercise->getBusinessNotorietyMaxNote();
        }
        return $results;
    }
}
