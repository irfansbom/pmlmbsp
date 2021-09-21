<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataentryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

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
        $query =  Input::create([
            'nks' => $request->nks,
            'dok_diterima' => $request->dok_diterima,
            'dok_diserahkan' => $request->dok_diserahkan,
            'deskripsi' => $request->deskripsi
        ]);

        Data::where('nks', $request->nks)->update([
            'nks' => $request->nks,
            'dok_diterima' => $request->dok_diterima,
            'dok_diserahkan' => $request->dok_diserahkan,
            'deskripsi' => $request->deskripsi
        ]);

        if ($query) {
            return  response()->json([
                'success' => true,
                'message' => 'berhasil terupdate',
            ], 200);
        } else {
            return  response()->json([
                'success' => false,
                'message' => 'gagal terupdate',
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showbynks(Request $request)
    {
        $query = Data::where('nks', $request->nks)->get()->toArray();
        return  response()->json([
            'success' => true,
            'message' => 'mengambil data',
            'data' => $query
        ], 200);
    }

    public function showbykab(Request $request)
    {
        //
        // dump($request->all());
        $query = Data::where('kode_kab', $request->kode_kab)->get()->toArray();
        // dump($query);
        return  response()->json([
            'success' => true,
            'message' => 'mengambil data',
            'data' => $query
        ], 200);
    }

    public function showall()
    {
        //
        // dump($request->all());
        $query = Data::get()->toArray();
        // dump($query);
        return  response()->json([
            'success' => true,
            'message' => 'mengambil data',
            'data' => $query
        ], 200);
    }


    public function nkslog(Request $request)
    {

        $query = input::where('nks', $request->nks)->get()->toArray();
        return  response()->json([
            'success' => true,
            'message' => 'History NKS',
            'data' => $query
        ], 200);
    }

    public function nks_bypml(Request $request)
    {
        $query = Data::where('pml', $request->kode_pml)->get()->toArray();
        return response()->json([
            'success' => true,
            'message' => 'Data NKS by PML',
            'data' => $query
        ], 200);
    }


    public function nkslogbypml(Request $request)
    {
        $pml = $request->kode_pml;
        $query = DB::table('input')
            ->join('m_dsbs', 'm_dsbs.nks', '=', 'input.nks')
            ->where('m_dsbs.pml', $pml)
            ->select()->get()->toArray();
        return  response()->json([
            'success' => true,
            'message' => 'nks by pml',
            'data' => $query
        ], 200);
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
