<?php

namespace App\Http\Controllers\Web;

use App\Forms\UserForm;
use App\Http\Controllers\Controller;
use App\Http\Library\Library;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kris\LaravelFormBuilder\FormBuilder;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{

    use Library;

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
        if ($this->isAdmin($user)) {
            $users = User::withTrashed()->get();
            if ($request->ajax()) {
                $users = User::withTrashed()->get();
                return DataTables::of($users)
                    ->addIndexColumn()
                    ->addColumn(
                        'action',
                        function ($user) {
                            return view('pages.users._actions', compact('user'));
                        }
                    )
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('pages.users.index');
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
        if ($this->isAdmin($user)) {
            $form = $this->_getForm();
            return view('pages.users.create', compact('form'));
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
        if ($this->isAdmin($user)) {
            $form = $this->_getForm();
            $form->redirectIfNotValid();
            $u = $this->_fillUserData($request);
            $u->save();
            $this->_fillUserRoles($u);
            return redirect()->route('user_index')
                ->with(
                    'success',
                    'Utilisateur crée avec succes!'
                );
        }
        abort(403, "Access denied!");
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request requette
     * @param \App\Models\User         $user    utilisateur à afficher
     * @param int                      $id      identifiant
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user, int $id)
    {
        $a = $request->user();
        if ($this->isAdmin($a)) {
            $user = User::find($id);
            if (empty($user)) {
                $user = User::withTrashed()->find($id);
            }
            return view('pages.users.details', compact('user'));
        }
        abort(403, "Access denied!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request requette
     * @param \App\Models\User         $user    utilisateur
     * @param int                      $id      identifiant
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user, int $id)
    {
        $a = $request->user();
        if ($this->isAdmin($a)) {
            $user = User::find($id);
            if (empty($user)) {
                $user = User::withTrashed()->find($id);
            }
            $form = $this->_getForm($user);
            return view('pages.users.create', compact('form', 'user'));
        }
        abort(403, "Access denied!");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request requette
     * @param \App\Models\User         $user    utilisateur à mettre à jour
     * @param int                      $id      identifiant
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, int $id)
    {
        $a = $request->user();
        if ($this->isAdmin($a)) {
            $user = User::find($id);
            $form = $this->_getForm($user);
            $form->redirectIfNotValid();
            $user = $this->_fillUserData($request, $user);
            $user->update();
            $user->rules()->detach();
            $this->_fillUserRoles($user);
            return redirect()->route('user_index')
                ->with(
                    'success',
                    'Utilisateur modifié avec succes!'
                );
        }
        abort(403, "Access denied!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request requette
     * @param \App\Models\User         $user    utilisateur à supprimer
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user, int $id)
    {
        $a = $request->user();
        if ($this->isAdmin($a)) {
            $user = User::find($id);
            $user->delete();
            return redirect()->route('user_index')
                ->with(
                    'success',
                    'Utilisateur supprimé avec succes!'
                );
        }
        abort(403, "Access denied!");
    }

    /**
     * Initialisation du formulaire utilisateur
     *
     * @param \App\Models\User $user model de données du formulaire.
     *
     * @return $mixed
     */
    private function _getForm(?User $user = null): UserForm
    {
        $user = $user ?: new User();
        return $this->_formbuilder->create(
            UserForm::class,
            [
                'model' => $user
            ]
        );
    }

    /**
     * Remplir les infos d'un user venant de la requette
     *
     * @param \Illuminate\Http\Request $req  requette utilisateur
     * @param \App\Models\User         $user utilisateur ma,ipulé
     *
     * @return \App\Models\User
     */
    private function _fillUserData(Request $req, ?User $user = null)
    {
        $u = $user ?: new User();
        $u->name = $req->name;
        $u->email = $req->email;
        $u->phone = $req->phone;
        $u->rule = $req->rule;
        if (!empty($req->password)) {
            $u->password = Hash::make($req->password);
        }
        return $u;
    }

    /**
     * Remplir les roles d'un user
     *
     * @param \App\Models\User $user utilisateur
     *
     * @return void
     */
    private function _fillUserRoles(User $user)
    {
        if ($user->rule == 'admin') {
            $user->rules()->attach([1, 2]);
        } else {
            $user->rules()->attach(2);
        }
    }
}
