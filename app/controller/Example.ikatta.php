<?php
namespace Ikatta\controller;

use DataRequest;
use Nilai;
use VigntDB;

class ExampleController
{
    public function data($id, $power)
    {
        $a = urldecode($id);
        echo "param 1 : $a <br> param 2 : $power";
    }

    public function vigntTable(DataRequest $dr)
    { 
        // var_dump($dr->all());
        // return json()->make($dr->all());
        return $dr->all();

    }

    public function viewvignt()
    {        
        
        return view('tesvignttable')->render();
    }

    public function index()
    {

        $data = 'Welcome to Vignt Ikatta The Simplest PHP Framework';
        $datas = [
            'Attala', 'Surya', 'Prima', 'Amanda',
        ];

        // $dataz2 = VigntDB::database('local')
        //     ->raw('SELECT * FROM outbond')
        //     ->param(['id' => 1])
        //     ->count();
            
        // $datazz = Nilai::join('master_mapel', 'master_mapel.id', '=', 'nilai.mapel_id', 'INNER')
        //     ->join('master_siswa', 'master_siswa.id', '=', 'nilai.siswa_id', 'INNER')
        //     ->where('nilai', '=', 80)
        //     ->orWhere('nilai', '>=', 90)
        //     ->whereRaw('pengampu = :pengampu AND mata_pelajaran = :babo', ['pengampu' => 'budi', 'babo' => 'ipa'])
        //     ->orderBy('nilai', 'ASC')
        //     ->sqlwp();

        // $datazz2 = VigntDB::database('default')->table('nilai')
        //     ->join('master_mapel', 'master_mapel.id', '=', 'nilai.mapel_id', 'INNER')
        //     ->join('master_siswa', 'master_siswa.id', '=', 'nilai.siswa_id', 'INNER')
        //     ->where('nilai', '=', 80)
        //     ->orWhere('nilai', '>=', 90)
        //     ->whereRaw('pengampu = :pengampuxx', ['pengampuxx' => 'budi'])
        //     ->orderBy('nilai', 'ASC')
        //     ->getAll();
            
        // dd($datazz, $datazz2);
            
        // return dengan pass data
        return view('tes_index.index')->data([
            'data' => $data,
            'datas' => $datas,
        ])->render();

        // return dalam bentuk json (api)
        return json()->make($datas);

        // return hanya dalam bentuk view
        return 'view@tes_index.index';
    }

    public function example()
    {
        // gunakan getOne untuk mendapatkan data pertama dan getLast untuk mendapatkan data terakhir
        // gunakan getAll() untuk mendapatkan semua data dalam bentuk array
        // gunakan alls() untuk mendapatkan seluruh data dalam bentuk object

        // jika menggunakan raw maka bind param dengan :xx ->param ('xx' => val);

        $dataz2 = VigntDB::database('local')->raw('SELECT * FROM outbond WHERE id = :id')->param(['id' => 1])->getAll();
        $dataz3 = VigntDB::database('local')->table('outbond')->whereIn('id', [1, 2, 3])->getAll();

        // gunakan dv untuk dump data dengan view dan dd untuk dump data dan mematikan code
        dv($dataz2);
        dd($dataz3);
    }

}
