<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\Input;
use App\Models\Kabkot;
use App\Models\Petugas;
use App\Models\Tanggal;
use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\This;
use PHPUnit\Util\Json;
use stdClass;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Reader\IReader;



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
                        // dump($datas);
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
                    // dump($datas);
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

            if ($request->kab != null && $request->kab != '1600') {
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
            $kabkotlist = Kabkot::all();
            // $kd_kab = session('kode_kab');
            if($request->kab != null ){
                $kd_kab = substr($request->kab,2,2);
            }else{
                if(session('kode_kab') == "00"){
                    $kd_kab = '01';
                }else{
                    $kd_kab = session('kode_kab');
                }
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
                    // dump($tgl['tanggal']);
                    $input = DB::table('input')->orderBy('updated_at', 'desc')->where('nks', $nks->nks)->where('tanggal_laporan', 'like', $tgl['tanggal'] . "%")->get();
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
            $labels = [];
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
            return view('tabeltanggal/tabeltanggal', compact(
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

    public function bytanggal(Request $request)
    {
        if (!session()->has('username')) {
            return redirect()->action([LoginController::class, 'logout']);
        } else {
            $kabkotlist = Kabkot::all();
            if($request->tgl != null ){
                $tgls = $request->tgl;
            }
            else{
               $tgls = '2021-09-23';
            }

            if($request->kab != null ){
                $kd_kab = substr($request->kab,2,2);
            }else{
                if(session('kode_kab') == "00"){
                    $kd_kab = '01';
                }else{
                    $kd_kab = session('kode_kab');
                }
            }
            $namakab = Kabkot::where('kode_kab', $kd_kab )->get();
            $nkss = Data::where('kd_kab', $kd_kab)->get();
            $tanggallist = Tanggal::all()->toArray();
            $datas2=[];
            foreach($nkss as $nks){
               $dat = Input::where('nks', $nks->nks)->where('tanggal_laporan', $tgls)->get();
               if(count($dat)!= 0){
                $datas2[] = [
                    'nks'=>$nks->nks,
                    'dok_diterima' => $dat[0]->dok_diterima,
                    'dok_diserahkan' => $dat[0]->dok_diserahkan,
                    'deskripsi' => $dat[0]->deskripsi,
                    'terakhir_diupdate' => $dat[0]->updated_at,
                ];
               }else{
                $datas2[] = [
                    'nks'=>$nks->nks,
                    'dok_diterima' => null,
                    'dok_diserahkan' => null,
                    'deskripsi' => null,
                    'terakhir_diupdate' => null,
                ];
               }
            }
            $labels = [];
            $dt_diterima = [];
            $dt_diserahkan = [];
            $color = [];
            $fillcolor = [];
            $dataset1 = [];
            $dataset2 = [];

            foreach($datas2 as $data2){
                $labels[] = $data2['nks'];
                $dt_diterima[] = $data2['dok_diterima'];
                $dt_diserahkan[] = $data2['dok_diserahkan'];
                $r = rand( 0,  255);
                $g = rand( 0,  255);
                $b = rand( 0,  255);
                $dt_diterima_color[] = 'rgba(' . $r . ', '.$g.', '.$b. ', 0.3)';
                $dt_diserahkan_color[] = 'rgba(' . $r . ', '.$g.', '.$b. ', 0.6)';
                $color[] = 'rgba(' . $r . ','.$g.','.$b. ', 1)';;
            }

            $dataset1[] = [
                'label'=> 'Dokumen Diterima',
                'data' => $dt_diterima,
                'borderWidth'=> 1,
                'borderColor'=> $color,
                'backgroundColor'=> $dt_diterima_color
            ];
            $dataset2[] = [
                'label'=> 'Dokumen Diserahkan',
                'data' => $dt_diserahkan,
                'borderWidth'=> 1,
                'borderColor'=> $color,
                'backgroundColor'=> $dt_diserahkan_color
            ];

            // dump($dataset1);
            return view('tabeltanggal/bytanggal', compact(
                'dataset1',
                'dataset2',
                'kabkotlist',
                'request',
                'labels',
                'nkss',
                'datas2',
                'tanggallist',
                'namakab',
                'tgls'
            ));
        }


    }

    public function bypml(Request $request)
    {
        if (!session()->has('username')) {
            return redirect()->action([LoginController::class, 'logout']);
        } else {
            $kabkotlist = Kabkot::all();
            $kd_kab = session('kode_kab');
            if($request->kab != null ){
                $kd_kab = substr($request->kab,2,2);
            }else{
                if(session('kode_kab') == "00"){
                    $kd_kab = '01';
                }else{
                    $kd_kab = session('kode_kab');
                }

            }

            $pmllist = Petugas::where('kd_kab', $kd_kab)->where('level', 'PML')->get();
            $pml1  = $pmllist[0]->kode;

            if($request->pml !=null ){
                $kode_pml = $request->pml;
            }else{
                $kode_pml = $pml1;
            }

            $namakab = Kabkot::where('kode_kab', $kd_kab )->get();
            $nkss = Data::where('pml', $kode_pml)->get();
            $tanggal = Tanggal::all()->toArray();
            $datas2 = [];
            foreach ($nkss as $key => $nks) {
                $data3 = [];
                $data4 = [];
                foreach ($tanggal as $tgl) {
                    $input = DB::table('input')->orderBy('updated_at', 'desc')->where('nks', $nks->nks)->where('tanggal_laporan', 'like', $tgl['tanggal'] . "%")->get();
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

            $labels = [];
            foreach ($tanggal as $tgl) {
                array_push($labels, $tgl['tanggal']);
            }
            $dataset1 = [];
            $dataset2 = [];
            foreach($datas2 as $data2){
                $dt_diterima = [];
                $dt_diserahkan = [];
                $color = [];
                foreach($data2['dok_diterima'] as $dt2){
                    array_push($dt_diterima, $dt2);
                    array_push($color, '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6));
                }
                foreach($data2['dok_diserahkan'] as $dt2){
                    array_push($dt_diserahkan, $dt2);
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

            return view('tabeltanggal/bypml', compact(
                'dataset1',
                'dataset2',
                'kabkotlist',
                'request',
                'labels',
                // 'nkss',
                'datas2',
                'tanggal',
                'namakab',
                'pmllist',
                'pml1'
            ));
        }

    }

     public function downloadreportkab(Request $request)
    {
        # code...
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $datas = $this->querykab(session('kode_kab'));
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NKS');
        $sheet->setCellValue('C1', 'Dokumen Diterima');
        $sheet->setCellValue('D1', 'Dokumen Diserahkan');
        $sheet->setCellValue('E1', 'Deskripsi');
        $sheet->setCellValue('F1', 'PML');
        $sheet->setCellValue('G1', 'Terakhir Diupdate');
        foreach($datas as $key => $data){
            $sheet->setCellValue('A'.floatval($key+2), $key+1);
            $sheet->setCellValue('B'.floatval($key+2), $data->nama);
            $sheet->setCellValue('C'.floatval($key+2), $data->dok_diterima);
            $sheet->setCellValue('D'.floatval($key+2), $data->dok_diserahkan);
            $sheet->setCellValue('E'.floatval($key+2), $data->deskripsi);
            $sheet->setCellValue('F'.floatval($key+2), $data->pml);
            $sheet->setCellValue('G'.floatval($key+2), $data->updated_at);
        }
        if ($request->petugas != null && $request->petugas != '0' && $request->petugas != "null") {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $datas = $this->querypetugas($request);
            // dd($datas);
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'NKS');
            $sheet->setCellValue('C1', 'Dokumen Diterima');
            $sheet->setCellValue('D1', 'Dokumen Diserahkan');
            $sheet->setCellValue('E1', 'Deskripsi');
            $sheet->setCellValue('F1', 'Terakhir Diupdate');
            foreach($datas as $key => $data){
                $sheet->setCellValue('A'.floatval($key+2), $key+1);
                $sheet->setCellValue('B'.floatval($key+2), $data->nama);
                $sheet->setCellValue('C'.floatval($key+2), $data->dok_diterima);
                $sheet->setCellValue('D'.floatval($key+2), $data->dok_diserahkan);
                $sheet->setCellValue('E'.floatval($key+2), $data->deskripsi);
                $sheet->setCellValue('F'.floatval($key+2), $data->updated_at);
            }
        }
        if($request->nks != NULL && $request->nks != '0' && $request->nks != "null" ){
            $datas = $this->querynks($request);
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Waktu');
            $sheet->setCellValue('C1', 'Dokumen Diterima');
            $sheet->setCellValue('D1', 'Dokumen Diserahkan');
            $sheet->setCellValue('E1', 'Deskripsi');
            foreach($datas as $key => $data){
                $sheet->setCellValue('A'.floatval($key+2), $key+1);
                $sheet->setCellValue('B'.floatval($key+2), $data->nama);
                $sheet->setCellValue('C'.floatval($key+2), $data->dok_diterima);
                $sheet->setCellValue('D'.floatval($key+2), $data->dok_diserahkan);
                $sheet->setCellValue('E'.floatval($key+2), $data->deskripsi);
            }
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = 'data.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        return $writer->save('php://output');
    }

    public function downloadreport(Request $request){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $datas = $this->querybiasa();

        // dd($datas);
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Kabupaten/Kota');
        $sheet->setCellValue('C1', 'Dokumen Diterima');
        $sheet->setCellValue('D1', 'Dokumen Diserahkan');
        $sheet->setCellValue('E1', 'Terakhir Diupdate');
        foreach($datas as $key => $data){
            $sheet->setCellValue('A'.floatval($key+2), $key+1);
            $sheet->setCellValue('B'.floatval($key+2), $data->nama);
            $sheet->setCellValue('C'.floatval($key+2), $data->dok_diterima);
            $sheet->setCellValue('D'.floatval($key+2), $data->dok_diserahkan);
            $sheet->setCellValue('E'.floatval($key+2), $data->updated_at);
        }
        if($request->kab != null && $request->kab != '00' && $request->kab != "null" ){
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $datas = $this->querykab(substr($request->kab,2,2));
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'NKS');
            $sheet->setCellValue('C1', 'Dokumen Diterima');
            $sheet->setCellValue('D1', 'Dokumen Diserahkan');
            $sheet->setCellValue('E1', 'Deskripsi');
            $sheet->setCellValue('F1', 'PML');
            $sheet->setCellValue('G1', 'Terakhir Diupdate');
            foreach($datas as $key => $data){
                $sheet->setCellValue('A'.floatval($key+2), $key+1);
                $sheet->setCellValue('B'.floatval($key+2), $data->nama);
                $sheet->setCellValue('C'.floatval($key+2), $data->dok_diterima);
                $sheet->setCellValue('D'.floatval($key+2), $data->dok_diserahkan);
                $sheet->setCellValue('E'.floatval($key+2), $data->deskripsi);
                $sheet->setCellValue('F'.floatval($key+2), $data->pml);
                $sheet->setCellValue('G'.floatval($key+2), $data->updated_at);
            }
        }


        if ($request->petugas != null && $request->petugas != '0' && $request->petugas != "null") {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $datas = $this->querypetugas($request);
            // dd($datas);
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'NKS');
            $sheet->setCellValue('C1', 'Dokumen Diterima');
            $sheet->setCellValue('D1', 'Dokumen Diserahkan');
            $sheet->setCellValue('E1', 'Deskripsi');
            $sheet->setCellValue('F1', 'Terakhir Diupdate');
            foreach($datas as $key => $data){
                $sheet->setCellValue('A'.floatval($key+2), $key+1);
                $sheet->setCellValue('B'.floatval($key+2), $data->nama);
                $sheet->setCellValue('C'.floatval($key+2), $data->dok_diterima);
                $sheet->setCellValue('D'.floatval($key+2), $data->dok_diserahkan);
                $sheet->setCellValue('E'.floatval($key+2), $data->deskripsi);
                $sheet->setCellValue('F'.floatval($key+2), $data->updated_at);
            }
        }
        if($request->nks != NULL && $request->nks != '0' && $request->nks != "null" ){
            $datas = $this->querynks($request);
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Waktu');
            $sheet->setCellValue('C1', 'Dokumen Diterima');
            $sheet->setCellValue('D1', 'Dokumen Diserahkan');
            $sheet->setCellValue('E1', 'Deskripsi');
            foreach($datas as $key => $data){
                $sheet->setCellValue('A'.floatval($key+2), $key+1);
                $sheet->setCellValue('B'.floatval($key+2), $data->nama);
                $sheet->setCellValue('C'.floatval($key+2), $data->dok_diterima);
                $sheet->setCellValue('D'.floatval($key+2), $data->dok_diserahkan);
                $sheet->setCellValue('E'.floatval($key+2), $data->deskripsi);
            }
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = 'data.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        return $writer->save('php://output');


    }

    public function downloadtabeltanggal(Request $request)
    {
        # code...
        if($request->kab != null ){
            $kd_kab = substr($request->kab,2,2);
        }else{
            if(session('kode_kab') == "00"){
                $kd_kab = '01';
            }else{
                $kd_kab = session('kode_kab');
            }
        }

        // dd($kd_kab);
        $nkss = Data::where('kd_kab', $kd_kab)->get();
        $tanggal = Tanggal::all()->toArray();
        $datas2 = [];
        // dd($tanggal);
        foreach ($nkss as $key => $nks) {
            $data3 = [];
            $data4 = [];
            foreach ($tanggal as $tgl) {
                // dump($tgl['tanggal']);
                $input = DB::table('input')->orderBy('updated_at', 'desc')->where('nks', $nks->nks)->where('tanggal_laporan', 'like', $tgl['tanggal'] . "%")->get();
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
        // dd($datas2);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // $datas = $this->querykab(session('kode_kab'));
        $sheet->mergeCells('A1:A3');
        $sheet->setCellValue('A1', 'No');
        $sheet->mergeCells('B1:B3');
        $sheet->setCellValue('B1', 'NKS');
        $sheet->mergeCells('C1:'.$this->number_to_alphabet((count($tanggal)*2)+2).'1');
        $sheet->setCellValue('C1', "Tanggal");

        foreach($tanggal as $key => $tgl){
            // dump(''.$this->number_to_alphabet(($key*2)+3).'2:'.$this->number_to_alphabet(($key*2)+4).'2');
            $sheet->mergeCells(''.$this->number_to_alphabet(($key*2)+3).'2:'.$this->number_to_alphabet(($key*2)+4).'2');
            $sheet->setCellValue(''.$this->number_to_alphabet(($key*2)+3).'2', $tgl['tanggal']);
            // dd($this->number_to_alphabet(($key*2)+3));
            $sheet->setCellValue(''.$this->number_to_alphabet(($key*2)+3).'3', "Diterima");
            $sheet->setCellValue(''.$this->number_to_alphabet(($key*2)+4).'3', "Diserahkan");
        }

        foreach($datas2 as $key => $data2){
            $sheet->setCellValue('A'.floatval($key+4) , $key+1);
            $sheet->setCellValue('B'.floatval($key+4) , $data2['nks']);
            // $sheet->setCellValue('C'.floatval($key+4) , $data2['dok_diterima']);
            foreach($data2['dok_diterima'] as $key2=> $dok_diterima){
                $sheet->setCellValue(''.$this->number_to_alphabet(($key2*2)+3).floatval($key+4) , $dok_diterima);
            }
            foreach($data2['dok_diserahkan'] as $key3=> $dok_diserahkan){
                $sheet->setCellValue(''.$this->number_to_alphabet(($key3*2)+4).floatval($key+4) , $dok_diserahkan);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'data.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        return $writer->save('php://output');
    }


    public function downloadtabeltanggalbypml(Request $request){
        $kd_kab = session('kode_kab');
        if($request->kab != null  ){
            $kd_kab = substr($request->kab,2,2);
        }else{
            if(session('kode_kab') == "00"){
                $kd_kab = '01';
            }else{
                $kd_kab = session('kode_kab');
            }

        }

        $pmllist = Petugas::where('kd_kab', $kd_kab)->where('level', 'PML')->get();
        dd($pmllist);
        $pml1  = $pmllist[0]->kode;
        if($request->pml !=null ){
            $kode_pml = $request->pml;
        }else{
            $kode_pml = $pml1;
        }

        $namakab = Kabkot::where('kode_kab', $kd_kab )->get();
        $nkss = Data::where('pml', $kode_pml)->get();
        $tanggal = Tanggal::all()->toArray();
        $datas2 = [];
        foreach ($nkss as $key => $nks) {
            $data3 = [];
            $data4 = [];
            foreach ($tanggal as $tgl) {
                $input = DB::table('input')->orderBy('updated_at', 'desc')->where('nks', $nks->nks)->where('tanggal_laporan', 'like', $tgl['tanggal'] . "%")->get();
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
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // $datas = $this->querykab(session('kode_kab'));
        $sheet->mergeCells('A1:A3');
        $sheet->setCellValue('A1', 'No');
        $sheet->mergeCells('B1:B3');
        $sheet->setCellValue('B1', 'NKS');
        $sheet->mergeCells('C1:'.$this->number_to_alphabet((count($tanggal)*2)+2).'1');
        $sheet->setCellValue('C1', "Tanggal");

        foreach($tanggal as $key => $tgl){
            // dump(''.$this->number_to_alphabet(($key*2)+3).'2:'.$this->number_to_alphabet(($key*2)+4).'2');
            $sheet->mergeCells(''.$this->number_to_alphabet(($key*2)+3).'2:'.$this->number_to_alphabet(($key*2)+4).'2');
            $sheet->setCellValue(''.$this->number_to_alphabet(($key*2)+3).'2', $tgl['tanggal']);
            // dd($this->number_to_alphabet(($key*2)+3));
            $sheet->setCellValue(''.$this->number_to_alphabet(($key*2)+3).'3', "Diterima");
            $sheet->setCellValue(''.$this->number_to_alphabet(($key*2)+4).'3', "Diserahkan");
        }

        foreach($datas2 as $key => $data2){
            $sheet->setCellValue('A'.floatval($key+4) , $key+1);
            $sheet->setCellValue('B'.floatval($key+4) , $data2['nks']);
            // $sheet->setCellValue('C'.floatval($key+4) , $data2['dok_diterima']);
            foreach($data2['dok_diterima'] as $key2=> $dok_diterima){
                $sheet->setCellValue(''.$this->number_to_alphabet(($key2*2)+3).floatval($key+4) , $dok_diterima);
            }
            foreach($data2['dok_diserahkan'] as $key3=> $dok_diserahkan){
                $sheet->setCellValue(''.$this->number_to_alphabet(($key3*2)+4).floatval($key+4) , $dok_diserahkan);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'data.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        return $writer->save('php://output');
    }


    public function downloadtabeltanggalbytanggal(Request $request){
        if($request->tgl != null ){
            $tgls = $request->tgl;
        }
        else{
           $tgls = '2021-09-23';
        }

        if($request->kab != null ){
            $kd_kab = substr($request->kab,2,2);
        }else{
            if(session('kode_kab') == "00"){
                $kd_kab = '01';
            }else{
                $kd_kab = session('kode_kab');
            }
        }
        $nkss = Data::where('kd_kab', $kd_kab)->get();
        $datas2=[];
        foreach($nkss as $nks){
           $dat = Input::where('nks', $nks->nks)->where('tanggal_laporan', $tgls)->get();
           if(count($dat)!= 0){
            $datas2[] = [
                'nks'=>$nks->nks,
                'pml'=>$nks->pml,
                'dok_diterima' => $dat[0]->dok_diterima,
                'dok_diserahkan' => $dat[0]->dok_diserahkan,
                'deskripsi' => $dat[0]->deskripsi,
                'terakhir_diupdate' => $dat[0]->updated_at,
            ];
           }else{
            $datas2[] = [
                'nks'=>$nks->nks,
                'pml'=>$nks->pml,
                'dok_diterima' => null,
                'dok_diserahkan' => null,
                'deskripsi' => null,
                'terakhir_diupdate' => null,
            ];
           }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NKS');
        $sheet->setCellValue('C1', 'Dokumen Diterima');
        $sheet->setCellValue('D1', 'Dokumen Diserahkan');
        $sheet->setCellValue('E1', 'Deskripsi');
        $sheet->setCellValue('F1', 'PML');
        $sheet->setCellValue('G1', 'Terakhir Diupdate');
        foreach($datas2 as $key => $data){
            $sheet->setCellValue('A'.floatval($key+2), $key+1);
            $sheet->setCellValue('B'.floatval($key+2), $data['nks']);
            $sheet->setCellValue('C'.floatval($key+2), $data['dok_diterima']);
            $sheet->setCellValue('D'.floatval($key+2), $data['dok_diserahkan']);
            $sheet->setCellValue('E'.floatval($key+2), $data['deskripsi']);
            $sheet->setCellValue('F'.floatval($key+2), $data['pml']);
            $sheet->setCellValue('G'.floatval($key+2), $data['terakhir_diupdate']);
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = 'data.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');

        return $writer->save('php://output');
    }

    public function querypetugas($request){
        $datas = DB::table('m_dsbs')->where('pml', $request->petugas)
                    ->select(DB::raw('nks as nama, dok_diterima , dok_diserahkan, deskripsi, pml, updated_at'))
                    ->get();
        return $datas;
    }

    public function querynks($request){
        $datas = DB::table('input')->orderBy('updated_at', 'desc')
        ->select(DB::raw('updated_at as nama , dok_diterima , dok_diserahkan, deskripsi'))
        ->where('nks', $request->nks)->get();
        return $datas;
    }

    public function querybiasa(){
        $datas = DB::table('m_dsbs')
                ->join('kabkot', 'm_dsbs.kd_kab', '=', 'kabkot.kode_kab')
                ->groupBy('m_dsbs.kd_kab')
                ->select(DB::raw('m_dsbs.kd_kab, nm_kab as nama, sum(m_dsbs.dok_diterima) as dok_diterima, sum(m_dsbs.dok_diserahkan) as dok_diserahkan, updated_at'))
                ->get();
        return $datas;
    }

    public function querykab($kode_kab){
        $datas = DB::table('m_dsbs')
                        ->where('kd_kab', $kode_kab)
                        ->select(DB::raw('nks as nama , dok_diterima, dok_diserahkan, deskripsi, pml, updated_at'))
                        ->get();
        return $datas;
    }


    function number_to_alphabet($number) {
        $number = intval($number);
        if ($number <= 0) {
           return '';
        }
        $alphabet = '';
        while($number != 0) {
           $p = ($number - 1) % 26;
           $number = intval(($number - $p) / 26);
           $alphabet = chr(65 + $p) . $alphabet;
       }
       return $alphabet;
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
