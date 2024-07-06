<?php

namespace App\Http\Controllers\progressproject;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\enumVar as enum;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\master\Manpower;
use App\Models\master\Material;
use App\Models\master\Perusahaan;
use App\Models\master\Sekolah;
use App\Models\progressproject\Project;
use App\Models\progressproject\Projectmanpower;
use App\Models\progressproject\Projectprogress;
use Illuminate\Support\Facades\Auth;

class ProjectController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->page = 'Project';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-35');
        $user = auth('sanctum')->user();

        Log::channel('mibedil')->info('Halaman '.$this->page);

        $project = [];
        $perusahaan = [];

        if($request->ajax())
        {
            // $search = $request->search;
            // $kotaid = $request->kotaid;
            // $jenis = $request->jenis;
            // $jenjang = $request->jenjang;
            // $perusahaanid = Auth::user()->isPerusahaan() ? auth('sanctum')->user()->perusahaanid : $request->perusahaanid;
            // $sekolahid = Auth::user()->isSekolah() ? auth('sanctum')->user()->sekolahid : $request->sekolahid;

            $data = [];
            $count = 0;
            $page = $request->get('start', 0);  
            $perpage = $request->get('length', 50);

            try {
                $project = DB::table('tbproject')
                        ->join('tbmmaterial', function($join)
                        {
                            $join->on('tbmmaterial.materialid', '=', 'tbproject.materialid');
                            $join->on('tbmmaterial.dlt','=',DB::raw("'0'"));
                        })
                        ->join('tbmmanpower', function($join)
                        {
                            $join->on('tbmmanpower.manpowerid', '=', 'tbproject.pic');
                            $join->on('tbmmanpower.dlt', '=', DB::raw("'0'"));
                            // $join->on('tbmmanpower.status', '=', DB::raw("'5'"));
                        })
                        // ->leftJoin('tbmperusahaan', function($join)
                        // {
                        //     $join->on('tbmperusahaan.perusahaanid', '=', 'tbdetailpagupenganggaran.perusahaanid');
                        //     $join->on('tbmperusahaan.dlt','=',DB::raw("'0'"));
                        // })
                        // ->join('tbmsubkeg', function($join)
                        // {
                        //     $join->on('tbmsubkeg.subkegid', '=', 'tbdetailpenganggaran.subkegid');
                        //     $join->on('tbmsubkeg.dlt','=',DB::raw("'0'"));
                        // })
                        // ->join('tbmsekolah', function($join){
                        //     $join->on('tbmsekolah.sekolahid', '=', 'tbtranssarpraskebutuhan.sekolahid');
                        //     $join->on('tbmsekolah.dlt', '=', DB::raw("'0'"));
                        // })
                        // ->leftJoin('tbdetaillaporan', function($join)
                        // {
                        //     $join->on('tbdetaillaporan.detailpaguanggaranid', '=', 'tbdetailpagupenganggaran.detailpaguanggaranid');
                        //     $join->on('tbdetaillaporan.dlt', '=', DB::raw("'0'"));
                        // })
                        ->select(
                            'tbproject.*',
                            'tbmmaterial.nama',
                            DB::raw('tbmmanpower.nama as pic')
                            // 'tbdetaillaporan.detaillaporanid',
                            // DB::raw('coalesce(tbdetailpagupenganggaran.progres, 0) as progres')
                            // DB::raw('MAX(tbdetaillaporan.progres) as progres')
                        )
                        // ->where('tbtranssarpraskebutuhan.status', '>=', enum::STATUS_KEBUTUHAN_SARPRAS_PROGRES_PEMBANGUNAN)
                        // ->where(function($query){
                        //     if(Auth::user()->isPerusahaan()) $query->where('tbmperusahaan.perusahaanid', auth('sanctum')->user()->perusahaanid);
                        // })
                        // ->where(function($query){
                        //     if(Auth::user()->isSekolah()) $query->where('tbmsekolah.sekolahid', auth('sanctum')->user()->sekolahid);
                        // })
                        // ->distinct()
                        // ->whereIn(DB::raw('(tbdetaillaporan.progres)'), function ($query) {
                        //     $query->select(DB::raw('coalesce(tbdetaillaporan.progres, 0) as progres'))
                        //         ->from('tbdetaillaporan')
                        //         ->where('tbdetaillaporan.dlt', '0')
                        //         ->orderBy('tbdetaillaporan.progres', 'desc')
                        //         ->skip(0)->take(1)
                        //         ->groupBy(
                        //             // 'tbdetaillaporan.detailpaguanggaranid',
                        //             'tbdetaillaporan.detaillaporanid'
                        //         );
                        // })
                        // ->where(function($query) use ($perusahaanid, $kotaid, $jenis, $jenjang, $sekolahid, $search)
                        // {
                        //     if (!is_null($perusahaanid) && $perusahaanid!='') $query->where('tbdetailpagupenganggaran.perusahaanid', $perusahaanid);
                        //     if (!is_null($kotaid) && $kotaid!='') $query->where('tbmsekolah.kotaid', $kotaid);
                        //     if (!is_null($jenis) && $jenis!='') $query->where('tbmsekolah.jenis', $jenis);
                        //     if (!is_null($jenjang) && $jenjang!='') $query->where('tbmsekolah.jenjang', $jenjang);
                        //     if (!is_null($sekolahid) && $sekolahid!='') $query->where('tbmsekolah.sekolahid', $sekolahid);

                        //     if (!is_null($search) && $search!='') {
                        //         // $query->where(DB::raw('CONCAT(tbmkota.kodekota, tbmkota.namakota)'), 'ilike', '%'.$search.'%');
                        //         $query->where(DB::raw('tbdetailpagupenganggaran.jenispagu'), 'ilike', '%'.$search.'%');
                        //         $query->orWhere(DB::raw('tbmperusahaan.nama'), 'ilike', '%'.$search.'%');
                        //         $query->orWhere(DB::raw('tbdetailpenganggaran.subdetailkegiatan'), 'ilike', '%'.$search.'%');
                        //         $query->orWhere(DB::raw('tbmsubkeg.subkegnama'), 'ilike', '%'.$search.'%');
                        //         $query->orWhere(DB::raw('tbdetailpagupenganggaran.nokontrak'), 'ilike', '%'.$search.'%');
                        //     }
                        // })
                        // ->whereIn(DB::raw('(tbdetaillaporan.detaillaporanid, tbdetaillaporan.tgladd)'), function ($query) {
                        //     $query->select('detailpaguanggaranid', DB::raw('MAX(tgladd)'))
                        //         ->from('tbdetaillaporan')
                        //         ->where('tbdetaillaporan.dlt', '0')
                        //         ->groupBy('detaillaporanid');
                        // })
                        ->where('tbproject.dlt', '0')
                        // ->groupBy(
                        //     'tbdetailpenganggaran.detailpenganggaranid',
                        //     'tbdetailpagupenganggaran.detailpaguanggaranid',
                        //     'tbdetailpagupenganggaran.jenispagu', 
                        //     'tbmperusahaan.nama', 
                        //     'tbmsubkeg.subkegnama', 
                        //     'tbdetailpenganggaran.subdetailkegiatan',
                        //     'tbdetailpagupenganggaran.nokontrak',
                        //     'tbdetailpagupenganggaran.nilaikontrak',
                        //     'tbtranssarpraskebutuhan.jeniskebutuhan',
                        //     'tbtranssarpraskebutuhan.sarpraskebutuhanid',
                        //     'tbtranssarpraskebutuhan.status',
                        //     'tbmsekolah.namasekolah',
                        //     // 'tbdetaillaporan.progres',
                        //     // 'tbdetaillaporan.detaillaporanid',
                        // )
                ;
                $count = $project->count();
                $data = $project->skip($page)->take($perpage)->get();

                // $querySum = DB::table('tbdetailpagupenganggaran')
                //     ->select(DB::raw('SUM(nilaipagu) as countpagu'), DB::raw('SUM(nilaikontrak) as countkontrak'))
                //     ->where('tbdetailpagupenganggaran.detailpenganggaranid', $id)
                //     ->where('tbdetailpagupenganggaran.dlt', 0)
                // ;

                // $sumNilaiKontrak = [];
                // foreach ($data as $key => $value) {
                //     $sumNilaiKontrak = $value[$key]->nilaikontrak;
                // }

                return $this->sendResponse([
                    'data' => $data,
                    'count' => $count,
                ], 'project retrieved successfully.');
            }catch (QueryException $e) {
                return $this->sendError('SQL Error', $this->getQueryError($e));
            }
            catch (Exception $e) {
                return $this->sendError('Error', $e->getMessage());
            }  
        }

        $manpower = DB::table('tbmmanpower')
            ->select('tbmmanpower.manpowerid', 'tbmmanpower.nama')
            ->where('tbmmanpower.dlt', 0)
            ->orderBy('tbmmanpower.nama')
            ->get()
        ;

        $material = DB::table('tbmmaterial')
            ->select('tbmmaterial.materialid', 'tbmmaterial.nama')
            ->where('tbmmaterial.dlt', 0)
            ->orderBy('tbmmaterial.nama')
            ->get()
        ;

        $sekolah = DB::table('tbmsekolah')
            ->select('tbmsekolah.sekolahid', 'tbmsekolah.namasekolah')
            ->where('tbmsekolah.dlt', 0)
            ->orderBy('tbmsekolah.namasekolah')
            ->get()
        ;

        $jenisperalatan = DB::table('tbmjenisperalatan')
            ->select('tbmjenisperalatan.jenisperalatanid', 'tbmjenisperalatan.nama')
            ->where('tbmjenisperalatan.dlt', 0)
            ->get()
        ;

        $userPerusahaan = Perusahaan::where('tbmperusahaan.perusahaanid', auth('sanctum')->user()->perusahaanid == null ? -1 : auth('sanctum')->user()->perusahaanid)->first();
        $userSekolah = Sekolah::where('tbmsekolah.sekolahid', auth('sanctum')->user()->sekolahid == null ? -1 : auth('sanctum')->user()->sekolahid)->first();

        return view(
            'progressproject.project.index', 
            [
                'page' => $this->page, 
                'createbutton' => false, 
                'manpower' => $manpower,
                'material' => $material,
                'sekolah' => $sekolah,
                'jenisperalatan' => $jenisperalatan,
                'isPerusahaan' => Auth::user()->isPerusahaan(),
                'isSekolah' => Auth::user()->isSekolah(),
                'userPerusahaan' => $userPerusahaan 
            ]);
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
        $this->authorize('add-35');

        $user = auth('sanctum')->user();

        try {
                $tglnow = date('Y-m-d');
                DB::beginTransaction();
                // Save multiple and file 
                $project = new Project();

                $project->lokasi = $request->lokasi;
                $project->activity = $request->activity;
                $project->category = $request->category;
                $project->pic = $request->pic;
                $project->materialid = $request->materialid;
                $project->target = $request->target;
                $project->progres = 0;
                $project->remark = $request->remark;
                $project->status = 0;
                $project->qtytotal = $request->qtytotal;
                $project->qtyprogress = 0;
                // $project->materialprogress = $request->materialprogress;

                $project->fill(['opadd' => $user->login, 'pcedit' => $request->ip()]);

                $project->save();

                // if ($project->save()) {
                //     $modelDetailPagu = DetailPaguPenganggaran::find($project->detailpaguanggaranid);

                //     $modelDetailPagu->progres = $request->progres;
                //     $modelDetailPagu->fill(['opedit' => $user->login, 'pcedit' => $request->ip()]);

                //     $modelDetailPagu->save();
                // }

                DB::commit();
                
                return response([
                    'success' => true,
                    'data'    => 'Success',
                    'message' => 'project added successfully.',
                ], 200);

        } catch (QueryException $e) {
            return $this->sendError('SQL Error', $this->getQueryError($e));
        }
        catch (Exception $e) {
            return $this->sendError('Error', $e->getMessage());
        }
    }

    public function storeProjectprogess(Request $request)
    {
        $this->authorize('add-35');

        $user = auth('sanctum')->user();

        try {
                $project = Project::where('projectid', $request->progress_projectid)->first();
                $material = Material::find($project->materialid);
                // dd($material->stock);
                // $tglnow = date('Y-m-d');
                DB::beginTransaction();
                // Save multiple and file 
                $model = new Projectprogress();
                
                $model->projectid = $request->progress_projectid;
                $model->tgldari = $request->daritgl;
                $model->tglsampai = $request->sampaitgl;
                $model->progres = $request->progres;

                if($material->stock < $request->qtymaterial){
                    // dd('stock kurang');
                    return response([
                        'success' => false,
                        'data'    => 'lessstock',
                        'message' => 'Stock material yang anda masukkan tidak cukup!',
                    ], 200);
                }else if ($project->qtyprogress + $request->qtymaterial > $project->qtytotal) {
                    return response([
                        'success' => false,
                        'data'    => 'lessstock',
                        'message' => 'Qty progress melebihi dari qty total!',
                    ], 200);
                }
                else {
                    $model->qtymaterial = $request->qtymaterial;
                }

                $model->fill(['opadd' => $user->login, 'pcedit' => $request->ip()]);


                if ($model->save()) {
                    $modelProject = Project::find($model->projectid);

                    $modelProject->progres = $model->progres;
                    $modelProject->qtyprogress = $modelProject->qtyprogress + $request->qtymaterial;
                    $modelProject->fill(['opedit' => $user->login, 'pcedit' => $request->ip()]);

                    $modelProject->save();

                    $material->stock = $material->stock - $request->qtymaterial;
                    $material->save();
                }

                DB::commit();
                
                return response([
                    'success' => true,
                    'data'    => 'Success',
                    'message' => 'progress project added successfully.',
                ], 200);

        } catch (QueryException $e) {
            return $this->sendError('SQL Error', $this->getQueryError($e));
        }
        catch (Exception $e) {
            return $this->sendError('Error', $e->getMessage());
        }
    }
    public function storeManpower(Request $request)
    {
        $this->authorize('add-35');

        $user = auth('sanctum')->user();

        try {
                DB::beginTransaction();
                // Save multiple and file 
                $model = new Projectmanpower();

                $model->projectid = $request->manpower_projectid;
                $model->manpowerid = $request->manpowerid;

                $model->fill(['opadd' => $user->login, 'pcedit' => $request->ip()]);

                $model->save();

                DB::commit();
                
                return response([
                    'success' => true,
                    'data'    => 'Success',
                    'message' => 'detail laporan added successfully.',
                ], 200);

        } catch (QueryException $e) {
            return $this->sendError('SQL Error', $this->getQueryError($e));
        }
        catch (Exception $e) {
            return $this->sendError('Error', $e->getMessage());
        }
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
    public function destroy(Request $request, $id)
    {
        $this->authorize('delete-35');

        $user = auth('sanctum')->user();

        $project = Project::find($id);

        $project->dlt = '1';

        $project->fill(['opedit' => $user->login, 'pcedit' => $request->ip()]);

        $project->save();

        return response([
            'success' => true,
            'data'    => 'Success',
            'message' => 'project deleted successfully.',
        ], 200);
    }
    public function destroymanpoweproject(Request $request, $id)
    {
        $this->authorize('delete-35');

        $user = auth('sanctum')->user();

        $manpower = Projectmanpower::find($id);

        $manpower->dlt = '1';

        $manpower->fill(['opedit' => $user->login, 'pcedit' => $request->ip()]);

        $manpower->save();

        return response([
            'success' => true,
            'data'    => 'Success',
            'message' => 'manpower deleted successfully.',
        ], 200);
    }
    public function destroyprogresproject(Request $request, $id)
    {
        $this->authorize('delete-35');

        $user = auth('sanctum')->user();

        $progress = Projectprogress::find($id);

        $progress->dlt = '1';

        $progress->fill(['opedit' => $user->login, 'pcedit' => $request->ip()]);

        $progress->save();

        return response([
            'success' => true,
            'data'    => 'Success',
            'message' => 'progress deleted successfully.',
        ], 200);
    }

    public function loadManpower(Request $request, $id)
    {

        $this->authorize('view-35');

        $user = auth('sanctum')->user();
        Log::channel('mibedil')->info('Halaman '.$this->page);

        if ($request->ajax()) {

            $data = [];
            $count = 0;
            $page = $request->get('start', 0);  
            $perpage = $request->get('length',50);

            try {
                $manpower = DB::table('tbprojectmanpower')
                    ->join('tbmmanpower', function($join)
                    {
                        $join->on('tbmmanpower.manpowerid', '=', 'tbprojectmanpower.manpowerid');
                        $join->on('tbmmanpower.dlt','=',DB::raw("'0'"));
                    })
                    ->select('tbprojectmanpower.*', 'tbmmanpower.nama', 'tbmmanpower.tgljoin')
                    ->where('tbprojectmanpower.projectid', $id)
                    ->where('tbprojectmanpower.dlt', 0)
                    ->orderBy('tbprojectmanpower.manpowerid')
                ;

                $count = $manpower->count();
                $data = $manpower->skip($page)->take($perpage)->get();

                return $this->sendResponse([
                    'data' => $data,
                    'count' => $count,
                ], 'Data retrieved successfully.'); 

            } catch (QueryException $e) {
                return $this->sendError('SQL Error', $this->getQueryError($e));
            }
            catch (Exception $e) {
                return $this->sendError('Error', $e->getMessage());
            }
        }
    }

    public function loadProgress(Request $request, $id)
    {

        $this->authorize('view-35');

        $user = auth('sanctum')->user();
        Log::channel('mibedil')->info('Halaman '.$this->page);

        if ($request->ajax()) {

            $data = [];
            $count = 0;
            $page = $request->get('start', 0);  
            $perpage = $request->get('length',50);

            try {
                $progress = DB::table('tbprojectprogress')
                    ->select('tbprojectprogress.*')
                    ->where('tbprojectprogress.projectid', $id)
                    ->where('tbprojectprogress.dlt', 0)
                    ->orderBy('tbprojectprogress.projectprogressid')
                ;

                $count = $progress->count();
                $data = $progress->skip($page)->take($perpage)->get();

                return $this->sendResponse([
                    'data' => $data,
                    'count' => $count,
                ], 'Data retrieved successfully.'); 

            } catch (QueryException $e) {
                return $this->sendError('SQL Error', $this->getQueryError($e));
            }
            catch (Exception $e) {
                return $this->sendError('Error', $e->getMessage());
            }
        }
    }

    public function selesai(Request $request, $id)
    {
        $this->authorize('edit-35');

        $user = auth('sanctum')->user();

        $project = Project::find($id);

        $project->status = '1';

        $project->fill(['opedit' => $user->login, 'pcedit' => $request->ip()]);

        $project->save();

        return response([
            'success' => true,
            'data'    => 'Success',
            'message' => 'project telah selesai.',
        ], 200);
    }
}
