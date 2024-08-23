<?php

namespace App\Entity;

use App\Repository\CarnetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarnetRepository::class)]
#[ORM\Table(name:'carnet')]
class Carnet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $valorTotal = null;

    #[ORM\Column(nullable: true)]
    private ?float $valorEntrada = null;

    #[ORM\Column]
    private ?int $qtdParcelas = null;

    #[ORM\Column(length: 255)]
    private ?string $periodicidade = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dataPrimeiroVenimento = null;

    #[ORM\OneToMany(targetEntity: Parcela::class, mappedBy: 'carnet', cascade: ['persist', 'remove'])]
    private Collection $parcelas;

    public function __construct()
    {
        $this->parcelas = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getValorTotal(): ?float
    {
        return $this->valorTotal;
    }

    public function setValorTotal(float $valorTotal): static
    {
        $this->valorTotal = $valorTotal;

        return $this;
    }

    public function getValorEntrada(): ?float
    {
        return $this->valorEntrada;
    }

    public function setValorEntrada(?float $valorEntrada): static
    {
        $this->valorEntrada = $valorEntrada;

        return $this;
    }

    public function getQtdParcelas(): ?int
    {
        return $this->qtdParcelas;
    }

    public function setQtdParcelas(int $qtdParcelas): static
    {
        $this->qtdParcelas = $qtdParcelas;

        return $this;
    }

    public function getPeriodicidade(): ?string
    {
        return $this->periodicidade;
    }

    public function setPeriodicidade(string $periodicidade): static
    {
        $this->periodicidade = $periodicidade;

        return $this;
    }

    public function getDataPrimeiroVenimento(): ?\DateTimeImmutable
    {
        return $this->dataPrimeiroVenimento;
    }

    public function setDataPrimeiroVencimento(\DateTimeImmutable $dataPrimeiroVenimento): static
    {
        $this->dataPrimeiroVenimento = $dataPrimeiroVenimento;

        return $this;
    }

    public function getParcelas(): Collection
    {
        return $this->parcelas;
    }

    public function addParcela(Parcela $parcela): static
    {
        if (!$this->parcelas->contains($parcela)) {
            $this->parcelas[] = $parcela;
            $parcela->setCarnet($this);
        }

        return $this;
    }

    public function removeParcela(Parcela $parcela): static
    {
        if ($this->parcelas->removeElement($parcela)) {
            if ($parcela->getCarnet() === $this) {
                $parcela->setCarnet(null);
            }
        }

        return $this;
    }

}
