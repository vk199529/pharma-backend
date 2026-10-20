<div class="modal fade"
id="bulkModal">

<div class="modal-dialog">

<form
method="POST"
action="{{ backpack_url('product/bulk-update') }}">

@csrf

<input
type="hidden"
name="ids"
id="selectedIds">


<div class="modal-content">

<div class="modal-header">

<h4>

Bulk Edit Products

</h4>

</div>


<div class="modal-body">

<label>

Product Type

</label>

<select
name="product_type"
class="form-control">

<option value="Generic">

Generic

</option>

<option value="Originator Brand">

Originator Brand

</option>

<option value="Biosimilar">

Biosimilar

</option>

</select>


<label>

Strength

</label>

<input
name="strength"
class="form-control">


<label>

Price

</label>

<input
name="price"
class="form-control">


<label>

WAC

</label>

<input
name="wac"
class="form-control">


<label>

ATC Code

</label>

<input
name="atc_code"
class="form-control">


</div>


<div class="modal-footer">

<button
class="btn btn-success">

Update

</button>

</div>

</div>

</form>

</div>

</div>
