# AdBullion_WorkingTest

Crear una página de compra

## Frontend

La página debe mostrar 4 productos simultáneamente al cliente y los 4 productos deben tener precios diferentes.
El cliente debe tener la posibilidad de elegir 4 paises para el envio del producto, y el costo de envio es diferente en cada país.
Los productos deben estar almacenados en una tabla en base de datos con nombre, precio, descripción y nombre de la imagen a mostrar. Los 4 productos mostrados deben ser aleatorios de la base de datos (utilizar mínimo 6 productos).
Lo mismo para los países a mostrar, una tabla con nombre del país y precio de envio. Debe mostrar 4 países aleatorios en cada carga de la página (utilizar mínimo 6 paises).
La misma página debe contener un formulario donde se solicite:
* Nombre Completo
* Telefono
* Email
* Botón de compra
Dependiendo del producto que elija y el país de envió la página debe mostrar el total de la orden.

Al presionar el botón de comprar, este debe enviar el formulario al servidor via ajax y mostrar un pop-up con el resultado de la comprar. En caso de fallar o tener un dato equivocado debe resaltar los campos en rojo los campos faltantes o errados. En caso de éxito mostrar el order ID y un boton de aceptar que debe llevar a google.com
Al enviar el formulario el boton de comprar debe cambiar o desaparecer, impidiendo al cliente realizar otra compra por error mientras se realiza la transacción.
La pagina debe ser mobile friendly, usar HTML 5 e input validation. SOLO JavaScript y JQuery está permitido  

## Backend

Toda visita debe ser registrada y recarga a la página debe ser registrada, generar un ID de visita para poder relacionarlo con la orden. La tabla de visitas debe contar con visit ID, referal link y browser.
Los campos nombre, telefono y email deben poder ser autopopulados via GET, importante sanitar las entradas que se hagan via GET
Crear un sistema que reciba la solicitud del cliente, este debe estar hecho en clases. Una clase para base de datos, una para verificación de email, otra para el número de telefono, otra para los productos y una última para los paises. Se debe garantizar que no haya emails ni teléfonos duplicados en la DB y esten bien formados.
El archivo de transacciones debe prevenir cross domain.
Todas las entradas a la DB deben estar sanitadas.
Los datos de los clientes deben ser almacenados en una tabla y debe estar prevenir duplicidad de email y número telefónico.
La tabla de órdenes debe relacionar el ID del cliente, ID de país y ID de productos, ID de visita y generar un ID de orden aleatorio AlphaNumeric y prevenir duplicidad del mismo.
El sistema debe devolver los errores a la página principal, errores de duplicidad, entradas inválidas o éxito con ID de la orden.

# NO USAR NINGUN FRAMEWORK EN PHP

### Recomendaciones

Verificar acceso al WEB, FTP, DB y PHP
Crear una página agradable a la vista, que sea responsive usando CSS, código limpio, comentado y tab indentado.
DB usar innodb, utf8, nombres claros separados por underscore, usar Key y foreign Key.
Organice sus archivos en carpetas y defina carpetas de acuerdo a la funcionalidad.
Demuestre sus conocimientos, sea creativo y no se limite. Entorno de desarrollo
Se dará acceso a un servidor WEB vía FTP y MySQL.
El servidor cuenta con Nginx, PHP y MySQL. No hay interface para MySQL via WEB, pero puede ser administrado via workbench.

## Evaluacion

- 10 Estructura del código
- 10 Código orientado a objetos
- 10 Seguridad + Input sanitize
- 10 estrutura de la DB
- 10 CSS responsive
- 10 mobile friendly
- 10 HTML5 + input validation
- 10 JavaScript
- 10 Presentacion (Candy Eye)
- 10 Pagina operativa
