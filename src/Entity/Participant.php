<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Payment::class, mappedBy="participant_id")
     */
    private $payments;

    /**
     * @ORM\ManyToOne(targetEntity=campaign::class, inversedBy="participants")
     */
    private $campaign_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_anonymous;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setParticipantId($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getParticipantId() === $this) {
                $payment->setParticipantId(null);
            }
        }

        return $this;
    }

    public function getCampaignId(): ?campaign
    {
        return $this->campaign_id;
    }

    public function setCampaignId(?campaign $campaign_id): self
    {
        $this->campaign_id = $campaign_id;

        return $this;
    }

    public function getIsAnonymous(): ?bool
    {
        return $this->is_anonymous;
    }

    public function setIsAnonymous(bool $is_anonymous): self
    {
        $this->is_anonymous = $is_anonymous;

        return $this;
    }
}
