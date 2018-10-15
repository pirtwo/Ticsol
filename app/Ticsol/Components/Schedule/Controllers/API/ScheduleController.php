<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\Schedule;
use App\Ticsol\Components\Schedule\Requests;
use App\Ticsol\Components\Schedule\Exceptions;
use App\Ticsol\Components\Schedule\Repository;
use App\Ticsol\Components\Schedule\Criterias\ScheduleCriteria;

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

            $this->repository->pushCriteria(new ScheduleCriteria($request));

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
            $schedule = new Schedule();
            $schedule->client_id = 1;
            $schedule->creator_id = 1;            
            $schedule->fill($request->all());
            $schedule->save();
            return Schedule::with(['user', 'job'])->where('id', $schedule->id)->get();
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
            $schedule = Job::find($id);
            if ($schedule == null) {
                throw new Exceptions\JobNotFound();
            }
            return $schedule;
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
            $schedule = $this->repository->findBy('id', $id);
            if ($schedule == null) {
                throw new Exceptions\JobNotFound();
            }
            
            $schedule->update($request->all());
            return $schedule;
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
    public function delete($id)
    {
        try {
            return $this->repository->delete('id', $id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
        }
    }
}
