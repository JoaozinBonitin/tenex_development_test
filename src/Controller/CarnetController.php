<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Carnet;
use App\Entity\Parcela;
use App\Repository\CarnetRepository;
use Symfony\Component\HttpFoundation\Request;

class CarnetController extends AbstractController
{
    #[Route('/carnet', name: 'carnet_create', methods: ['POST'])]
    public function create(Request $request, CarnetRepository $carnetRepository): JsonResponse
    {
        $data = $request->toArray();

        $valorTotal = $data['valor_total'];
        $qtdParcelas = $data['qtd_parcelas'];
        $dataPrimeiroVencimento = new \DateTimeImmutable($data['data_primeiro_vencimento']);
        $periodicidade = $data['periodicidade'];
        $valorEntrada = $data['valor_entrada'] ?? null;

        $carnet = new Carnet();
        $carnet->setValorTotal($valorTotal)
               ->setQtdParcelas($qtdParcelas)
               ->setPeriodicidade($periodicidade)
               ->setDataPrimeiroVencimento($dataPrimeiroVencimento)
               ->setValorEntrada($valorEntrada);

        $valorParcela = ($valorTotal - ($valorEntrada ?? 0)) / $qtdParcelas;
        for ($i = 0; $i < $qtdParcelas; $i++) {
            $parcela = new Parcela();
            $vencimento = (clone $dataPrimeiroVencimento)->modify("+$i month"); // ajuste para a periodicidade
            $parcela->setDataVencimento($vencimento)
                    ->setValor($valorParcela)
                    ->setNumero($i + 1)
                    ->setCarnet($carnet);
            $carnet->addParcela($parcela);
        }

        if ($valorEntrada) {
            $entrada = new Parcela();
            $entrada->setDataVencimento($dataPrimeiroVencimento)
                    ->setValor($valorEntrada)
                    ->setNumero(0)
                    ->setEntrada(true)
                    ->setCarnet($carnet);
            $carnet->addParcela($entrada);
        }

        $carnetRepository->save($carnet, true);

        return $this->json([
            'total' => $carnet->getValorTotal(),
            'valor_entrada' => $carnet->getValorEntrada(),
            'parcelas' => array_map(function (Parcela $parcela) {
                return [
                    'data_vencimento' => $parcela->getDataVencimento()->format('Y-m-d'),
                    'valor' => $parcela->getValor(),
                    'numero' => $parcela->getNumero(),
                    'entrada' => $parcela->isEntrada(),
                ];
            }, $carnet->getParcelas()->toArray()),
        ]);
    }

    #[Route('/carnet/{id}/parcelas', name: 'carnet_parcelas', methods: ['GET'])]
    public function getParcelas(int $id, CarnetRepository $carnetRepository): JsonResponse
    {
        $carnet = $carnetRepository->find($id);
        if (!$carnet) {
            return $this->json(['error' => 'Carnet not found'], 404);
        }

        return $this->json(array_map(function (Parcela $parcela) {
            return [
                'data_vencimento' => $parcela->getDataVencimento()->format('Y-m-d'),
                'valor' => $parcela->getValor(),
                'numero' => $parcela->getNumero(),
                'entrada' => $parcela->isEntrada(),
            ];
        }, $carnet->getParcelas()->toArray()));
    }
}
