<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralRequest;
use App\Http\Requests\ProductsPriceRequest;
use App\Http\Requests\ProductStockRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $pro = $product->select('id', 'slug', 'price', 'created_at' , 'is_active')->paginate(PAGINATION_COUNT);
        return view('dashboard.products.general.index', [
            'products' => $pro,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Brand $brand, Category $category, Tag $tag)
    {
        $data['brands'] = $brand->active()->select('id')->get();
        $data['categories'] = $category->active()->select('id')->get();
        $data['tags'] = $tag->get();
        return view('dashboard.products.general.create', [
            'brands' => $brand->active()->select('id')->get(),
            'categories' => $category->active()->select('id')->get(),
            'tags' => $tag->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GeneralRequest $request)
    {
        DB::beginTransaction();

        //validation

        if (!$request->has('is_active'))
            $request->request->add(['is_active' => 0]);
        else
            $request->request->add(['is_active' => 1]);

        $product = Product::create([
            'slug' => $request->slug,
            'brand_id' => $request->brand_id,
            'is_active' => $request->is_active,
        ]);
        //save translations
        $product->name = $request->name;
        $product->description = $request->description;
        $product->short_description = $request->short_description;
        $product->save();

        //save product categories

        $product->categories()->attach($request->categories);

        //save product tags

        DB::commit();
        return redirect()->route('products.index')->with(['success' => 'تم ألاضافة بنجاح']);
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getPrice($product_id )
    {
        //$product = Product::get();
        return view('dashboard.products.prices.create')->with('id' , $product_id)/*->with('product' , $product)*/;
    }

    public function saveProductPrice(ProductsPriceRequest $request)
    {
        //return $request;
        try {

            Product::whereId($request->product_id)->update($request->only(['price', 'special_price', 'special_price_type', 'special_price_start', 'special_price_end']));
            return redirect()->route('products.index')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('products.index')->with('erorrs' , 'حدث خطا ما يرجى المحاولة لاحقا');
        }
    }

    public function getStok($product_id){
        return view('dashboard.products.stock.create')->with('id' , $product_id);
    }
    
    public function saveProductStok(ProductStockRequest $request){
          // return $request;
        try{
            Product::whereId($request->product_id) -> update($request -> except(['_token','product_id']));
            return redirect()->route('products.index')->with(['success' => 'تم التحديث بنجاح']);
        }catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('products.index')->with('erorrs' , 'حدث خطا ما يرجى المحاولة لاحقا');
        }
    }
    
    public function getImag($product_id){
        return view('dashboard.products.image.create')->with('id' , $product_id);
    }
    public function saveProductImag(Request $request){

        $file = $request->file('dzfile');
        $filename = uploadImage('products', $file);

        return response()->json([
            'name' => $filename,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
}
