@extends('home')

@section('title', config('app.name') . ' - Nouvel utilisateur')

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

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {!! form($form) !!}
        </div>
    </div>
@endsection
