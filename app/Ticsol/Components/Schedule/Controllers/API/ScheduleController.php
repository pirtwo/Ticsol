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

    function list(Request $req) {           
        $page = 
            $req->query('page') ?? null;
        $perPage = 
            $req->query('perPage') ?? 15;

        if ($page == null) {
            return $this->repository->all();
        } else {
            return $this->repository->paginate($perPage);
        }        
    }

    public function view($id)
    {
        return $this->repository->findBy('id', $id);
    }

    public function create(Requests\CreateSchedule $req)
    {
        $item = new Schedule();
        $item->client_id = $req->user()->client_id;
        $item->creator_id = $req->user()->id;
        $item->fill($req->all());
        $item->save();
    }

    public function update(Requests\UpdateSchedule $req, $id)
    {
        $this->repository->update($req->all(), 'id', $id);
    }

    public function delete(Request $req, $id)
    {
        return $this->repository->delete('id', $id);
    }
}
