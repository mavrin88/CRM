<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\HallsResource;
use App\Http\Resources\HallResource;
use App\Hall;
use App\Log;
use App\User;

class HallController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $user = User::find(Auth::user()->id);

        $collection = collect();

        foreach ($user->branches as $branch) {
            foreach ($branch->halls as $hall) {
                $collection->push($hall);
            }
        }

        return HallsResource::collection($collection->all());



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hall = Hall::create($request->all());

        Log::create(array(
             'user_id' => Auth::id(),
             'channel' => '3',
             'level_name' => 'success',
             'message' => 'добавил зал '.$hall->id)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $hall = Hall::find($id)->load(['schedule_hall' => function ($query) {
            $query->with('group.children.journal')->where('day', 1);
        }]);

         return new HallResource($hall);

        //$hall = Hall::find($id);

       // return new HallResource($hall->load(['schedule_hall.programm.children.journal']));

        //$hall = Hall::find($id)->schedule_hall()->where('day',1)->get();


        // $hall = Hall::find($id)->load(['schedule_hall' => function ($query) {
        //     $query->where('day', 1);
        // }]);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hall $hall)
    {
        $field_name = $request['field_name'];
        $hall->$field_name = $request['field_value'];
        $hall->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hall $hall)
    {
        $hall->delete();

        Log::create(array(
             'user_id' => Auth::id(),
             'channel' => '3',
             'level_name' => 'success',
             'message' => 'удалил зал '.$hall->id)
        );

    }

    public function getHalls(Request $request){

        $halls = Hall::where('branch_id', $request['branch_id'])->get();

        return HallResource::collection($halls);
    }
}
