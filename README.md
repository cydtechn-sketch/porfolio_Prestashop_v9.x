# Portfolio para PrestaShop 9 — v5.1.3

Módulo de portafolio adaptado para PrestaShop 9.x y PHP 8.1+. Conserva las tablas
`portfolio` y `portfolio_lang`, así como las imágenes existentes en `/img/portfolio/`.

## Instalación o actualización

1. En el Back Office, abre **Módulos > Gestor de módulos > Subir un módulo**.
2. Sube `portfolio-ps9-v5.1.3.zip`.
3. Si la tienda tiene instalada la versión 5.0.0 o 5.1.0, usa la actualización cuando PrestaShop lo indique.
   No es necesario desinstalar la versión anterior; la actualización conserva los registros y las imágenes.
4. Vacía la caché de PrestaShop si la página o el menú no se actualizan inmediatamente.

La página pública se sirve en `/portfolio`. La gestión está disponible desde **Mejorar > Portfolio**.

La desinstalación conserva las tablas y las imágenes. La actualización 5.1.3 asegura la entrada del
menú **Portfolio** y limpia los hooks que usaban las adaptaciones anteriores.
