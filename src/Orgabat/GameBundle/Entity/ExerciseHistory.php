<?php

namespace Orgabat\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ExerciseHistory.
 *
 * @ORM\Table(name="exercise_history")
 * @ORM\Entity(repositoryClass="Orgabat\GameBundle\Repository\ExerciseHistoryRepository")
 */
class ExerciseHistory
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="integer")
     */
    private $timer;

    /**
     * @var int
     *
     * @ORM\Column(name="healthNote", type="integer")
     * @Assert\Range(
     *     min = 0,
     *     minMessage= "La note de peut pas être inférieure à {{ limit }}."
     * )
     */
    private $healthNote;

    /**
     * @var int
     *
     * @ORM\Column(name="organizationNote", type="integer")
     * @Assert\Range(
     *     min = 0,
     *     minMessage= "La note de peut pas être inférieure à {{ limit }}."
     * )
     */
    private $organizationNote;

    /**
     * @var int
     *
     * @ORM\Column(name="businessNotorietyNote", type="integer")
     * @Assert\Range(
     *     min = 0,
     *     minMessage= "La note de peut pas être inférieure à {{ limit }}."
     * )
     */
    private $businessNotorietyNote;

    /**
     * @ORM\ManyToOne(targetEntity="Orgabat\GameBundle\Entity\Exercise", inversedBy="exerciseHistories", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $exercise;

    /**
     * @ORM\ManyToOne(targetEntity="Orgabat\GameBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return HistoryRealisation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set timer.
     *
     * @param \DateTime $timer
     *
     * @return HistoryRealisation
     */
    public function setTimer($timer)
    {
        $this->timer = $timer;

        return $this;
    }

    /**
     * Get timer.
     *
     * @return \DateTime
     */
    public function getTimer()
    {
        return $this->timer;
    }

    /**
     * Set exercise.
     *
     * @param Exercise $exercise
     *
     * @return HistoryRealisation
     */
    public function setExercise(Exercise $exercise)
    {
        $this->exercise = $exercise;
        $exercise->addExerciseHistory($this);

        return $this;
    }

    /**
     * Get exercise.
     *
     * @return Exercise
     */
    public function getExercise()
    {
        return $this->exercise;
    }

    /**
     * Set healthNote.
     *
     * @param int $healthNote
     *
     * @return HistoryRealisation
     */
    public function setHealthNote($healthNote)
    {
        $this->healthNote = $healthNote;

        return $this;
    }

    /**
     * Get healthNote.
     *
     * @return int
     */
    public function getHealthNote()
    {
        return $this->healthNote;
    }

    /**
     * Set organizationNote.
     *
     * @param int $organizationNote
     *
     * @return HistoryRealisation
     */
    public function setOrganizationNote($organizationNote)
    {
        $this->organizationNote = $organizationNote;

        return $this;
    }

    /**
     * Get organizationNote.
     *
     * @return int
     */
    public function getOrganizationNote()
    {
        return $this->organizationNote;
    }

    /**
     * Set businessNotorietyNote.
     *
     * @param int $businessNotorietyNote
     *
     * @return HistoryRealisation
     */
    public function setBusinessNotorietyNote($businessNotorietyNote)
    {
        $this->businessNotorietyNote = $businessNotorietyNote;

        return $this;
    }

    /**
     * Get businessNotoriety.
     *
     * @return int
     */
    public function getBusinessNotorietyNote()
    {
        return $this->businessNotorietyNote;
    }

    /**
     * Set user.
     *
     * @param \Orgabat\GameBundle\Entity\User $user
     *
     * @return HistoryRealisation
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Orgabat\GameBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get the score.
     *
     * @return float The score
     */
    public function getScore()
    {
        $sum = $this->getHumanScore();
        $sumCoeffs = $this->getExercise()->getBusinessNotorietyMaxNote()
            + $this->getExercise()->getHealthMaxNote()
            + $this->getExercise()->getOrganizationMaxNote();

        return $sum / $sumCoeffs;
    }

    /**
     * Get the human readable score.
     *
     * @return int The human readable score
     */
    public function getHumanScore()
    {
        return $this->businessNotorietyNote + $this->healthNote + $this->organizationNote;
    }
}
