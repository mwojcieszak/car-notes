<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Make.
 *
 * @ORM\Table(name="makes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MakeRepository")
 */
class Make
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
     * @ORM\Column(name="make_name", type="string", length=100)
     */
    private $makeName;

    /**
     * @ORM\OneToMany(targetEntity="Model", mappedBy="makeId")
     */
    private $models;

    public function __construct()
    {
        $this->models = new ArrayCollection();
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
     * Set makeName.
     *
     * @param string $makeName
     *
     * @return Make
     */
    public function setMakeName($makeName)
    {
        $this->makeName = $makeName;

        return $this;
    }

    /**
     * Get makeName.
     *
     * @return string
     */
    public function getMakeName()
    {
        return $this->makeName;
    }

    /**
     * Add models.
     *
     * @param \AppBundle\Entity\Model $models
     *
     * @return Make
     */
    public function addModel(\AppBundle\Entity\Model $models)
    {
        $this->models[] = $models;

        return $this;
    }

    /**
     * Remove models.
     *
     * @param \AppBundle\Entity\Model $models
     */
    public function removeModel(\AppBundle\Entity\Model $models)
    {
        $this->models->removeElement($models);
    }

    /**
     * Get models.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModels()
    {
        return $this->models;
    }
}
