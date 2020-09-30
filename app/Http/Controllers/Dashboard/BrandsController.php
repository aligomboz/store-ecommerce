<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandsRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Brand $brand)
    {
        $bra =  $brand->latest()->paginate(PAGINATION_COUNT);
        return view('dashboard.brands.index',[
            'brands' =>$bra,
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandsRequest $request , Brand $brand)
    {
        /*
        $brand->create($request->all());
        return redirect()->route('brands.index');
        */
        try {
            DB::beginTransaction();
            if (!$request->has('is_active')){
                $request->request->add(['is_active' => 0]);
            }
            else{
                $request->request->add(['is_active' => 1]);
            }
                $fileName = "";
                if ($request->has('photo')) {
                    $fileName = uploadImage('brands', $request->photo);
                }

            $bra = $brand->create($request->except('_token' , 'photo'));

            //save translations
            $bra->name = $request->name;
            $bra->photo = $fileName;
            $bra->save();
            DB::commit();
            return redirect()->route('brands.index')->with(['success' => 'تم ألاضافة بنجاح']);
        }

        catch(\Exception $ex){
            DB::rollBack();
          return redirect()->route('brands.index')->with('erorrs' , 'حدث خطا ما يرجى المحاولة لاحقا');
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
        $brand = Brand::findOrFail($id);
        return view('dashboard.brands.edit' , [
            'brand' =>$brand,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandsRequest $request,Brand $brand)
    {
          //  $bra =  Brand::findOrFail($brand->id);
          try{
            DB::beginTransaction();
            if($request->has('photo')){
                $fileName = uploadImage('brands' , $request->photo);
                $brand->where('id' , $brand->id)->update([
                    'photo' => $fileName,
                ]);
            }
            if (!$request->has('is_active')){
                $request->request->add(['is_active' => 0]);
            }
            else{
                $request->request->add(['is_active' => 1]);
            }
            Storage::disk('brands')->delete($brand->photo);
            $brand->update($request->except('_token' , 'id' ,'photo'));

            //save translations
            $brand->name = $request->name;
            $brand->save();
            DB::commit();
            return redirect()->route('brands.index')->with(['success' => 'تم التعديل بنجاح']);
          }
            catch(\Exception $ex){
                DB::rollBack();
              return redirect()->route('brands.index')->with('erorrs' , 'حدث خطا ما يرجى المحاولة لاحقا');
           }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        try{
            $brand->translations()->delete();
            $brand->delete();
            Storage::disk('brands')->delete($brand->photo);

            return redirect()->route('brands.index')->with(['success' => 'تم الحذف بنجاح']);
        }
        catch(\Exception $ex){
            DB::rollBack();
          return redirect()->route('brands.index')->with('erorrs' , 'حدث خطا ما يرجى المحاولة لاحقا');
       }

    }
}
