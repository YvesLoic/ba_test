@extends('home')

@section('title', config('app.name') . ' - Products')

@section('styles')
    <link href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection

@section('center')
    <div class="card">
        <div class="card-header">
            Liste des produits
            <a href="{{ route('product_create') }}" class="btn btn-info float-end">
                Nouveau
            </a>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="card-body">
            <table class="table datatables" id="produits"></table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#produits').DataTable({
                "processing": true,
                "responsive": true,
                "initComplete": function(sttings, json) {
                    $('#produits').show();
                },
                "serverSide": true,
                "select": true,
                "dataSrc": "tableData",
                "bDestroy": true,
                "columns": [{
                    "data": "name",
                    "name": "name",
                    "title": "Nom_Produit",
                }, {
                    "data": "description",
                    "name": "description",
                    "title": "Description",
                    mRender: function(desc) {
                        return desc.slice(0, 50);
                    }
                }, {
                    "data": "quantity",
                    "name": "quantity",
                    "title": "Quantité",
                }, {
                    "data": "price",
                    "name": "price",
                    "title": "Prix",
                }, {
                    "data": "published",
                    "name": "published",
                    "title": "Publié ?",
                    mRender: function(status) {
                        if (status == '1') {
                            return '<span class="badge bg-success">Publié</span>';
                        } else {
                            return '<span class="badge bg-info">Non publié</span>';
                        }
                    }
                }, {
                    "data": "deleted_at",
                    "name": "deleted_at",
                    "title": "Statut du produit",
                    mRender: function(status) {
                        if (status !== null) {
                            return '<span class="badge bg-danger">Supprimé</span>';
                        } else {
                            return '<span class="badge bg-success">Non Supprimé</span>';
                        }
                    }
                }, {
                    "data": "action",
                    "name": "action",
                    "title": "Action",
                }],
                "language": {
                    "emptyTable": "No records found..."
                },
                "ajax": "{{ route('product_index') }}"
            });
        });
    </script>
@endsection
