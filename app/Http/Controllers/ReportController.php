<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\Input;
use App\Models\Kabkot;
use App\Models\Petugas;
use App\Models\Tanggal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Json;
use stdClass;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dump(session()->has('username'));
        if (session('level') != 'ADMIN') {
            return redirect()->action([LoginController::class, 'logout']);
        } else {
            $arraykab = [];
            $arraydok_terima = [];
            $arraydok_serah = [];
            $kabkotlist = Kabkot::all();
            $label = [];
            $chart = [];

            if ($request->kab != null && $request->kab != '1600') {
                $kode_kab = substr($request->kab, 2, 2);
                #wilayah terpilih
                if ($request->petugas != null && $request->petugas != '0') {
                    #wilayah terpilih petugas terpilih
                    if ($request->nks != null && $request->nks != '0') {
                        #wilayah terpilih petugas terpilih nks terpilih
                        $datas = DB::table('input')
                            ->select(DB::raw('updated_at as nama , dok_diterima , dok_diserahkan, deskripsi, created_at'))
                            ->where('nks', $request->nks)->get();
                        foreach ($datas as $data) {
                            array_push($arraykab, $data->nama);
                            array_push($arraydok_terima, $data->dok_diterima);
                            array_push($arraydok_serah, $data->dok_diserahkan);
                        }
                        dump($datas);
                    } else {
                        #wilayah terpilih petugas terpilih nks semua
                        $datas = DB::table('m_dsbs')
                            ->where('m_dsbs.pml', $request->petugas)
                            ->select(DB::raw('m_dsbs.kd_kab, nks as nama ,m_dsbs.dok_diterima as dok_diterima, m_dsbs.dok_diserahkan as dok_diserahkan, pml, deskripsi, created_at'))
                            ->get();
                        foreach ($datas as $data) {
                            array_push($arraykab, $data->nama);
                            array_push($arraydok_terima, $data->dok_diterima);
                            array_push($arraydok_serah, $data->dok_diserahkan);
                        }
                    }
                    $nkss = Data::where('pml', $request->petugas)->where('kd_kab', $kode_kab)->get();
                } else {

                    # wilayah terpilih petugas bebas
                    if ($request->nks != null && $request->nks != '0') {
                        $datas = DB::table('input')
                            ->select(DB::raw('updated_at as nama , dok_diterima , dok_diserahkan, deskripsi, created_at'))
                            ->where('nks', $request->nks)->get();
                        foreach ($datas as $data) {
                            array_push($arraykab, $data->nama);
                            array_push($arraydok_terima, $data->dok_diterima);
                            array_push($arraydok_serah, $data->dok_diserahkan);
                        }
                    } else {
                        $datas = DB::table('m_dsbs')
                            ->where('m_dsbs.kd_kab', $kode_kab)
                            ->groupBy('m_dsbs.nks')
                            ->select(DB::raw('m_dsbs.kd_kab, nks as nama , m_dsbs.dok_diterima as dok_diterima, m_dsbs.dok_diserahkan as dok_diserahkan, pml'))
                            ->get();
                        foreach ($datas as $data) {
                            array_push($arraykab, $data->nama);
                            array_push($arraydok_terima, $data->dok_diterima);
                            array_push($arraydok_serah, $data->dok_diserahkan);
                        }
                    }

                    $nkss = Data::where('kd_kab', $kode_kab)->get();
                }
                $petugass = Petugas::where('level', 'PML')->where('kd_kab', $kode_kab)->get();
            } else {

                if ($request->petugas != null && $request->petugas != '0') {

                    if ($request->nks != null && $request->nks != '0') {
                        $datas = DB::table('input')
                            ->select(DB::raw('updated_at as nama , dok_diterima , dok_diserahkan, deskripsi, created_at'))
                            ->where('nks', $request->nks)->get();
                        foreach ($datas as $data) {
                            array_push($arraykab, $data->nama);
                            array_push($arraydok_terima, $data->dok_diterima);
                            array_push($arraydok_serah, $data->dok_diserahkan);
                        }
                        dump($datas);
                    } else {

                        $datas = DB::table('m_dsbs')
                            ->where('m_dsbs.pml', $request->petugas)
                            ->select(DB::raw('m_dsbs.kd_kab, nks as nama ,m_dsbs.dok_diterima as dok_diterima, m_dsbs.dok_diserahkan as dok_diserahkan, pml, deskripsi, created_at'))
                            ->get();
                        foreach ($datas as $data) {
                            array_push($arraykab, $data->nama);
                            array_push($arraydok_terima, $data->dok_diterima);
                            array_push($arraydok_serah, $data->dok_diserahkan);
                        }
                    }
                    $nkss = Data::where('pml', $request->petugas)->get();
                } else {
                    if ($request->nks != null && $request->nks != '0') {
                        $datas = DB::table('input')
                            ->select(DB::raw('updated_at as nama , dok_diterima , dok_diserahkan, deskripsi, created_at'))
                            ->where('nks', $request->nks)->get();
                        foreach ($datas as $data) {
                            array_push($arraykab, $data->nama);
                            array_push($arraydok_terima, $data->dok_diterima);
                            array_push($arraydok_serah, $data->dok_diserahkan);
                        }
                    } else {
                        $datas = DB::table('m_dsbs')->join('kabkot', 'm_dsbs.kd_kab', '=', 'kabkot.kode_kab')
                            ->groupBy('m_dsbs.kd_kab')
                            ->select(DB::raw('m_dsbs.kd_kab, nm_kab as nama, sum(m_dsbs.dok_diterima) as dok_diterima, sum(m_dsbs.dok_diserahkan) as dok_diserahkan'))
                            ->get();
                        foreach ($datas as $data) {
                            array_push($arraykab, $data->nama);
                            array_push($arraydok_terima, $data->dok_diterima);
                            array_push($arraydok_serah, $data->dok_diserahkan);
                        }
                    }

                    $nkss = Data::all();
                }
                $petugass = Petugas::where('level', 'PML')->get();
            }
            return view('report', compact(
                'datas',
                'petugass',
                'kabkotlist',
                'request',
                'arraykab',
                'arraydok_terima',
                'arraydok_serah',
                'nkss'
            ));
        }
    }

    public function adminkab(Request $request)
    {
        if (session('level') != 'ADMINKAB') {
            return redirect()->action([LoginController::class, 'logout']);
        } else {
            $arraykab = [];
            $arraydok_terima = [];
            $arraydok_serah = [];
            $kode_kab = session('kode_kab');
            if ($request->petugas == null || $request->petugas == '0') {
                if ($request->nks == null || $request->nks == '0') {
                    $datas = DB::table('m_dsbs')
                        ->where('kd_kab', $kode_kab)
                        ->select(DB::raw('nks as nama , dok_diterima, dok_diserahkan, deskripsi, pml, updated_at'))
                        ->get();
                } else {
                    $datas = DB::table('input')->orderBy('updated_at', 'desc')
                        ->select(DB::raw('updated_at as nama , dok_diterima , dok_diserahkan, deskripsi'))
                        ->where('nks', $request->nks)
                        ->get();
                }
                $nkss = Data::where('kd_kab', $kode_kab)->get();
            } else {
                if ($request->nks == null || $request->nks == '0') {
                    $datas = DB::table('m_dsbs')
                        ->where('kd_kab', $kode_kab);
                    if ($request->petugas != null && $request->petugas != '0') {
                        $datas = $datas->where('pml', $request->petugas);
                    }
                    $datas = $datas->select(DB::raw('nks as nama, dok_diterima , dok_diserahkan, deskripsi, pml, updated_at'))
                        ->get();
                    dump($datas);
                } else {
                    $datas = DB::table('input')->orderBy('updated_at', 'desc')
                        ->select(DB::raw('updated_at as nama , dok_diterima , dok_diserahkan, deskripsi'))
                        ->where('nks', $request->nks)->get();
                }
                $nkss = Data::where('kd_kab', $kode_kab)->where('pml', $request->petugas)->get();
            }
            $petugass = Petugas::where('kd_kab', $kode_kab)->where('level', "PML")->get();
            foreach ($datas as $data) {
                array_push($arraykab, $data->nama);
                array_push($arraydok_terima, $data->dok_diterima);
                array_push($arraydok_serah, $data->dok_diserahkan);
            }
            return view('reportadminkab', compact(
                'datas',
                'petugass',
                'request',
                'arraykab',
                'arraydok_terima',
                'arraydok_serah',
                'nkss'
            ));
        }
    }

    public function admin(Request $request)
    {
        if (session('level') != 'ADMINPROP') {
            return redirect()->action([LoginController::class, 'logout']);
        } else {
            $kabkotlist = Kabkot::all();
            $arraykab = [];
            $arraydok_terima = [];
            $arraydok_serah = [];
            $kode_kab = substr($request->kab, 2, 2);

            $datas = DB::table('m_dsbs')
                ->join('kabkot', 'm_dsbs.kd_kab', '=', 'kabkot.kode_kab')
                ->groupBy('m_dsbs.kd_kab')
                ->select(DB::raw('m_dsbs.kd_kab, nm_kab as nama, sum(m_dsbs.dok_diterima) as dok_diterima, sum(m_dsbs.dok_diserahkan) as dok_diserahkan, updated_at'));
            $petugass = Petugas::where('level', "PML")->get();
            $nkss = Data::all();

            if ($request->kab != null && $request->kab != '0') {
                $nkss = Data::where('kd_kab', $kode_kab)->get();
                $petugass = Petugas::where('kd_kab', $kode_kab)->where('level', "PML")->get();
                $datas = DB::table('m_dsbs');
                $datas = $datas->where('kd_kab', $kode_kab)->select(DB::raw('nks as nama, dok_diterima , dok_diserahkan, deskripsi, pml, updated_at'));
            }
            if ($request->petugas != null && $request->petugas != '0') {
                $nkss = Data::where('pml', $request->petugas)->get();
                $datas = $datas->where('pml', $request->petugas)->select(DB::raw('nks as nama, dok_diterima , dok_diserahkan, deskripsi, pml, updated_at'));
            }

            $datas = $datas->get();
            if ($request->nks != null && $request->nks != '0') {
                $datas = DB::table('input')->orderBy('updated_at', 'desc')
                    ->select(DB::raw('updated_at as nama , dok_diterima , dok_diserahkan, deskripsi'))
                    ->where('nks', $request->nks)->get();
            }
            // dump($nkss);
            foreach ($datas as $data) {
                array_push($arraykab, $data->nama);
                array_push($arraydok_terima, $data->dok_diterima);
                array_push($arraydok_serah, $data->dok_diserahkan);
            }
            return view('reportadmin', compact(
                'datas',
                'petugass',
                'kabkotlist',
                'request',
                'arraykab',
                'arraydok_terima',
                'arraydok_serah',
                'nkss'
            ));
        }
    }


    public function tabeltanggal(Request $request)
    {
        if (!session()->has('username')) {
            return redirect()->action([LoginController::class, 'logout']);
        } else {
            $labels = [];
            $kabkotlist = Kabkot::all();
            $kd_kab = session('kode_kab');
            if(session('kode_kab')=='00'){
                $kd_kab = '02';
            }
            
            $namakab = Kabkot::where('kode_kab', $kd_kab )->get();
            $nkss = Data::where('kd_kab', $kd_kab)->get();
            $tanggal = Tanggal::all()->toArray();
            $datas2 = [];
            // dd($namakab);
            foreach ($nkss as $key => $nks) {
                $data3 = [];
                $data4 = [];
                foreach ($tanggal as $tgl) {
                    $input = DB::table('input')->orderBy('updated_at', 'desc')->where('nks', $nks->nks)->where('updated_at', 'like', $tgl['tanggal'] . "%")->get();
                    if (count($input) != 0) {
                        array_push($data3, $input[0]->dok_diterima);
                        array_push($data4, $input[0]->dok_diserahkan);
                    } else {
                        array_push($data3, null);
                        array_push($data4, null);
                    }
                }
                $datas2[] = [
                    'nks' => $nks->nks,
                    'dok_diterima' => $data3,
                    'dok_diserahkan' =>$data4
                ];
            }
            // dump($datas2);
            foreach ($tanggal as $tgl) {
                array_push($labels, $tgl['tanggal']);
                // array_push($arraydok_terima, $data->dok_diterima);
                // array_push($arraydok_serah, $data->dok_diserahkan);
            }
            $dataset1 = [];
            foreach($datas2 as $data2){
                // dump($data2); 
                $dt_diterima = [];
                $dt_diserahkan = [];
                $color = [];
                foreach($data2['dok_diterima'] as $dt2){
                    array_push($dt_diterima, $dt2);
                    array_push($color, '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6));
                }
                foreach($data2['dok_diserahkan'] as $dt2){
                    array_push($dt_diserahkan, $dt2);
                    // array_push($color, '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6));
                }
            
                $dataset1[] = [
                    'label'=> $data2['nks'],
                    'data' => $dt_diterima,
                    'borderWidth'=> 1,
                    'borderColor'=> $color,
                    'backgroundColor'=> $color
                ];
                $dataset2[] = [
                    'label'=> $data2['nks'],
                    'data' => $dt_diserahkan,
                    'borderWidth'=> 1,
                    'borderColor'=> $color,
                    'backgroundColor'=> $color
                ];
            }

            // dump($dataset1);
            return view('tabeltanggal', compact(
                'dataset1',
                'dataset2',
                'kabkotlist',
                'request',
                'labels',
                'nkss',
                'datas2',
                'tanggal',
                'namakab'
            ));
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
