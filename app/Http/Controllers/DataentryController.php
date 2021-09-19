<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;

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
        //
        // $query = Data::updateOrCreate([
        //     'nks'   => $request->nks,
        // ], [
        //     'dok_diterima'     => $request->get('about'),
        //     'dok_diserahkan' => $request->get('sec_email'),
        //     'deskripsi'    => $request->get("gender")
        // ]);
        $query = Data::where('nks', $request->nks)
            ->update(['dok_diterima' => $request->dok_diterima, 'dok_diserahkan' => $request->dok_diserahkan, 'deskripsi' => $request->deskripsi]);
        // dump($query);
        if ($query == 1) {
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
    public function show(Request $request)
    {
        //
        // dump($request->all());
        $query = Data::where('nks', $request->nks)->get()->toArray();
        // dump($query);
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

    public function showall(Request $request)
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
