<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('setting') }}'><i class='nav-icon la la-cog'></i> <span>{{ trans('messages.settings') }}</span></a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('companies') }}'><i class='nav-icon la la-building'></i> {{ trans('messages.companies') }}</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('departments') }}'><i class='nav-icon la la-sitemap'></i> {{ trans('messages.departments') }}</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('contacts') }}'><i class='nav-icon la la-address-book'></i> {{ trans('messages.contacts_index_heading') }}</a></li>
