


Dim wshShell
Set wshShell = CreateObject("WScript.Shell")
wshShell.Run "C:\Orbita\sincronizador\SERVER.bat", 0, false
wshShell.Run "C:\Orbita\sincronizador\COMUNICACAO.bat", 0, false
