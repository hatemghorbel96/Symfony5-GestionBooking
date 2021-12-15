<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("today",message="la date d'arrivée doit etre ultérieure à la date d'aujourd'hui" ,groups={"front"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")    
     *  @Assert\GreaterThan(propertyPath="startDate",message="la date d'arrivée doit etre ultérieure à la date d'aujourd'hui !") 
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Undocumented function 
     *@ORM\PrePersist
     * @ORM\PreUpdate
     * @return void
     */
    public function prePersist(){
        if(empty($this->createdAt)){
            $this->CreatedAt = new \DateTime();
        }
        if(empty($this->montant)){
            // prix de l'annonce * nombre de jour
           $this->montant= $this->ad->getPrice() * $this->getDuration();
        }
    }

    public function getDuration(){
        $diff =$this->endDate->diff($this->startDate);
        return $diff->days;
    }

    public function isBookableDates(){
        // il faut connaitre les dates qui sont impossibles pour l'annonce
        $notAvailableDays = $this->ad->getNotAvailableDays();
        // il faut comparer les dates choisies avec les dates impossible
        $bookingDays  =$this->getDays();

            $formatDay=function($day){
                return $day->format('Y-m-d');
            };
        //tableau des chaines de caractéres de mes journées
        $days         =  array_map($formatDay, $bookingDays);

        $notAvailable = array_map($formatDay,$notAvailableDays);

        foreach($days as $day){
            if(array_search($day,$notAvailable) !== false) return false;
        }

        return true;
    }
    /**
     * permet de récupérer un tableau des journées qui correspondent à ma réservation
     *
     * @return array
     */
        public function getDays(){
            $resultat=range(
                $this->startDate->getTimestamp(),
                $this->endDate->getTimestamp(),
                24 * 60 *60
            );

            $days = array_map(function($dayTimestamp){
                return new \DateTime(date('Y-m-d',$dayTimestamp));
            },$resultat);

            return $days;
        }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
