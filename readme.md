# Testback

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![The Whole Fruit Manifesto](https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen)](https://github.com/the-whole-fruit/manifesto)


This package provides articles functionality for projects that use the [Backpack for Laravel](https://backpackforlaravel.com/) administration panel. 

More exactly, it adds Articles and Categories so that you can easily do content managing.



![Backpack Toggle Field Addon](https://via.placeholder.com/600x250?text=screenshot+needed)


## Installation

Via Composer

``` bash
composer require rainbow1997/testback --prefer-source
php artisan migrate
```

## Usage

> Notice: You have to follow these operations:

After installation you should add these lines into your resources/vendor/backpack/base/inc/sidebar_content.blade.php file. 
If you don't have it you should copy it 
from vendor/backpack/crud/src/resources/views/base/inc/sidebar_content like:
```bash
cd /path/to/your/laravel
cp ./vendor/backpack/crud/src/resources/views/base/inc/sidebar.blade.php ./resources/views/vendor/backpack/base/inc/sidebar_content.blade.php
```
codes you should add :
```html
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('article') }}"><i class="nav-icon la la-magic"></i> Articles</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url('category') }}"><i class="nav-icon la la-magic"></i> Categories</a></li>

```
After install the package you can show your managing part in backpack admin panel.



## Overwriting


If you need to change the field in any way, you can easily publish the file to your app, and modify that file any way you want. But please keep in mind that you will not be getting any updates.

**Step 1.** Copy-paste the blade file to your directory:
```bash
# create the fields directory if it's not already there
mkdir -p resources/views/vendor/backpack/crud/fields

# copy the blade file inside the folder we created above
cp -i vendor/rainbow1997/testback/src/resources/views/fields/field_name.blade.php resources/views/vendor/backpack/crud/fields/field_name.blade.php
```

**Step 2.** Remove the vendor namespace wherever you've used the field:
```diff
$this->crud->addField([
    'name' => 'agreed',
    'type' => 'toggle',
    'label' => 'I agree to the terms and conditions',
-   'view_namespace' => 'rainbow1997.testback::fields'
]);
```

**Step 3.** Uninstall this package. Since it only provides one file, and you're no longer using that file, it makes no sense to have the package installed:
```bash
composer remove rainbow1997/testback
```

## Change log

Changes are documented here on Github. Please see the [Releases tab](https://github.com/rainbow1997/testback/releases).

## Testing

``` bash
composer test
```

## Contributing

Please see [contributing.md](contributing.md) for a todolist and howtos.

## Security

If you discover any security related issues, please email m.jamali@dornica.net instead of using the issue tracker.

## Credits

- [Mostafa Jamali][link-author]
- [All Contributors][link-contributors]

## License

This project was released under MIT, so you can install it on top of any Backpack & Laravel project. Please see the [license file](license.md) for more information. 

However, please note that you do need Backpack installed, so you need to also abide by its [YUMMY License](https://github.com/Laravel-Backpack/CRUD/blob/master/LICENSE.md). That means in production you'll need a Backpack license code. You can get a free one for non-commercial use (or a paid one for commercial use) on [backpackforlaravel.com](https://backpackforlaravel.com).


[ico-version]: https://img.shields.io/packagist/v/rainbow1997/testback.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rainbow1997/testback.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rainbow1997/testback
[link-downloads]: https://packagist.org/packages/rainbow1997/testback
[link-author]: https://github.com/rainbow1997
[link-contributors]: ../../contributors
