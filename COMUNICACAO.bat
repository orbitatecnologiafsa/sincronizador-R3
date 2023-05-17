@echo off
:LOOP
C:\Orbita\sincronizador\php\php.exe C:\Orbita\sincronizador\source_code\artisan command:init
timeout /t 60 /nobreak
goto LOOP