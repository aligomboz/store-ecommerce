<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Enumeration\CategoryType;
use App\Http\Requests\MainCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaiCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('_parent')->latest()->paginate(PAGINATION_COUNT);
        return view('dashboard.categories.index' , [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        return view('dashboard.categories.create' , [
            'categories' =>$category->parent()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MainCategoryRequest $request , Category $category)
    {
       // return $request;
        try {
            DB::beginTransaction();

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

                //if user choose main category then we must remove paret id from the request

            if($request ->type == CategoryType::MainCategory) //main category
            {
                $request->request->add(['parent_id' => null]);
            }

            //if he choose child category we mus t add parent id

            $category = Category::create($request->except('_token'));

            //save translations
            $category->name = $request->name;
            $category->save();

            DB::commit();
            return redirect()->route('categories.index')->with(['success' => 'تم ألاضافة بنجاح']);

        }

         catch(\Exception $ex){
             DB::rollBack();
           return redirect()->route('categories.index')->with('erorrs' , 'حدث خطا ما يرجى المحاولة لاحقا');
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
    public function edit(Category $category)
    {
        if(!$category){
            return redirect()->route('categories.index')->with('error' , 'هذا القسم غير موجود');
        }
        $categories = Category::parent()-> get();

        return view('dashboard.categories.edit' , [
            'category' =>$category,
            'categories' =>$categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MainCategoryRequest $request, Category $category)
    {
        try{
            $this->getFind($category);
            if(!$request->has('is_active')){
                $request->request->add(['is_active' => 0]) ;
            }else{
                $request->request->add(['is_active' => 1]);
            }
            $category->update($request->all());

            $category->name = $request->name;
            $category->save();
            return redirect()->route('categories.index')->with('success' , 'تم التحديث بنجاح');
    
        }
        catch(\Exception $ex){
            return redirect()->route('categories.index')->with('erorrs' , 'حدث خطا ما يرجى المحاولة لاحقا');

        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try{
            $this->getFind($category);
            $category->delete();
            return redirect()->route('categories.index')->with('success' , 'تم الحذف بنجاح');

        }catch(\Exception $ex){
                return redirect()->route('categories.index')->with('erorrs' , 'حدث خطا ما يرجى المحاولة لاحقا');
            }
       
    }

    protected function getFind(Category $category){
         $category->orderBy('id' , 'DESC')->find($category->id);
            if(!$category){
                return redirect()->route('categories.index')->with('erorrs' , 'هذا القسم غير موجود');
            }
    }
}
