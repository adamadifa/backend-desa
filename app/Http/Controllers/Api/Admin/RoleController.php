<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::when(request()->search, function ($roles) {
            $roles = $roles->where('name', 'like', '%' . request()->search . '%');
        })->with('permissions')->latest()->paginate(5);

        $roles->appends(['search' => request()->search]);

        return new RoleResource(true, 'List Data Role', $roles);
    }

    public function store(Request $request)
    {

        //Validasi Request

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'required'
        ]);

        if (!$validator) {
            return response()->json($validator->errors(), 422);
        }


        //Simpan Data Role
        $role = Role::create([
            'name' => $request->name
        ]);

        //Masukan Permission ke Role

        $role->givePermissionTo($request->permissions);

        //Ketika Berhasil Simpan Role

        if ($role) {
            return new RoleResource(true, 'Data Berhasil Disimpan', $role);
        }

        //Jika Gagal

        return new RoleResource(false, 'Data Gagal Disimpan', null);
    }

    //Menampilkan Data Role

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        if ($role) {
            return new RoleResource(true, 'Detail Data Role', $role);
        }

        return new RoleResource(false, 'Data Tidak Ditemukan', null);
    }

    //Update Data Role

    public function update(Request $request, Role $role)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'required'
        ]);

        if (!$validator) {
            return response()->json($validator->errors(), 422);
        }

        $role->update(['name' => $request->name]);

        //Sinkronisasi Permissions

        $role->syncPermissions($request->permissions);

        if ($role) {
            return new RoleResource(true, 'Data Berhasil Diupdate', $role);
        }

        return  new RoleResource(false, 'Data Gagal Diupdate', null);
    }

    //Hapus Data Role

    public function destroy($id)
    {
        $role = Role::findorFail($id);
        if ($role->delete()) {
            return new RoleResource(true, 'Data Berhasil dihapus', $role);
        }

        return new RoleResource(false, 'Data Gagal Dihapus', null);
    }

    public function all()
    {
        $role = Role::latest()->get();

        return new RoleResource(true, 'List Data Role', $role);
    }
}
