<?php

if (!function_exists('admin')) {
    function admin() {
        return auth()->guard('admin');
    }
}

if (!function_exists('setting')) {
    function setting() {
        return \App\Models\Setting::first() ?? new \App\Models\Setting([
            'name' => 'لوحة التحكم',
            'logo' => 'assets/logo.png',
            'fav_icon' => 'assets/fav_icon.png'
        ]);
    }
}

if (!function_exists('get_file')) {
    function get_file($file) {
        if ($file && file_exists(public_path($file))) {
            return asset($file);
        }
        if ($file) {
            return asset($file);
        }
        return asset('assets/default.png');
    }
}

if (!function_exists('tableAction')) {
    function tableAction($id, $edit = true, $delete = true) {
        $html = '';
        if ($edit) {
            $html .= '<button type="button" id="editBtn" data-id="' . $id . '" class="btn btn-sm btn-info text-white me-1" title="تعديل"><i class="fe fe-edit"></i></button>';
        }
        if ($delete) {
            $html .= '<button type="button" data-id="' . $id . '" class="btn btn-sm btn-danger text-white delete" title="حذف"><i class="fe fe-trash-2"></i></button>';
        }
        return $html;
    }
}
