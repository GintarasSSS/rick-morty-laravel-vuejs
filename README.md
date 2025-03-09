<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Application Boot

```
cd rick-morty-laravel-vuejs
```
```
cp .env.example .env
```
```
docker-compose up -d
```
```
docker-compose exec app composer install
```
```
docker-compose exec app php artisan key:generate
```
```
docker-compose exec app php artisan migrate
```
## Testing
```
docker-compose exec -it app bash
```
```
XDEBUG_MODE=coverage php artisan test --coverage
```
![img_1.png](img_1.png)
