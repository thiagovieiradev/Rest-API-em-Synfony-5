<?php

namespace App\Entity;

use App\Repository\TurmaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TurmaRepository::class)
 */
class Turma
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Groups({"mostrar_turma"})
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"mostrar_turma"})
     */
    private $descricao;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="turmas")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"mostrar_turma"})
     */
    private $status;

    /**
     * @ORM\Column(type="date")
     * @Groups({"mostrar_turma"})
     */
    private $data_inicio;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"mostrar_turma"})
     */
    private $data_termino;

    /**
     * @ORM\ManyToMany(targetEntity=Disciplina::class, inversedBy="turmas")
     * @Groups({"mostrar_turma"})
     */
    private $disciplina;

    /**
     * @ORM\ManyToMany(targetEntity=Colaborador::class, inversedBy="turmas")
     * @Groups({"mostrar_turma"})
     */
    private $colaborador;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;

    public function __construct()
    {
        $this->disciplina = new ArrayCollection();
        $this->colaborador = new ArrayCollection();
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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDataInicio(): ?\DateTimeInterface
    {
        return $this->data_inicio;
    }

    public function setDataInicio(\DateTimeInterface $data_inicio): self
    {
        $this->data_inicio = $data_inicio;

        return $this;
    }

    public function getDataTermino(): ?\DateTimeInterface
    {
        return $this->data_termino;
    }

    public function setDataTermino(?\DateTimeInterface $data_termino): self
    {
        $this->data_termino = $data_termino;

        return $this;
    }

    /**
     * @return Collection<int, Disciplina>
     */
    public function getDisciplina(): Collection
    {
        return $this->disciplina;
    }

    public function addDisciplina(Disciplina $disciplina): self
    {
        if (!$this->disciplina->contains($disciplina)) {
            $this->disciplina[] = $disciplina;
        }

        return $this;
    }

    public function removeDisciplina(Disciplina $disciplina): self
    {
        $this->disciplina->removeElement($disciplina);

        return $this;
    }

    /**
     * @return Collection<int, Colaborador>
     */
    public function getColaborador(): Collection
    {
        return $this->colaborador;
    }

    public function addColaborador(Colaborador $colaborador): self
    {
        if (!$this->colaborador->contains($colaborador)) {
            $this->colaborador[] = $colaborador;
        }

        return $this;
    }

    public function removeColaborador(Colaborador $colaborador): self
    {
        $this->colaborador->removeElement($colaborador);

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
