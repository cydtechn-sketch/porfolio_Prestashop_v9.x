# Portfolio para PrestaShop 9

Adaptación del módulo **Portfolio** para utilizarlo en tiendas PrestaShop 9. Permite crear y mostrar entradas de portafolio desde el Back Office.

> Esta adaptación se probó en una tienda PrestaShop 9.2.0. La compatibilidad declarada por el módulo es PrestaShop 9.x; el funcionamiento puede variar según la versión, el tema y otros módulos instalados.

## Funciones

- Crear, editar, activar, desactivar y eliminar entradas de portafolio.
- Añadir título, subtítulo, descripción, características, enlace e imagen.
- Gestionar las entradas desde **Mejorar > Portfolio** en el Back Office.
- Mostrar el portafolio en la página pública `/portfolio`.
- Configurar el título de la página, el número de entradas por página y el orden.
- Usar varios idiomas. Si se deja vacío el título de un idioma, se utiliza el título ingresado en otro idioma.
- Conservar las entradas y las imágenes al desinstalar el módulo.

## Requisitos

- PrestaShop 9.x.
- PHP compatible con la versión de PrestaShop instalada.

## Instalación

1. Descarga el ZIP de la versión deseada desde **Releases** en GitHub.
2. En el Back Office, abre **Módulos > Gestor de módulos > Subir un módulo**.
3. Selecciona el ZIP y completa la instalación.
4. Abre **Mejorar > Portfolio** para administrar las entradas.

El ZIP de distribución debe contener una carpeta raíz llamada `portfolio/`, y dentro de ella el archivo `portfolio.php` y el resto de los archivos del módulo.

## Actualización

Sube el ZIP de la nueva versión desde el Gestor de módulos y aplica la actualización que indique PrestaShop. Las actualizaciones incluidas conservan las entradas y las imágenes existentes. Después de actualizar, limpia la caché si el menú o la página no reflejan los cambios.

## Uso

En **Mejorar > Portfolio**, crea una entrada y completa al menos un título. Los títulos que se dejen vacíos en los otros idiomas se completan automáticamente. Puedes añadir una imagen JPG, PNG o GIF y, opcionalmente, un enlace HTTP o HTTPS.

La página pública está disponible en:

```text
/portfolio
```

La disponibilidad y el estilo de la página pueden depender del tema activo y de la configuración de URLs amigables de PrestaShop.

## Datos al desinstalar

La desinstalación elimina la configuración del módulo y su acceso del menú del Back Office. Las tablas de portafolio y las imágenes se conservan para evitar la pérdida accidental de contenido.

## Reportar problemas y contribuir

Para reportar un problema, abre un **Issue** e incluye la versión de PrestaShop, PHP y del módulo, los pasos para reproducirlo y el mensaje de error relevante. No publiques contraseñas, tokens ni datos personales.

Las contribuciones mediante **Pull Request** son bienvenidas. Describe el cambio y las versiones en las que lo verificaste.

## Créditos y licencia

Esta adaptación conserva referencias a **FMM Modules**, autor indicado en los metadatos del módulo original. Antes de redistribuir públicamente el código, confirma que la licencia del módulo original permite su publicación y redistribución. Añade el archivo de licencia correspondiente a este repositorio; el encabezado de algunos archivos menciona `LICENSE.md`, pero ese archivo no está incluido actualmente.
