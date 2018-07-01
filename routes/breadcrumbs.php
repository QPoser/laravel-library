<?php

use App\Entities\Library\Book;
use App\Entities\Library\Book\Author;
use App\Entities\Library\Book\Bundle;
use App\Entities\Library\Book\Genre;
use App\User;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::register('home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Cabinet', route('home'));
});

Breadcrumbs::register('library.books.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Home', route('library.books.home'));
});

// Admin

Breadcrumbs::register('admin.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('library.books.home');
    $crumbs->push('Admin', route('admin.home'));
});

// Admin books

Breadcrumbs::register('admin.books.index', function (BreadcrumbsGenerator $crumbs) {
   $crumbs->parent('admin.home');
   $crumbs->push('Books', route('admin.books.index'));
});

Breadcrumbs::register('admin.books.show', function (BreadcrumbsGenerator $crumbs, Book $book) {
    $crumbs->parent('admin.books.index');
    $crumbs->push($book->title, route('admin.books.show', $book));
});

Breadcrumbs::register('admin.books.edit', function (BreadcrumbsGenerator $crumbs, Book $book) {
    $crumbs->parent('admin.books.show', $book);
    $crumbs->push('Edit', route('admin.books.edit', $book));
});

Breadcrumbs::register('admin.books.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.books.index');
    $crumbs->push('Create book', route('admin.books.create'));
});

// Admin genres

Breadcrumbs::register('admin.genres.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Genres', route('admin.genres.index'));
});

Breadcrumbs::register('admin.genres.show', function (BreadcrumbsGenerator $crumbs, Genre $genre) {
    $crumbs->parent('admin.genres.index');
    $crumbs->push($genre->name, route('admin.genres.show', $genre));
});

Breadcrumbs::register('admin.genres.edit', function (BreadcrumbsGenerator $crumbs, Genre $genre) {
    $crumbs->parent('admin.genres.show', $genre);
    $crumbs->push('Edit', route('admin.genres.edit', $genre));
});

Breadcrumbs::register('admin.genres.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.genres.index');
    $crumbs->push('Create genre', route('admin.genres.create'));
});

// Admin Authors

Breadcrumbs::register('admin.authors.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Authors', route('admin.authors.index'));
});

Breadcrumbs::register('admin.authors.show', function (BreadcrumbsGenerator $crumbs, Author $author) {
    $crumbs->parent('admin.authors.index');
    $crumbs->push($author->name, route('admin.authors.show', $author));
});

Breadcrumbs::register('admin.authors.edit', function (BreadcrumbsGenerator $crumbs, Author $author) {
    $crumbs->parent('admin.authors.show', $author);
    $crumbs->push('Edit', route('admin.authors.edit', $author));
});

Breadcrumbs::register('admin.authors.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.authors.index');
    $crumbs->push('Create author', route('admin.authors.create'));
});

// Admin users

Breadcrumbs::register('admin.users.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Users', route('admin.users.index'));
});

Breadcrumbs::register('admin.users.show', function (BreadcrumbsGenerator $crumbs, User $user) {
    $crumbs->parent('admin.users.index');
    $crumbs->push('Show ' . $user->id, route('admin.users.show', $user));
});

Breadcrumbs::register('admin.users.edit', function (BreadcrumbsGenerator $crumbs, User $user) {
    $crumbs->parent('admin.users.show', $user);
    $crumbs->push('Edit', route('admin.users.edit', $user));
});

Breadcrumbs::register('admin.users.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.users.index');
    $crumbs->push('Create user', route('admin.users.create'));
});

// Auth

Breadcrumbs::register('login', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Login', route('login'));
});

Breadcrumbs::register('register', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Register', route('register'));
});

Breadcrumbs::register('password.request', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Reset password', route('password.request'));
});

Breadcrumbs::register('password.reset', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Reset password', route('password.reset'));
});

// Cabinet

Breadcrumbs::register('cabinet.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Cabinet', route('cabinet.home'));
});

// Books

Breadcrumbs::register('cabinet.books.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Books', route('cabinet.books.home'));
});

Breadcrumbs::register('cabinet.books.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.books.home');
    $crumbs->push('Create book', route('cabinet.books.create'));
});

Breadcrumbs::register('cabinet.books.show', function (BreadcrumbsGenerator $crumbs, Book $book) {
    $crumbs->parent('cabinet.books.home');
    $crumbs->push($book->title, route('cabinet.books.show', $book));
});

Breadcrumbs::register('cabinet.books.edit', function (BreadcrumbsGenerator $crumbs, Book $book) {
    $crumbs->parent('cabinet.books.show', $book);
    $crumbs->push('Edit', route('cabinet.books.edit', $book));
});

// Bundles

Breadcrumbs::register('cabinet.bundles.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Bundles', route('cabinet.bundles.home'));
});

Breadcrumbs::register('cabinet.bundles.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.bundles.home');
    $crumbs->push('Create bundle', route('cabinet.bundles.create'));
});

Breadcrumbs::register('cabinet.bundles.show', function (BreadcrumbsGenerator $crumbs, Bundle $bundle) {
    $crumbs->parent('cabinet.bundles.home');
    $crumbs->push($bundle->title, route('cabinet.bundles.show', $bundle));
});

// Profile

Breadcrumbs::register('cabinet.profile.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Profile', route('cabinet.profile.home'));
});

Breadcrumbs::register('cabinet.profile.edit', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.profile.home');
    $crumbs->push('Edit profile', route('cabinet.profile.edit'));
});

// Library


Breadcrumbs::register('library.books.show', function (BreadcrumbsGenerator $crumbs, Book $book) {
    $crumbs->parent('library.books.home');
    $crumbs->push($book->title, route('library.books.show', $book));
});

Breadcrumbs::register('library.bundles.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Bundles', route('library.bundles.home'));
});

Breadcrumbs::register('library.bundles.show', function (BreadcrumbsGenerator $crumbs, Bundle $bundle) {
    $crumbs->parent('library.bundles.home');
    $crumbs->push($bundle->title, route('library.bundles.show', $bundle));
});

Breadcrumbs::register('library.users.show', function (BreadcrumbsGenerator $crumbs, User $user) {
    $crumbs->parent('library.books.home');
    $crumbs->push($user->name, route('library.users.show', $user));
});





