<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ProductsExport implements FromCollection, WithHeadings, WithCustomCsvSettings
{
    public function collection()
    {
        return Product::all()->map(function ($item) {
            return [
                'Generic Name'               => $item->generic_name,
                'Brand Names'               => $item->brand_names,
				'Product Type'              => $item->product_type,
                'Form'                      => $item->form,
				'Catogory'                  => optional($item->category)->name ?? '',
                'Strength'                  => $item->strength,
                'Therapeutic Class'        => $item->therapeutic_class,
                'ATC Code'                  => $item->atc_code,
                'Manufacturer Name'         => $item->manufacturer_name,
                'Manufacturer Country'      => $item->manufacturer_country,
                'GMP Status'                => $item->gmp_status,
                'DMF Number'                => $item->dmf_number,
                'Cofepris Status'           => $item->cofepris_status,
                'Free Sale Certificate'     => $item->free_sale_certificate,
                'MOQ'                       => $item->moq,
                'Shelf Life'                => $item->shelf_life,
                'Storage'                   => $item->storage,
                'Incoterms'                 => $item->incoterms,
                'Lead Time'                 => $item->lead_time,
                'COA'                       => $item->coa,
                'SDS'                       => $item->sds,
                'CTD Summary'               => $item->ctd_summary,
                'RFQ'                       => $item->rfq,
                'Regulatory Advisor'        => $item->contact_regulatory_advisor,
                'Dossier'                   => $item->download_dossier,
                'Price'                     => number_format((float)$item->price, 2, '.', ','),
                'WAC'                       => number_format((float)$item->wac, 2, '.', ','),
                'Image'                     => $item->image ? asset('storage/' . $item->image) : '',
                'Special Notes'             => strip_tags($item->special_notes ?? ''),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Generic Name',
            'Brand Names',
			'Product Type' ,
            'Form',
			'Catogory',
            'Strength',
            'Therapeutic Class',
            'ATC Code',
            'Manufacturer Name',
            'Manufacturer Country',
            'GMP Status',
            'DMF Number',
            'COFEPRIS Status',
            'Free Sale Certificate',
            'MOQ',
            'Shelf Life',
            'Storage',
            'Incoterms',
            'Lead Time',
            'C.O.A.',
            /* 'GMP Certificate', */
            'MSDS / SDS',
            'CTD Summary',
            'RFQ Available',
            'Regulatory Advisor',
            'Dossier Available',
            'Price',
			'WAC',
			'Image',
            'Special Notes'
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'use_bom' => true,
            'input_encoding' => 'UTF-8',
        ];
    }
}
