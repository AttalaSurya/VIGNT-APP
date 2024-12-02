<?php
namespace Ikatta\controller;

use DataRequest;
use VigntDB;

class VigntIkattaController
{
    // public function viewIndex()
    // {
    //     return 'view@index';
    // }
    
    public function viewindex()
    {

        $data = 'Welcome to Vignt Ikatta The Simplest PHP Framework';

        $datas = [
            'Attala', 'Surya', 'Prima', 'Amanda',
        ];

        // dd($data);

        return view('tes_index.index')->data([
            'data' => $data,
            'datas' => $datas,
        ])->render();
    }

    public function viewOut()
    {
        
        $datas = VigntDB::database('local')->table('outbond')->orderBy('date', 'DESC')->getAll();
        $total = 0;
        
        foreach ($datas as $data) {
            if(array_key_exists('value', $data)) {
                $total += $data['value'];
            }
        }

        $total = $this->formatRupiah($total);

        return view('out')->data([
            'active' => 'out',
            'menu' => 'Pengeluaran Keseluruhan',
            'icon'  => 'bx-right-top-arrow-circle',
            'total_pengeluaran' => $total,
        ])->render('base_ikatta');

    }

    public function viewIn()
    {
        $datas = VigntDB::database('local')->table('inbound')->orderBy('date', 'DESC')->getAll();
        $total = 0;

        foreach ($datas as $data) {
            if(array_key_exists('value', $data)) {
                $total += $data['value'];
            }
        }

        $total = $this->formatRupiah($total);
        return view('in')->data([
            'active' => 'in',
            'menu' => 'Pemasukan',
            'icon' => 'bx-dollar-circle',
            'total_pemasukan' => $total,
        ])->render('base_ikatta');
    }

    public function getDataOut()
    {
        $datas = [];
        $datas = VigntDB::database('local')->table('outbond')->orderBy('date', 'DESC')->getAll();

        return $datas;
    }

    public function updateValueOut()
    {
        try {
            $datas = [];
            $datas = VigntDB::database('local')->table('outbond')->orderBy('date', 'DESC')->getAll();
            $total = 0;
            
            foreach ($datas as $data) {
                if(array_key_exists('value', $data)) {
                    $total += $data['value'];
                }
            }

            $response = [
                'success' => 1,
                'data' => $total,
                'message' => '',
            ];
        } catch (\Exception $e) {
            $response = [
                'success' => 0,
                'data' => [],
                'message' => $e->getMessage(),
            ];
        }

        return $response;
    }

    public function updateValueIn()
    {
        try {
            $datas = [];
            $datas = VigntDB::database('local')->table('inbound')->orderBy('date', 'DESC')->getAll();
            $total = 0;
            
            foreach ($datas as $data) {
                if(array_key_exists('value', $data)) {
                    $total += $data['value'];
                }
            }

            $response = [
                'success' => 1,
                'data' => $total,
                'message' => '',
            ];
        } catch (\Exception $e) {
            $response = [
                'success' => 0,
                'data' => [],
                'message' => $e->getMessage(),
            ];
        }

        return $response;
    }

    public function getDataIn()
    {
        $datas = [];
        $datas = VigntDB::database('local')->table('inbound')->getAll();

        return $datas;
    }

    public function insertOut(DataRequest $dr)
    {
        $note = $dr->note;
        $label = $dr->label;
        $value = $dr->value;
        $date = $dr->date;
        $error = '';

        if (!$date) {$error = 'Tanggal harus diisi';}
        if (!$note) {$error = 'Keterangan harus diisi';}
        if (!$label) {$error = 'Label harus diisi';}
        if (!$value) {$error = 'Nominal harus diisi';}
        if ($error != '') {return ['success' => 0, 'message' => $error];}

        $insert = VigntDB::database('local')->table('outbond')->insert([
            'note' => $note,
            'label' => $label,
            'value' => $value,
            'date' => $date,
        ]);

        if ($insert) {
            $return = [
                'success' => 1,
                'message' => 'data berhasil disimpan',
            ];
        } else {
            $return = [
                'success' => 0,
                'message' => 'data gagal disimpan',
            ];
        }

        return $return;
    }

