<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalResource;
use App\Models\Damage_report;
use Illuminate\Http\Request;
use App\Services\FirebaseService;

class ApiDamageReportController extends Controller
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
        $report = Damage_report::all();

        return new GlobalResource(true, 'List Data Damage Report', $report);
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
        $report = Damage_report::create($request->all());

        $reportId = $report->id_damage;
        $database = $this->firebaseService->getDatabase();
        $database->getReference('damage_reports/' . $reportId)->set($request->all());

        return new GlobalResource(true, 'Damage report created successfully', $report);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $report = Damage_report::find($id);

        return new GlobalResource(true, 'Damage report details', $report);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Damage_report $damage_report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $report = Damage_report::find($id);

        $report->update($request->all());

        $reportId = $report->id_damage;
        $database = $this->firebaseService->getDatabase();
        $database->getReference('damage_reports/' . $reportId)->set($request->all());

        return new GlobalResource(true, 'Damage report updated successfully', $report);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $report = Damage_report::find($id);
        $report->delete();

        $database = $this->firebaseService->getDatabase();
        $database->getReference('damage_reports/' . $report->id_damage)->remove();

        return new GlobalResource(true, 'Damage report deleted successfully', null);
    }
}
