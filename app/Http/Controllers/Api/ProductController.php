<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\ProductLibrary;
use App\Http\Resources\ProductResource;
use App\Http\Services\UploadService;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ProductLibrary, UploadService;
    private $num = 5;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isOwner($user)) {
            $produits = $this->isAdmin($user) ?
                Product::withTrashed()
                ->with('creator')
                ->forPage($request->page, $this->num)
                ->get()
                : Product::withTrashed()
                ->with('creator')->where('user_id', $user->id)
                ->forPage($request->page, $this->num)
                ->get();
            $produits = ProductResource::collection($produits);
            return $this->success($produits, sizeof($produits) . " products from page " . $request->page);
        }
        return $this->error(403, "Access denied!", "getProductList from method 'index'");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isOwner($user)) {
            $validator = Validator::make($request->all(), $this->productValidatedRules(), $this->productMessagesError());
            if (!$validator->fails()) {
                $product = $this->_fillProductData($request);
                if (!empty($request->file('image'))) {
                    $this->uploadProductImage($request, $product);
                }
                $product->save();
                return $this->success(new ProductResource($product), "Product created successfully!");
            }
            return $this->error(400, $validator->errors(), "Create product from method 'store'");
        }
        return $this->error(403, "Access denied!", "Create product from method 'store'");
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request requette utilisateur
     * @param  int     $id      identifiant du produit
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isOwner($user)) {
            $product = Product::withTrashed()->with('creator')->find($id);
            if (!empty($product)) {
                $product = new ProductResource($product);
                return $this->success($product, "Product found!");
            }
            return $this->error(404, "Product not found!", "Get single product from method 'show'");
        }
        return $this->error(403, "Access denied!", "Get single product from method 'show'");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request requette utilisateur
     * @param  int     $id      identifiant du produit
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isOwner($user)) {
            $validator = Validator::make($request->all(), $this->productValidatedRules(), $this->productMessagesError());
            if (!$validator->fails()) {
                $product = Product::find($id);
                if (empty($product)) {
                    return $this->error(404, "Product not found!", "Update product from method 'update'");
                }
                $product = $this->_fillProductData($request, $product);
                if (!empty($request->file('image'))) {
                    empty($product->image) ?: File::delete(public_path($product->image));
                    $this->uploadProductImage($request, $product);
                }
                $product->update();
                return $this->success(new ProductResource($product), "Product updated successfully!");
            }
            return $this->error(400, $validator->errors(), "Update user from method 'update'");
        }
        return $this->error(403, "Access denied!", "Update user from method 'update'");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request requette utilisateur
     * @param int     $id      identifiant du produit
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isOwner($user)) {
            $product = Product::find($id);
            if (!empty($product)) {
                $product->delete();
                return $this->success(new ProductResource($product), "Product deleted!");
            }
            return $this->error(404, "Product not found!", "Delete product from method 'destroy'");
        }
        return $this->error(403, "Access denied!", "Delete product from method 'destroy'");
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  Request $request
     * @param  int     $id
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, int $id)
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $product = Product::withTrashed()->find($id);
            if (!empty($product)) {
                $product->restore();
                return $this->success(new ProductResource($product), "Product restored!");
            }
            return $this->error(404, "Product not found!", "Restore product from method 'restore'");
        }
        return $this->error(403, "Access denied!", "Restore product from method 'restore'");
    }

    /**
     * Remplir les infos d'un produit venant de la requette
     *
     * @param \Illuminate\Http\Request $req     requette utilisateur
     * @param \App\Models\Product      $product produit manipulé
     *
     * @return \App\Models\product
     */
    private function _fillProductData(Request $request, ?Product $product = null)
    {
        $p = $product ?: new Product();
        $p->name = $request->name;
        $p->description = $request->description;
        $p->price = $request->price;
        $p->quantity = $request->quantity;
        $p->user_id = $request->user()->id;
        if (!empty($request->published)) {
            $p->published = $request->published;
        }
        return $p;
    }
}
