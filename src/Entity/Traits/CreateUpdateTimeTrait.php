<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

trait CreateUpdateTimeTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column (name="created_at", type="datetime", nullable=true)
     * @Context({ DateTimeNormalizer::FORMAT_KEY = "Y-m-d H:i" })
     * @Groups ({"default"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column (name="updated_at", type="datetime", nullable=true)
     * @Context({ DateTimeNormalizer::FORMAT_KEY = "Y-m-d H:i" })
     * @Groups ({"default"})
     */
    private $updatedAt;

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime('now');

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime('now');;

        return $this;
    }
}