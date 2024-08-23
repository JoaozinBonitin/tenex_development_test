<?php

namespace App\Entity;

use App\Repository\ParcelaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParcelaRepository::class)]
#[ORM\Table(name:'parcela')]
class Parcela
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $valor = null;

    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column]
    private ?bool $entrada = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $dataVencimento = null;

    #[ORM\ManyToOne(targetEntity: Carnet::class, inversedBy: 'parcelas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Carnet $carnet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getValor(): ?float
    {
        return $this->valor;
    }

    public function setValor(float $valor): static
    {
        $this->valor = $valor;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function isEntrada(): ?bool
    {
        return $this->entrada;
    }

    public function setEntrada(bool $entrada): static
    {
        $this->entrada = $entrada;

        return $this;
    }

    public function getDataVencimento(): ?\DateTimeImmutable
    {
        return $this->dataVencimento;
    }

    public function setDataVencimento(\DateTimeImmutable $dataVencimento): static
    {
        $this->dataVencimento = $dataVencimento;

        return $this;
    }

    public function getCarnet(): ?Carnet
    {
        return $this->carnet;
    }

    public function setCarnet(?Carnet $carnet): static
    {
        $this->carnet = $carnet;

        return $this;
    }
}
