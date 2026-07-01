<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Brand\StoreBrandRequest;
use App\Http\Requests\Admin\Brand\UpdateBrandRequest;
use App\Services\Admin\BrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct(protected BrandService $brandService)
    {
    }

    /**
     * Display the country-scoped brands DataTable.
     */
    public function index(Request $request): mixed
    {
        $countryId = $this->getCountryId();

        if ($request->ajax()) {
            return $this->brandService->getBrandsDataTable($countryId);
        }

        return view('Admin.Brand.index');
    }

    /**
     * Return the create brand form partial.
     */
    public function create(): string
    {
        $this->getCountryId();

        return view('Admin.Brand.parts.create')->render();
    }

    /**
     * Store a new brand assigned to the authenticated admin's country.
     */
    public function store(StoreBrandRequest $request): JsonResponse
    {
        $countryId = $this->getCountryId();
        $this->brandService->storeBrand($request->validated(), $countryId);

        return response()->json(['message' => 'تم الاضافة بنجاح']);
    }

    /**
     * Return the edit brand form partial pre-populated with current values.
     */
    public function edit(string $uuid): string
    {
        $countryId = $this->getCountryId();
        $brand     = $this->brandService->findForCountry($uuid, $countryId);

        return view('Admin.Brand.parts.edit', compact('brand'))->render();
    }

    /**
     * Update the brand's name after verifying country ownership.
     */
    public function update(UpdateBrandRequest $request, string $uuid): JsonResponse
    {
        $countryId = $this->getCountryId();
        $brand     = $this->brandService->findForCountry($uuid, $countryId);
        $this->brandService->updateBrand($brand, $request->validated());

        return response()->json(['message' => 'تم التعديل بنجاح']);
    }

    /**
     * Soft-delete a brand after verifying country ownership.
     */
    public function destroy(string $uuid): JsonResponse
    {
        $countryId = $this->getCountryId();
        $this->brandService->deleteBrand($uuid, $countryId);

        return response()->json(['message' => 'تم الحذف بنجاح']);
    }

    /**
     * Retrieve the authenticated admin's country_id.
     * Aborts with 403 if the admin has no country assigned.
     *
     * @return int
     */
    private function getCountryId(): int
    {
        $countryId = admin()->user()->country_id;

        if (is_null($countryId)) {
            abort(403, 'غير مصرح لك بهذا الإجراء');
        }

        return (int) $countryId;
    }
}
