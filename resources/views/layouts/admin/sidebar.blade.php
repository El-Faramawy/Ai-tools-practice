<?php $setting = App\Models\Setting::first(); ?>

    <!--APP-SIDEBAR-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{route('admin.home')}}">
            <img src="{{get_file($setting->logo)}}" class="header-brand-img desktop-logo" alt="logo">
            <img src="{{get_file($setting->logo)}}" class="header-brand-img toggle-logo" alt="logo">
            <img src="{{get_file($setting->logo)}}" class="header-brand-img light-logo" alt="logo">
            <img src="{{get_file($setting->logo)}}" class="header-brand-img light-logo1" alt="logo">
        </a><!-- LOGO -->
    </div>
    <ul class="side-menu">
        {{--        <li><h3>الرئيسية</h3></li>--}}
        <x-sidebar-link href="{{route('admin.home')}}">
            <i class="fe fe-home  side-menu__icon"></i>
            <span class="side-menu__label">الرئيسية</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('admins.index')}}">
            <i class="fe fe-user-check  side-menu__icon"></i>
            <span class="side-menu__label">المشرفين</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('users.index')}}">
            <i class="fe fe-users  side-menu__icon"></i>
            <span class="side-menu__label">المستخدمين</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('user_invite.index')}}">
            <i class="fe fe-users  side-menu__icon"></i>
            <span class="side-menu__label">دعوة المستخدمين</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('admin_customers.index')}}">
            <i class="fe fe-users  side-menu__icon"></i>
            <span class="side-menu__label">مستخدمين سلة</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('brands.index')}}">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"
                 class="side-menu__icon">
                <path d="M0 0h24v24H0V0z" fill="none"/>
                <path d="M6.26 9L12 13.47 17.74 9 12 4.53z" opacity=".3"/>
                <path
                    d="M19.37 12.8l-7.38 5.74-7.37-5.73L3 14.07l9 7 9-7zM12 2L3 9l1.63 1.27L12 16l7.36-5.73L21 9l-9-7zm0 11.47L6.26 9 12 4.53 17.74 9 12 13.47z"/>
            </svg>
            <span class="side-menu__label">البراند</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('duets.index')}}">
            <i class="fe fe-share-2 side-menu__icon"></i>
            <span class="side-menu__label">الثنائيات</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('ads.index')}}">
            <i class="fe fe-image side-menu__icon"></i>
            <span class="side-menu__label">الاعلانات</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('categories.index')}}">
            <i class="fe fe-list side-menu__icon"></i>
            <span class="side-menu__label">الاقسام</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('countries.index')}}">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24" class="side-menu__icon">
                <path d="M0 0h24v24H0V0z" fill="none"></path>
                <path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"></path>
                <path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"></path>
            </svg>
            <span class="side-menu__label">البلدان</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('cities.index')}}">
            <i class="fe fe-map side-menu__icon"></i>
            <span class="side-menu__label">المدن</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('packages.index')}}">
            <i class="fe fe-package side-menu__icon"></i>
            <span class="side-menu__label">الباقات</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('brand_subscriptions.index')}}">
            <i class="fe fe-dollar-sign side-menu__icon"></i>
            <span class="side-menu__label">الاشتراكات</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('answers.index')}}">
            <i class="fe fe-info side-menu__icon"></i>
            <span class="side-menu__label">الاجابات</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{route('questions.index')}}">
            <i class="fe fe-help-circle side-menu__icon"></i>
            <span class="side-menu__label">الاسئلة</span>
        </x-sidebar-link>

{{--        <x-sidebar-link href="{{route('notifications.index')}}">--}}
{{--            <i class="fe fe-bell side-menu__icon"></i>--}}
{{--            <span class="side-menu__label">الاشعارات</span>--}}
{{--        </x-sidebar-link>--}}

        <x-sidebar-link href="{{route('settings.index')}}">
            <i class="fe fe-settings side-menu__icon"></i>
            <span class="side-menu__label">الاعدادات</span>
        </x-sidebar-link>

    </ul>
</aside>
<!--/APP-SIDEBAR-->
