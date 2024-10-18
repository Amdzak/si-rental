<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalResource;
use App\Models\Repair_report;
use Illuminate\Http\Request;
use App\Services\FirebaseService;

class ApiRepairReportController extends Controller
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
        $repair = Repair_report::all();

        return new GlobalResource(true, 'List Data Repair reports', $repair);
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
        $request->validate([
            'before_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'after_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $beforePictureUrl = null;
        $afterPictureUrl = null;

        if ($request->hasFile('before_picture')) {
            $beforePictureUrl = $this->firebaseService->uploadFile($request->file('before_picture'), 'repair_reports');
        }

        if ($request->hasFile('after_picture')) {
            $afterPictureUrl = $this->firebaseService->uploadFile($request->file('after_picture'), 'repair_reports');
        }

        $repair = Repair_report::create([
            'id_machine' => $request->id_machine,
            'id_operator' => $request->id_operator,
            'repair_date' => $request->repair_date,
            'repair_description' => $request->repair_description,
            'before_picture' => $beforePictureUrl,
            'after_picture' => $afterPictureUrl, 
            'status' => $request->status,
        ]);

        $repairId = $repair->repair_id;
        $database = $this->firebaseService->getDatabase();
        $database->getReference('repair_reports/' . $repairId)->set([
            'id_machine' => $repair->id_machine,
            'id_operator' => $repair->id_operator,
            'repair_date' => $repair->repair_date,
            'repair_description' => $repair->repair_description,
            'before_picture' => $repair->before_picture,
            'after_picture' => $repair->after_picture,
            'status' => $repair->status,
        ]);

        return new GlobalResource(true, 'Repair report created successfully', $repair);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $repair = Repair_report::find($id);

        return new GlobalResource(true, 'Repair report details', $repair);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $repair = Repair_report::find($id);

        if ($request->hasFile('before_picture')) {
            if ($repair->before_picture) {
                $this->firebaseService->deleteFile($repair->before_picture);
            }
            $repair['before_picture'] = $this->firebaseService->uploadFile($request->file('before_picture'), 'repair_reports');
        }

        if ($request->hasFile('after_picture')) {
            if ($repair->after_picture) {
                $this->firebaseService->deleteFile($repair->after_picture);
            }
            $repair['after_picture'] = $this->firebaseService->uploadFile($request->file('after_picture'), 'repair_reports');
        }

        $repair->update([
            'id_machine' => $request->id_machine,
            'id_operator' => $request->id_operator,
            'repair_date' => $request->repair_date,
            'repair_description' => $request->repair_description,
            'status' => $request->status,
            'before_picture' => $repair['before_picture'],
            'after_picture' => $repair['after_picture'],
        ]);


        $this->firebaseService->getDatabase()
            ->getReference('repair_reports/' . $repair->repair_id)
            ->set([
                'id_machine' => $repair->id_machine,
                'id_operator' => $repair->id_operator,
                'repair_date' => $repair->repair_date,
                'repair_description' => $repair->repair_description,
                'before_picture' => $repair->before_picture,
                'after_picture' => $repair->after_picture,
                'status' => $repair->status,
            ]);

        return new GlobalResource(true, 'Repair report updated successfully', $repair);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $repair = Repair_report::find($id);

        if ($repair->before_picture) {
            $this->firebaseService->deleteFile($repair->before_picture);
        }
        if ($repair->after_picture) {
            $this->firebaseService->deleteFile($repair->after_picture);
        }

        $repair->delete();

        $this->firebaseService->getDatabase()
            ->getReference('repair_reports/' . $repair->repair_id)
            ->remove();

        return new GlobalResource(true, 'Repair report deleted successfully', null);
    }
}
