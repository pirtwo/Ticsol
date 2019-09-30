<?php

namespace App\Ticsol\Components\Controllers\API;

use App\Http\Controllers\Controller;
use App\Ticsol\Base\Criteria\ClientCriteria;
use App\Ticsol\Base\Criteria\CommonCriteria;
use App\Ticsol\Base\Exceptions\NotFound;
use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\User\Events;
use App\Ticsol\Components\User\Repository;
use App\Ticsol\Components\User\Requests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{

    /**
     * Schedule items repository.
     * @var Repository\UserRepository
     */
    protected $repository;

    public function __construct(Repository\UserRepository $rep)
    {
        $this->repository = $rep;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('list', User::class);

        $page =
        $request->query('page') ?? null;
        $perPage =
        $request->query('perPage') ?? 15;
        $with =
        $request->query('with') != null ? explode(',', $request->query('with')) : [];

        $this->repository->pushCriteria(new CommonCriteria($request));
        $this->repository->pushCriteria(new ClientCriteria($request));

        if ($page == null) {
            return $this->repository->all($with);
        } else {
            return $this->repository->paginate($perPage, $with);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateUser $request)
    {
        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('create', User::class);

        try {

            DB::beginTransaction();

            $user = new User();
            $password = Str::random(8);
            $user->client_id = $request->user()->client_id;

            $user->name = $request->input("firstname") . " " . $request->input("lastname");
            $user->password = \bcrypt($password);
            $user->meta = [];
            $user->fill($request->all());
            $user->save();

            $user->teams()->sync($request->input("teams"));
            $user->roles()->sync($request->input("roles"));

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['code' => 1005, 'error' => true, 'message' => 'Internal Server Error.'], 500);
        }
        
        event(new Events\UserCreated($user, $password));

        return $user;
    }

    /**
     * show the current user info.
     */
    public function me(Request $request)
    {
        $with =
        $request->query('with') != null ? explode(',', $request->query('with')) : [];

        $user = $this->repository
            ->find($request->user()->id, $with);

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $with =
        $request->query('with') != null ? explode(',', $request->query('with')) : [];

        $user = $this->repository->find($id, $with);
        if ($user == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('view', $user);

        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateUser $request, $id)
    {
        $user = $this->repository->findBy('id', $id);
        if ($user == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('update', $user);

        $user->fill($request->all());

        $user = $this->setupAvatar($request, $user);
        $user = $this->setupSettings($request, $user);

        $user->save();

        event(new Events\UserUpdated($user));

        return $user;
    }

    /**
     * enable user account.
     */
    public function enable(Request $request, $id)
    {
        $user = $this->repository->findBy('id', $id);
        if ($user == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('activation', $user);

        $user->isactive = true;
        $user->save();

        return $user;
    }

    /**
     * disable user account.
     */
    public function disable(Request $request, $id)
    {
        $user = $this->repository->findBy('id', $id);
        if ($user == null) {
            throw new NotFound();
        }

        //----------------------------
        //      AUTHORIZE ACTION
        //----------------------------
        $this->authorize('activation', $user);

        $user->isactive = false;
        $user->save();

        return $user;
    }

    public function iCal($userId, $icalId)
    {
        $user = User::where("id", $userId)
            ->where("meta->ical", $icalId)
            ->first();

        if ($user == null) {
            return "";
        }

        $data = $this->createIcalContent($user->schedules()->where("type", "schedule")->get());
        $path = "ical/" . Str::random(8) . ".ics";
        Storage::disk("local")->put($path, $data);

        return response()->download(Storage::disk("local")->path($path))->deleteFileAfterSend();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function createIcalContent($eventList)
    {
        $vCal = "BEGIN:VCALENDAR\nVERSION:2.0";
        for ($i = 0; $i < \sizeof($eventList); $i++) {
            $event = $eventList[$i];
            $summary = $event->job ? "Booked for {$event->job->title}" : \strtoupper($event->event_type);
            $start = Carbon::create($event->start)->format("Ymd His");
            $start = \str_replace(" ", "T", $start);
            $end = Carbon::create($event->end)->format("Ymd His");
            $end = \str_replace(" ", "T", $end);
            $vEvent = "\nBEGIN:VEVENT\nUID:{$event->id}\nSUMMARY:{$summary}\nDTSTART:{$start}\nDTEND:{$end}\nEND:VEVENT";
            $vCal = $vCal . $vEvent;
        }
        $vCal = $vCal . "\nEND:VCALENDAR";
        return $vCal;
    }

    /**
     * Setup the user settings.
     */
    private function setupSettings($request, $user)
    {
        $meta = $user->meta;

        if ($request->has("theme")) {
            $meta["theme"] = $request->input("theme");
        }

        if ($request->has("schedule_view")) {
            $meta["schedule_view"] = $request->input("schedule_view");
        }

        if ($request->has("schedule_range")) {
            $meta["schedule_range"] = $request->input("schedule_range");
        }

        if ($request->has("ical") && $request->input("ical", false)) {
            if (array_key_exists("ical", $meta)) {
                $meta["ical"] = $meta["ical"] == "" ? $meta["ical"] = Str::random(20) : $meta["ical"];
            } else {
                $meta["ical"] = Str::random(20);
            }
        }

        if ($request->has("ical") && !$request->input("ical", false)) {
            $meta["ical"] = "";
        }

        $user->meta = $meta;

        return $user;
    }

    /**
     * Setup the user avatar.
     */
    private function setupAvatar($request, $user)
    {
        $meta = $user->meta;
        $defaultAvatar = "/img/avatar/default.png";

        if (array_key_exists("avatar", $meta)) {
            $oldAvatar = $meta["avatar"];
        } else {
            $meta["avatar"] = $defaultAvatar;
        }

        if ($request->file("avatar")) {
            $meta["avatar"] = "/storage/" . $request->file('avatar')->store('img/avatar', 'public');

            // delete old avatar
            if ($oldAvatar != $defaultAvatar) {
                Storage::disk("public")->delete(\substr($oldAvatar, 9));
            }
        }

        $user->meta = $meta;

        return $user;
    }
}
