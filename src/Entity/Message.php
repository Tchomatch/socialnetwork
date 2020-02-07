<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=350, nullable=true)
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_envoi;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userReceiver;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messageSender")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userSender;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conversation", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conversation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImageChat", mappedBy="message")
     */
    private $imageChats;

    public function __construct()
    {
        $this->imageChats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateEnvoi(): ?\DateTimeInterface
    {
        return $this->date_envoi;
    }

    public function setDateEnvoi(\DateTimeInterface $date_envoi): self
    {
        $this->date_envoi = $date_envoi;

        return $this;
    }

    public function getUserReceiver(): ?User
    {
        return $this->userReceiver;
    }

    public function setUserReceiver(?User $userReceiver): self
    {
        $this->userReceiver = $userReceiver;

        return $this;
    }

    public function getUserSender(): ?User
    {
        return $this->userSender;
    }

    public function setUserSender(?User $userSender): self
    {
        $this->userSender = $userSender;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * @return Collection|ImageChat[]
     */
    public function getImageChats(): Collection
    {
        return $this->imageChats;
    }

    public function addImageChat(ImageChat $imageChat): self
    {
        if (!$this->imageChats->contains($imageChat)) {
            $this->imageChats[] = $imageChat;
            $imageChat->setMessage($this);
        }

        return $this;
    }

    public function removeImageChat(ImageChat $imageChat): self
    {
        if ($this->imageChats->contains($imageChat)) {
            $this->imageChats->removeElement($imageChat);
            // set the owning side to null (unless already changed)
            if ($imageChat->getMessage() === $this) {
                $imageChat->setMessage(null);
            }
        }

        return $this;
    }
}
