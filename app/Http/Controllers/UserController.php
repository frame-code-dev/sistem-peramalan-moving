<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $param['title'] = 'List User';
        $param['data'] = User::latest()->get();

        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backoffice.users.index',$param);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $param['title'] = 'Tambah Petugas';
        return view('backoffice.users.create', $param);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users,email',
        ],[
            'required' => ':attribute Harus Terisi !',
            'not_in' => 'Hak Akses Harus Dipilih !'
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('user.index');
        }
        try {
            DB::beginTransaction();
            $petugas = new User;
            $petugas->name = $request->name;
            $petugas->email = $request->email;
            $petugas->password = Hash::make($request->password);
            $petugas->save();

            DB::commit();
            toast('Berhasil menambahkan data.','success');
            return redirect()->route('user.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('user.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $param['title'] = "Detail Petugas";
        $param['petugas'] = User::find($id);
        return view('backoffice.users.show', $param);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $param['title'] = "Edit Petugas";
        $param['petugas'] = User::find($id);
        return view('backoffice.users.edit', $param);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
        ],[
            'required' => ':attribute Harus Terisi !',
            'not_in' => 'Hak Akses Harus Dipilih !'
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('user.index');
        }
        try {
            DB::beginTransaction();
            $petugas = User::find($id);
            $petugas->name = $request->name;
            $petugas->email = $request->email;
            if ($request->has('password') || $request->get('password') != NULL || $request->get('password') != '') {
                $petugas->password = Hash::make($request->password);
            }
            $petugas->update();

            // update roles
            DB::commit();
            toast('Berhasil mengganti data.','success');
            return redirect()->route('user.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('user.index');
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
            $delete = User::find($id);

            $delete->delete();
            toast('Berhasil menghapus data.','error');
            return redirect()->route('user.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('user.index');
        }
    }
}
