<span>
    <a class="btn btn-info" href="{{ route('product_edit', ['id' => $product->id]) }}">
        <i class="bi bi-pencil-square"></i>
    </a>
    <a class="btn btn-info" href="{{ route('product_show', ['id' => $product->id]) }}">
        <i class="bi bi-eye"></i>
    </a>
</span>
