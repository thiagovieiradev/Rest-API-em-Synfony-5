<?php

namespace App\Entity;

use App\Repository\ColaboradorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ColaboradorRepository::class)
 */
class Colaborador
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Groups({"mostrar_colaborador", "mostrar_turma"})
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $sobrenome;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotNull(message="O campo CPF deve ser obrigatório")
     */
    private $cpf;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $email;

    /**
     * @ORM\Column(type="date")
     */
    private $data_nascimento;

    /**
     * @ORM\Column(type="date")
     */
    private $data_admissao;

    /**
    * @ORM\ManyToMany(targetEntity="Competencia", mappedBy="colaboradores")
    * @Groups({"mostrar_colaborador"})
    */
    private $competencias;

    /**
     * @ORM\Column(type="datetime")
     */
    private $deleted_at;

    /**
     * @ORM\ManyToOne(targetEntity=Senioridade::class, inversedBy="colaboradors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $senioridade;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto;

    /**
     * @ORM\ManyToMany(targetEntity=Turma::class, mappedBy="colaborador")
     */
    private $turmas;


    public function __construct()
    {
        $this->competencias = new ArrayCollection();
        $this->testes = new ArrayCollection();
        $this->turmas = new ArrayCollection();
    }

    public function addCompetencias($competencias): self
    {
        foreach($competencias as $c) {
            $this->competencias[] = $c;
        }
            
        return $this;
    }
    
    public function removeCompetencias(Competencia $competencia): bool
    {
        return $this->competencias->removeElement($competencia);
    }
    
    public function getCompetencias(): Collection
    {
        return $this->competencias;
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

    public function getSobrenome(): ?string
    {
        return $this->sobrenome;
    }

    public function setSobrenome(?string $sobrenome): self
    {
        $this->sobrenome = $sobrenome;

        return $this;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): self
    {
        $this->cpf = $cpf;

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

    public function getDataNascimento(): ?\DateTimeInterface
    {
        return $this->data_nascimento;
    }

    public function setDataNascimento(\DateTimeInterface $data_nascimento): self
    {
        $this->data_nascimento = $data_nascimento;

        return $this;
    }

    public function getDataAdmissao(): ?\DateTimeInterface
    {
        return $this->data_admissao;
    }

    public function setDataAdmissao(\DateTimeInterface $data_admissao): self
    {
        $this->data_admissao = $data_admissao;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(\DateTimeInterface $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    public function getSenioridade(): ?Senioridade
    {
        return $this->senioridade;
    }

    public function setSenioridade(?Senioridade $senioridade): self
    {
        $this->senioridade = $senioridade;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * @return Collection<int, Turma>
     */
    public function getTurmas(): Collection
    {
        return $this->turmas;
    }

    public function addTurma(Turma $turma): self
    {
        if (!$this->turmas->contains($turma)) {
            $this->turmas[] = $turma;
            $turma->addColaborador($this);
        }

        return $this;
    }

    public function removeTurma(Turma $turma): self
    {
        if ($this->turmas->removeElement($turma)) {
            $turma->removeColaborador($this);
        }

        return $this;
    }

    
}
