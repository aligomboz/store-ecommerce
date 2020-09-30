<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagsRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Tag $tag)
    {
        $tags = $tag->latest()->paginate(PAGINATION_COUNT);
  
        return view('dashboard.tags.index' ,[
            'tags' =>$tags,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagsRequest $request , Tag $tag)
    {
        try{
            DB::beginTransaction();
            $nameTag=  $tag->create($request->all());

            $nameTag->name = $request->name;
            $nameTag->save();
            DB::commit();
            return redirect()->route('tags.index')->with(['success' => 'تم ألاضافة بنجاح']);

        }catch(\Exception $ex){
            DB::rollBack();
          return redirect()->route('tags.index')->with('erorrs' , 'حدث خطا ما يرجى المحاولة لاحقا');
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
    public function edit(Tag $tag)
    {
        $tags = $tag->findOrFail($tag->id);
        return view('dashboard.tags.edit' , [
            'tag' =>$tags,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TagsRequest $request, Tag $tag)
    {
        try{
            DB::beginTransaction();
            $tag->update($request->all());
            $tag->name= $request->name;
            $tag->save();
            DB::commit();
            return redirect()->route('tags.index')->with(['success' => 'تم التعديل بنجاح']);
        }catch(\Exception $ex){
            DB::rollBack();
          return redirect()->route('tags.index')->with('erorrs' , 'حدث خطا ما يرجى المحاولة لاحقا');
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        try{
            DB::beginTransaction();
            $tag->translations()->delete();
            $tag->delete();
            DB::commit();
            return redirect()->route('tags.index')->with(['success' => 'تم الحذف بنجاح']);
        }catch(\Exception $ex){
            DB::rollBack();
          return redirect()->route('tags.index')->with('erorrs' , 'حدث خطا ما يرجى المحاولة لاحقا');
       }
    }
}
