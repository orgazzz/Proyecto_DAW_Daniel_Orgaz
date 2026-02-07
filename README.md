# Proyecto_DAW_Daniel_Orgaz

Proyecto web básico para demostrar:
- Control de versiones con Git/GitHub (ramas, commits, merge).
- Gestión colaborativa con Issues y Project.
- Integración conceptual con LDAP (OpenLDAP).
- Documentación PHP con phpDocumentor.

## Estructura
- index.html
- css/styles.css
- js/app.js
- php/conexion.php
- docs/ (documentación generada)

## Ejecución
Abrir `index.html` en el navegador.

## Autenticación LDAP (conceptual)

La aplicación podría autenticarse contra el directorio OpenLDAP siguiendo este flujo:

1. El usuario introduce `uid` y contraseña en un formulario.
2. El servidor PHP busca el DN del usuario en LDAP usando un filtro, por ejemplo:
   - `(uid=tuusuario)`
3. Si existe, se intenta un `bind` LDAP con:
   - DN encontrado + contraseña introducida.
4. Si el `bind` es correcto, se considera autenticado.
5. (Opcional) Se consulta pertenencia a grupos (`developers`, `designers`, `managers`)
   para autorizar funcionalidades según rol (RBAC).

El archivo `php/conexion.php` incluye una implementación conceptual del proceso.
