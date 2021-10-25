<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#description">Description</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#requirements">Requirements</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#use">Use</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## Acerca del proyecto

Paquete composer Laravel Passport para starter de Visyon360.

### Built With
* [Laravel Passport](https://laravel.com/passport)
* [Laravel Modules](https://github.com/nWidart/laravel-modules)


## Getting Started

### Requirements

Composer 2.0 será necesario para la correcta instalación de este paquete

### Installation
1. Instalar paquete en proyecto Laravel con composer
   ```sh
   composer require visyon360/authpassport-module
   ```
2. Una vez instalado el paquete debemos incluir el trait HasApiToken en nuestro User model
   ```sh
   use Laravel\Passport\HasApiTokens;
   ```
3. El paquete dispone de un instalador para ahorrarnos ejecutar los comandos necesarios. En principio el instalador
   es ejecutado en el instalador del modulo, pero si por algun motivo no llega a ejecutarse, corremos el siguiente
   comando:
   ```sh
   php artisan module-passport:install
   ```
3. Finalmente, para que la aplicación utilice este módulo se debe de cambiar el driver de autenticación en la opción
   de configuración `guard`

    ```
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport', // Cambiamos esta opción.
            'provider' => 'users',
        ],
    ],
    ```

4. Corremos migraciones con el comando artisan (opcional)
   ```sh
   php artisan migrate
   ```
5. Para comprobar que el paquete se ha instalado correctamente podemos correr el comando artisan test
    ```sh
   php artisan test
   ```



<!-- USAGE EXAMPLES -->
## Use
Para más información puedes acceder a la documentación oficial de Laravel Passport [Documentation](https://laravel.com/docs/passport)

<!-- CONTACT -->
## Contact

Alejandro Páez Espinosa - apaez@kiteris.com

Project Link: [https://github.com/visyon360/authpassport-module](https://github.com/visyon360/authpassport-module)
