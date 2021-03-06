<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Branch;
use App\Http\Resources\GenerlResource;
use App\Http\Resources\GetUsersWarehouse;
use App\Http\Resources\WarehouseLogResource;
use App\Http\Resources\WarehousePositionResource;
use App\Region;
use App\Warehouse;
use App\Loger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $warehouses = Warehouse::with(
            [
                'branch' => function ($query) {
                    $query->whereHas('users', function ($query) {
                        $query->where('id', auth()->id());
                    });
                }
            ])
            ->whereHas('branch', function ($query) {
                $query->whereHas('users', function ($query) {
                    $query->where('id', auth()->id());
                });
            })
            ->get();

        return $warehouses;
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
        $warehouse = Warehouse::create($request->all());

        // Добавляем в лог запись
        loger(6, null, null, $warehouse->id, 'Создан новый склад ' . $warehouse->name);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $warehous = Warehouse::find($id);

        return new GenerlResource($warehous);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $warehouse->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $warehouse = Warehouse::find($id)->articles->sum('quantity');

        if ($warehouse == 0){

            Warehouse::find($id)->delete(); // Тут нужно использовать SofDeletes для псевдоудаления

        }else{
            return [
                'response'  => "error",
            ];
        }
    }


    public function getBranches(Request $request)
    {
        $branches = Branch::where('region_id', $request->id)->get();

        return $branches;
    }

    public function getRegions()
    {
        $regions = Region::all();

        return $regions;
    }

    public function getUsers(Request $request)
    {
        $users = Branch::find($request->id);

        return GetUsersWarehouse::collection($users->users);

    }

    public function warehouseLog(Request $request)
    {
        $warehouse_log = Loger::where('warehouse_id', $request->id)->get();

        return WarehouseLogResource::collection($warehouse_log);

    }


}
