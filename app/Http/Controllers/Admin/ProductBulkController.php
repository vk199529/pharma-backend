<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProductBulkController extends Controller
{
    public function index(Request $request)
    {
        $ids = array_filter(
            array_map(
                'intval',
                explode(',', (string) $request->query('ids', ''))
            )
        );

        if (empty($ids)) {
            return redirect()
                ->back()
                ->with('error', 'Please select at least one product.');
        }

        $products = Product::whereIn('id', $ids)->get();

        return view('admin.product_bulk', compact('products'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => ['required', 'array', 'min:1'],
            'products.*' => ['required', 'array'],
            'products.*.brand_names' => ['nullable', 'string'],
            'products.*.product_type' => ['nullable', 'string'],
            'products.*.strength' => ['nullable', 'string'],
            'products.*.therapeutic_class' => ['nullable', 'string'],
            'products.*.price' => ['nullable', 'numeric'],
            'products.*.wac' => ['nullable', 'numeric'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $fields = [
            'brand_names',
            'product_type',
            'strength',
            'therapeutic_class',
            'price',
            'wac',
        ];

        $productsData = $validator->validated()['products'];
        $updated = 0;

        try {
            DB::transaction(function () use (
                $productsData,
                $fields,
                &$updated
            ) {
                foreach ($productsData as $id => $data) {
                    $product = Product::find($id);

                    if (!$product) {
                        continue;
                    }

                    foreach ($fields as $field) {
                        // Skip fields not submitted by the form.
                        if (!array_key_exists($field, $data)) {
                            continue;
                        }

                        $value = $data[$field];

                        // These fields are configured as translatable
                        // in the Product model.
                        $translatableFields = [
                            'brand_names',
                            'product_type',
                            'therapeutic_class',
                        ];

                        if (in_array($field, $translatableFields, true)) {
                            if ($value === null) {
                                $product->setTranslations($field, []);
                            } else {
                                $product->setTranslation(
                                    $field,
                                    'en',
                                    $value
                                );
                            }
                        } else {
                            $product->{$field} = $value;
                        }
                    }

                    $product->save();
                    $updated++;
                }
            });
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Products could not be updated. Please check the application logs.'
                );
        }

        if ($updated === 0) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'No products were updated. Please check the selected product IDs.'
                );
        }

        \Alert::success(
            "{$updated} product(s) updated successfully."
        )->flash();

        return redirect()->back();
    }
}