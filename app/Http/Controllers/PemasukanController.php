<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PemasukanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $param;
    public function index()
    {
        $param['title'] = 'List Pemasukan';
        $param['data'] = Pemasukan::with('user')
                                ->latest()
                                ->get();
        $title = 'Delete Pemasukan!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backoffice.pemasukan.index',$param);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $param['title'] = 'Tambah Pemasukan';
        return view('backoffice.pemasukan.create',$param);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'tanggal' => 'required',
            'nominal' => 'required',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('pemasukan.index');
        }
        DB::beginTransaction();
        try {
            $date = DateTime::createFromFormat('m/d/Y', $request->tanggal)->format('Y-m-d');
            $pemasukan = new Pemasukan;
            $pemasukan->nominal = $this->formatNumber($request->nominal);
            $pemasukan->date = $date;
            $pemasukan->user_id = Auth::user()->id;
            $pemasukan->keterangan = $request->has('keterangan') ? $request->keterangan : null;
            $pemasukan->save();
            DB::commit();
            toast('Berhasil menambahkan data.','success');
            return redirect()->route('pemasukan.index');
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $e->getMessage());
            return redirect()->route('pemasukan.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $param['title'] = 'Detail Pemasukan';
        $param['data'] = Pemasukan::with('user')->find($id);
        return view('backoffice.pemasukan.show',$param);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $param['title'] = 'Edit Pemasukan';
        $param['data'] = Pemasukan::with('user')->find($id);
        return view('backoffice.pemasukan.edit',$param);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = Validator::make($request->all(),[
            'tanggal' => 'required',
            'nominal' => 'required',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('pemasukan.index');
        }
        DB::beginTransaction();
        try {
            $pemasukan = Pemasukan::find($id);
            $pemasukan->nominal = $this->formatNumber($request->nominal);
            $pemasukan->date = DateTime::createFromFormat('m/d/Y', $request->tanggal)->format('Y-m-d');
            $pemasukan->user_id = Auth::user()->id;
            $pemasukan->keterangan = $request->has('keterangan') ? $request->keterangan : null;
            $pemasukan->update();
            DB::commit();
            toast('Berhasil mengganti data.','success');
            return redirect()->route('pemasukan.index');
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $e->getMessage());
            return redirect()->route('pemasukan.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            DB::commit();
            Pemasukan::find($id)->delete();
            toast('Berhasil menghapus data.','error');
            return redirect()->route('pemasukan.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('pemasukan.index');
        }
    }

    public function formatNumber($param)
    {
        return (int)str_replace('.', '', $param);
    }
}
