# Timebound OS

Timebound OS is an open source time tracking tool written in PHP. It is based from a popular SAAS time tracking tool. 

Coded with love by [Koodi](http://koodi.ph).

This project uses the following:

* [Laravel framework 5.2](http://laravel.com/docs)
* Vuejs
* Webpack
* Bootstrap 3

## Requirements

* PHP 5.6 or higher. Tested using PHP 5.6 and PHP 7.1
* Mysql. Tested using Mysql 5.7

## Installation

* `$ git clone https://github.com/koodiph/timebound-os.git`
* Create `.env` file.
* Copy the content of `.env.example` to `.env`
* Put your database credentials in `.env` and STMP settings
* `$ composer install`
* `$ php artisan key:generate`
* `$ php artisan module:migrate`
* `$ php artisan module:seed`
* Make sure the following directories exist and make them writable

`public/images/profile`
`public/download`

## Installation - Dev

To work on javascript, you need npm and webpack

* `$ npm install`


## Documentation

Documentation to follow

## Contributing

All contributors are welcome! Contributing guide to follow.

## Security Vulnerabilities

If you discover a security vulnerability within the app, please send an e-mail to the author. All security vulnerabilities will be promptly addressed.

## License

This app is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
