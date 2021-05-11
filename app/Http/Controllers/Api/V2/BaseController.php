<?php

namespace App\Http\Controllers\Api\V2;

use App\BaseArticle;
use App\Contract;
use App\Http\Resources\GetBaseResource;
use App\Http\Resources\BranchesResource;
use App\Http\Resources\CommentsResource;
use App\Http\Resources\KitsResource;
use App\Http\Resources\UsersResource;
use App\Http\Resources\BaseAllResource;
use App\Http\Resources\VmContractResource;
use App\Http\Resources\AgregatorAllResource;
use App\Http\Resources\TestResource;
use App\Http\Resources\AddChildrenGroupResource;
use App\Http\Resources\GetUserInGroupResource;
use App\Http\Resources\HallResource;
use App\Http\Resources\Schedule_hallResource;
use App\Http\Controllers\Controller;
use App\KassaOperation;
use App\KassaOperationType;
use App\Settings;
use App\Statuses;
use Illuminate\Http\Request;
use App\Filters\UsersFilter;
use App\Base;
use App\Programm;
use App\Branch;
use App\User;
use App\Role;
use App\Journal;
use App\Loger;
use App\Comments;
use App\Hall;
use App\Group;
use App\Schedule_hall;
use App\Contract_pay;
use Carbon\Carbon;
use Gate;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;


class BaseController extends Controller
{

    public $freezing = 'fe fe-sun text-primary';
    public $workout = 'fe fe-check text-success';
    public $notVisit = 'fe fe-x text-danger';
    public $newWorkout = 'fe fe-alert-circle text-warning';

// СОЗДАТЬ КОНТРОЛЛЕР ЖУРНАЛА И ПЕРЕНЕСТИ ТУДА ВСЕ МЕТОДЫ =================================================

    public function deleteSchedule(Request $request)
    {

        Schedule_hall::find($request->id)->delete();
    }

    public function getGroupInHall(Request $request)
    {
        return Group::where('hall_id', $request->hall_id)->get();

    }

    public function saveSchedule(Request $request)
    {
        Schedule_hall::create($request->all());

        $schedule_hall = Schedule_hall::where('hall_id', $request->hall_id)->where('day', $request->day)->get();

        return Schedule_hallResource::collection($schedule_hall);
    }

    public function Schedule(Request $request)
    {
        $schedule_hall = Schedule_hall::where('hall_id', $request->hall_id)->where('day', $request->day)->get();

        return Schedule_hallResource::collection($schedule_hall);
    }

    public function showHall(Request $request)
    {
        $hall = Hall::find($request->hall_id)->load(['schedule_hall' => function ($query) use ($request){
            $query->with('group')->where('day', $request->day);
            $query->orderBy('time');
        }]);

        return new HallResource($hall);
    }


    public function showHProgramms(Request $request)
    {
        return Programm::where('branch_id', $request->branch_id)->get();

    }

    public function getPkProgramm(Request $request)
    {
        $group = Group::find($request->id);
        return $group->programm->type;

    }


    public function addClientInGroup(Request $request)
    {
        $base = Base::where('branch', $request->id)->get();

        return AddChildrenGroupResource::collection($base);
    }

    public function saveClientInGroup(Request $request)
    {
        $base = Base::find($request->id);
        $base->group_id = $request->group_id;
        $base->save();


        if ($base->group_id)
        {
            $group_from = Group::find($base->group_id);

            $group_in = Group::find($request->group_id);

            loger(2,
                $base->id,
                null,
                null,
                'Переведен из ' . $group_from->name . ' в ' . $group_in->name
            );
        }
    }

    public function updateTest(Request $request){

        $journal = Journal::create($request->all());
    }


