<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\Schedule;
use App\Ticsol\Components\Schedule\Requests;
use App\Ticsol\Components\Schedule\Exceptions;
use App\Ticsol\Components\Schedule\Repository;

class ScheduleController extends Controller
{

    /**
     * Schedule items repository.
     * @var Repository\ScheduleRepository
     */
    protected $repository;

    public function __construct(Repository\ScheduleRepository $rep)
    {
        $this->repository = $rep;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $page =
            $request->query('page') ?? null;
            $perPage =
            $request->query('perPage') ?? 15;
            $with =
            $request->query('with') != null ? explode(',', $request->query('with')) : [];

            if ($page == null) {
                return $this->repository->all($with);
            } else {
                return $this->repository->paginate($perPage, $with);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * 
     * Type         : schedule, timesheet
     * Status       : tentative, confirmed, submitted
     * Event_type   : leave, unavailable hours, scheduled, RDO
     * 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateSchedule $request)
    {
        try {
            $item = new Schedule();
            $item->client_id = 1;
            $item->creator_id = 1;
            $item->type = 'assigned';
            $item->status = 'unconfirmed';
            $item->fill($request->all());
            $item->save();
            return Schedule::with(['user', 'job'])->where('id', $item->id)->get();
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return $this->repository->findBy('id', $id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateSchedule $request, $id)
    {
        try {
            $this->repository->update($request->all(), 'id', $id);
            return Schedule::with(['user', 'job'])->where('id', $id)->get();
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            return $this->repository->delete('id', $id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
        }
    }
}
