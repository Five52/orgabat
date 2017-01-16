<?php

namespace Orgabat\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

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
     * @ORM\ManyToOne(targetEntity="Orgabat\GameBundle\Entity\Section", inversedBy="apprentices")
     * @ORM\JoinColumn(nullable=true)
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

    /**
     * @param mixed $section
     */
    public function setSection($section)
    {
        $this->section = $section;
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
            $this->getSection()->getName(),
        ];
    }

}
