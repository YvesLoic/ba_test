@extends('home')

@section('title', config('app.name') . ' - Nouveau produit')

@section('center')
    <div class="card">
        <div class="card-header">
            Informations d'un produit
            @if (!empty($product) && !empty($product->deleted_at))
                <span class="badge bg-danger float-end">
                    Produit Supprimé
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

@section('scripts')
    <script type="text/javascript">
        (function($) {
            let url = $('#image')[0].defaultValue;
            if (url !== "") {
                $('#image').parent()
                    .append(
                        $('<div id="preview"></div>')
                        .append(`<img src="${url}" alt="prévue de l\'image" style="width: 200px; heigth: 250px;"/>`)
                    );
            }
            $('#image').on('change', function() {
                $('#preview').remove();
                let reader = new FileReader();
                reader.onload = (e) => {
                    $(this).parent()
                        .append(
                            $('<div id="preview"></div>')
                            .append(
                                `<img src="${e.target.result}" alt="prévue de l\'image" style="width: 200px; heigth: 250px;"/>`
                            )
                        );
                }
                reader.readAsDataURL(this.files[0]);
            });

        })(jQuery);
    </script>
@endsection
