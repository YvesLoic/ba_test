@extends('home')

@section('title', config('app.name') . ' - Users')

@section('styles')
    <link href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection

@section('center')
    <div class="card">
        <div class="card-header">
            Utilisateurs
            <a href="{{ route('user_create') }}" class="btn btn-info float-end">
                Nouveau
            </a>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <strong>{{ $message }}</strong>
                </div>
            @endif
        </div>
        <div class="card-body">
            <table class="table datatables" id="users"></table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#users').DataTable({
                "processing": true,
                "responsive": true,
                "initComplete": function(sttings, json) {
                    $('#users').show();
                },
                "serverSide": true,
                "select": true,
                "dataSrc": "tableData",
                "bDestroy": true,
                "columns": [{
                    "data": "id",
                    "name": "id",
                    "title": "Identifiant",
                }, {
                    "data": "name",
                    "name": "name",
                    "title": "Nom",
                }, {
                    "data": "email",
                    "name": "email",
                    "title": "Email",
                }, {
                    "data": "rule",
                    "name": "rule",
                    "title": "Role",
                }, {
                    "data": "deleted_at",
                    "name": "deleted_at",
                    "title": "Statut du compte",
                    mRender: function(status) {
                        if (status !== null) {
                            return '<span class="badge bg-danger">Supprimé</span>';
                        } else {
                            return '<span class="badge bg-success">Actif</span>';
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
                "ajax": "{{ route('user_index') }}"
            });
        });
    </script>
@endsection
