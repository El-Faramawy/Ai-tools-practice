<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class AdminService
{
    /**
     * Get admins data table excluding the currently authenticated admin.
     *
     * @return mixed
     */
    public function getAdminsDataTable()
    {
        $admins = Admin::latest()->where('id', '!=', admin()->user()->id);

        return Datatables::of($admins)
            ->addColumn('action', function ($admin) {
                return tableAction($admin->id, true, true);
            })
            ->addColumn('checkbox', function ($admin) {
                return '<input type="checkbox" class="sub_chk" data-id="' . $admin->id . '">';
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store a new admin.
     *
     * @param array $data
     * @return Admin
     */
    public function storeAdmin(array $data): Admin
    {
        $data['password'] = Hash::make($data['password']);
        return Admin::create($data);
    }

    /**
     * Update an existing admin.
     *
     * @param Admin $admin
     * @param array $data
     * @return bool
     */
    public function updateAdmin(Admin $admin, array $data): bool
    {
        if (isset($data['password']) && $data['password'] != null) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $admin->update($data);
    }

    /**
     * Delete an admin.
     *
     * @param Admin $admin
     * @return bool|null
     */
    public function deleteAdmin(Admin $admin): ?bool
    {
        return $admin->delete();
    }

    /**
     * Bulk delete admins by IDs.
     *
     * @param string $idsString
     * @return void
     */
    public function multiDelete(string $idsString): void
    {
        $ids = explode(",", $idsString);
        Admin::whereIn('id', $ids)->delete();
    }

    /**
     * Update the authenticated admin's profile.
     *
     * @param array $data
     * @return bool
     */
    public function updateProfile(array $data): bool
    {
        $admin = Admin::find(admin()->id());
        $admin->name = $data['name'];
        $admin->email = $data['email'];

        if (isset($data['password']) && $data['password'] != '') {
            $admin->password = Hash::make($data['password']);
        }

        return $admin->save();
    }
}