    public function getUserInGroup(Request $request){

        $base = Base::with(['journal' => function($query) use ($request){
            $query->where('year', $request->year);
            $query->where('month', $request->month);
        }])->where('group_id', $request->group_id)->get();


        $collection = collect();

        foreach ($base as $a) {
            $contracts = $a->contracts->where('end_actually', '>', Carbon::today()->toDateString())->flatten();
            $group = $a->group->programm;

            if ($group){
                $group = $group->type;
            }

            // Если есть активный контракт или группа равна ли ПК или ВМ
            if ($contracts->count() == 1 || $group == 2 || $group == 3){
                $collection->push($a);
            }
        }

        if ($collection->count() > 0) {
            return GetUserInGroupResource::collection($collection);
        }else{
            return 'error';
        }
    }

    // Метод возвращает группы доступные для редактирование у зала
    public function getEditingGroup(Request $request){

        return Group::where('hall_id', $request->hall_id)->get();

    }



    public function workout(Request $request){

        $checkDay = $this->checkDay($request->base_id, $request->year, $request->month, $request->day);

        if ($checkDay->isEmpty()) return ['response' => "На этот день нет распсисания для текущей группы"];

        // Если на эту дату тренировка уже проставлена то нужно решить что дальше делать
        $base = Base::find($request->base_id);


        // Выбираем только активные контракты и с типом основной контракт
        $contracts = $base->contracts->where('end_actually', '>', Carbon::today()->toDateString())->flatten()->where('contract_type', 'main');

        // Выбираем только активные контракты и с типом ВМ
        $contractsVM = $base->contracts->where('end_actually', '>', Carbon::today()->toDateString())->flatten()->where('contract_type', 'vm');

//        if ($contracts->count() == 1 || $contractsVM->count() == 1) {

            $journal = Journal::where('base_id', $request->base_id)->where('day', $request->day)->where('month', $request->month)->get();

            // Если уже стоит тренировка то выдаем ошибку
            if ($journal[0]->type == 1) {
                return [
                    'response'  => "Уже стоит тренировка, выберите другой статус",
                ];
            }

            // Если стоит пропусщеная тренировка то выдаем ошибку
            if($journal[0]->type == 3){
                return [
                    'response'  => "Клиент пропустил занятие",
                ];
            }


            // Если на текущей ячейки не стоит пропущенная тренировка и количество тренировок в контракте больше нуля то списываем одну тренировку
            if ($journal[0]->type !== 3 && $contracts->count()) {
                if($contracts[0]->classes_total > 0){
                    $base->contracts()->where('contract_type', 'main')->decrement('classes_total');
                }
            }


            // Проверяем состоит ли клиент в группе с программой ПК
            $group = Base::find($request->base_id)->group->programm;

            if($group->type == 2 && $journal[0]->type == 4){
                // В агрегаторе лидов изменяем статус и этап, дату звонка ставим за день до назначеной тренировки
                Statuses::where('base_id', $request->base_id)->update(
                    [
                        'status_id' => 4,
                        'steps_id' => 2,
                    ]
                );

                // Добавляем в лог запись
                loger(4, $request->base_id, null,null,'Присвоен статус Явка ПК');

            }

            // Если группа ВМ, стоит тренировка и статус Покупка ВМ
            if($group->type == 3 && $journal[0]->type == 4 && $base->statuses->status_id == 5){
                // В агрегаторе лидов изменяем статус и этап на Явка ВМ 1

                // Чтоб узнать дату следующего звонка нужно выяснить есть ли после этой еще трнеировки
                $jour = Journal::where('base_id', $request->base_id)
                    ->where('type', 4)
                    ->orderBy('id', 'desc')
                    ->first();

                Statuses::where('base_id', $request->base_id)->update(
                    [
                        'status_id' => 6,
                        'steps_id' => 3,
                        'call_date' => $jour ? Carbon::createFromDate($jour->year, $jour->month, $jour->day)->addDays(-1) : $jour->call_date,
                    ]
                );

                // Добавляем в лог запись
                loger(4, $request->base_id, null,null,'Присвоен статус Явка ВМ 1');

            }

            // Если группа ВМ, стоит тренировка и статус Явка ВМ 1
            if($group->type == 3 && $journal[0]->type == 4 && $base->statuses->status_id == 6){
                // В агрегаторе лидов изменяем статус и этап на Явка ВМ 2

                // Чтоб узнать дату следующего звонка нужно выяснить есть ли после этой еще трнеировки
                $jour = Journal::where('base_id', $request->base_id)
                    ->where('type', 4)
                    ->orderBy('id', 'desc')
                    ->first();

                Statuses::where('base_id', $request->base_id)->update(
                    [
                        'status_id' => 7,
                        'steps_id' => 3,
                        'call_date' => $jour ? Carbon::createFromDate($jour->year, $jour->month, $jour->day)->addDays(-1) : $jour->call_date,
                    ]
                );

                // Добавляем в лог запись
                loger(4, $request->base_id, null,null,'Присвоен статус Явка ВМ 2');

            }

            // Если группа ВМ, стоит тренировка и статус Явка ВМ 2
            if($group->type == 3 && $journal[0]->type == 4 && $base->statuses->status_id == 7){
                // В агрегаторе лидов изменяем статус и этап на Явка ВМ 3

                // Чтоб узнать дату следующего звонка нужно выяснить есть ли после этой еще трнеировки
                $jour = Journal::where('base_id', $request->base_id)
                    ->where('type', 4)
                    ->orderBy('id', 'desc')
                    ->first();

                Statuses::where('base_id', $request->base_id)->update(
                    [
                        'status_id' => 8,
                        'steps_id' => 3,
                        'call_date' => $jour ? Carbon::createFromDate($jour->year, $jour->month, $jour->day)->addDays(-1) : $jour->call_date,
                    ]
                );

                // Добавляем в лог запись
                loger(4, $request->base_id, null,null,'Присвоен статус Явка ВМ 3');

            }



            // Если есть запись то обнавляем иконку и данные, если нет то создаем новую
            $journal = Journal::updateOrCreate(
                ['base_id' => $request->base_id, 'year' => $request->year, 'month' => $request->month, 'day' => $request->day],
                [
                    'base_id' => $request->base_id,
                    'day' => $request->day,
                    'month' => $request->month,
                    'year' => $request->year,
                    'icon' => $this->workout,
                    'type' => 1
                ]
            );


            return [
                'response'  => "success",
            ];
            // Если и так уже стоит значение, то ничего не делать
//        }

//        if ($contracts->count() == 0) {
//            return [
//                'response'  => "У данного клиента закончился контракт - посещение занятий приостановлено",
//            ];
//        }

//        if ($contracts->count() > 1) {
//            return [
//                'response'  => "У клиента больше одного активного контракта, обратитесь к администратору",
//            ];
//        }

    }

