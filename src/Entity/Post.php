<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImagePost", mappedBy="post")
     */
    private $imagePosts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    public function __construct()
    {
        $this->imagePosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * @return Collection|ImagePost[]
     */
    public function getImagePosts(): Collection
    {
        return $this->imagePosts;
    }

    public function addImagePost(ImagePost $imagePost): self
    {
        if (!$this->imagePosts->contains($imagePost)) {
            $this->imagePosts[] = $imagePost;
            $imagePost->setPost($this);
        }

        return $this;
    }

    public function removeImagePost(ImagePost $imagePost): self
    {
        if ($this->imagePosts->contains($imagePost)) {
            $this->imagePosts->removeElement($imagePost);
            // set the owning side to null (unless already changed)
            if ($imagePost->getPost() === $this) {
                $imagePost->setPost(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
