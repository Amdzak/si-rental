<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalResource;
use App\Models\Machine;
use Illuminate\Http\Request;
use App\Services\FirebaseService;

class ApiMachinesController extends Controller
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
        $machine = Machine::all();

        return new GlobalResource(true, 'List Data Machines', $machine);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $machine = Machine::create($request->all());

        $machineId = $machine->id_machine;
        $database = $this->firebaseService->getDatabase();
        $database->getReference('machines/' . $machineId)->set($request->all());

        return new GlobalResource(true, 'Machine created successfully', $machine);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $machine = Machine::find($id);

        return new GlobalResource(true, 'Machine details', $machine);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Machine $machine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $machine = Machine::find($id);

        $machine->update($request->all());

        $machineId = $machine->id_schedule;
        $database = $this->firebaseService->getDatabase();
        $database->getReference('machines/' . $machineId)->set($request->all());

        return new GlobalResource(true, 'Machine updated successfully', $machine);    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $machine = Machine::find($id);
        $machine->delete();

        $database = $this->firebaseService->getDatabase();
        $database->getReference('machines/' . $machine->id_machine)->remove();

        return new GlobalResource(true, 'Machine deleted successfully', null); 
    }
}