    public function notVisit(Request $request){

        $checkDay = $this->checkDay($request->base_id, $request->year, $request->month, $request->day);

        if ($checkDay->isEmpty()) return ['response' => "На этот день нет распсисания для текущей группы"];


        $base = Base::find($request->base_id);

        // Выбираем только активные контракты и с типом основной контракт
        $contracts = $base->contracts->where('end_actually', '>', Carbon::today()->toDateString())->flatten()->where('contract_type', 'main');

//        if ($contracts->count() == 1) {

            // Проверяем стоит ли сейчас посещенная тренировка на текущей ячейки
            $journal = $this->checkJournal($request->base_id, $request->year, $request->month, $request->day, 1);

            // Если записи нет то списываем одну тренировку
            if ($journal->isEmpty()) {
                $base->contracts()->where('contract_type', 'main')->decrement('classes_total');
            }

            // Если есть запись то обнавляем иконку и данные, если нет то создаем новую
            $journal = Journal::updateOrCreate(
                ['base_id' => $request->base_id, 'year' => $request->year, 'month' => $request->month, 'day' => $request->day],
                [
                    'base_id'   => $request->base_id,
                    'day'       => $request->day,
                    'month'     => $request->month,
                    'year'      => $request->year,
                    'icon'      => $this->notVisit,
                    'type'      => 3,
                ]
            );

            // И добавляем комментарий
            loger(5, $request->base_id,null,null, $request->comment);

            return [
                'response'  => "success",
            ];
//        }

//        if ($contracts->count() == 0) {
//            return [
//                'response'  => "У данного клиента закончился контракт - посещение занятий приостановлено",
//            ];
//        }

//        if ($contracts->count() > 1) {
//            return [
//                'response'  => "У клиента больше одного активного контракта, обратитесь к администратору",
//            ];
//        }

    }

