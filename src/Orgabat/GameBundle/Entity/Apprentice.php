<?php

namespace Orgabat\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="apprentice")
 * @UniqueEntity(fields = "username", targetClass = "Orgabat\GameBundle\Entity\User", message="fos_user.username.already_used")
 */
class Apprentice extends User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Orgabat\GameBundle\Entity\Section", inversedBy="apprentices", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $section;

    /**
     * @ORM\Column(type="string")
     * Format: JJMMAAAA
     */
    private $birthDate;

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

    public function getName() {
        return $this->getFirstName().$this->getLastName();
    }

    /**
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    public function getFormattedBirthDate() {
        $day = substr($this->birthDate,0,2);
        $month = substr($this->birthDate,2,2);
        $year = substr($this->birthDate,4,2);

        $fullDate = $year . '-' . $month . '-' . $day . " 00:00:00";
        return new \DateTime($fullDate);
    }

    /**
     * @param mixed $section
     */
    public function setSection($section)
    {
        $this->section = $section;
        $section->addApprentice($this);
    }

    /**
     * Returns an array of data for the csv generation
     * @return array Apprentice data
     */
    public function getData()
    {
        return [
            $this->getFirstName(),
            $this->getLastName(),
            $this->getBirthDate(),
            $this->getEmail(),
            ($this->getSection() !== null)
            ? $this->getSection()->getName()
            : "null"
        ];
    }

}
