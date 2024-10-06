<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class GejalaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gejala = [
            [
                "kode" => "G1",
                "nama" => "Semai busuk sebelum atau sesudah muncul dari tanah"
            ],
            [
                "kode" => "G2",
                "nama" => "Tanaman tumbuh menjadi tanaman kerdil"
            ],
            [
                "kode" => "G3",
                "nama" => "Daun terlihat pucat"
            ],
            [
                "kode" => "G4",
                "nama" => "Bagian atas daun terlihat layu "
            ],
            [
                "kode" => "G5",
                "nama" => "Tanaman layu dan mati"
            ],
            [
                "kode" => "G6",
                "nama" => "Batang terdapat coreng kecoklatan"
            ],
            [
                "kode" => "G7",
                "nama" => "Batang memiliki massa spora merah jambu"
            ],
            [
                "kode" => "G8",
                "nama" => "Batang terlihat pecah mengeluarkan cairan bewarna coklat"
            ],
            [
                "kode" => "G9",
                "nama" => "Jika batang dibelah tampak bagian kayu dari batang bewarna coklat "
            ],
            [
                "kode" => "G10",
                "nama" => "Bagian bawah daun terdapat bercak agak bulat keputihan "
            ],
            [
                "kode" => "G11",
                "nama" => "Batang terdapat bercak coreng kecoklatan"
            ],
            [
                "kode" => "G12",
                "nama" => "Bagian atas daun terdapat bercak bulat keputihan "
            ],
            [
                "kode" => "G13",
                "nama" => "Seluruh daun tampak dilapisi tepung putih "
            ],
            [
                "kode" => "G14",
                "nama" => "Bagian atas daun terdapat bercak kuning"
            ],
            [
                "kode" => "G15",
                "nama" => "Pada cuaca lembab, sisi bawah bercak terdapat jamur berbulu bewarna keunguan atau keabuan"
            ],
            [
                "kode" => "G16",
                "nama" => "Daun terlihat menjadi coklat"
            ],
            [
                "kode" => "G17",
                "nama" => "Daun terlihat mengeriput"
            ],
            [
                "kode" => "G18",
                "nama" => "Daun terdapat bercak bulat coklat "
            ],
            [
                "kode" => "G19",
                "nama" => "Daun terdapat bercak coklat tua kemerahan "
            ],
            [
                "kode" => "G20",
                "nama" => "Bercak coklat tua kemerahan pada daun meluas, saling berhubungan sehingga daun mengering"
            ],
            [
                "kode" => "G21",
                "nama" => "Batang atau tangkai terdapat daun terdapat bercak sempit memanjang , kebasahan, mengedap bewarna kuning atau coklat"
            ],
            [
                "kode" => "G22",
                "nama" => "Pada buah yang masih muda terdapat bercak melekuk (mengendap) dalam, garis tengahnya dapat mencapai 1 cm"
            ],
            [
                "kode" => "G23",
                "nama" => "Pada tepi buah mengeluarkan cairan yang mengering seperti karet."
            ],
            [
                "kode" => "G24",
                "nama" => "Pada bercak di buah terbentuk spora pathogen bewarna hijau kecoklatan "
            ],
            [
                "kode" => "G25",
                "nama" => "Pada buah yang lebih tua terdapat kudis coklat yang bergabus"
            ],
            [
                "kode" => "G26",
                "nama" => "Daun terdapat bercak kuning kecil bersudut, pada bagian bawah mengeluarkan eksudat bewarna coklat"
            ],
            [
                "kode" => "G27",
                "nama" => "Daun terdapat bercak coklat muda kelabu"
            ],
            [
                "kode" => "G28",
                "nama" => "Bercak di daun berlubang"
            ],
            [
                "kode" => "G29",
                "nama" => "Pada buah terjadi pembusukan yang masuk sampai ke dalam daging buah"
            ],
            [
                "kode" => "G30",
                "nama" => "Daun layu tetapi warna daun tetap hijau kemudian semua daun layu dan mati"
            ],
            [
                "kode" => "G31",
                "nama" => "Batang layu, jika dipotong akan mengeluarkan lender bakteri bewarna putih kental dan lengkat"
            ],
            [
                "kode" => "G32",
                "nama" => "Pada buah terdapat bercak kebasahan dan lunak bewarna coklat yangpada akhirnya bercak mengendap dan berkerut"
            ],
            [
                "kode" => "G33",
                "nama" => "Pada buah terdapat bercak kebasahan, lunak, lembek dan akan pecah jika sedikit ditekan"
            ],
            [
                "kode" => "G34",
                "nama" => "Pada bagian buah yang busuk terbentuk miselium yang hebat"
            ],
            [
                "kode" => "G35",
                "nama" => "Daun mengalami kloris (perubahan warna menjadi menguning) tanpa adanya bercak"
            ],
            [
                "kode" => "G36",
                "nama" => "Daun mengalami perubahan bentuk (daun menjadi kriting dan lebih kecil dari biasanya)"
            ]
        ];

        foreach ($gejala as $val) {
            DB::table('gejala')->insert([
                'nama' => $val['nama'],
                'kode' => $val['kode'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}