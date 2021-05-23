<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseAllResource;
use App\Http\Resources\KassaOperationTypeAllResource;
use App\User;
use Gate;
use Illuminate\Http\Request;
use App\KassaOperationType;
use Illuminate\Support\Facades\Auth;

class OperationTypeController extends Controller
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
            foreach ($branch->kassa_operation_type as $kassa_operation_type) {
                $collection->push($kassa_operation_type);
            }
        }

//        return KassaOperationTypeAllResource::collection($collection->all());

        return (KassaOperationTypeAllResource::collection($collection->all()))
            ->additional([
                    'can' => [
                        'kassa_add_operation_type'      => Gate::allows('kassa_add_operation_type'),
                    ]
                ]
            );
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
        KassaOperationType::create($request->all());
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
