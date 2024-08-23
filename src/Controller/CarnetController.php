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

        $valorTotal = (float) $data['valor_total'];
        $qtdParcelas = (int) $data['qtd_parcelas'];
        $dataPrimeiroVencimento = new \DateTimeImmutable($data['data_primeiro_vencimento']);
        $periodicidade = $data['periodicidade'];
        $valorEntrada = isset($data['valor_entrada']) ? (float) $data['valor_entrada'] : 0;

        // Validação dos parametros
        if ($valorTotal <= 0 || $qtdParcelas <= 0) {
            return new JsonResponse(['error' => 'Valor total e quantidade de parcelas devem ser maiores que zero.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($valorEntrada >= $valorTotal) {
            return new JsonResponse(['error' => 'O valor de entrada não pode ser maior ou igual ao valor total.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $valorRestante = $valorTotal - $valorEntrada;
        $valorParcela = $valorRestante / $qtdParcelas;

        $carnet = new Carnet();
        $carnet->setValorTotal($valorTotal)
               ->setQtdParcelas($qtdParcelas)
               ->setPeriodicidade($periodicidade)
               ->setDataPrimeiroVencimento($dataPrimeiroVencimento)
               ->setValorEntrada($valorEntrada);

        $dataAtual = (clone $dataPrimeiroVencimento);

        if ($valorEntrada > 0) {
            // Parcela de entrada
            $entrada = new Parcela();
            $entrada->setDataVencimento($dataPrimeiroVencimento)
                    ->setValor(round($valorEntrada, 2))
                    ->setNumero(0)
                    ->setEntrada(true)
                    ->setCarnet($carnet);
            $carnet->addParcela($entrada);
        }

        $parcelas = [];
        $valorAcumulado = 0;

        for ($i = 0; $i < $qtdParcelas; $i++) {
            $parcela = new Parcela();
            $parcelaValor = $i === ($qtdParcelas - 1) ? $valorRestante - $valorAcumulado : $valorParcela; 
            $parcela->setDataVencimento($dataAtual)
                    ->setValor(round($parcelaValor, 2))
                    ->setNumero($i + 1 + ($valorEntrada > 0 ? 1 : 0))
                    ->setCarnet($carnet);

            $parcelas[] = $parcela;
            $valorAcumulado += $parcelaValor;

            if ($periodicidade === 'mensal') {
                $dataAtual = $dataAtual->add(new \DateInterval('P1M'));
            } elseif ($periodicidade === 'semanal') {
                $dataAtual = $dataAtual->add(new \DateInterval('P1W'));
            }
        }

        foreach ($parcelas as $parcela) {
            $carnet->addParcela($parcela);
        }

        $carnetRepository->save($carnet, true);

        return $this->json([
            'total' => round($carnet->getValorTotal(), 2),
            'valor_entrada' => round($carnet->getValorEntrada() ?? 0, 2),
            'parcelas' => array_map(function (Parcela $parcela) {
                return [
                    'data_vencimento' => $parcela->getDataVencimento()->format('Y-m-d'),
                    'valor' => round($parcela->getValor(), 2),
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
