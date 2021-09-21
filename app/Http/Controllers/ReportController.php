<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\Input;
use App\Models\Kabkot;
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Json;

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
        if (session('level') != 'ADMINKAB') {
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
            $kabkotlist = Kabkot::all();
            $arraykab = [];
            $arraydok_terima = [];
            $arraydok_serah = [];
            $kode_kab = session('kode_wil');
            if ($request->petugas == null || $request->petugas == '0') {
                if ($request->nks == null || $request->nks == '0') {
                    $datas = DB::table('m_dsbs')->where('kd_kab', $kode_kab)->join('kabkot', 'm_dsbs.kd_kab', '=', 'kabkot.kode_kab')
                        ->groupBy('m_dsbs.kd_kab')
                        ->select(DB::raw('m_dsbs.kd_kab, nm_kab as nama, sum(m_dsbs.dok_diterima) as dok_diterima, sum(m_dsbs.dok_diserahkan) as dok_diserahkan'))
                        ->get();

                    $datas = DB::table('m_dsbs')->where('kd_kab', $kode_kab)
                        ->groupBy('m_dsbs.nks')
                        ->select(DB::raw('nks as nama , dok_diterima, dok_diserahkan, deskripsi, pml, updated_at'))->get();
                } else {
                    $datas = DB::table('input')
                        ->select(DB::raw('updated_at as nama , dok_diterima , dok_diserahkan, deskripsi, created_at'))
                        ->where('nks', $request->nks)->get();
                }
                $nkss = Data::where('kd_kab', $kode_kab)->get();
            } else {
                if ($request->nks == null && $request->nks == '0') {
                    $datas = DB::table('m_dsbs')
                        ->where('m_dsbs.pml', $request->petugas)
                        ->select(DB::raw('m_dsbs.kd_kab, nks as nama ,m_dsbs.dok_diterima as dok_diterima, m_dsbs.dok_diserahkan as dok_diserahkan, pml, deskripsi, created_at'))
                        ->get();
                } else {
                    $datas = DB::table('input')
                        ->select(DB::raw('updated_at as nama , dok_diterima , dok_diserahkan, deskripsi, created_at'))
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
                'kabkotlist',
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
        if (session('level') != 'ADMINKAB') {
            return redirect()->action([LoginController::class, 'logout']);
        } else {
            $kabkotlist = Kabkot::all();
            $arraykab = [];
            $arraydok_terima = [];
            $arraydok_serah = [];
            $kode_kab = $request->kab;
            if ($request->kab == null || $request->kab == '1600') {
            } else {
            }
            if ($request->petugas == null || $request->petugas == '0') {
                if ($request->nks == null || $request->nks == '0') {
                    $datas = DB::table('m_dsbs')->where('kd_kab', $kode_kab)->join('kabkot', 'm_dsbs.kd_kab', '=', 'kabkot.kode_kab')
                        ->groupBy('m_dsbs.kd_kab')
                        ->select(DB::raw('m_dsbs.kd_kab, nm_kab as nama, sum(m_dsbs.dok_diterima) as dok_diterima, sum(m_dsbs.dok_diserahkan) as dok_diserahkan'))
                        ->get();

                    $datas = DB::table('m_dsbs')->where('kd_kab', $kode_kab)
                        ->groupBy('m_dsbs.nks')
                        ->select(DB::raw('nks as nama , dok_diterima, dok_diserahkan, deskripsi, pml, updated_at'))->get();
                } else {
                    $datas = DB::table('input')
                        ->select(DB::raw('updated_at as nama , dok_diterima , dok_diserahkan, deskripsi, created_at'))
                        ->where('nks', $request->nks)->get();
                }
                $nkss = Data::where('kd_kab', $kode_kab)->get();
            } else {
                if ($request->nks == null && $request->nks == '0') {
                    $datas = DB::table('m_dsbs')
                        ->where('m_dsbs.pml', $request->petugas)
                        ->select(DB::raw('m_dsbs.kd_kab, nks as nama ,m_dsbs.dok_diterima as dok_diterima, m_dsbs.dok_diserahkan as dok_diserahkan, pml, deskripsi, created_at'))
                        ->get();
                } else {
                    $datas = DB::table('input')
                        ->select(DB::raw('updated_at as nama , dok_diterima , dok_diserahkan, deskripsi, created_at'))
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
