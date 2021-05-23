<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupInKassaResource;
use App\Http\Resources\KassaGroupsResource;
use App\KassaGroup;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KassaGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);

        $collection = collect();

        foreach ($user->branches as $branch) {
            foreach ($branch->kassa_group as $kassa_group) {
                $collection->push($kassa_group);
            }
        }

        return KassaGroupsResource::collection($collection->all());
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
        KassaGroup::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return string
     */
    public function destroy($id)

    {
        $post = KassaGroup::find($id);

        $post->delete();

       return "Группа удалена";
    }



    public function editNameInKassaSettings(Request $request)
    {

        $field_name = $request['field_name'];
        $KassaGroup = KassaGroup::find($request['field_id']);
        $KassaGroup->$field_name = $request['field_value'];
        $KassaGroup->save();

            return "Имя Группы обновлено";


    }

    public function editKassaInKassaSettings(Request $request)
    {


        $field_name = $request['field_name'];
        $KassaGroup = KassaGroup::find($request['field_id']);
        $KassaGroup->$field_name = $request['field_value'];
        $KassaGroup->save();

        return "Данные обновлены";

    }

    public function getGroupInKassa(Request $request){

        $group_in_kassa = KassaGroup::where('branch_id', $request->branch_id)->get();

        return GroupInKassaResource::collection($group_in_kassa);
    }

}
