@if ($crud->hasAccess('browser_print'))
    <a href="{{ url($crud->route.'/'.$entry->getKey().'/browser-print') }}" target="print_frame" class="btn btn-sm btn-link" data-toggle="tooltip" title="Print">
        <i class="la la-print"></i> Print
    </a>
@endif
