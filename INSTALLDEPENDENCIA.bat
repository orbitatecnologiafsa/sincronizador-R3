@echo off

cd C:\Orbita\sincronizador\source_code
C:\Orbita\sincronizador\php\php.exe C:\Orbita\sincronizador\composer\composer.phar install %*
C:\Orbita\sincronizador\php\php.exe  artisan vendor:publish --tag=laravel-assets --ansi --force
C:\Orbita\sincronizador\php\php.exe  artisan storage:link
C:\Orbita\sincronizador\php\php.exe -r "copy('C:\Orbita\sincronizador\cacert.pem','C:\Orbita\sincronizador\source_code\storage\app\cacert.pem');"
C:\Orbita\sincronizador\php\php.exe  -r "copy('.env.example', '.env');"
C:\Orbita\sincronizador\php\php.exe  artisan key:generate --ansi

pause