    public function newWorkout(Request $request){

        $checkDay = $this->checkDay($request->base_id, $request->year, $request->month, $request->day);

        if ($checkDay->isEmpty()) return ['response' => "На этот день нет распсисания для текущей группы"];

        // Проверяем стоит ли на текущей ячейки какие нибудь данные
        $journal = Journal::where('base_id', $request->base_id)->where('day', $request->day)->get();

        // Проверяем в какой группе состоит клиент (ВМ или ПК)
        $group = Base::find($request->base_id)->group->programm;



        // Если записи нет то спросто назначаем тренировку
        if ($journal->isEmpty()) {
            $journal = Journal::create(
                [
                    'base_id' => $request->base_id,
                    'day' => $request->day,
                    'month' => $request->month,
                    'year' => $request->year,
                    'icon' => $this->newWorkout,
                    'type' => 4,
                ]
            );

            if($group->type == 2){
                // В агрегаторе лидов изменяем статус и этап, дату звонка ставим за день до назначеной тренировки
                Statuses::where('base_id', $request->base_id)->update(
                    [
                        'status_id' => 3,
                        'steps_id' => 2,
                        'call_date' => Carbon::createFromDate($request->year, $request->month, $request->day)->addDays(-1)
                    ]
                );
                // Добавляем в лог запись
                loger(4, $request->base_id, null,null,'Присвоен статус Запись ПК');

            }

            if($group->type == 3){
                // В агрегаторе лидов изменяем статус и этап, дату звонка ставим за день до назначеной тренировки
                Statuses::where('base_id', $request->base_id)->update(
                    [
                        'status_id' => 5,
                        'steps_id' => 3,
                        'call_date' => Carbon::createFromDate($request->year, $request->month, $request->day)->addDays(-1)
                    ]
                );
                // Добавляем в лог запись
                loger(4, $request->base_id, null,null,'Присвоен статус Покупка ВМ');

            }

            return "success";
        }

    }


