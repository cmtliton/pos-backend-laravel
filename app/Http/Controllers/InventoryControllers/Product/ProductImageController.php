<?php

namespace App\Http\Controllers\InventoryControllers\Product;

use App\Models\InventoryModels\Product;
use App\Models\InventoryModels\ProductImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
   public function index(int $product_id) {
    $product = Product::findOrFail($product_id);
    $productImages = ProductImage::where('product_id', $product_id)->get();
    return response()->json([
      'Product' => $product,
      'ProductImages' => $productImages,
    ], 200);
   }

   public function store(Request $request, int $product_id) {
    
    $product = Product::findOrFail($product_id);
    // Validate incoming request data
    $request->validate([
      'images' => 'required',
      'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
  ]);

  // Initialize an array to store image information
  $images = [];

  // Process each uploaded image
  foreach($request->file('images') as $image) {
      // Generate a unique name for the image
      $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        
      // Move the image to the desired location
      $image->move(public_path('uploads/products/'), $imageName);

      // Add image information to the array
      $images[] = [
        'product_id' => $product->id,
        'image' => $imageName
      ];
  }

  // Store images in the database using create method
  foreach ($images as $imageData) {
    ProductImage::create($imageData);
  }

  return response()->json([
    'message' => 'Images uploaded successfully',
    'data' => $product_id
  ]);

  //   $product = Product::findOrFail($product_id);
  //   $imageData = [];

  //   if($files = $request->file('image')) {
  //     foreach($files as $key => $file) {
  //        $extension = $file->getClientOriginalExtension();
  //       dd($extension);
  //        $filename = $key.'-'.time().'.'.$extension;
  
  //        $path = "uploads/products/";
  //        dd($path);
  //        $file->move($path, $filename);
  //        $imageData[] = [
  //           'product_id' => $product->id,
  //           'image' => $path.$filename,
  //        ];
  //     }
  //   }
  //  // dd($imageData);
  //   ProductImage::insert($imageData);

    // return response()->json([
    //   'message' => 'Uploaded Successfully!',
    //   'data' => $imageData
    // ]);
   }

   public function destroy(int $product_imageId) {
    $productImage = ProductImage::findOrFail($product_imageId);
    if(File::exists($productImage->image)) {
      File::delete($productImage->image);
    }
    $productImage->delete();
    return response()->json([
      'message' => 'Image Deleted!'
    ], 200);
   }
}
