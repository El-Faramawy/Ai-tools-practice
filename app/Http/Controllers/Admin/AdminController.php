<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\Admin\UpdateAdminRequest;
use App\Http\Requests\Admin\Profile\UpdateProfileRequest;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $admins = Admin::latest()->where('id', '!=', admin()->user()->id);
            return Datatables::of($admins)
                ->addColumn('action', function ($admin) {
                    return tableAction($admin->id, true,true);
                })->addColumn('checkbox', function ($admin) {
                    return '<input type="checkbox" class="sub_chk" data-id="' . $admin->id . '">';
                })
                ->escapeColumns([])
                ->make(true);
        }
        return view('Admin.Admin.index');
    }

    public function create()
    {
        return view('Admin.Admin.parts.create')->render();
    }

    public function store(StoreAdminRequest $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        Admin::create($data);
        return response()->json(['message' => 'تم الاضافة بنجاح ']);
    }

    public function edit(Admin $admin)
    {
        return view('Admin.Admin.parts.edit', compact('admin'));
    }

    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $data = $request->except('password');

        if ($request->password != null)
            $data['password'] = Hash::make($request->password);

        $admin->update($data);

        return response()->json(['message' => 'تم التعديل بنجاح ']);
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }

    public function multiDelete(Request $request)
    {
        $ids = explode(",", $request->ids);
        Admin::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'تم الحذف بنجاح']);
    }

    public function update_profile(UpdateProfileRequest $request)
    {
        if (isset($request->password) && $request->password != null) {
            $valedator = Validator::make($request->all(), [
                'password' => 'required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'required'
            ],
                [
                    'password.required_with' => ' كلمة المرور مطلوبة',
                    'password.same' => 'كلمة المرور و تاكيد كلمة المرور غير متطابقين ',
                    'confirm_password.required' => 'تاكيد كلمة المرور مطلوب',
                ]
            );
            if ($valedator->fails())
                return response()->json(['messages' => $valedator->errors()->getMessages()], 422);
        }
        $update = Admin::find(\admin()->id());
        $update->name = $request->name;
        $update->email = $request->email;
        if (isset($request->password) && $request->password != '') {
            $update->password = Hash::make($request->password);
        }
        $update->save();
        return response()->json(['message' => 'تم تعديل البيانات بنجاح']);
    }

    public function profile()
    {
        return view('Admin.Profile.index');
    }


}
