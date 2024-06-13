<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $param['title'] = 'List Pengeluaran';
        $param['data'] = Pengeluaran::with('user')
                                ->latest()
                                ->get();
        $title = 'Delete Pengeluaran!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backoffice.pengeluaran.index',$param);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $param['title'] = 'Tambah Pengeluaran';
        return view('backoffice.pengeluaran.create',$param);
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
            return redirect()->route('pengeluaran.index');
        }
        DB::beginTransaction();
        try {
            $date = DateTime::createFromFormat('m/d/Y', $request->tanggal)->format('Y-m-d');
            $pengeluaran = new Pengeluaran;
            $pengeluaran->nominal = $this->formatNumber($request->nominal);
            $pengeluaran->date = $date;
            $pengeluaran->user_id = Auth::user()->id;
            $pengeluaran->keterangan = $request->has('keterangan') ? $request->keterangan : null;
            $pengeluaran->save();
            DB::commit();
            toast('Berhasil menambahkan data.','success');
            return redirect()->route('pengeluaran.index');
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $e->getMessage());
            return redirect()->route('pengeluaran.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $param['title'] = 'Detail Pengeluaran';
        $param['data'] = Pengeluaran::with('user')->find($id);
        return view('backoffice.pengeluaran.show',$param);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $param['title'] = 'Edit Pengeluaran';
        $param['data'] = Pengeluaran::with('user')->find($id);
        return view('backoffice.pengeluaran.edit',$param);
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
            return redirect()->route('pengeluaran.index');
        }
        DB::beginTransaction();
        try {
            $pengeluaran = Pengeluaran::find($id);
            $pengeluaran->nominal = $this->formatNumber($request->nominal);
            $pengeluaran->date = DateTime::createFromFormat('m/d/Y', $request->tanggal)->format('Y-m-d');
            $pengeluaran->user_id = Auth::user()->id;
            $pengeluaran->keterangan = $request->has('keterangan') ? $request->keterangan : null;
            $pengeluaran->update();
            DB::commit();
            toast('Berhasil mengganti data.','success');
            return redirect()->route('pengeluaran.index');
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $e->getMessage());
            return redirect()->route('pengeluaran.index');
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
            Pengeluaran::find($id)->delete();
            toast('Berhasil menghapus data.','error');
            return redirect()->route('pengeluaran.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('pengeluaran.index');
        }
    }

    public function formatNumber($param)
    {
        return (int)str_replace('.', '', $param);
    }
}
