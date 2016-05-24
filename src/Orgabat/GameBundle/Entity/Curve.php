<?php

namespace Orgabat\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Curve
 *
 * @ORM\Table(name="curve")
 * @ORM\Entity(repositoryClass="Orgabat\GameBundle\Repository\CurveRepository")
 */
class Curve
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
     * @var int
     *
     * @ORM\Column(name="healthLevel", type="integer")
     */
    private $healthLevel;

    /**
     * @var int
     *
     * @ORM\Column(name="organizationLevel", type="integer")
     */
    private $organizationLevel;

    /**
     * @var int
     *
     * @ORM\Column(name="businessNotoriety", type="integer")
     */
    private $businessNotoriety;


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
     * Set healthLevel
     *
     * @param integer $healthLevel
     *
     * @return Curve
     */
    public function setHealthLevel($healthLevel)
    {
        $this->healthLevel = $healthLevel;

        return $this;
    }

    /**
     * Get healthLevel
     *
     * @return int
     */
    public function getHealthLevel()
    {
        return $this->healthLevel;
    }

    /**
     * Set organizationLevel
     *
     * @param integer $organizationLevel
     *
     * @return Curve
     */
    public function setOrganizationLevel($organizationLevel)
    {
        $this->organizationLevel = $organizationLevel;

        return $this;
    }

    /**
     * Get organizationLevel
     *
     * @return int
     */
    public function getOrganizationLevel()
    {
        return $this->organizationLevel;
    }

    /**
     * Set businessNotoriety
     *
     * @param integer $businessNotoriety
     *
     * @return Curve
     */
    public function setBusinessNotoriety($businessNotoriety)
    {
        $this->businessNotoriety = $businessNotoriety;

        return $this;
    }

    /**
     * Get businessNotoriety
     *
     * @return int
     */
    public function getBusinessNotoriety()
    {
        return $this->businessNotoriety;
    }
}

