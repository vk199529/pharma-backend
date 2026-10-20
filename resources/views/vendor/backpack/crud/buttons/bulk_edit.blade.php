<div id="bulk-error"
     class="alert alert-warning mt-2"
     style="display:none;max-width:300px;">
    Please select at least one product.
</div>

<a href="javascript:void(0)"
   class="btn btn-primary"
   id="bulk-edit-btn"
   data-url="{{ backpack_url('product/bulk-edit') }}">

    <i class="la la-edit"></i>
    <span id="bulk-count">
        Bulk Edit Selected (0)
    </span>

</a>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const selectAll = document.getElementById('select-all');

    const bulkBtn = document.getElementById('bulk-edit-btn');

    const bulkCount = document.getElementById('bulk-count');

    const errorBox = document.getElementById('bulk-error');


    // Update count
    function updateCount() {

        let count = document.querySelectorAll('.bulk-checkbox:checked').length;

        bulkCount.innerHTML =
        'Bulk Edit Selected (' + count + ')';

    }


    // Select all
    if(selectAll){

        selectAll.addEventListener('click', function () {

            document.querySelectorAll('.bulk-checkbox')
            .forEach(function (checkbox) {

                checkbox.checked = selectAll.checked;

            });

            errorBox.style.display = 'none';

            updateCount();

        });

    }



    // Individual checkbox change
    document.addEventListener('change', function(e){

        if(e.target.classList.contains('bulk-checkbox')){

            let total =
            document.querySelectorAll('.bulk-checkbox').length;

            let checked =
            document.querySelectorAll('.bulk-checkbox:checked').length;


            if(selectAll){

                selectAll.checked = (total === checked);

            }


            errorBox.style.display = 'none';

            updateCount();

        }

    });




    // Bulk edit button click

    bulkBtn.addEventListener('click', function () {

        let ids = [];

        document.querySelectorAll('.bulk-checkbox:checked')
        .forEach(function(item){

            ids.push(item.value);

        });


        if(ids.length === 0){

            errorBox.style.display = 'block';

            return;

        }


        errorBox.style.display = 'none';


        let url = this.dataset.url;

        window.location.href =
        url + '?ids=' + ids.join(',');

    });


    updateCount();

});

</script>