    public function freezing(Request $request){


        $checkDay = $this->checkDay($request->base_id, $request->year, $request->month, $request->day);

        if ($checkDay->isEmpty()) return ['response' => "На этот день нет распсисания для текущей группы"];



        // Если на эту дату тренировка уже проставлена то нужно решить что дальше делать
        $base = Base::find($request->base_id);


        // Выбираем только активные контракты и с типом основной контракт
        $contracts = $base->contracts->where('end_actually', '>', Carbon::today()->toDateString())->flatten()->where('contract_type', 'main');

        // Если найден один активный контракт
//        if ($contracts->count() == 1) {

            // Проверяем стоит ли сейчас посещенная тренировка на текущей ячейки
            $journal = $this->checkJournal($request->base_id, $request->year, $request->month, $request->day, 1);

            // Если есть запись посещенной тренировки, то останавливаем
            if ($journal->isNotEmpty()) {
                return [
                    'response'  => "Клиент посетил тренировку, поставить заморозку не возможно",
                ];
            }

            // Проверяем стоит ли сейчас новая тренировка на текущей ячейки
            $journal = $this->checkJournal($request->base_id, $request->year, $request->month, $request->day, 4);


            // Если есть запись новой тренировки, то останавливаем
            if ($journal->isNotEmpty()) {
                return [
                    'response'  => "Нельзя поставить заморозку этому клиенту",
                ];
            }

            $journal = $this->checkJournal($request->base_id, $request->year, $request->month, $request->day, 2);

            // Если уже стоит этот статус то выдаем оштбку
            if ($journal->isNotEmpty()) {
                return [
                    'response'  => "Уже стоит заморозка, выберите другой статус",
                ];
            }

            // Проверяем остались ли заморозки, если да то уменьшаем на еденицу, если нет возвращаем сообщение с ошибкой
            if ($contracts->first()->freezing_total == 0) {
                return [
                    'response'  => "У клиента закончились заморозки",
                ];
            }else{
                $contracts[0]->decrement('freezing_total');

                // Смещаем дату фактического окончания контракта
                // 1 заморозка прибавляем к дате окончания контракта 3 дня
                // 2 заморозки прибавляем к дате окончания контракта 7 дней
                // 3 заморозки прибавляем к дате окончания контракта 10 дней

                switch ($contracts->first()->freezing_kolvo) {
                    case 1:
                        $contracts->first()->end_actually = Carbon::createFromDate($contracts[0]->end_actually)->addDays(3);
                        $contracts->first()->save();
                        break;
                    case 2:
                        $contracts->first()->end_actually = Carbon::createFromDate($contracts[0]->end_actually)->addDays(7);
                        $contracts->first()->save();
                        break;
                    case 3:
                        $contracts->first()->end_actually = Carbon::createFromDate($contracts[0]->end_actually)->addDays(10);
                        $contracts->first()->save();
                        break;
                }

                // Записываем в журнал как заморозку
                Journal::updateOrCreate(
                    ['base_id' => $request->base_id, 'year' => $request->year, 'month' => $request->month, 'day' => $request->day],
                    [
                        'base_id'   => $request->base_id,
                        'day'       => $request->day,
                        'month'     => $request->month,
                        'year'      => $request->year,
                        'icon'      => $this->freezing,
                        'type'      => 2,
                    ]
                );
            }

            return [
                'response'  => "success",
            ];

//        }

//        if ($contracts->count() == 0) {
//            return [
//                'response'  => "У данного клиента закончился контракт - посещение занятий приостановлено",
//            ];
//        }

//        if ($contracts->count() > 1) {
//            return [
//                'response'  => "У клиента больше одного активного контракта, обратитесь к администратору",
//            ];
//        }
    }

    public function addNewComent(Request $request){

        // Добавляем комментарий
        loger(5, $request->base_id, null,null, $request->comment);

    }


    public function uploadDocument(Request $request){

        $path = $request['file']->store('public/files');
        $path = str_replace("public","storage", $path);

        $document = Document::create([
            'base_id'   => $request->base_id,
            'name'   => $request->name,
            '$path'   => $path,
        ]);
    }


    public function showHistory(Request $request){

        $comments = Loger::where('base_id', $request->base_id)->get();

        return CommentsResource::collection($comments);
    }
















    public function test(Request $request){


        $user = User::find(Auth::user()->id);

        $collection = collect();

        foreach ($user->branches as $branch) {
            foreach ($branch->bases as $base) {
                $collection->push($base);
            }
        }

        return BaseAllResource::collection($collection->all());
    }

    public function index(){

        $user = User::find(Auth::user()->id);

        $collection = collect();

        foreach ($user->branches as $branch) {
            foreach ($branch->bases as $base) {
                $collection->push($base);
            }
        }

        return (BaseAllResource::collection($collection->all()))
            ->additional([
                    'can' => [
                        'base_create'     => Gate::allows('base_create'),
                    ]
                ]
            );

    }

    public function addNewUser(Request $request){

        // Добавляем нового клиента
        $base = Base::create($request->all());

        // Добавляем в агрегатор лидов
        Statuses::create([
            'base_id' => $base->id,
            'call_date' => Carbon::now(),
        ]);


        // Добавляем в лог информацию что клиент создан
        loger(2, $base->id, null, null, 'Добавил клиента в базу');

        // Добавляем в лог коментарий если он имеется
        if ($request->comment)
        {
            loger(2, $base->id, null, null, $request->comment);
        }


        // Добавляем в лог информацию что клиенту присвоен статус
        loger(4, $base->id, null, null, 'Присвоен статус Новый');

        return $base->id;
//        return [
//            'id' => $base->id
//        ];
    }

    public function getDiffInHoursAttribute($starttime)
    {
        $open = Carbon::parse($starttime);

        return Carbon::now()->diffInHours($open);
    }


