<?php

namespace App\Entity;

use App\Repository\SenioridadeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SenioridadeRepository::class)
 */
class Senioridade
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Groups({"mostrar_senioridades"})
     */
    private $nome;

    /**
     * @ORM\OneToMany(targetEntity=Colaborador::class, mappedBy="senioridade_id")
     */
    private $colaboradores;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;

    
    public function __construct()
    {
        $this->colaboradores = new ArrayCollection();        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return Collection<int, Colaborador>
     */
    public function getColaboradores(): Collection
    {
        return $this->colaboradores;
    }

    public function addColaboradore(Colaborador $colaboradore): self
    {
        if (!$this->colaboradores->contains($colaboradore)) {
            $this->colaboradores[] = $colaboradore;
            $colaboradore->setSenioridadeId($this);
        }

        return $this;
    }

    public function removeColaboradore(Colaborador $colaboradore): self
    {
        if ($this->colaboradores->removeElement($colaboradore)) {
            // set the owning side to null (unless already changed)
            if ($colaboradore->getSenioridadeId() === $this) {
                $colaboradore->setSenioridadeId(null);
            }
        }

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeInterface $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

   
}
