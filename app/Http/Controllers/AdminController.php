<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::all();
        return view('admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('imageAdmin', 'public');
        }
        
        Admin::create($data);
        
        return redirect()->route('admins.index')
            ->with('success', 'Administrador creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        return view('admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        return view('admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $data = $request->validated();
        
        if ($request->hasFile('foto')) {
            // Eliminar la foto anterior si existe
            if ($admin->foto) {
                Storage::disk('public')->delete($admin->foto);
            }
            $data['foto'] = $request->file('foto')->store('imageAdmin', 'public');
        }
        
        $admin->update($data);
        
        return redirect()->route('admins.index')
            ->with('success', 'Administrador actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        // Eliminar la foto si existe
        if ($admin->foto) {
            Storage::disk('public')->delete($admin->foto);
        }
        
        $admin->delete();
        
        return redirect()->route('admins.index')
            ->with('success', 'Administrador eliminado exitosamente');
    }
}