    public function getInfo(Request $request){

//        dd(config('session.lifetime'));

        $base = Base::find($request->id);

        $base->increment('total_open');


        if ($this->getDiffInHoursAttribute($base->date_open) >= 1){
            $base->date_open = Carbon::now();
            $base->block = false;
            $base->user_block_id = false;
        }

        // Если в карточке кто нибудь работает - возвращаем соответствующий response, иначе блокируем
        if ($request->set_block && $base->block){
            return [
                'response'          => "block",
                'user_block_name'   => $base->user_block_name->surname .' '. $base->user_block_name->name,
                'curent_user'       => $base->user_block_id === Auth::user()->id ? true : false,
            ];
        }else if($request->set_block && !$base->block){
            $base->block = 1;
            $base->user_block_id = Auth::user()->id;
            $base->save();

            $base->date_open = Carbon::now();
        }

        if ($base->total_open == 2){
            $statuses = Statuses::where('base_id', $request->id)
                ->where('status_id', 1)
                ->update(['status_id' => 2]);

            // Добавляем в лог запись если статус поменялся на В работе
            if ($statuses){
                loger(4, $base->id, null,null,'Присвоен статус В работе');
            }
        }

//        return new GetBaseResource($base);

        return (new GetBaseResource($base))
            ->additional([
                    'can' => [
                        'base_history'                  => Gate::allows('base_history'),
                        'base_contract'                 => Gate::allows('base_contract'),
                        'base_skils'                    => Gate::allows('base_skils'),
                        'base_call'                     => Gate::allows('base_call'),
                        'base_vm'                       => Gate::allows('base_vm'),
                        'base_main'                     => Gate::allows('base_main'),
                        'base_edit'                     => Gate::allows('base_edit'),
                        'base_source'                   => Gate::allows('base_source'),
                        'base_branch'                   => Gate::allows('base_branch'),
                        'base_name_and_birthday'        => Gate::allows('base_name_and_birthday'),
                        'base_pay'                      => Gate::allows('base_pay'),
                    ]
                ]
            );
    }

    public function getVmContract(Request $request){

        $base = Base::find($request->id);

        return new VmContractResource($base->load(['base_branch']));
    }

    public function getBranches(){

        $user = User::find(Auth::user()->id);

//        return new BranchesResource($user);
        return BranchesResource::collection($user->branches);
    }

    public function getManagers(){

        $roles = Role::where('title', 'like' , "%менеджер%")->get();

        $collection = collect();

        foreach ($roles as $role) {
            foreach ($role->rolesUsers as $value) {
                $collection->push($value);
            }
        }

        return UsersResource::collection($collection);
    }

    public function getInstructors(){

        $roles = Role::where('title', 'like' , "%тренер%")->get();

        $collection = collect();

        foreach ($roles as $role) {
            foreach ($role->rolesUsers as $value) {
                $collection->push($value);
            }
        }

        return UsersResource::collection($collection);
    }

    public function getProgramms(Request $request){

        $base = Base::find($request->id)->base_branch->programms;

        return new UsersResource($base);
    }

    public function getUsers(){

        return UsersResource::collection(User::select('id', 'name', 'surname')->get());
    }

    public function upload(Request $request){

        $path = $request['file']->store('public/avatars');
        $path = str_replace("public","storage", $path);

        $base = Base::find($request['id']);
        $base->avatar = $path;
        $base->save();

        loger(2, $base->id, null,null,'Изменил аватар '.$base->name);


        return $path;
    }

    public function baseFilter(Request $request)
    {

        $user = User::find(Auth::user()->id);

        $collection = collect();

        foreach ($user->branches as $branch) {
            foreach ($branch->bases as $base) {
                $collection->push($base);
            }
        }

        // Исправить костыль с фильтром

        $users = Base::filter($request->all())->get();

        $collectionA = $users->keyBy('id');

        $collectionB = $collection->keyBy('id');

        $collection = $collectionA->intersectByKeys($collectionB);

        return BaseAllResource::collection($collection);
    }

//    Обновляем ЛПР родителя
    public function updateLpr(Request $request){

        Base::where('id', $request->id)->update(array('mother_lpr' => '0', 'father_lpr' => '0', 'other_relative_lpr' => '0'));

        Base::where('id', $request->id)->update([$request->lpr => 1]);

        return [
            'success' => 'ok'
        ];
    }


