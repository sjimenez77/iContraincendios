iContraincendios
================
---
Autor: Santos Jiménez Linares <sjimenez77@gmail.com>

Aplicación Web Silex para el cálculo de las necesidades de una instalación contraincendios.

Instalación
===========
---

Para instalarlo es necesario [composer](http://getcomposer.org/), crear una base de datos con el código SQL adjunto en el archivo src/fire.sql y modificar la configuración de acceso a la base de datos en el archivo src/app.php. A continuación ejecutar los siguientes comandos:

	composer self-update
	composer update

Utilización
===========
---
El manejo de esta aplicación es muy sencillo y se puede dividir en dos apartados. El primero, el acceso y registro en la plataforma y el segundo el manejo de la propia plataforma. Es importante indicar que la interfaz de usuario se verá de diferente manera automáticamente dependiendo del dispositivo desde donde se acceda. Es decir, mientras que un PC aparecerán todos los botones y menús, en un smartphone algunos aparecerán mediante desplegables fácilmente accesibles en la parte superior de las diferentes páginas o pantallas.

El registro se realiza pinchando o pulsando el botón "Registrar nuevo técnico" y rellenando correctamente los campos del formulario. En caso de que se detecte algún fallo el propio sistema indicará cúal es. Para cualquier duda o problema rellene los datos del formulario de contacto pulsando el botón "Contacto" y un técnico de la plataforma se pondrá en contacto con usted en la mayor brevedad posible.

Para acceder al sistema, una vez se haya registrado, debe pinchar sobre el botón, situado a la derecha en la barra superior verde, con forma de lápiz e introducir su usuario y contraseña. En caso de que todo haya funcionado bien el icono del botón cambiará a un engranaje, y al pulsarlo aparecerán las opciones del usuario conectado: ver su perfil y desconectarse de la plataforma. En caso de error, el icono no cambiará, pero al pinchar mostrará el mensaje de usuario y/o contraseña incorrectos.

Para el manejo de la plataforma simplemente ha de pinchar en el uso de la instalación y rellenar el formulario adjunto. Al enviarlo verá los resultados y, si está conectado, le permitirá archivarlo introduciendo unos datos referentes a dicho inmueble: dirección, código postal, población, etc. Los resultados se mustran siguiendo el siguiente patrón:

- Instalación necesaria: en azul con un icono de tick
- Instalación prescindible: en negro con un icono de cruz
 
Cuando el usuario desee consultar sus informes almacenados, puede también consultar la localización del inmueble relacionado en Google Maps pulsando el siguiente botón con la etiqueta "Ver localización en mapa".
 
