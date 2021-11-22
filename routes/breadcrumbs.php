<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.

use App\Models\Companies;
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push(trans('messages.Home'), route('home'));
});

// Home > Blog
Breadcrumbs::for('phonebook', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(trans('messages.frontend_phonebook'), route('phonebook'));
});

// Home > Blog
Breadcrumbs::for('company', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(trans('messages.frontend_orgchart'), route('company'));
});

// Home > Blog > [Category]
Breadcrumbs::for('slug', function (BreadcrumbTrail $trail, $slug) {
    $name = Companies::getCompanyNameBySlug($slug);
    $trail->parent('phonebook');
    $trail->push($name, route('phonebook', $name));
});

Breadcrumbs::for('company.show', function (BreadcrumbTrail $trail, $slug) {
    $name = Companies::getCompanyNameBySlug($slug);
    $trail->parent('company');
    $trail->push($name, route('company.show', $name));
});
