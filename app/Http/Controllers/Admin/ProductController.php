<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductSubCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Size;
use App\Models\Color;
use App\Models\ProductCategory;
use Spatie\MediaLibrary;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Traits\ImageUpload;
use App\Traits\VideoUpload;
use App\Models\OrderItem;
use App\Models\Order;

class ProductController extends Controller
{
    use ImageUpload;
    use VideoUpload;
    
    public function index()
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::with('category', 'sub_category', 'sizes', 'colors', 'media')->get();
        

        return view('admin.products.index', compact('products'));
    }

    public function product(Request $request){

        $categoryId = $request->input('category');
        $sub_categories = ProductSubCategory::where('product_category_id', $categoryId)->get();
        $categories = ProductCategory::all();
        $sizes = Size::all();
        $colors = Color::all();

        session(['selected_category' => $categoryId]);

        return back()->with(compact('sizes', 'colors', 'sub_categories', 'categories'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sizes = Size::all();
        $colors = Color::all();
        $categories = ProductCategory::all();

        $selectedCategory = session('selected_category');
        $sub_categories = null;

        if ($selectedCategory) {
            $sub_categories = ProductSubCategory::where('product_category_id', $selectedCategory)->get();
        }
        return view('admin.products.create', compact('sizes', 'colors', 'categories', 'sub_categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());

        if ($request->has('sizes')) {
            foreach ($request->input('sizes') as $sizeId) {
                $product->sizes()->attach($sizeId);
            }
        }

        if ($request->has('colors')) {
            foreach ($request->input('colors') as $colorId) {
                $product->colors()->attach($colorId);
            }
        }

        if ($request->hasFile('images')) {
            
            foreach ($request->file('images') as $image) {
                $productImage = $this->manualStoreMedia($image)['name'];
                 $product->addMedia(storage_path('tmp/uploads/'.basename($productImage)))->toMediaCollection('images');
 
             }

        }
        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = ProductCategory::all();
        $selectedCategory = session('selected_category');
        $sub_categories = null;

        if ($selectedCategory) {
            $sub_categories = ProductSubCategory::where('product_category_id', $selectedCategory)->get();
        }
        $sizes = Size::all();

        $colors = Color::all();

        return view('admin.products.edit', compact('product', 'sizes', 'colors', 'categories', 'sub_categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());
        if ($request->has('sizes')) {

            $product->sizes()->detach();

            foreach ($request->input('sizes') as $sizeId) {              
                $product->sizes()->attach($sizeId);
            }
        }

        if ($request->has('colors')) {

        $product->colors()->detach();

            foreach ($request->input('colors') as $colorId) {
                
                $product->colors()->attach($colorId);
            }
        }

        if ($request->hasFile('images')) {
            if ($product->hasMedia('images')) {
                $product->clearMediaCollection('images');
            }

            // Add the new images
            foreach ($request->file('images') as $image) {
                $productImage = $this->manualStoreMedia($image)['name'];
                 $product->addMedia(storage_path('tmp/uploads/'.basename($productImage)))->toMediaCollection('images');
 
             }
        }

        return redirect()->route('admin.products.index');
    }

    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->delete();

        return back();
    }

    public function getProductFormData()
    {
        $categories = ProductCategory::with('subcategories')->get();
        $sizes = Size::all();
        $colors = Color::all();

        return response()->json([
            'categories' => $categories,
            'sizes' => $sizes,
            'colors' => $colors
        ], 200);
    }

    Public function getOrder()
    {
        $orders = Order::with('orderItems.product', 'billingInformation')->get();
        
        return view('admin.productOrders.index' , compact('orders'));

        git 
    }
    Public function showOrder($id)
    {
        $orders = OrderItem::with('product', 'product.media', 'order', 'order.billingInformation', 'color', 'size')
                            ->where('order_id', $id)
                            ->get();

        return view('admin.productOrders.show' , compact('orders'));

        
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        $products = Product::find(request('ids'));

        foreach ($products as $product) {
            $product->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function manualStoreMedia($file)
    {

        $path = storage_path('tmp/uploads');

        try {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        } catch (\Exception $e) {
        }

        if(is_array($file)){
            $files = $file;
            $response = [];
            foreach($files as $key => $file){
                $name = uniqid() . '_' . trim($file->getClientOriginalName());
                $file->move($path, $name);
                $response[$key] = ['name' => $name, 'original_name' => $file->getClientOriginalName()];
            }
            return $response;
        } else{
            $name = uniqid() . '_' . trim($file->getClientOriginalName());

            $file->move($path, $name);

            return array(
                'name'=> $name,
                'original_name' => $file->getClientOriginalName()
            );
        }
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('product_create') && Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Product();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
