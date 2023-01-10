@extends('home')

@section('title', config('app.name') . ' - Détails d\'un utilisateur')

@section('center')
    <div class="card">
        <div class="card-header">
            Informations de l'utilisateur
            @if (!empty($user) && !empty($user->deleted_at))
                <span class="badge bg-warning float-end">
                    Utilisateur Supprimé
                </span>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 label">Nom</div>
                <div class="col-lg-6 col-md-6">{{ $user->name }}</div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 label">Email</div>
                <div class="col-lg-6 col-md-6">{{ $user->email }}</div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 label">Phone</div>
                <div class="col-lg-6 col-md-6">{{ $user->phone }}</div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 label">Role</div>
                <div class="col-lg-6 col-md-6">{{ $user->rule }}</div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 label">Etat du compte</div>
                <div class="col-lg-6 col-md-6">
                    @if (!empty($user->deleted_at))
                        <span class="badge bg-danger">Compte Supprimé le
                            {{ $user->deleted_at->format('j F, Y H:i') }}</span>
                    @else
                        <span class="badge bg-success">Compte Actif</span>
                    @endif
                </div>
            </div>
            @if (Auth::user()->rule == 'admin' && Auth::user()->id !== $user->id)
                <div class="row">
                    <div class="col-lg-6 col-md-6 label">Supprimer ce compte</div>
                    <div class="col-lg-6 col-md-6">
                        <form action="{{ route('user_delete', ['id' => $user->id]) }}" method="DELETE">
                            @csrf
                            <button class="btn btn-default float-end" type="submit">
                                <i class="bi bi-trash-fill" style="color: red;"></i>
                                <span style="color: red;">Supprimer</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
