<?php

namespace App\Http\Controllers;

use App\Http\Libraries\System;
use App\Models\Modal;
use App\Models\Panen;
use App\Models\Pendapatan;
use App\Models\Pengeluaran;
use App\Models\SDM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardKeuanganController extends Controller
{
    public function getDataChart($greenhouseId)
    {
        $panen = Panen::where('greenhouse_id', $greenhouseId)
            ->get();

        $labels = [];
        $pendapatan = [];
        $pengeluaran = [];

        foreach ($panen as $val) {
            $labels[] = $val->nama;

            $pendapatan[] = Pendapatan::where('panen_id', $val->id)
                ->where('greenhouse_id', $greenhouseId)
                ->sum('nominal');

            $pengeluaran[] = Pengeluaran::where('panen_id', $val->id)
                ->where('greenhouse_id', $greenhouseId)
                ->sum('nominal');
        }

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'keuangan chart',
            'data' => [
                'labels' => $labels,
                'pendapatan' => $pendapatan,
                'pengeluaran' => $pengeluaran
            ]
        ]);
    }

    public function getDataPie(Request $request, $greenhouseId)
    {
        $panen = $request->input('panen') ?: null;

        $getLabelPendapatan = Pendapatan::where('greenhouse_id', $greenhouseId);
        $getLabelPengeluaran = Pengeluaran::where('greenhouse_id', $greenhouseId);

        if ($panen) {
            $getLabelPendapatan->where('panen_id', $panen);
            $getLabelPengeluaran->where('panen_id', $panen);
        }

        $getLabelPendapatan = $getLabelPendapatan
            ->distinct()
            ->select(['kategori'])
            ->get();

        $getLabelPengeluaran = $getLabelPengeluaran
            ->distinct()
            ->select(['kategori'])
            ->get();

        $labelsPendapatan = [];
        $dataPendapatan = [];
        foreach ($getLabelPendapatan as $val) {
            $pendapatan = Pendapatan::where('greenhouse_id', $greenhouseId)
                ->where('kategori', $val->kategori);

            if ($panen)
                $pendapatan->where('panen_id', $panen);

            $labelsPendapatan[] = $val->kategori;
            $dataPendapatan[] = $pendapatan->sum('nominal');
        }

        $labelsPengeluaran = [];
        $dataPengeluaran = [];
        foreach ($getLabelPengeluaran as $val) {
            $pengeluaran = Pengeluaran::where('greenhouse_id', $greenhouseId)
                ->where('kategori', $val->kategori);

            if ($panen)
                $pengeluaran->where('panen_id', $panen);

            $labelsPengeluaran[] = $val->kategori;
            $dataPengeluaran[] = $pengeluaran->sum('nominal');
        }

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'keuangan chart',
            'data' => [
                'pendapatan' => [
                    'labels' => $labelsPendapatan,
                    'data' => $dataPendapatan
                ],
                'pengeluaran' => [
                    'labels' => $labelsPengeluaran,
                    'data' => $dataPengeluaran
                ]
            ]
        ]);
    }

    public function getDataLaba($greenhouseId, $tahun)
    {
        $panen = Panen::where('greenhouse_id', $greenhouseId)
            ->get();

        $labels = [];
        $data = [];
        $laba = [];
        $totalLaba = 0;

        foreach ($panen as $val) {
            $labels[] = $val->nama;

            $pendapatan = Pendapatan::where('panen_id', $val->id)
                ->where('greenhouse_id', $greenhouseId)
                ->whereYear('tanggal', $tahun)
                ->sum('nominal');
            $pengeluaran = Pengeluaran::where('panen_id', $val->id)
                ->where('greenhouse_id', $greenhouseId)
                ->whereYear('tanggal', $tahun)
                ->sum('nominal');
            $sdm = SDM::where('panen_id', $val->id)
                ->whereYear('tanggal', $tahun)
                ->whereHas('panen', function ($q) use ($greenhouseId) {
                    $q->where('greenhouse_id', $greenhouseId);
                })->sum('nominal');
            $modal = Modal::where('panen_id', $val->id)
                ->whereYear('tanggal', $tahun)
                ->whereHas('panen', function ($q) use ($greenhouseId) {
                    $q->where('greenhouse_id', $greenhouseId);
                })->sum('nominal');

            // Log::info($modal);
            // Log::info($greenhouseId);

            $data[] = $pendapatan - ($pengeluaran + $sdm + $modal);
            $laba[$val->id][] = $pendapatan - ($pengeluaran + $sdm + $modal);
            $totalLaba += $pendapatan - ($pengeluaran + $sdm + $modal);
        }

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'keuangan chart',
            'data' => [
                'chart' => [
                    'labels' => $labels,
                    'data' => $data,
                ],
                'laba' => $laba,
                'panen' => $panen,
                'total_laba' => $totalLaba
            ]
        ]);
    }

    public function getPanenByGreenhouse($greenhouseId)
    {
        $data = Panen::where('greenhouse_id', $greenhouseId)
            ->get();

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'keuangan chart',
            'data' => $data
        ]);
    }

    public function getTotal($greenhouseId)
    {
        $pendapatan = Pendapatan::where('greenhouse_id', $greenhouseId)
            ->sum('nominal');

        $pengeluaran = Pengeluaran::where('greenhouse_id', $greenhouseId)
            ->sum('nominal');

        return System::response(200, [
            'statusCode' => 200,
            'message' => 'keuangan total',
            'data' => [
                'pendapatan' => number_format($pendapatan),
                'pengeluaran' => number_format($pengeluaran),
                'laba' => number_format($pendapatan - $pengeluaran)
            ]
        ]);
    }
}