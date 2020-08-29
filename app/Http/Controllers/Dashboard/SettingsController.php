<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingsRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SettingsController extends Controller
{
    //free inner outer for route editShippingMethods
    public function editShippingMethods($type){
        if($type == 'free'){
             $shipping = Setting::where('key' , 'free_shipping_label')->first();
        }
        elseif($type == 'inner'){
            $shipping = Setting::where('key' , 'local_label')->first();
        }
        elseif($type == 'outer'){
            $shipping = Setting::where('key' , 'outer_label')->first();
        }
        else{
            $shipping = Setting::where('key' , 'free_shipping_label')->first();
        }
        return view('dashboard.setting.shipping.edit' ,[
            'shipping' =>$shipping
        ]);
    }

    public function updateShippingMethods(ShippingsRequest $request , $id){
        try{
            $setting = Setting::find($id);
            DB::beginTransaction();
            $setting->update([
                'plain_value' =>$request->plain_value
            ]);
            $setting->value = $request->value;
            $setting->save();
            DB::commit();
            return redirect()->back()->with('success' , __('تم التحديث بنجاح'));
        } catch (\Exception $ex) {
            return redirect()->back()->with(['erorr' => 'هناك خطا ما يرجي المحاولة فيما بعد']);
            DB::rollback();
        }
        
    }
}
