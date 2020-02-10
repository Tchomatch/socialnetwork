<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImagePostRepository")
 */
class ImagePost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
<<<<<<< HEAD
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="imagePosts")
     * @ORM\JoinColumn(nullable=false)
=======
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="image")
>>>>>>> 4ecfc66a35ab89cac7c15c730e58818aadbb1659
     */
    private $post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
