@extends('layout')

@section('content')
    <div class="container mt-4">
        <div class="row">
            @foreach ($products as $product)
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}"
                            style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted">
                                {{ \Illuminate\Support\Str::limit($product->description, 60) }}
                            </p>
                            <p class="card-text"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                            <button class="btn btn-warning mt-auto text-center add-to-cart" data-id="{{ $product->id }}">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Add to Cart button click handler
            $('.add-to-cart').on('click', function(e) {
                e.preventDefault();

                let productId = $(this).data('id');

                $.ajax({
                    url: '{{ route('add.to.cart', '') }}/' + productId,
                    method: 'GET',
                    success: function(response) {
                        alert(response.message);
                        updateCartUI(response.cartItems, response.totalPrice);
                    },
                    error: function() {
                        alert('Error adding product to cart');
                    }
                });
            });

            // Function to update the entire cart UI in the dropdown
            function updateCartUI(cartItems, totalPrice) {
                let $cartBody = $('.dropdown-menu');
                $cartBody.empty(); // Clear previous cart details

                // Update cart count
                $('.btn-info .badge').text(cartItems.length);

                // Create new cart item elements
                cartItems.forEach(item => {
                    let cartDetail = `
                        <div class="row cart-detail">
                            <div class="col-lg-4 col-sm-4 col-4 cart-detail-img">
                                <img src="${item.product.image}" />
                            </div>
                            <div class="col-lg-8 col-sm-8 col-8 cart-detail-product">
                                <p>${item.product.name}</p>
                                <span class="price text-info">$${item.product.price.toFixed(2)}</span>
                                <span class="count">Quantity: ${item.quantity}</span>
                            </div>
                        </div>
                    `;
                    $cartBody.append(cartDetail);
                });

                // Update total price
                let totalSection = `
                    <div class="row total-header-section">
                        <div class="col-lg-6 col-sm-6 col-6">
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            <span class="badge badge-pill badge-danger">${cartItems.length}</span>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-6 total-section text-right">
                            <p>Total: <span class="text-info">$${totalPrice.toFixed(2)}</span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-12 text-center checkout">
                            <a href="{{ route('cart') }}" class="btn btn-primary btn-block">View all</a>
                        </div>
                    </div>
                `;
                $cartBody.append(totalSection);
            }
        });
    </script>
@endsection
