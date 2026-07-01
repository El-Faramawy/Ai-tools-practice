<?php

namespace App\Services\Admin;

use App\Models\Brand;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class BrandService
{
    /**
     * Get a Yajra DataTables instance scoped to the given country.
     *
     * @param int $countryId
     * @return mixed
     */
    public function getBrandsDataTable(int $countryId): mixed
    {
        $brands = Brand::where('country_id', $countryId)->latest();

        return DataTables::of($brands)
            ->addColumn('action', function (Brand $brand) {
                return tableAction($brand->uuid, true, true);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store a new brand assigned to the given country.
     *
     * @param array $data  Validated input (name)
     * @param int   $countryId
     * @return Brand
     */
    public function storeBrand(array $data, int $countryId): Brand
    {
        return Brand::create([
            'uuid'       => Str::uuid()->toString(),
            'name'       => $data['name'],
            'country_id' => $countryId,
        ]);
    }

    /**
     * Find a brand by UUID that belongs to the given country.
     * Returns 404 if UUID does not exist at all.
     * Aborts with 403 if UUID exists but belongs to a different country (CHK028 resolution).
     *
     * @param string $uuid
     * @param int    $countryId
     * @return Brand
     */
    public function findForCountry(string $uuid, int $countryId): Brand
    {
        // Step 1: Find by UUID — 404 if brand doesn't exist at all
        $brand = Brand::where('uuid', $uuid)->firstOrFail();

        // Step 2: Check country ownership — 403 if it belongs to a different country
        if ((int) $brand->country_id !== $countryId) {
            abort(403, 'غير مصرح لك بهذا الإجراء');
        }

        return $brand;
    }

    /**
     * Update a brand's name.
     *
     * @param Brand $brand
     * @param array $data  Validated input (name)
     * @return Brand
     */
    public function updateBrand(Brand $brand, array $data): Brand
    {
        $brand->update(['name' => $data['name']]);
        return $brand;
    }

    /**
     * Soft-delete a brand after verifying country ownership.
     *
     * @param string $uuid
     * @param int    $countryId
     * @return void
     */
    public function deleteBrand(string $uuid, int $countryId): void
    {
        $brand = $this->findForCountry($uuid, $countryId);
        $brand->delete();
    }
}
