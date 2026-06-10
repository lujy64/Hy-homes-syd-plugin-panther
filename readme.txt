=== HY Homes Syd Panther Landing ===
Contributors: The Panther Soft - Vaira Maria Lujan
Tags: real estate, landing, elementor, wpbakery, search
Requires at least: 5.8
Tested up to: 6.5
Stable tag: 1.1.30
License: GPLv2 or later

Plugin para crear una landing inmobiliaria de HY Homes Syd con buscador, resultados filtrados, fichas de propiedades, carruseles, banners por localidad, administracion de propiedades y carga masiva desde planillas.

== Descripcion ==

Desarrollado por The Panther Soft - Vaira Maria Lujan.

HY Homes Syd Panther Landing agrega elementos reutilizables para WordPress, Elementor y WPBakery. La idea es poder armar paginas con shortcodes y seguir agregando contenido debajo o alrededor sin depender de una plantilla cerrada.

El plugin incluye:

* Buscador de propiedades con filtros.
* Pagina de resultados filtrados.
* Carrusel de propiedades recientes.
* Ficha individual de propiedad.
* Propiedades relacionadas por localidad.
* Banners por localidad.
* Carrusel aleatorio de banners.
* Carrusel de localidades.
* Boton flotante de WhatsApp con seleccion de agentes.
* Modal de WhatsApp reutilizable para botones externos.
* Panel de administracion para propiedades, localidades, banners, WhatsApp e importaciones.
* Importacion desde CSV, XLSX y Google Sheets.
* Carga de medios externos desde ZIP para usar imagenes y videos alojados en un subdominio.
* Conversion opcional de imagenes a AVIF y videos a WebM.

== Compatibilidad ==

Se puede usar con:

* Shortcodes nativos de WordPress.
* Elementor.
* WPBakery.
* Paginas normales de WordPress.

== Shortcodes disponibles ==

Buscador con filtro:

[hy_homes_search_filter]

Resultados filtrados:

[hy_homes_property_results]

Carrusel de propiedades recientes:

[hy_homes_recent_properties_carousel]

Ficha de propiedad:

[hy_homes_property_detail]

Carrusel aleatorio de banners:

[hy_homes_random_banners]

Carrusel de localidades:

[hy_homes_locations]

== Ejemplos de uso ==

Buscador principal:

[hy_homes_search_filter results_url="/properties/" neighborhood_source="auto" move_in_options="Immediate|Next 2 weeks|Next month"]

Pagina de resultados:

[hy_homes_property_results posts_per_page="8" results_banner_image="https://example.com/banner-results.jpg"]

Carrusel de propiedades recientes:

[hy_homes_recent_properties_carousel title="Explore Our Available Places" posts_per_page="12" columns="4"]

Ficha de propiedad:

[hy_homes_property_detail related_per_page="4"]

Carrusel aleatorio de banners:

[hy_homes_random_banners]

Localidades automaticas:

[hy_homes_locations results_url="/properties/" locations="auto"]

Localidades manuales agrupadas:

[hy_homes_locations results_url="/properties/" locations="Eastgardens & Mascot|Kingsford & Kensington|Randwick & Maroubra"]

== Buscador con filtro ==

El shortcode [hy_homes_search_filter] muestra:

* Selector de localidad.
* Campo numerico de habitaciones.
* Selector de fecha de disponibilidad.
* Boton de busqueda.

Por defecto, las localidades salen de HY Homes Syd > Localidades. Tambien se pueden pasar opciones manuales desde el shortcode, Elementor o WPBakery.

La fecha de disponibilidad se calcula desde la fecha cargada en cada propiedad. El plugin interpreta automaticamente:

* Immediate.
* Next 2 weeks.
* Next month.

== Pagina de resultados ==

La pagina de resultados debe ser una pagina normal de WordPress, por ejemplo:

/properties/

Dentro de esa pagina se coloca:

[hy_homes_property_results]

Si se quiere mostrar una foto fija arriba del buscador de resultados, usar:

[hy_homes_property_results results_banner_image="https://example.com/banner-results.jpg"]

Ese banner es una sola imagen fija y no cambia segun la localidad seleccionada.

El buscador envia los filtros a esa pagina usando parametros como:

* hy_neighborhood
* hy_rooms
* hy_move_in

Importante: evitar usar /hy-properties/ como pagina de resultados, porque esa URL corresponde al archivo interno del tipo de contenido de propiedades. El plugin normaliza automaticamente los formularios para usar /properties/ cuando no se configura otra URL valida.

== Ficha de propiedad ==

Las cards de propiedades abren la ficha individual a traves de una pagina con:

[hy_homes_property_detail]

Por defecto, la URL de ficha es:

