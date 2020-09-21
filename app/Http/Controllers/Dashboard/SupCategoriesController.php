<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupCategoriesController extends Controller
{
    public function index(Category $category)
    {
        
        $cat =  $category->child()->latest()->paginate(PAGINATION_COUNT);
        return view('dashboard.aupCategories.index',[
            'categories' =>$cat,
        ]);
    }

    public function create(Category $category)
    {
        $cat = $category->parent()->orderBy('id','DESC')->get();
        return view('dashboard.aupCategories.create',[
            'categories' => $cat,
        ]);
    }


    public function store(SubCategoryRequest $request , Category $category)
    {

        try {

            DB::beginTransaction();

            //validation

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            $cat = $category->create($request->except('_token'));

            //save translations
            $cat->name = $request->name;
            $cat->save();

            DB::commit();
            return redirect()->route('sup-categories.index')->with(['success' => 'تم ألاضافة بنجاح']);


        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('sup-categories.index')->with(['erorrs' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }


    public function edit($id)
    {


        //get specific categories and its translations
        $category = Category::orderBy('id', 'DESC')->find($id);

        if (!$category)
            return redirect()->route('admin.subcategories')->with(['erorrs' => 'هذا القسم غير موجود ']);

        $categories = Category::parent()->orderBy('id','DESC') -> get();

        return view('dashboard.aupCategories.edit',[
            'category' =>$category,
            'categories' =>$categories,
        ]);


    }


    public function update($id, SubCategoryRequest $request)
    {
        try {
            //validation

            //update DB


            $category = Category::find($id);

            if (!$category)
                return redirect()->route('sup-categories.index')->with(['erorrs' => 'هذا القسم غير موجود']);

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            $category->update($request->all());

            //save translations
            $category->name = $request->name;
            $category->save();

            return redirect()->route('sup-categories.index')->with(['success' => 'تم ألتحديث بنجاح']);
        } catch (\Exception $ex) {

            return redirect()->route('sup-categories.index')->with(['erorrs' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }


    public function destroy($id)
    {

        try {
            //get specific categories and its translations
            $category = Category::orderBy('id', 'DESC')->find($id);

            if (!$category)
                return redirect()->route('sup-categories.index')->with(['erorrs' => 'هذا القسم غير موجود ']);

            $category->delete();

            return redirect()->route('sup-categories.index')->with(['success' => 'تم  الحذف بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('sup-categories.index')->with(['erorrs' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }
}
