# Laravel Commentable
Allows for threaded comments to be added to multiple and different models within your app for Laravel 4 and 5.

For Laravel 4, look at the [1.0 branch](https://github.com/RomainLanz/laravel-commentable/tree/1.0).

This package use Nested Sets pattern with [Baum](https://github.com/etrepat/baum).

[More information about Nested Sets](http://en.wikipedia.org/wiki/Nested_set_model)

# Installation
Edit your project's composer.json file to require `lanz/laravel-commentable`.
````
"require": {
  "lanz/laravel-commentable": "~2.0"
}
````

Next, update Composer from the terminal.
````
composer update
````

As with most Laravel packages you'll need to register Commentable *service provider*. In your `config/app.php` add `'Lanz\Commentable\CommentableServiceProvider'` to the end of the `$providers` array.
````php
'providers' => [

    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    ...
    'Lanz\Commentable\CommentableServiceProvider',

],
````

# Getting started
After the package is correctly installed, you need to generate migration.
````
php artisan commentable:migration
````

It will generate the `<timestamp>_create_comments_table.php` migration. You may now run it with the artisan migrate command:
````
php artisan migrate
````

After the migration, one new table will be present, `comments`.

# Usage
You need to set on your model that it acts as commentable.
````php
<?php namespace App;

use Lanz\Commentable\Commentable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    use Commentable;

}
````

Now, your model has access to `comments` method.
````php
$post = Post::first();

$comment = new Lanz\Commentable\Comment;
$comment->body = 'My first comment!';
$comment->user_id = \Auth::id();

$post->comments()->save($comment);

dd(Post::first()->comments);
````

For all information about threaded comment, look at the [documentation on Baum](https://github.com/etrepat/baum#usage).



