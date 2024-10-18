<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalResource;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Services\FirebaseService;

class ApiSparepartController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $spareparts = Sparepart::all();

        return new GlobalResource(true, 'List Data Spareparts', $spareparts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sparepart = Sparepart::create($request->all());

        $sparepartId = $sparepart->id_sparepart;
        $database = $this->firebaseService->getDatabase();
        $database->getReference('spareparts/' . $sparepartId)->set($request->all());

        return new GlobalResource(true, 'Sparepart created successfully', $sparepart);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sparepart = Sparepart::find($id);

        return new GlobalResource(true, 'Sparepart details', $sparepart);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sparepart = Sparepart::find($id);

        $sparepart->update($request->all());

        $sparepartId = $sparepart->id_sparepart;
        $database = $this->firebaseService->getDatabase();
        $database->getReference('spareparts/' . $sparepartId)->set($request->all());

        return new GlobalResource(true, 'Sparepart updated successfully', $sparepart);  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sparepart = Sparepart::find($id);
        $sparepart->delete();

        $database = $this->firebaseService->getDatabase();
        $database->getReference('spareparts/' . $sparepart->sparepartId)->remove();

        return new GlobalResource(true, 'Sparepart deleted successfully', null); 
    }
}
