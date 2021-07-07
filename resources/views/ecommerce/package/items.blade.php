<table class="table table-hover w-100" id="packageItemDynamicForm">

    <thead>
        <tr>
            <th scope="col" style="width: 35%;">{{ trans_choice('labels.product', 1) }}</th>
            <th scope="col" style="width: 35%;">{{ __('labels.sku') }}</th>
            <th scope="col" style="width: 20%;">{{ __('labels.quantity') }}</th>
            <th scope="col" style="width: 10%;">{{ __('labels.action') }}</th>
        </tr>
    </thead>

    <tbody>

        @if (!empty($items))
        @foreach ($items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->sku }}</td>
            <td>{{ $item->pivot->quantity }}</td>
            <td>
                <a role="button" href="#" class="btn btn-danger" onclick="event.preventDefault(); deleteAlert('{{ __('messages.confirm_question') }}', '{{ __('messages.delete_info') }}', '{{ route('ecommerce.packages.products.destroy', ['package' => $package->id, 'product' => $item->id]) }}')">
                    <i class="fas fa-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
        @endif

        @forelse ((array) old('items') as $index => $item)
        <tr>
            <td>
                <select name="items[{{ $index }}][product]" id="product{{ $index }}" class="form-control select2 sku-filter">
                    <option value="{{ $index }}" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.product', 1))]) }} ---</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ old('items.'.$index.'.product') == $product->id ? 'selected' : null }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="items[{{ $index }}][sku]" id="sku{{ $index }}" class="form-control select2 sku-dropdown" data-selected="{{ old('items.'.$index.'.sku') }}" data-sku-route="{{ route('data.products.sku', '__PRODUCT__') }}">
                    <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => __('labels.sku')]) }} ---</option>
                </select>
            </td>
            <td>
                <input type="number" name="items[{{ $index }}][quantity]" id="quantity{{ $index }}" class="form-control" value="{{ old('items.'.$index.'.quantity', 0) }}" min="0" step="1">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-remove-row">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        @empty
        @if (empty($items))
        <tr>
            <td>
                <select name="items[0][product]" id="product0" class="form-control select2 sku-filter">
                    <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.product', 1))]) }} ---</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ old('items.0.product') == $product->id ? 'selected' : null }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="items[0][sku]" id="sku0" class="form-control select2 sku-dropdown" data-selected="{{ old('items.0.sku') }}" data-sku-route="{{ route('data.products.sku', '__PRODUCT__') }}">
                    <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => __('labels.sku')]) }} ---</option>
                </select>
            </td>
            <td>
                <input type="number" name="items[0][quantity]" id="quantity0" class="form-control" value="0" min="0" step="1">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-remove-row">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        @endif
        @endforelse


        <tr id="packageItemCloneTemplate" aria-hidden="true" hidden="true">
            <td>
                <select name="items[__REPLACE__][product]" id="product__REPLACE__" class="form-control select2 sku-filter" disabled>
                    <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.product', 1))]) }} ---</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="items[__REPLACE__][sku]" id="sku__REPLACE__" class="form-control select2 sku-dropdown" disabled data-selected="{{ old('items.__REPLACE__.sku') }}" data-sku-route="{{ route('data.products.sku', '__PRODUCT__') }}">
                    <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => __('labels.sku')]) }} ---</option>
                </select>
            </td>
            <td>
                <input type="number" name="items[__REPLACE__][quantity]" id="quantity__REPLACE__" class="form-control" value="0" min="0" step="1" disabled>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-remove-row">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>

    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">
                <button type="button" class="btn btn-outline-primary btn-add-row">
                    <i class="fas fa-plus"></i>
                    {{ __('labels.add_more') }}
                </button>
            </td>
        </tr>
    </tfoot>
</table>