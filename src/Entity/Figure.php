<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FigureRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: FigureRepository::class)]
#[UniqueEntity(
    fields: ['name'],
    message: 'Une figure portant ce nom existe déjà, veuillez changer de nom.',
)]
class Figure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $name;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'figures')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\OneToMany(mappedBy: 'figure', targetEntity: Image::class, orphanRemoval: true, cascade:['persist'])]
    private $images;

    #[ORM\OneToMany(mappedBy: 'figure', targetEntity: Video::class, orphanRemoval: true, cascade:['persist'])]
    private $videos;

    #[ORM\OneToMany(mappedBy: 'figure', targetEntity: Message::class, orphanRemoval: true)]
    private $messages;

    #[ORM\ManyToOne(targetEntity: FigureType::class, inversedBy: 'figures')]
    #[ORM\JoinColumn(nullable: false)]
    private $type;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\datetime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\datetime $createdAt): self
    {
        if ($this->getCreatedAt() === null) {
            $this->createdAt = $createdAt;
        }

        return $this;
    }

    public function getUpdatedAt(): ?\datetime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\datetime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setFigure($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getFigure() === $this) {
                $image->setFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setFigure($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getFigure() === $this) {
                $video->setFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setFigure($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getFigure() === $this) {
                $message->setFigure(null);
            }
        }

        return $this;
    }

    public function getType(): ?FigureType
    {
        return $this->type;
    }

    public function setType(?FigureType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
    
    public function displayDate()
    {
        return $this->getCreatedAt()->format('d/m/Y H:i:s'); 
    }

    public function slug() {
        $slug = $this->getName() . '-' . $this->displayDate();
        return str_replace([' ', '/', ':'] ,'-' , $slug);
    }
}