    //-------------------- Добавляем данные о ребенке ---------------------------------------------

    public function addClientFromPromoter(Request $request){

        $base = Base::create($request->all());

        loger(2, $base->id, null, null, 'Добавил ребенка в базу');

        return 'ребенок добавлен и лог записан' . $base->name ;
    }

    //------------------- Снимаем блокировку с карточки -----------------------------------------

    public function removeBlock(Request $request){

        Base::where('id', $request->id)->update(['block' => 0, 'user_block_id' => 0]);

    }

    public function getAgregatorLids(){

        $testing_date = Settings::find(1);

        if ($testing_date->value){
            $dates = new Carbon($testing_date->value);
        }else{
            $dates = Carbon::now();
        }

//        $dates = Carbon::now();

//        $date = Carbon::today()->addDays(-3)->toDateString();


        $user = User::find(Auth::user()->id);

        $collection = collect();
        $collection_itog = collect();

        foreach ($user->branches as $branch) {
            foreach ($branch->bases as $base) {
                $collection->push($base);
            }
        }


        //--------------------------------------------------------------------------------
//        // Звонки клиентам дата платежа у которых наступает за 1 день.
        $date = $dates->addDays(+ 1)->toDateString();
//
//        // Проверяем есть ли клиенты оплаты котороые наступают за день до платежа
        foreach ($collection as $value){
            if ($value->contracts->where('contract_type', 'main')->first()){
                $contract = $value->contracts->where('contract_type', 'main')->first();
                foreach ($contract->contract_pays as $pay){
                       if ($pay->date == $date){
                           $collection_itog->push($value);
                       }
                }
            }
        }
        //--------------------------------------------------------------------------------


        //--------------------------------------------------------------------------------

//        // Напоминание прихода на ПК - Проверяем есть ли клиенты тренировка ПК которая наступает за один день от текущей даты
        foreach ($collection as $value){
            if ($value->group){
                if ($value->group->programm->type == 2){
                    $journal = Journal::where('base_id', $value->id)
                        ->where('year', $dates->year)
                        ->where('month', $dates->month)
                        ->where('day', $dates->day + 1)
                        ->where('type', 4)
                        ->get()
                        ->first();
                    if ($journal){
                        $collection_itog->push($value);
                    }
                }
            }
        }

        //--------------------------------------------------------------------------------


        //--------------------------------------------------------------------------------

        // Напоминание прихода на ВМ - Проверяем есть ли клиенты тренировка ВМ которая наступает за один день от текущей даты
        foreach ($collection as $value){
            if ($value->group){
                if ($value->group->programm->type == 3){
                    $journal = Journal::where('base_id', $value->id)
                        ->where('year', $dates->year)
                        ->where('month', $dates->month)
                        ->where('day', $dates->day + 1)
                        ->where('type', 4)
                        ->get()
                        ->first();
                    if ($journal){
                        $collection_itog->push($value);
                    }
                }
            }
        }

        //--------------------------------------------------------------------------------


        //--------------------------------------------------------------------------------

        // Напоминание клиенту о первой тренировке
        $date = $dates->addDays(+ 1)->toDateString();

        // Проверяем есть ли по активным контраактам начала действия договора
        foreach ($collection as $value){
            if ($value->contracts->where('contract_type', 'main')->where('start', $date)->first()){
                    $collection_itog->push($value);
                }
            }

        //--------------------------------------------------------------------------------


        //--------------------------------------------------------------------------------

//        // Клиенты которые были записаны на ПК но не явились
        foreach ($collection as $value){
            if ($value->group){
                if ($value->group->programm->type == 2){
                    $journal = Journal::where('base_id', $value->id)
                        ->where('type', 3)
                        ->get()
                        ->first();
                    if ($journal){
                        $collection_itog->push($value);
                    }
                }
            }
        }

        //--------------------------------------------------------------------------------


        //--------------------------------------------------------------------------------

        // Все со статусом "новые"
        foreach ($collection as $value){
            if ($value->statuses->status_id == 1){
                    $collection_itog->push($value);
                }
            }

        //--------------------------------------------------------------------------------


        //--------------------------------------------------------------------------------

        // Все у кого дата следующего звонка меньше или равно сегодняшней
        foreach ($collection as $value){
            $date_value = Carbon::parse($value->statuses->call_date)->toDateString();
            if ($date_value <= $dates->toDateString()){
                $collection_itog->push($value);
            }
        }

        //--------------------------------------------------------------------------------


        return AgregatorAllResource::collection($collection_itog->unique());

    }


