<?php

namespace App\Http\Controllers\Web;

use App\Forms\ProductForm;
use App\Http\Controllers\Controller;
use App\Http\Library\Library;
use App\Http\Services\UploadService;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Yajra\DataTables\DataTables;
use Kris\LaravelFormBuilder\FormBuilder;

class ProductController extends Controller
{
    use Library, UploadService;

    private $_formbuilder;

    /**
     * Constructeur par defaut du controlleur des users.
     *
     * @param \Kris\LaravelFormBuilder\FormBuilder $formBuilder demarreur de template
     */
    public function __construct(FormBuilder $formBuilder)
    {
        $this->_formbuilder = $formBuilder;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request requette
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isOwner($user)) {
            if ($request->ajax()) {
                $products = $this->isAdmin($user)
                    ? Product::withTrashed()->get() :
                    Product::withTrashed()->where('user_id', $user->id)->get();
                return DataTables::of($products)
                    ->addIndexColumn()
                    ->addColumn(
                        'action',
                        function ($product) {
                            return view(
                                'pages.products._actions',
                                compact('product')
                            );
                        }
                    )
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('pages.products.index');
        }
        abort(403, "Access denied!");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request requette
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isOwner($user)) {
            $form = $this->_getForm();
            return view('pages.products.create', compact('form'));
        }
        abort(403, "Access denied!");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request requette
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isOwner($user)) {
            $form = $this->_getForm();
            $form->redirectIfNotValid();
            $product = $this->_fillProductData($request);
            if (!empty($request->file('image'))) {
                $this->uploadProductImage($request, $product);
            }
            $product->save();
            return redirect()->route('product_index')
                ->with(
                    'success',
                    'Produit enregistré avec succès!'
                );
        }
        abort(403, "Access denied!");
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request requette
     * @param \App\Models\Product      $product produit
     * @param int                      $id      identifiant du produit
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Product $product, int $id)
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isOwner($user)) {
            $product = Product::find($id);
            if (empty($product)) {
                $product = Product::withTrashed()->find($id);
            }
            $autor = User::find($product->user_id);
            return view('pages.products.details', compact('product', 'autor'));
        }
        abort(403, "Access denied!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request requette
     * @param \App\Models\Product      $product produit
     * @param int                      $id      identifiant du produit
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Product $product, int $id)
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isOwner($user)) {
            $product = Product::find($id);
            if (empty($product)) {
                $product = Product::withTrashed()->find($id);
            }
            $form = $this->_getForm($product);
            return view('pages.products.create', compact('form', 'product'));
        }
        abort(403, "Access denied!");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request requette
     * @param \App\Models\Product      $product produit
     * @param int                      $id      identifiant du produit
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, int $id)
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isOwner($user)) {
            $product = Product::find($id);
            $form = $this->_getForm($product);
            $form->redirectIfNotValid();
            $product = $this->_fillProductData($request, $product);
            if (!empty($request->file('image'))) {
                $this->uploadProductImage($request, $product);
            }
            $product->updated_at = Date::now();
            $product->update();
            return redirect()->route('product_index')
                ->with(
                    'success',
                    'Produit modifié avec succès!'
                );
        }
        abort(403, "Access denied!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request requette
     * @param \App\Models\Product      $product produit
     * @param int                      $id      identifiant du produit
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Product $product, int $id)
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isOwner($user)) {
            $product = Product::find($id);
            $product->published = false;
            $product->delete();
            return redirect()->route('product_index')
                ->with(
                    'success',
                    'Produit supprimé avec succès!'
                );
        }
        abort(403, "Access denied!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request requette
     * @param int                      $id      identifiant du produit
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, int $id)
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            Product::withTrashed()->find($id)->restore();
            return redirect()->route('product_index')
                ->with(
                    'success',
                    'Produit restoré avec succès!'
                );
        }
        abort(403, "Access denied!");
    }

    /**
     * Initialisation du formulaire utilisateur
     *
     * @param \App\Models\Product $product model de données du formulaire.
     *
     * @return $mixed
     */
    private function _getForm(?Product $product = null): ProductForm
    {
        $product = $product ?: new Product();
        return $this->_formbuilder->create(
            ProductForm::class,
            [
                'model' => $product
            ]
        );
    }

    /**
     * Remplir les infos d'un user venant de la requette
     *
     * @param \Illuminate\Http\Request $req     requette utilisateur
     * @param \App\Models\Product      $product produit manipulé
     *
     * @return \App\Models\product
     */
    private function _fillProductData(Request $req, ?Product $product = null)
    {
        $p = $product ?: new Product();
        $p->name = $req->name;
        $p->description = $req->description;
        $p->price = $req->price;
        $p->quantity = $req->quantity;
        $p->user_id = $req->user()->id;
        if (!empty($req->published)) {
            $p->published = $req->published;
        }
        return $p;
    }
}
