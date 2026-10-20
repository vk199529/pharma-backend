@extends(backpack_view('blank'))

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h3>Bulk Edit Products</h3>
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ backpack_url('product/bulk-update') }}">

                @csrf

                <table class="table table-bordered table-striped">

                    <thead>
							<tr>
								<th>ID</th>
								<th>Generic Name</th>
								<th>Brand Names</th>
								<th>Product Type</th>
								<th>Strength</th>
								<th>Therapeutic Class</th>
								<th>Price</th>
								<th>WAC (Wholesale Acquisition Costs)</th>
							</tr>
							</thead>

                    <tbody>

                    @foreach($products as $product)

                        <tr>

							<td>
								{{ $product->id }}

								<input type="hidden"
									   name="products[{{$product->id}}][id]"
									   value="{{$product->id}}">
							</td>

							<td>
								{{ $product->generic_name }}
							</td>

							<td>
								<input
									type="text"
									class="form-control"
									name="products[{{$product->id}}][brand_names]"
									value="{{ $product->brand_names }}">
							</td>

							<td>

								<select
									class="form-control"
									name="products[{{$product->id}}][product_type]">

									<option value=""
									{{ empty($product->product_type) ? 'selected' : '' }}>
										-- Select Product Type --
									</option>

									<option value="Originator Brand"
									{{ $product->product_type=='Originator Brand' ? 'selected':'' }}>
										Originator Brand
									</option>

									<option value="Generic"
									{{ $product->product_type=='Generic' ? 'selected':'' }}>
										Generic
									</option>

									<option value="Biosimilar"
									{{ $product->product_type=='Biosimilar' ? 'selected':'' }}>
										Biosimilar
									</option>

									<option value="Biocomparable"
									{{ $product->product_type=='Biocomparable' ? 'selected':'' }}>
										Biocomparable
									</option>

									<option value="API Only"
									{{ $product->product_type=='API Only' ? 'selected':'' }}>
										API Only
									</option>
									
									<option value="Special Import/Named Patient"
									{{ $product->product_type=='Special Import/Named Patient' ? 'selected':'' }}>
										Special Import/Named Patient
									</option>
									
									<option value="Govt Procurement Only"
									{{ $product->product_type=='Govt Procurement Only' ? 'selected':'' }}>
										Govt Procurement Only
									</option>

								</select>

							</td>

							<td>

								<input
									type="text"
									class="form-control"
									name="products[{{$product->id}}][strength]"
									value="{{ $product->strength }}">

							</td>

							<td>

								<input
									type="text"
									class="form-control"
									name="products[{{$product->id}}][therapeutic_class]"
									value="{{ $product->therapeutic_class }}">

							</td>

							<td>

								<input
									type="number"
									step="0.01"
									class="form-control"
									name="products[{{$product->id}}][price]"
									value="{{ $product->price }}">

							</td>

							<td>

								<input
									type="number"
									step="0.01"
									class="form-control"
									name="products[{{$product->id}}][wac]"
									value="{{ $product->wac }}">

							</td>

						</tr>

                    @endforeach

                    </tbody>

                </table>


                <button type="submit"
                        class="btn btn-success">

                    Save Selected Products

                </button>


                <a href="{{ backpack_url('product') }}"
                   class="btn btn-secondary">

                    Back

                </a>


            </form>

        </div>

    </div>

</div>

@endsection