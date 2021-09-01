<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait CreateUpdateTimeTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column (name="created_at", type="datetime", nullable=true)
     * @Groups ({"default"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column (name="updated_at", type="datetime", nullable=true)
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
     * @param \DateTime $createdAt
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime('now');;

        return $this;
    }
}