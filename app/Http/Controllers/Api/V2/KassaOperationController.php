<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\KassaOperationResource;
use App\Http\Resources\KassaOperationTypeForKassaSettings;
use App\Http\Resources\KassaOperationTypeResource;
use App\KassaOperation;
use App\Branch;
use App\KassaOperationType;
use App\User;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KassaOperationController extends Controller
{
    public function index()
    {

        $user = User::find(Auth::user()->id);

        foreach ($user->branches as $branch) {
            $collection[] = $branch;
        }

        return $collection;

    }

    public function addOperation(Request $request)
    {

        KassaOperation::create([
            'branch_id' => $request->branch_id,
            'user_id' => Auth::user()->id,
            'operation_type_id' => $request->operation_type_id,
            'payment' => $request->payment,
            'in_or_out' => $request->in_or_out,
            'sum' => $request->sum,
            'coment' => $request->coment,
        ]);

        $sum = Branch::where('id', $request->branch_id)->value('sum');

        Branch::where('id', $request->branch_id)->update(
            [
                'sum' => $request->type ? ($sum + $request->sum) : ($sum - $request->sum)
            ]
        );

    }

    public function getRadioButton(Request $request)
    {

        return KassaOperationType::find($request->id);

    }

    public function getOperationsType(Request $request)
    {

        $type = $request->type ? 'coming' : 'out';

        // Получаем все виды операций
        $kassa_operation_type = KassaOperationType::where('branch_id', $request->id)
            ->where($type, 1)->get();

        // Получаем все продукты и будем считать что это виды операции
        $product_operation_type = Product::where('branch_id', $request->id)->get();

        $collection = $kassa_operation_type->merge($product_operation_type);

        return KassaOperationTypeResource::collection($collection);
    }

    public function showKassaOperation(Request $request)
    {
        $kassa_operation = KassaOperation::where('branch_id', $request->kassa_id)->get();

        return KassaOperationResource::collection($kassa_operation);
    }

    //---------------------------- Operations in Kassa-Settings -------------------//

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return string
     */
    public function destroy(int $id): string

    {
        $post = KassaOperationType::find($id);

        $post->delete();

        return "Операция удалена";
    }

    public function editNameInKassaSettings(Request $request): string
    {

        $field_name = $request['field_name'];
        $KassaGroup = KassaOperationType::find($request['field_id']);
        $KassaGroup->$field_name = $request['field_value'];
        $KassaGroup->save();

        return "Имя Операции обновлено";

    }

    public function editKassaInKassaSettings(Request $request): string
    {

        $field_name = $request['field_name'];
        $KassaGroup = KassaOperationType::find($request['field_id']);
        $KassaGroup->$field_name = $request['field_value'];
        $KassaGroup->save();

        return "Данные обновлены";

    }

    public function editCheckboxInKassaSettings(Request $request): string
    {

        $field_name = $request['field_name'];
        $KassaGroup = KassaOperationType::find($request['field_id']);
        $KassaGroup->$field_name = $request['field_value'];
        $KassaGroup->save();

        return "точки записаны";

    }

    public function getCheckboxDataInKassaSettings(Request $request)
    {

        $otvet = KassaOperationType::find($request->id);

        return new KassaOperationTypeForKassaSettings ($otvet);

    }


}
