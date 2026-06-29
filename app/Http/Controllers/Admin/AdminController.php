<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\Admin\UpdateAdminRequest;
use App\Http\Requests\Admin\Profile\UpdateProfileRequest;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    protected AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->adminService->getAdminsDataTable();
        }
        return view('Admin.Admin.index');
    }

    public function create()
    {
        return view('Admin.Admin.parts.create')->render();
    }

    public function store(StoreAdminRequest $request)
    {
        $this->adminService->storeAdmin($request->all());
        return response()->json(['message' => 'تم الاضافة بنجاح ']);
    }

    public function edit(Admin $admin)
    {
        return view('Admin.Admin.parts.edit', compact('admin'));
    }

    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $this->adminService->updateAdmin($admin, $request->all());
        return response()->json(['message' => 'تم التعديل بنجاح ']);
    }

    public function destroy(Admin $admin)
    {
        $this->adminService->deleteAdmin($admin);
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }

    public function multiDelete(Request $request)
    {
        $this->adminService->multiDelete($request->ids);
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }

    public function update_profile(UpdateProfileRequest $request)
    {
        if (isset($request->password) && $request->password != null) {
            $validator = Validator::make($request->all(), [
                'password' => 'required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'required'
            ],
                [
                    'password.required_with' => ' كلمة المرور مطلوبة',
                    'password.same' => 'كلمة المرور و تاكيد كلمة المرور غير متطابقين ',
                    'confirm_password.required' => 'تاكيد كلمة المرور مطلوب',
                ]
            );
            if ($validator->fails()) {
                return response()->json(['messages' => $validator->errors()->getMessages()], 422);
            }
        }

        $this->adminService->updateProfile($request->all());
        return response()->json(['message' => 'تم تعديل البيانات بنجاح']);
    }

    public function profile()
    {
        return view('Admin.Profile.index');
    }
}
