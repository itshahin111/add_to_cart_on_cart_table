@extends('layout')

@section('content')
    <div class="container mt-4">
        <h3>Your Cart</h3>

        @if ($cartItems->isEmpty())
            <p>Your cart is empty.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="cart-items-body">
                    @foreach ($cartItems as $item)
                        <tr data-id="{{ $item->id }}">
                            <td>{{ $item->product->name }}</td>
                            <td>${{ number_format($item->product->price, 2) }}</td>
                            <td>
                                <input type="number" class="form-control update-quantity" value="{{ $item->quantity }}"
                                    min="1">
                            </td>
                            <td class="subtotal">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                            <td>
                                <button class="btn btn-danger remove-from-cart">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-right">
                <h4>Total Price: $<span id="total-price">{{ number_format($totalPrice, 2) }}</span></h4>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Update cart item quantity
            $('#cart-items-body').on('change', '.update-quantity', function() {
                let row = $(this).closest('tr');
                let cartId = row.data('id');
                let quantity = $(this).val();

                $.ajax({
                    url: '{{ route('update.cart') }}',
                    method: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: cartId,
                        quantity: quantity
                    },
                    success: function(response) {
                        alert(response.message);
                        updateRow(row, quantity);
                        $('#total-price').text(response.totalPrice.toFixed(2));
                    },
                    error: function() {
                        alert('Error updating cart item');
                    }
                });
            });

            // Remove cart item
            $('#cart-items-body').on('click', '.remove-from-cart', function() {
                let row = $(this).closest('tr');
                let cartId = row.data('id');

                $.ajax({
                    url: '{{ route('remove.from.cart') }}',
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: cartId
                    },
                    success: function(response) {
                        alert(response.message);
                        row.remove(); // Remove row from UI
                        $('#total-price').text(response.totalPrice.toFixed(2));
                    },
                    error: function() {
                        alert('Error removing item from cart');
                    }
                });
            });

            // Function to update cart row
            function updateRow(row, quantity) {
                let price = parseFloat(row.find('td:nth-child(2)').text().replace('$', ''));
                let subtotal = price * quantity;
                row.find('.subtotal').text('$' + subtotal.toFixed(2));
            }
        });
    </script>
@endsection