https://hyhomesyd.thepanthersoft.com.ar/property-detail/

El plugin agrega automaticamente el parametro:

?hy_property=slug-de-la-propiedad

Tambien se puede mostrar una ficha especifica manualmente:

[hy_homes_property_detail post_id="123"]

o:

[hy_homes_property_detail slug="property-slug"]

La ficha incluye:

* Buscador con filtros ya seleccionados.
* Migas de pan.
* Galeria de imagenes y videos.
* Precio sobre la galeria.
* Titulo, direccion y descripcion.
* Boton de WhatsApp con seleccion de agente.
* Mapa de Google.
* Propiedades relacionadas por localidad.
* Carrusel de banners por localidad.

El titulo de propiedades relacionadas se muestra en ingles como:

RELATED PROPERTIES

== Carrusel de propiedades recientes ==

El shortcode [hy_homes_recent_properties_carousel] muestra propiedades recientes en formato carrusel.

En escritorio se pueden mostrar hasta cuatro columnas. Incluye flechas de navegacion y usa las mismas cards del sistema de propiedades.

== Localidades ==

Las localidades se administran desde:

HY Homes Syd > Localidades

Cada localidad puede tener:

* Nombre.
* Descripcion.
* Etiqueta destacada.
* URL de imagen.

El shortcode [hy_homes_locations] muestra las localidades como cards con imagen, etiqueta, descripcion y boton Explore Properties.

Si dos o mas localidades tienen la misma descripcion, la misma etiqueta destacada y la misma URL de imagen, el plugin las agrupa automaticamente en una sola card. Por ejemplo:

Waterloo + Zetland

se muestra como:

Waterloo & Zetland

El boton Explore Properties filtra todas las localidades agrupadas.

Tambien se puede escribir una localidad combinada manualmente usando &, por ejemplo:

Eastgardens & Mascot

En ese caso, el resultado filtra ambas localidades.

== Banners por localidad ==

Los banners se administran desde el panel de HY Homes Syd.

Cada banner puede tener:

* Titulo.
* Descripcion.
* Imagen.
* Boton.
* Localidad relacionada.

En la ficha de una propiedad, el plugin muestra los banners correspondientes a la localidad de esa propiedad.

El carrusel de banners por localidad:

* Muestra un banner por vez.
* Se desplaza automaticamente.
* No muestra flechas.
* Usa el mismo boton para redireccionar a las locations.

== Carrusel aleatorio de banners ==

El shortcode [hy_homes_random_banners] reutiliza el carrusel de banners, pero muestra todos los banners en orden aleatorio sin depender de una localidad especifica.

== WhatsApp ==

Los agentes se configuran desde:

HY Homes Syd > WhatsApp

Se pueden cargar hasta tres numeros. Usar codigo de pais sin + ni espacios. Ejemplo:

61400000000

Los mismos agentes se usan en:

* Boton flotante general.
* Boton de consulta dentro de la ficha de propiedad.
* Botones externos configurados con clases CSS.

Si un agente esta vacio, no se muestra.

== Botones externos de WhatsApp ==

Para abrir el menu del WhatsApp flotante desde otro boton, agregar esta clase:

hy-homes-whatsapp-open

Ejemplo:

<a href="#" class="hy-homes-whatsapp-open">WhatsApp</a>

Para abrir un modal independiente al lado de un boton personalizado, usar:

hy-homes-whatsapp-open-local

Ejemplo:

<a href="#" class="hy-homes-whatsapp-open-local">Chat on WhatsApp</a>

Tambien se pueden usar atributos:

data-hy-homes-whatsapp-open

o:

data-hy-homes-whatsapp-local

El modal funciona si hay al menos un agente cargado en HY Homes Syd > WhatsApp.

== Panel de administracion ==

El plugin agrega un menu lateral:

HY Homes Syd

Desde ahi se puede:

* Agregar propiedades.
* Ver propiedades.
* Editar propiedades.
* Eliminar propiedades.
* Agregar banners.
* Ver banners.
* Editar banners.
* Eliminar banners.
* Administrar localidades.
* Configurar agentes de WhatsApp.
* Importar desde Excel, CSV o Google Sheets.
* Subir medios externos desde ZIP.

== Campos de propiedades ==

Al crear o editar propiedades se cargan estos campos:

* Localidad: se puede seleccionar una existente o elegir + agregar localidad.
* Direccion.
* Precio.
* Fecha disponible.
* Cantidad de habitaciones.
* Cantidad de banos.
* Descripcion.
* URL de Maps.
* URLs de imagenes y videos.

El nombre de la propiedad se genera automaticamente con este formato:

Modern Apartment in direccion, localidad

Por eso no hace falta completar manualmente un campo de nombre.

== Imagenes y videos ==

