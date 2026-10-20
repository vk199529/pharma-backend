{{-- Dashboard --}}
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>

{{-- Catalog Dropdown --}}
<x-backpack::menu-dropdown title="Catalog" icon="la la-box">
    <x-backpack::menu-dropdown-item title="Products" :link="backpack_url('product')" />
    <x-backpack::menu-dropdown-item title="Categories" :link="backpack_url('categories')" />
    <x-backpack::menu-dropdown-item title="RFQs" :link="backpack_url('rfq')" />
</x-backpack::menu-dropdown>

{{-- FAQs Dropdown --}}
<x-backpack::menu-dropdown title="FAQs" icon="la la-question-circle">
    <x-backpack::menu-dropdown-item title="Custom Query" :link="backpack_url('faq')" />
    <x-backpack::menu-dropdown-item title="Shipping Query" :link="backpack_url('shipping-faq')" />
    <x-backpack::menu-dropdown-item title="Company Query" :link="backpack_url('company-faq')" />
</x-backpack::menu-dropdown>
