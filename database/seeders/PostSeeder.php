<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penyakit = [
            [
                "penyakit_id" => "1",
                "nama" => "Layu Fusarium",
                "detail" => "layu fusarium ini menunjukkan gejala layu pada tanaman yang sedang terserang. Layu fusarium sendiri merupakan penyakit yang disebabkan oleh infeksi jamur patogen Fusarium oxysporum. Penyebab Penyakit : Penyakit layu pada Melon disebabkann oleh jamur Fusarium oxysporum Schlecht. sp melonis Snyd.et Hans.
Morfologi Patogen :
Awalnya miselium tidak berwarna, semakin tua warna menjadi krem, akhirnya koloni tampak mempunyai benang benang berwarna oker.
Pada miselium yang lebih tua terbentuk klamidospora. Jamur membentuk banyak mikrokodidium ber sel 1, tidak berwarna, lonjong atau bulat telur, 6-15 x 2,5 -4um. Makrokonidium lebih jarang terdapat, berbentuk kumparan, tidak berwarna, kebanyakan bersekat dua atau tiga, berukuran 25 -33 x 3,5 -5,5 um.
Gejala Serangan :
Pada gejala awal terjadi pucatnya tulang – tulang daun diikuti merunduknya tangkai, akhirnya tanaman menjadi layu secara keseluruhan dan tanaman mati.
Jika pangkal batang atau dikelupas dengan kuku atau pisau akan terlihat suatu cincin coklat dari berkas pembuluh.Jamur berada di dalam pembuluh kayu dan menyebabkan berkas pembuluh terdapat nekrotik  berwarna coklat.
Daur Penyakit : 

Fusarium oxysporum bisa bertahan 3 – 5 tahun di dalam tanah. Tanah yang telah terinfeksi sangat sukar untuk disterilkan dari jamur patogen Fusarium oxysporum.

Sel Jamur Fusarium yang diperbesar dengan Mikroskop

Jamur mengadakan infeksnya pada akar, terutama melalui luka luka, lalu menetap dan berkembang di berkas pembuluh. Pengangkutan air dan hara tanah terganggu sehingga tanaman menjadi layu.
Jamur membentuk polipeptida, yang disebut likomarasmin yang dapat mengganggu permeabilitas membran plasma dari tanaman.
Jika jaringan pembuluh mati,pada akar yang terinfeksi jamur akan membentuk spora warna putih keunguan yaitu saat udara sekitar lembab."
                ,
                "saran" => "Penangendaliannya Menghindari lahan yang pernah terserang layu Fusarium untuk ditanami tanaman yang sama dengan tanaman yang terserang sebelumnya.
Untuk memutus siklus hidup penyakit, disarankan untuk melakukan solarisasi lahan. (Lihat : Manfaat solarisasi lahan untuk pengendalian jamur tular tanah)
Apabila sebelumnya ditanami melon  dan terserang layu fusarium maka untuk berikutnya dianami padi atau palawija seperti kacang tanah, atau kedelai.
Pemberian agens Hayati yang berbahan Trichoderma sp dan Gliocladium merupakan antagonis terhadap jamur fusarium penyebab layu ini.  Agens hayati tersebut dapat mengendalikan merebaknya perkembangan jamur tular tanah ini. (Lihat manfaat jamur antagonis untuk pengendalian jamur tular tanah). Salah satu agens hayati yang dimaksud misalnya MOSA GLIO. Agens hayati tersebut diberikan pada media persemaian bibit maupun pada lubang tanam dengan cara ditabur atau dikocorkan."
                ,
                "gambar" => "1717928884500-DSdE2g.jpg"
            ],
            [
                "penyakit_id" => "2",
                "nama" => "Embun Tepung",
                "detail" => "Penyakit embun tepung (Inggris: powdery mildew) merupakan salah satu penyakit tanaman melon yang disebabkan oleh jamur Ordo Eryshiphales",
                "saran" => "solusi efektif yang dapat dilakukan untuk mengontrol penyakit embun tepung adalah melalui pemuliaan tanaman dengan menggunakan kultivar tanaman yang resisten terhadap penyakit ini. Penyemprotan fungisida yang efektif seperti Afugan 0,1%-0,2%",
                "gambar" => "1717928884500-ghR3gG.png"
            ],
            [
                "penyakit_id" => "3",
                "nama" => "Busuk Daun",
                "detail" => "jamur ini tersebar luas di seluruh dunia. Terutama pada ketimun juga potensial pada semangka dan Melon. Gejala serangan adalah terjadi bercak-bercak kuning pada permukaan daun, dan bila cuaca lembab pada sisi bawah bercak terdapat jamur seperti bulu bewarna ke ungu-unguan atau keabu-abuan. gejal awal mulai terjadi pada daundaun muda diyakini klorosisi ( kuning) kemudian menjadi coklat dan keriput.",
                "saran" => "Pengendaliannya penyakit busuk daun antara lain dengan menjaga kebersihan kebun dari sumber infeksi, mencabut tanaman yang terserang cukup parah, dan disemprot fungisida seperti dithane M-45 atau daconil 0,1%-0,2%. Pengendalian hayati
Perlakuan benih dengan produk yang mengandung Pseudomonas fluorescens (10g/kg benih) dapat dilakukan. Penyemprotan larutan yang mengandung bakteri ini setiap 10 hari mengurangi infeksi secara signifikan. Bakteri lain (Bacillus circulans dan Serratia marcescens) telah digunakan untuk mengendalikan spesies Mycosphaerella lain dan mengurangi terjadinya penyakit yang terkait pada tanaman lain. Alternatif lain adalah semprotan 3 gm sulfur yang dapat dibasahi setiap satu liter air atau memberi bubuk sulfur 8-10 kg per hektar.
& Pengendalian kimiawi
Selalu pertimbangkan pendekatan terpadu dengan tindakan pencegahan bersama dengan perlakuan hayati jika tersedia. Pada tahap awal penyakit atau pada tingkat keparahan yang rendah, perlakuan hayati harus dipertimbangkan. Pada tahap lanjutan penyakit atau pada keparahan yang meningkat, pemberian fungisida baru yang mengandung propikonazol atau heksakonazol (2ml/1) disarankan. Ulangi perlakuan setelah satu minggu hingga 10 hari."
                ,
                "gambar" => "1717928884500-Hr42df.jpg"
            ],
            [
                "penyakit_id" => "4",
                "nama" => "Antraknos",
                "detail" => "Menyerang tanaman melon dengan cara menginfeksi daun, batang dan buah, sehingga menimbulkan gejala bercak-bercak warna coklat muda berbentuk bulat. Bercak-bercak ini lambat laun meluas dan bersatu sehingga daun mengering, coklat tua atau hitam",
                "saran" => "Dapat dikendalikan dengan melakukan pola pergiliran tanaman dengan jenis yang bukan famili cucurbitaceae, menanam benih yang sehat, dan disemprotkan fungisida seperti Dithane M-45 atau Delsene MX-200 0,1% - 0,2%",
                "gambar" => "1717928884500-fhT4Ds.jpg"
            ],
            [
                "penyakit_id" => "5",
                "nama" => "Kudis",
                "detail" => "Serangannya buah melon yang masih muda bercak-bercak melekuk ke dalam bergaris tengah sampai 1 cm, dan dibagian tepinya mengeluarkan cairan yang menegring seperti karet. Pada tingkat serangan yang lebih berat menyebabkan buah melon kudis coklat yang bergabus.",
                "saran" => "Pengendaliannya dapat melakukan penggiliran tanaman yang bukan famili cucurbitaceae dan penyemprotan fungisida golongan ditiokarbamat seperti betalate, felimex 80 WP, dan Trineb 80 WP.",
                "gambar" => "1717928884500-TdaE3h.jpg"
            ],
            [
                "penyakit_id" => "6",
                "nama" => "Bercak Daun Bersudut",
                "detail" => "bercak-bercak kecil bulat pada daun yang kemudian membentuk area yang luas, bersudut hingga tidak beraturan dan basah. Area yang terifeksi berubah menjadi abu-abu, rontok, dan meninggalkan lubang yang tidak beraturan. Bercak-bercak melingkar pada buah-buahan, kemudian berubah menjadi putih dan terbuka ",
                "saran" => "Pengendalian hayati
Bahan penyemaian yang terinfeksi dapat diolah dengan larutan bawang putih dan air panas (50° c) selama 30 menit. Di rumah kaca, terjadinya bercak bersudut pada daun dapat ditekan dengan mengendalikan kelembaban malam hari (hingga 80-90%) dengan penurun kelembapan. Agen pengendalian hayati Pentaphage secara efektif menghancurkan P. syringae. Fungisida tembaga organik dapat memperlambat penyebaran penyakit.
Pengendalian kimiawi
Selalu pertimbangkan pendekatan terpadu berupa tindakan pencegahan bersama dengan perlakuan hayati jika tersedia. Pestisida yang mengandung tembaga hidroksida dapat diberikan. Perawatan ini paling efektif ketika suhu di atas 24°C dan dedaunan basah. Menyemprot pada hari yang panas ketika dedaunan kering dapat melukai tanaman. Penyemprotan mingguan mungkin diperlukan untuk mengendalikan penyakit."
                ,
                "gambar" => "1717928884500-Gj4Rtd.jpg"
            ],
            [
                "penyakit_id" => "7",
                "nama" => "Layu Bakteri",
                "detail" => "Layu Bakteri
Gejala utama penyakit ini adalah tanaman merambat yang layu parah, diikuti dengan kematian tanaman yang cepat. Penyakit ini disebabkan oleh bakteri Erwinia tracheiphila , dan pada awalnya mungkin hanya menyerang beberapa tanaman merambat pada satu tanaman. Namun, seiring berkembangnya penyakit, semakin banyak daun yang layu, dan akhirnya seluruh tanaman merambat terpengaruh. Penyakit layu bakteri paling parah terjadi pada mentimun dan melon, dan lebih ringan pada labu, labu, dan semangka."
                ,
                "saran" => "Pencegahan & Pengobatan: Tidak ada pengendalian kimiawi untuk layu bakteri setelah tanaman terinfeksi. Bakteri ini dibawa dari satu tanaman ke tanaman lainnya melalui kumbang mentimun yang belang atau berbintik. Kumbang menyebarkan bakteri layu dengan memakan tanaman merambat yang terinfeksi dan kemudian memakan tanaman sehat.

Layu bakteri dapat dikurangi di kebun Anda jika kumbang dapat dikendalikan saat pertama kali muncul aktivitas. Insektisida yang mengendalikan kumbang mentimun belang dan berbintik di kebun sayur rumah antara lain bifenthrin, cyhalothrin, atau cypermethrin (lihat HGIC 2207, Mentimun, Labu, Melon & Serangga Cucurbit Lainnya ). Lebah menyerbuki sebagian besar sayuran ini, jadi semprotkan semua insektisida pada sore hari. Oleskan semua bahan kimia sesuai petunjuk pada label. Buang semua tanaman yang terinfeksi untuk mengurangi penyebaran bakteri oleh kumbang."
                ,
                "gambar" => "1717928884500-Ghrdsa.jpg"
            ],
            [
                "penyakit_id" => "8",
                "nama" => "Busuk Phytophthora",
                "detail" => "Penyakit ini disebabkan oleh Phytophthora nicotianae. Penyakit ini menyebabkan	terjadinya bercak kebasahan yang berubah menjadi cokelat dan lunak dan	akhirny bercak mengendap dan	berkerut. Penyakit tanaman melon ini disebabkan oleh jamur patogen Phytophthora nicotianae, P. capsici, dan Pythium sp. Penyakit ini bisa menyebabkan munculnya bercak coklat kebasahan yang memanjang dan daun seperti tersiram air panas. Becak kebahasan pada buah melon bisa berubah menjadi coklat kehitaman dan lunak. Semakin lama, becak akan mengkerut dan mengendap Bagian buah yang terserang akan terselimuti jamur. Kondisi ini menyebabkan kualitas buah menurun dan tidak layak dikonsumsi."
                ,
                "saran" => "erdapat fungisida kimia preventif yang dapat digunakan untuk mengendalikan hawar Phytophthora. Perawatan benih dan penyemprotan fungisida dapat mencegah kematian bibit dan mengurangi penyakit hawar daun dan busuk buah. Perlakuan benih dengan mefenoxam [Apron XL LS dengan takaran 0,42 ml/kg (0,64 fl oz/100 lb) benih] atau metalaxyl [Allegiance FL dengan takaran 0,98 ml/kg (1,5 fl oz/100 lb) benih] dapat melindungi bibit melon dari P. capsici hingga 5 minggu setelah tanam. Aplikasi penyemprotan dimethomorph [Acrobat 50WP dengan takaran 448 g/ha (6,4 oz/A)] ditambah tembaga sulfat [misalnya Cuprofix Disperss 36.9F dengan takaran 2,25 kg/ha (2 lb/A)], dengan interval mingguan , dapat memberikan perlindungan efektif terhadap penyakit hawar daun dan busuk buah akibat P. capsici di lahan melon Menggabungkan perlakuan benih Apron XL LS dengan aplikasi penyemprotan Acrobat plus tembaga dapat meminimalkan kerugian panen akibat penyakit hawar Phytophthora di lahan cucurbit. 
",
                "gambar" => "1717928884500-gKdSl3.jpg"
            ],
            [
                "penyakit_id" => "9",
                "nama" => "Busuk Pythuim",
                "detail" => "Busuk Pythium memiliki gejala yang hampir sama	 dengan	 busuk buah phytophtora. Penyakit ini disebabkan oleh jamur Pythium aphanidermatum.Gejala busuk buah pythium dimulai dengan lesi berwarna kecoklatan dan basah kuyup yang dengan cepat menjadi besar, berair, lunak, dan busuk. Pembusukan umumnya dimulai pada bagian buah yang bersentuhan dengan tanah. Pada mentimun, lepuh berwarna coklat hingga hijau tua sering terlihat pada buah sebelum menjadi encer dan membusuk. Miselium kapas putih terlihat pada jaringan busuk, terutama saat cuaca lembab. Busuk buah Pythium paling parah terjadi di lahan dengan drainase buruk selama cuaca basah. Penyakit ini dapat menyebabkan buah tidak dapat dipasarkan."
                ,
                "saran" => "Kontrol biologis
Tidak ada strategi pengendalian biologis yang dikembangkan untuk busuk buah Pythium.
Kontrol Kimia
Pengendalian kimiawi harus digunakan bersamaan dengan pengendalian budaya agar menjadi paling efektif. Yaitu dengan pestisida Mefenosam dan Ridomil 2E"
                ,
                "gambar" => "1717928884500-34fHGd.jpg"
            ],
            [
                "penyakit_id" => "10",
                "nama" => "Mosaic (Wmv)",
                "detail" => "Penyebabnya adalah Watermelon Mosaic Virus (WMV) dan Cucumber Mosaic Virus (CMV). Gejala umum serangan virus ini adalah menyebabkan klorosis, belang-belang, lepuh, perubahan bentuk daun (abnormal), dan terhambatnya pertumbuhan.
",
                "saran" => "Pencegahan  menggunakan benih atau bibit yang sehat dan bebas virus, mencabut tanaman sakit,dan menyemprot kutu-kutu daun dengan insectisida efektif seperti hostathior 40 EC atau lebaycid 500 EC 0,1% sampai 0,2%
",
                "gambar" => "1717928884500-h43Dt5.jpg"
            ],
        ];

        foreach ($penyakit as $val) {
            DB::table('post')->insert([
                'penyakit_id' => $val['penyakit_id'],
                'nama' => $val['nama'],
                'detail' => $val['detail'],
                'saran' => $val['saran'],
                'gambar' => $val['gambar'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}