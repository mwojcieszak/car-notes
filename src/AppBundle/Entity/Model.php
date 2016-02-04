<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Models.
 *
 * @ORM\Table(name="models")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModelsRepository")
 */
class Model
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="model_name", type="string", length=100)
     */
    private $modelName;

    /**
     * @ORM\ManyToOne(targetEntity="Make", inversedBy="models")
     * @ORM\JoinColumn(name="make_id", referencedColumnName="id")
     */
    private $makeId;

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
     * Set modelName.
     *
     * @param string $modelName
     *
     * @return Models
     */
    public function setModelName($modelName)
    {
        $this->modelName = $modelName;

        return $this;
    }

    /**
     * Get modelName.
     *
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * Set makeId.
     *
     * @param \AppBundle\Entity\Make $makeId
     *
     * @return Models
     */
    public function setMakeId(\AppBundle\Entity\Make $makeId = null)
    {
        $this->makeId = $makeId;

        return $this;
    }

    /**
     * Get makeId.
     *
     * @return \AppBundle\Entity\Make
     */
    public function getMakeId()
    {
        return $this->makeId;
    }

    public function __toString()
    {
        return $this->getModelName();
    }
}