En las propiedades se pueden cargar URLs de imagenes y videos, una por linea.

Acepta:

* Imagenes directas.
* Videos directos.
* Enlaces embebibles.
* URLs alojadas en un subdominio externo.

Para mejor rendimiento, se recomienda alojar imagenes y videos en un subdominio de medios y no cargar todo dentro del WordPress principal.

== Carga de medios externos ==

Ir a:

HY Homes Syd > Media externo

Primero configurar:

* URL base: URL publica del subdominio de medios. Ejemplo: https://media.hyhomessyd.com/
* Ruta del servidor: carpeta fisica del hosting donde apunta ese subdominio.
* Ruta de FFmpeg: opcional, para convertir videos a WebM.

Luego se puede subir un ZIP con imagenes y videos. El plugin extrae los archivos permitidos en la carpeta seleccionada y devuelve las URLs listas para usar.

Formatos permitidos:

* jpg
* jpeg
* png
* webp
* gif
* avif
* heic
* heif
* mp4
* webm
* ogg
* mov
* m4v

La conversion a AVIF depende del soporte del servidor. La conversion a WebM depende de FFmpeg y del limite de tiempo del hosting.

== Importacion por Excel, CSV o Google Sheets ==

Ir a:

HY Homes Syd > Import Excel / Sheets

Columnas recomendadas:

action,type,id,slug,title,description,neighborhood,room_type,bedrooms,bathrooms,street,address,price,availability_date,availability,price_suffix,status,move_in,detail_url,featured_image_url,gallery_media,map_embed_url,image_url,button_url

Usar:

* type=property para propiedades.
* type=banner para banners.
* action vacio para crear o actualizar.
* action=delete para enviar a papelera usando id o slug.

Notas:

* En propiedades, el titulo se genera automaticamente. La columna title puede quedar vacia.
* En banners, la columna title se usa como titulo del banner.
* availability_date acepta formato YYYY-MM-DD.
* availability se mantiene por compatibilidad con importaciones anteriores.
* Si detail_url queda vacio, se usa la URL de ficha por defecto.
* Google Sheets debe estar publicado o compartido como CSV legible.

Plantilla disponible:

hy-homes-syd-import-template.csv

== Guia visual HTML ==

El archivo:

shortcodes-preview.html

incluye una guia visual estatica para GitHub con una previsualizacion de cada shortcode.

== Elementor ==

El plugin registra widgets para:

* HY Homes Search Filter
* HY Homes Property Results
* HY Homes Recent Properties Carousel
* HY Homes Property Detail
* HY Homes Random Banners
* HY Homes Locations

== WPBakery ==

El plugin registra elementos para:

* HY Homes Search Filter
* HY Homes Property Results
* HY Homes Recent Properties Carousel
* HY Homes Property Detail
* HY Homes Random Banners
* HY Homes Locations

== Recomendaciones importantes ==

* Crear una pagina /properties/ con [hy_homes_property_results].
* Crear una pagina /property-detail/ con [hy_homes_property_detail].
* No usar /hy-properties/ como pagina publica de resultados.
* Configurar al menos un agente en HY Homes Syd > WhatsApp.
* Usar URLs directas para imagenes y videos siempre que sea posible.
* Para muchos medios, usar el modulo Media externo y un subdominio.
* Limpiar cache del sitio si se actualiza CSS o JS y no se ven los cambios.

== Changelog ==

= 1.1.30 =
* Se agrego banner fijo opcional en la pagina de resultados mediante results_banner_image.
* El banner de resultados no depende de la localidad seleccionada.

= 1.1.29 =
* Readme reescrito completamente en espanol.

= 1.1.28 =
* Se agrego un disparador local de WhatsApp para botones personalizados sin abrir el menu del WhatsApp flotante.

= 1.1.27 =
* Se corrigio que los botones externos de WhatsApp cerraran el modal flotante inmediatamente despues de abrirlo.

= 1.1.26 =
* Se agrego una clase reutilizable para abrir el modal flotante de agentes de WhatsApp desde cualquier boton externo.

= 1.1.25 =
* Los carruseles de banners por localidad ahora se desplazan automaticamente, de a un banner por vez y sin flechas visibles.

= 1.1.24 =
* Se reemplazo el icono de WhatsApp por el PNG provisto y se fijo el boton flotante en 81px por 81px.

= 1.1.23 =
* Se alinearon las flechas del carrusel de propiedades recientes con el titulo del carrusel.

= 1.1.22 =
* Se cambio el titulo por defecto de propiedades relacionadas en la ficha a ingles.

= 1.1.21 =
* Las localidades con la misma descripcion, etiqueta destacada e imagen ahora se agrupan automaticamente en una sola card.
* Las cards agrupadas usan un titulo combinado como Waterloo & Zetland y filtran todas las localidades agrupadas.

