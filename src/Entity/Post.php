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
<<<<<<< HEAD
     * @ORM\Column(type="text")
=======
     * @ORM\Column(type="string", length=350, nullable=true)
>>>>>>> 4ecfc66a35ab89cac7c15c730e58818aadbb1659
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
<<<<<<< HEAD
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
=======
    private $datepost;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImagePost", mappedBy="post")
     */
    private $image;

    public function __construct()
    {
        $this->image = new ArrayCollection();
>>>>>>> 4ecfc66a35ab89cac7c15c730e58818aadbb1659
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

<<<<<<< HEAD
    public function setContenu(string $contenu): self
=======
    public function setContenu(?string $contenu): self
>>>>>>> 4ecfc66a35ab89cac7c15c730e58818aadbb1659
    {
        $this->contenu = $contenu;

        return $this;
    }

<<<<<<< HEAD
    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;
=======
    public function getDatepost(): ?\DateTimeInterface
    {
        return $this->datepost;
    }

    public function setDatepost(\DateTimeInterface $datepost): self
    {
        $this->datepost = $datepost;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
>>>>>>> 4ecfc66a35ab89cac7c15c730e58818aadbb1659

        return $this;
    }

    /**
     * @return Collection|ImagePost[]
     */
<<<<<<< HEAD
    public function getImagePosts(): Collection
    {
        return $this->imagePosts;
    }

    public function addImagePost(ImagePost $imagePost): self
    {
        if (!$this->imagePosts->contains($imagePost)) {
            $this->imagePosts[] = $imagePost;
            $imagePost->setPost($this);
=======
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(ImagePost $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setPost($this);
>>>>>>> 4ecfc66a35ab89cac7c15c730e58818aadbb1659
        }

        return $this;
    }

<<<<<<< HEAD
    public function removeImagePost(ImagePost $imagePost): self
    {
        if ($this->imagePosts->contains($imagePost)) {
            $this->imagePosts->removeElement($imagePost);
            // set the owning side to null (unless already changed)
            if ($imagePost->getPost() === $this) {
                $imagePost->setPost(null);
=======
    public function removeImage(ImagePost $image): self
    {
        if ($this->image->contains($image)) {
            $this->image->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getPost() === $this) {
                $image->setPost(null);
>>>>>>> 4ecfc66a35ab89cac7c15c730e58818aadbb1659
            }
        }

        return $this;
    }
<<<<<<< HEAD

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
=======
>>>>>>> 4ecfc66a35ab89cac7c15c730e58818aadbb1659
}
