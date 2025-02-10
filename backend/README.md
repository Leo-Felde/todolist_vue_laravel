<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Projeto Todo List com Vue.js 3, Quasar e PHP Laravel
Back-end do projeto

## Requisitos
- [Node.js](https://nodejs.org/en/download)
- [Composer](https://getcomposer.org/)
- [PostgreSQL](https://www.postgresql.org/)
- Servidor PHP (E.g [Apache](https://httpd.apache.org/), [Nginx](https://nginx.org/) ou [Xampp](https://www.apachefriends.org/pt_br/index.html))

### Instalar dependências

   ```bash
npm install
composer install
   ```
   
### Configurar o ambiente
Crie uma cópia do arquivo `.env.example` e renomeie-o para `.env` **(sem .example)** <br>
Altere os valores conforme a configuração de seu servidor
   
### Criar tabelas no banco de dados
```bash
php artisan migrate  
```

### Rodar os testes
```bash
php artisan test  
```

### Rodar servidor de Desenvolvimento

```
php artisan serve
```