= 1.1.20 =
* Los filtros de busqueda ahora normalizan la accion del formulario hacia la pagina real /properties/ por defecto.
* Al volver a elegir filtros desde resultados ya no se envia la busqueda al archivo interno /hy-properties/.
* Elementor y WPBakery advierten no usar /hy-properties/ como Results URL.

= 1.1.19 =
* Se mejoro visualmente el dashboard de HY Homes Syd.
* Se agrego el shortcode de localidades al listado del dashboard.

= 1.1.18 =
* Se corrigieron busquedas filtradas que podian enviar a /hy-properties/ y devolver 404.
* Los resultados ahora prefieren la pagina publicada que contiene [hy_homes_property_results] o el widget de Elementor.
* Las URLs configuradas como /hy-properties/ se reemplazan por la pagina real de resultados.
* Las reglas de reescritura se refrescan al actualizar version para evitar URLs antiguas.

= 1.1.17 =
* Se agrego el shortcode [hy_homes_locations], widget de Elementor y elemento de WPBakery.
* Se agregaron URL de imagen y etiqueta destacada a Localidades.
* Las cards de localidades enlazan a resultados filtrados y soportan nombres combinados con &.

= 1.1.16 =
* Se reemplazo la flecha de texto de las cards por el SVG largo provisto.

= 1.1.15 =
* Se oculto el campo tecnico de URL de ficha del formulario de propiedades.

= 1.1.14 =
* Se agrego una seccion global de WhatsApp con tres numeros de agentes.
* Se agrego boton flotante de WhatsApp con seleccion de agente.
* Las consultas desde la ficha permiten elegir agente y enviar mensaje especifico de la propiedad.
* Se elimino el numero de WhatsApp por propiedad del formulario y del CSV.

= 1.1.13 =
* Se mejoro el selector de localidad de propiedades con la opcion + agregar localidad.

= 1.1.12 =
* Se elimino el campo manual de nombre de propiedad del formulario.
* Los nombres de propiedades se generan automaticamente desde direccion y localidad.
* Las importaciones CSV ya no requieren title cuando hay direccion o localidad.

= 1.1.11 =
* Se reemplazaron los iconos de habitacion y bano por SVGs personalizados.

= 1.1.10 =
* Se agrego el primer flujo de consulta por WhatsApp en la ficha.
* Los mapas de detalle normalizan enlaces comunes de Google Maps a iframes embebibles.
* Se agregaron alias adicionales de planilla para URLs de mapa.

= 1.1.9 =
* Se agrego la pagina de detalle HY Homes Syd como detail_url por defecto para propiedades.
* El admin de propiedades muestra detail_url precargado.
* Las importaciones CSV y enlaces de cards usan la URL de detalle por defecto si detail_url esta vacio.

= 1.1.8 =
* El shortcode de ficha soporta busqueda por slug y parametro hy_property.
* Las cards pueden enlazar a una pagina de detalle con shortcode mediante detail_url.
* La paginacion relacionada y los mensajes de WhatsApp mantienen la URL activa de la ficha.

= 1.1.7 =
* Se agrego conversion opcional de imagenes a AVIF en ZIPs de medios externos.
* Se agrego conversion opcional de videos a WebM usando FFmpeg.
* Se agrego configuracion de ruta de FFmpeg y resumen de conversiones.

= 1.1.6 =
* Se agrego panel de medios externos para subir ZIPs a un subdominio y generar URLs listas.

= 1.1.5 =
* Se corrigieron los carruseles de banners para mostrar solo un banner por slide.

= 1.1.4 =
* Se agrego campo de fecha disponible para propiedades.
* Las fechas disponibles calculan automaticamente Immediate, Next 2 weeks o Next month.
* Se agrego soporte availability_date / fecha_disponible a CSV, XLSX y Google Sheets.

= 1.1.3 =
* Se agrego selector Auto/Manual de localidades en Elementor y WPBakery.
* El buscador usa por defecto localidades creadas en HY Homes Syd > Localidades.

= 1.1.2 =
* Se mejoro el layout admin de pantallas de localidades y banners.
* Se agregaron etiquetas bilingues espanol/ingles a campos admin.

= 1.1.1 =
* Se mejoro el layout del formulario admin de propiedades.

= 1.1.0 =
* Se agrego panel admin HY Homes Syd.
* Se agrego contenido editable de banners por localidad.
* Se agrego importacion CSV/XLSX y Google Sheets para propiedades y banners.
* Se agrego soporte de eliminar/enviar a papelera por id o slug desde planillas.

= 1.0.0 =
* Version inicial con buscador y resultados de propiedades.
