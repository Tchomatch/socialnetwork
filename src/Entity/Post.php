<?phpnamespace App\Entity;use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;/**
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
     * @ORM\Column(type="string", length=350, nullable=true)
     */
    private $contenu;    
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $datepost;    
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;    
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImagePost", mappedBy="post")
     */
    private $image;    public function __construct()
    {
        $this->image = new ArrayCollection();
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
        $this->contenu = $contenu;        return $this;
    }    
  
    public function getDatepost(): ?\DateTimeInterface
    {
        return $this->datepost;
    }    
  
    public function setDatepost(\DateTimeInterface $datepost): self
    {
        $this->datepost = $datepost;        return $this;
    }    
  
    public function getUser(): ?User
    {
        return $this->user;
    }    
  
    public function setUser(?User $user): self
    {
        $this->user = $user;        return $this;
    }    
  
    /**
     * @return Collection|ImagePost[]
     */
    public function getImagePost(): Collection
    {
        return $this->image;
    }    
  
    public function addImagePost(ImagePost $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setPost($this);
        }        return $this;
    }    
  
    public function removeImagePost(ImagePost $image): self
    {
        if ($this->image->contains($image)) {
            $this->image->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getPost() === $this) {
                $image->setPost(null);
            }
        }        return $this;
    }
}