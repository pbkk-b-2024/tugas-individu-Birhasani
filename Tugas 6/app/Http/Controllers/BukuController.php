<?php

namespace App\Http\Controllers;

use App\Facades\BukuServiceFacade as BukuService;
use App\Http\Requests\Buku\NewBukuRequest;
use App\Http\Requests\Buku\UpdateBukuRequest;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $queryString = $request->query();
        $data['buku'] = BukuService::getBukusWithKategoris($request);
        $data['buku']->appends($queryString);
        $data['kategori'] = Kategori::all();
        return view('pertemuan5.buku.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $data['kategori'] = Kategori::all();
        return view('pertemuan5.buku.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewBukuRequest $request)
    {
        $buku = BukuService::createBuku($request);
        return redirect()->route('buku.list')->with('success', 'Buku "' . $buku->judul . '" sukses ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Buku $buku)
    {
        $data['buku'] = $buku;
        return view('pertemuan5.buku.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buku $buku)
    {
        $data['buku'] = $buku;
        $data['buku-kategori'] = $buku->kategoris->pluck('id')->toArray();
        $data['kategori'] = Kategori::all();
        return view('pertemuan5.buku.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBukuRequest $request, Buku $buku)
    {
        $buku = BukuService::updateBuku($request, $buku);
        return redirect()->route('buku.list')->with('success', 'Buku "' . $buku->judul . '" sukses diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buku $buku)
    {
        $judul = $buku->judul;
        $buku->kategoris()->detach();
        $buku->delete();
        return redirect()->route('buku.list')->with('success', 'Buku "' . $judul . '" sukses dihapus".');
    }
    
    public function list(Request $request)
    {
        $queryString = $request->query();
        $data['buku'] = BukuService::getBukusWithKategoris($request);
        $data['buku']->appends($queryString);
        $data['kategori'] = Kategori::all();
        return view('pertemuan5.buku.list', compact('data'));
    }

    public function detail(Buku $buku){
        $data['buku'] = $buku;
        return view('pertemuan5.buku.detail', compact('data'));
    }
}
