<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalResource;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Services\FirebaseService;

class ApiScheduleController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function index()
    {
        $schedule = Schedule::all();

        return new GlobalResource(true, 'List Data Schedules', $schedule);
    }

    public function store(Request $request)
    {
        $schedule = Schedule::create($request->all());

        $scheduleId = $schedule->id_schedule;
        $database = $this->firebaseService->getDatabase();
        $database->getReference('schedules/' . $scheduleId)->set($request->all());

        return new GlobalResource(true, 'Schedule created successfully', $schedule);

    }

    public function update(Request $request,$id)
    {
        $schedule = Schedule::find($id);

        $schedule->update($request->all());

        $scheduleId = $schedule->id_schedule;
        $database = $this->firebaseService->getDatabase();
        $database->getReference('schedules/' . $scheduleId)->set($request->all());

        return new GlobalResource(true, 'Schedule updated successfully', $schedule);

    }
    public function destroy($id)
    {
        $schedule = Schedule::find($id);
        $schedule->delete();

        $database = $this->firebaseService->getDatabase();
        $database->getReference('schedules/' . $schedule->id_schedule)->remove();

        return new GlobalResource(true, 'Schedule deleted successfully', null);

    }

    public function show($id)
    {
        $schedule = Schedule::find($id);

        return new GlobalResource(true, 'Schedule details', $schedule);
    }
}