    public function checkDay($base_id, $year, $month, $day){

        $dayOfWeek = Carbon::createFromDate($year, $month, $day)->dayOfWeek;

        $base = Base::find($base_id)->group->schedule_hall;

        $filtered = $base->where('day', $dayOfWeek);

        return $filtered;

    }


    /**
     * Запросы по журналу, есть ли сейчас на ячейки соответствующие значения
     */

    public function checkJournal($base_id, $year, $month, $day, $type){

        $journal = Journal::where(
            [
                'base_id'   => $base_id,
                'year'      => $year,
                'month'     => $month,
                'day'       => $day,
                'type'      => $type,
            ])->get();

        return $journal;
    }

    public function saveNewPay(Request $request){

        $pay = Contract_pay::where('id' , $request->id)->value('pay');

        Contract_pay::where('id', $request->id)->update(
            [
                'pay' => $pay - $request->pay,
            ]
        );

        $balance = Contract::where('id' , $request->contract_id)->value('balance');


        Contract::where('id', $request->contract_id)->update(
            [
                'balance' => $balance - $request->pay,
            ]
        );

    }

    public function saveNewBalance(Request $request){

        $balance = Contract::where('id' , $request->id)->value('balance');


        Contract::where('id', $request->id)->update(
            [
                'balance' => $balance - $request->balance,
            ]
        );

            $base = Base::find($request->base_id);

            // Добавляем вид операции
            $kassa_operation = KassaOperationType::create(
                [
                    'name' => "Платеж " . $base->child_surname . ' ' . mb_substr($base->child_name, 0, 1) . '.' . mb_substr($base->child_middle_name, 0, 1),
                    'branch_id' => $base->branch,
                    'coming' => true,
                    'cash' => true,
                ]
            );

            // Добавляем операцию в кассу
            $kassa_operation = KassaOperation::create(
                [
                    'branch_id' => $base->branch,
                    'user_id' => Auth::id(),
                    'coming' => true,
                    'cash' => true,
                    'operation_type_id' => $kassa_operation->id,
                    'payment' => true,
                    'in_or_out' => true,
                    'sum' => $request->balance,
                ]
            );

            // Добавить внесение в кассу
            $sum = Branch::where('id', $base->branch)->value('sum');

            Branch::where('id', $base->branch)->update(
                [
                    'sum' => $sum + $balance,
                ]
            );


    }

    public function freezingOff(Request $request){

        $contracts = Contract::find($request->id);

        switch ($request->freezing) {
            case 1:
                $contracts->end_actually = Carbon::createFromDate($contracts->end_actually)->addDays(3);
                $contracts->save();
                break;
            case 2:
                $contracts->end_actually = Carbon::createFromDate($contracts->end_actually)->addDays(7);
                $contracts->save();
                break;
            case 3:
                $contracts->end_actually = Carbon::createFromDate($contracts->end_actually)->addDays(10);
                $contracts->save();
                break;
        }
    }

    public function getBaseKit(Request $request){

        $kits = BaseArticle::where('base_id', $request->id)->orderBy('created_at','desc')->get();

        return KitsResource::collection($kits);
    }

}
