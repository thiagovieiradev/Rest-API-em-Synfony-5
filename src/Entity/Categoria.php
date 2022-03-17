<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriaRepository::class)
 */
class Categoria
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"mostrar_categorias"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Groups({"mostrar_categorias"})
     */
    private $nome;

    /**
     * @ORM\OneToMany(targetEntity=Competencia::class, mappedBy="categoria", cascade={"persist"})
     * @Groups({"mostrar_competencias"})
     */
    private $competencias;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;

    public function __construct()
    {
        $this->competencias_id = new ArrayCollection();
        $this->competencias = new ArrayCollection();
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
     * @return Collection<int, Competencia>
     */
    public function getCompetencias(): Collection
    {
        return $this->competencias;
    }

    public function addCompetencia(Competencia $competencia): self
    {
        if (!$this->competencias->contains($competencia)) {
            $this->competencias[] = $competencia;
            $competencia->setCategoria($this);
        }

        return $this;
    }

    public function removeCompetencia(Competencia $competencia): self
    {
        if ($this->competencias->removeElement($competencia)) {
            // set the owning side to null (unless already changed)
            if ($competencia->getCategoria() === $this) {
                $competencia->setCategoria(null);
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
