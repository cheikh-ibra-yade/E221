<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BienRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=BienRepository::class)
 * @Vich\Uploadable 
 */
class Bien
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description du bien est obligatoire")
     * 
     */
    private $description;

    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice(choices=Bien::TYPE, message="Le Type choisit n'est pas valide.")
     */
    private $type;

    /**
     * @ORM\Column(type="float")
     * 
     * @Assert\Type(
     *     type="numeric",
     *     message=" {{ value }} N'est pas de type{{ type }}."
     * )
     * 
     * @Assert\Positive(message="Veulliez saisire une valeur positif.")
     * 
     *
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice(choices=Bien::PERIODE, message="La période choisit n'est pas valide.")
     */
    private $periode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice(choices=Bien::TYPE_USAGE, message="Le type d'usage choisit n'est pas valide.")
     */
    private $typeUsage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat = self::ETAT["Encours"];

    /**
     * @Vich\UploadableField(mapping="bien_avatars", fileNameProperty="avatar") 
     * @var File | null 
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity=Zone::class, inversedBy="biens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $zone;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="biens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="bien")
     */
    private $reservations;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La description du bien est obligatoire")
     */
    private $titre;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    Const TYPE = [
        'Immeuble' => "Immeuble",
        "Chambre" => "Chambre",
        "Studio" => "Studio",
        "Appartemant" => "Appartemant"
    ];

    Const ETAT = [
        "Encours" => "Encours",
        "Bloqué" => "Bloqué",
        "Libre" => "Libre",
        "Loué" => "Loué",
        "En réfection" => "En réfection"
    ];

    Const TYPE_USAGE = [
        "Bureau" => "Bureau",
        "Logement" => "Logement"
    ];
    
    Const PERIODE = [
        "Mensuelle" => "Mensuelle",
        "Annuelle" => "Annuelle",
        "Hebdomadaire" => "Hebdomadaire"
    ];

    public function getId(): ?int
    {
        return $this->id;
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

    

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getPeriode(): ?string
    {
        return $this->periode;
    }

    public function setPeriode(string $periode): self
    {
        $this->periode = $periode;

        return $this;
    }

    public function getTypeUsage(): ?string
    {
        return $this->typeUsage;
    }

    public function setTypeUsage(string $typeUsage): self
    {
        $this->typeUsage = $typeUsage;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }


    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

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

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setBien($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getBien() === $this) {
                $reservation->setBien(null);
            }
        }

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }


    public function __toString()
    {
        return $this->titre;
    }
    
}