    public function insertIn(DataRequest $dr)
    {
        $note = $dr->note;
        $value = $dr->value;
        $date = $dr->date;
        $error = '';

        if (!$date) {$error = 'Tanggal harus diisi';}
        if (!$note) {$error = 'Keterangan harus diisi';}
        if (!$value) {$error = 'Nominal harus diisi';}
        if ($error != '') {return ['success' => 0, 'message' => $error];}

        $insert = VigntDB::database('local')->table('inbound')->insert([
            'note' => $note,
            'value' => $value,
            'date' => $date,
        ]);

        if ($insert) {
            $return = [
                'success' => 1,
                'message' => 'data berhasil disimpan',
            ];
        } else {
            $return = [
                'success' => 0,
                'message' => 'data gagal disimpan',
            ];
        }

        return $return;
    }

    private function formatRupiah($angka) {
        return 'Rp' . number_format($angka, 2, ',', '.');
    }

    public function viewSaldo()
    {
        $dataPemasukan = VigntDB::database('local')->table('inbound')->orderBy('date', 'DESC')->getAll();
        $dataPengeluaran = VigntDB::database('local')->table('outbond')->orderBy('date', 'DESC')->getAll();
        $pemasukan = 0;
        $pengeluaran = 0;
        
        foreach ($dataPemasukan as $masuk) {
            if(array_key_exists('value', $masuk)) {
                $pemasukan += $masuk['value'];
            }
        }

        foreach ($dataPengeluaran as $keluar) {
            if(array_key_exists('value', $keluar)) {
                $pengeluaran += $keluar['value'];
            }
        }

        $saldo = $pemasukan - $pengeluaran;


        $datas = [];
        $datas = VigntDB::database('local')
                        ->raw('SELECT 
                                SUM(CASE WHEN label="Rumah" THEN value ELSE 0 END) AS rumah,
                                SUM(CASE WHEN label="Mingguan" THEN value ELSE 0 END) AS mingguan,
                                SUM(CASE WHEN label="Listrik" THEN value ELSE 0 END) AS listrik,
                                SUM(CASE WHEN label="Laundry" THEN value ELSE 0 END) AS laundry,
                                SUM(CASE WHEN label="Alfa" THEN value ELSE 0 END) AS alfa,
                                SUM(CASE WHEN label="Cadangan" THEN value ELSE 0 END) AS cadangan,
                                SUM(CASE WHEN label="Gas" THEN value ELSE 0 END) AS gas
                                FROM outbond
                                WHERE DATE_FORMAT(DATE_SUB(date, INTERVAL 25 DAY), "%Y-%m") = DATE_FORMAT(DATE_SUB(CURRENT_DATE(), INTERVAL 25 DAY), "%Y-%m")
                                GROUP BY DATE_FORMAT(DATE_SUB(date, INTERVAL 25 DAY), "%Y-%m")')
                        ->getAll();
        $item = [];
        foreach ($datas as $data){
            $item['rumah'] = $this->formatRupiah($data['rumah']);
            $item['rumah maksimal'] = $this->formatRupiah(2450000);
            $item['rumah sisa'] = $this->formatRupiah((2450000 - $data['rumah']));
            $item['mingguan'] = $this->formatRupiah($data['mingguan']);
            $item['mingguan maksimal'] = $this->formatRupiah(500000);
            $item['mingguan sisa'] = $this->formatRupiah((500000 - $data['mingguan']));
            $item['listrik'] = $this->formatRupiah($data['listrik']);
            $item['listrik maksimal'] = $this->formatRupiah(400000);
            $item['listrik sisa'] = $this->formatRupiah((400000 - $data['listrik']));
            $item['laundry'] = $this->formatRupiah($data['laundry']);
            $item['laundry maksimal'] = $this->formatRupiah(30000);
            $item['laundry sisa'] = $this->formatRupiah((30000 - $data['laundry']));
            $item['alfa'] = $this->formatRupiah($data['alfa']);
            $item['alfa maksimal'] = $this->formatRupiah(300000);
            $item['alfa sisa'] = $this->formatRupiah((300000 - $data['alfa']));
            $item['cadangan'] = $this->formatRupiah($data['cadangan']);
            $item['cadangan maksimal'] = $this->formatRupiah(800000);
            $item['cadangan sisa'] = $this->formatRupiah((800000 - $data['cadangan']));
            $item['gas'] = $this->formatRupiah($data['gas']);
            $item['gas maksimal'] = $this->formatRupiah(50000);
            $item['gas sisa'] = $this->formatRupiah((50000 - $data['gas']));
        }

        return view('home')->data([
            'active'        => 'home',
            'menu'          => 'Home',
            'icon'          => 'bx-home',
            'pemasukan'     => $this->formatRupiah($pemasukan),
            'pengeluaran'   => $this->formatRupiah($pengeluaran),
            'saldo'         => $this->formatRupiah($saldo),
            'item'          => $item
        ])->render('base_ikatta');
    }

    public function viewMonthly()
    {
        return view('monthly')->data([
            'active'        => 'monthly',
            'menu'          => 'Transaksi Bulanan',
            'icon'          => 'bx-calendar'
        ])->render('base_ikatta');
    }

    public function getDataMonthly()
    {
        $datas = [];
        $datas = VigntDB::database('local')
                        ->raw('SELECT 
                            p.period,
                            COALESCE(i.total_pemasukan, 0) AS inValue,
                            COALESCE(o.total_pengeluaran, 0) AS outValue,
                            COALESCE(i.total_pemasukan, 0) - COALESCE(o.total_pengeluaran, 0) AS sisa
                            FROM 
                                (SELECT DISTINCT DATE_FORMAT(DATE_SUB(date, INTERVAL 25 DAY), "%Y-%m") AS period
                                FROM 
                                    (SELECT DISTINCT date FROM inbound
                                    UNION
                                    SELECT DISTINCT date FROM outbond) AS dates) AS p
                                     LEFT JOIN 
                                    (SELECT DATE_FORMAT(DATE_SUB(date, INTERVAL 25 DAY), "%Y-%m") AS period,
                                        SUM(value) AS total_pemasukan
                                        FROM inbound
                                        GROUP BY DATE_FORMAT(DATE_SUB(date, INTERVAL 25 DAY), "%Y-%m")) AS i
                                    ON p.period = i.period
                                    LEFT JOIN 
                                    (SELECT DATE_FORMAT(DATE_SUB(date, INTERVAL 25 DAY), "%Y-%m") AS period,
                                        SUM(value) AS total_pengeluaran
                                    FROM outbond
                                    GROUP BY DATE_FORMAT(DATE_SUB(date, INTERVAL 25 DAY), "%Y-%m")) AS o
                                    ON p.period = o.period
                                    ORDER BY p.period')
                        ->getAll();

        foreach($datas as &$data){
            if(array_key_exists('inValue', $data) && array_key_exists('outValue', $data)){
                $data['sisa'] = $data['inValue'] - $data['outValue'];
                
            }   
        }

        return $datas;
    }

    public function viewOutLabel()
    {
        return view('outlabel')->data([
            'active'        => 'out-label',
            'menu'          => 'Pengeluaran Per Label',
            'icon'          => 'bx-right-top-arrow-circle'
        ])->render('base_ikatta');
    }

    public function getDataOutLabel()
    {
        $datas = [];
        $datas = VigntDB::database('local')
                        ->raw('SELECT 
                                p.period,
                                s.rumah,
                                s.mingguan,
                                s.listrik,
                                s.laundry,
                                s.alfa,
                                s.cadangan,
                                s.gas,
                                s.rumah + s.mingguan + s.listrik + s.laundry + s.alfa + s.cadangan + s.gas AS total
                            FROM 
                                (SELECT DISTINCT DATE_FORMAT(DATE_SUB(date, INTERVAL 25 DAY), "%Y-%m") AS period
                                FROM 
                                (SELECT DISTINCT date FROM outbond) AS dates) AS p
                                LEFT JOIN 
                                (SELECT DATE_FORMAT(DATE_SUB(date, INTERVAL 25 DAY), "%Y-%m") AS period,
                                SUM(CASE WHEN label="Rumah" THEN value ELSE 0 END) AS rumah,
                                SUM(CASE WHEN label="Mingguan" THEN value ELSE 0 END) AS mingguan,
                                SUM(CASE WHEN label="Listrik" THEN value ELSE 0 END) AS listrik,
                                SUM(CASE WHEN label="Laundry" THEN value ELSE 0 END) AS laundry,
                                SUM(CASE WHEN label="Alfa" THEN value ELSE 0 END) AS alfa,
                                SUM(CASE WHEN label="Cadangan" THEN value ELSE 0 END) AS cadangan,
                                SUM(CASE WHEN label="Gas" THEN value ELSE 0 END) AS gas
                                FROM outbond
                                GROUP BY DATE_FORMAT(DATE_SUB(date, INTERVAL 25 DAY), "%Y-%m")) AS s
                                ON p.period = s.period
                                ORDER BY p.period')
                        ->getAll();

        return $datas;
    }
}