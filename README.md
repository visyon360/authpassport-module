<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#acerca-del-proyecto">Descripcion</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#uso">Uso</a></li>
    <li><a href="#contacto">Contacto</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## Acerca del proyecto

Paquete composer Auth-passport para starter de Visyon360.

### Built With
* [Laravel](https://laravel.com)



<!-- GETTING STARTED -->
## Getting Started

### Prerequisitos

Composer 2.0 será necesario para la correcta instalación de este paquete

### Installation
1. Instalar paquete en proyecto Laravel con composer
   ```sh
   composer require visyon360/authpassport-module
   ```
2. Una vez instalado el paquete debemos incluir y llamar a la clase HasApiToken en nuestro User model
   ```sh
   use Laravel\Passport\HasApiTokens;
   ```
3. Corremos migraciones con el comando artisan
   ```sh
   php artisan migrate
   ``` 
4. Para crear las claves de encriptación necesarias para generar tokens de acceso seguros ejecutar:
   ```sh
   php artisan passport:install
   ```
5. Para comprobar que el paquete se ha instalado correctamente podemos correr el comando artisan test
    ```sh
   php artisan test
   ``` 



<!-- USAGE EXAMPLES -->
## Uso
Para más información puedes acceder a la documentación oficial de Laravel passport [Documentation](https://laravel.com/docs/8.x/passport)

<!-- CONTACT -->
## Contacto

Dani Carvajal - dcarvajal@kiteris.com

Project Link: [https://github.com/visyon360/authpassport-module](https://github.com/visyon360/authpassport-module)
