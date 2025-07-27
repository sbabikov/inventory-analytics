<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Аналітика товарів</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .highlight-diff {
            background-color: #f8d7da !important;
            color: #721c24;
        }
    </style>
</head>
<body>
<div class="container-fluid mt-5">
    <h2 class="mb-4">Таблиця товарів</h2>

    <!-- Фільтр по каталогу -->
    <form method="GET" class="mb-4">
        <div class="row g-2">
            <div class="col-auto">
                <select name="catalog_id" id="catalog_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Усі каталоги</option>
                    @foreach ($catalogs as $catalog)
                        <option value="{{ $catalog->id }}" @selected(request('catalog_id') == $catalog->id)>
                            {{ $catalog->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <!-- Таблиця -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID Firma</th>
            <th>ID Linker</th>
            <th>Назва</th>
            <th>SKU / EAN</th>
            <th>К-сть на складі (Firma)</th>
            <th>К-сть у Linker</th>
            <th>Ціна (Firma)</th>
            <th>Ціна у Linker</th>
            <th>Заг. продажі</th>
            <th>Сер. продажі (7 днів)</th>
            <th>Джерела продажів</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->firma_id }}</td>
                <td>{{ $product->linker_id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }} / {{ $product->ean }}</td>
                <td>{{ $product->firma_stock }}</td>
                <td class="@if($product->firma_stock !== $product->linker_stock) highlight-diff @endif">
                    {{ $product->linker_stock }}
                </td>
                <td>{{ number_format($product->firma_price, 2) }} грн</td>
                <td class="@if($product->firma_price !== $product->linker_price) highlight-diff @endif">
                    {{ number_format($product->linker_price, 2) }} грн
                </td>
                <td>{{ $product->total_sales }}</td>
                <td>{{ $product->average_sales_7_days }}</td>
                <td>
                    @foreach($product->sales_by_source as $source => $count)
                        <span class="badge bg-secondary">{{ $source }}: {{ $count }}</span>
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Пагінація -->
    <div class="d-flex justify-content-center">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
</body>
</html>
