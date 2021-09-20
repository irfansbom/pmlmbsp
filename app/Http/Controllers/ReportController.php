<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\Kabkot;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        if (!session()->has('username')) {
            return redirect()->action([LoginController::class, 'index']);
        } else {
            $arraykab = [];
            $arraydok_terima = [];
            $arraydok_serah = [];
            $kabkotlist = Kabkot::all();

            if ($request->kab != null && $request->kab != '1600') {
                $kode_kab = substr($request->kab, 2, 2);
                $datas = DB::table('data')
                    ->where('data.kode_kab', $kode_kab)
                    ->join('kecamatan', 'data.kode_kec', '=', 'kecamatan.id_kec')
                    ->groupBy('data.nks')
                    ->select(DB::raw('data.kode_kab, nks as nama ,sum(data.dok_diterima) as dok_diterima, sum(data.dok_diserahkan) as dok_diserahkan, pml'))
                    ->get();
                foreach ($datas as $data) {
                    array_push($arraykab, $data->nama);
                    array_push($arraydok_terima, $data->dok_diterima);
                    array_push($arraydok_serah, $data->dok_diserahkan);
                }
                $petugass = User::where('level', 'PML')->where('kode_wil', $request->kab)->get();
            } else {
                $datas = DB::table('data')->join('kabkot', 'data.kode_kab', '=', 'kabkot.kode_kab')
                    ->groupBy('data.kode_kab')
                    ->select(DB::raw('data.kode_kab, nm_kab as nama,sum(data.dok_diterima) as dok_diterima, sum(data.dok_diserahkan) as dok_diserahkan'))
                    ->get();
                foreach ($datas as $data) {
                    array_push($arraykab, $data->nama);
                    array_push($arraydok_terima, $data->dok_diterima);
                    array_push($arraydok_serah, $data->dok_diserahkan);
                }
                $petugass = User::where('level', 'PML')->get();
            }


            return view('report', compact(
                'datas',
                'petugass',
                'kabkotlist',
                'request',
                'arraykab',
                'arraydok_terima',
                'arraydok_serah'
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
