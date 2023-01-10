@extends('home')

@section('title', config('app.name') . ' - Détails d\'un produit')

@section('center')
    <div class="card">
        <div class="card-header">
            Informations d'un roduit
            @if (!empty($product) && !empty($product->deleted_at))
                <span class="badge bg-danger float-end">
                    Produit Supprimé
                </span>
                @if (Auth::user()->rule == 'admin')
                    <span>
                        <a href="{{ route('product_restore', ['id' => $product->id]) }}" class="btn btn-info">
                            Restorer
                        </a>
                    </span>
                @endif
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 label">Nom</div>
                <div class="col-lg-6 col-md-6">{{ $product->name }}</div>
            </div>
            @if (Auth::user()->rule == 'admin')
                <div class="row">
                    <div class="col-lg-6 col-md-6 label">Créer le</div>
                    <div class="col-lg-6 col-md-6">
                        {{ $product->created_at->format('j F, Y H:i') }} par: {{ $autor->name }}
                    </div>
                </div>
            @elseif (Auth::user()->rule == 'owner' && Auth::user()->id == $product->user_id)
                <div class="row">
                    <div class="col-lg-6 col-md-6 label">Crée par moi le</div>
                    <div class="col-lg-6 col-md-6">
                        {{ $product->created_at->format('j F, Y H:i') }} .
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-6 col-md-6 label">Prix</div>
                <div class="col-lg-6 col-md-6">{{ $product->price }}</div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 label">Quantité</div>
                <div class="col-lg-6 col-md-6">{{ $product->quantity }}</div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 label">Description</div>
                <div class="col-lg-6 col-md-6">{{ $product->description }}</div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 label">Image</div>
                <div class="col-lg-6 col-md-6">
                    @if (!empty($product->image))
                        <img src="{{ asset($product->image) }}" alt="Image" style="width: 200px; height: 200px">
                    @else
                        <span>Pas d'image pour ce produit</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 label">Etat du produit</div>
                <div class="col-lg-6 col-md-6">
                    @if (!empty($product->deleted_at))
                        <span class="badge bg-danger">Produit Supprimé le
                            {{ $product->deleted_at->format('j F, Y H:i') }}</span>
                    @else
                        <span class="badge bg-success">Produit Actif</span>
                    @endif
                </div>
            </div>
            @if (empty($product->deleted_at))
                <div class="row">
                    <div class="col-lg-6 col-md-6 label">Supprimer ce produit</div>
                    <div class="col-lg-6 col-md-6">
                        <form action="{{ route('product_delete', ['id' => $product->id]) }}" method="delete">
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
