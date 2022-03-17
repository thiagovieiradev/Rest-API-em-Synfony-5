<?php

namespace App\Entity;

use App\Repository\CompetenciaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CompetenciaRepository::class)
 */
class Competencia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"mostrar_competencia"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Groups({"mostrar_competencia", "mostrar_colaborador"})
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"mostrar_competencia"})
     */
    private $descricao;

    /**
     * @Assert\NotNull(message="O campo categoria não pode ser nulo")
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="competencias")
     * @Groups({"mostrar_categorias"})
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id", nullable=false)     
     */
    private $categoria;

    /**
     * @ORM\ManyToMany(targetEntity="Colaborador", inversedBy="competencias", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="colaborador_competencia",
     *     joinColumns={
     *          @ORM\JoinColumn(name="competencia_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="colaborador_id", referencedColumnName="id")
     *     }
     * )
     */
    private $colaboradores;       

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;

    public function __construct()
    {
        $this->colaboradores = new ArrayCollection();
        $this->testes = new ArrayCollection();
    }

    public function addColaborador(Colaborador $colaborador): self
    {
        
        $this->colaboradores[] = $colaborador;
 
        return $this;
    }
 
    public function removeColaborador(Colaborador $colaborador): bool
    {
        return $this->colaboradores->removeElement($colaborador);
    }
 
    public function getColaborador(): Collection
    {
        return $this->colaboradores;
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

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

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
