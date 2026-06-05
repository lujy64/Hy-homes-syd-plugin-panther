=== HY Homes Syd Panther Landing ===
Contributors: The Panther Soft - Vaira Maria Lujan
Tags: real estate, landing, elementor, wpbakery, search
Requires at least: 5.8
Tested up to: 6.5
Stable tag: 1.1.3
License: GPLv2 or later

Landing page elements for HY Homes Syd properties.

== Description ==

Developed by The Panther Soft - Vaira Maria Lujan.

The first element is a property search filter with:

* Neighborhood selector.
* Room type number field.
* Move-in date selector.
* Search properties button.

The second element is a filtered results page block with:

* The selected search bar at the top.
* Breadcrumb filter links.
* Location title.
* Property cards from WordPress content.
* Pagination.

The third element is a carousel for the most recent properties with:

* Four visible columns on desktop.
* Property cards from WordPress content.
* Previous and next arrows.

The property detail element includes:

* Selected search bar and breadcrumb filters.
* Image/video gallery.
* Property details and WhatsApp inquiry button.
* Google map.
* Related properties by neighborhood.
* Location banner carousel.

The random banners element includes:

* All location banners from the HY Homes Syd admin panel.
* Random display order.
* Shared banner carousel design.

The admin panel includes:

* Sidebar menu: HY Homes Syd.
* Property create, edit and delete flows.
* Banner create, edit and delete flows.
* Locality selection through the shared Neighborhoods taxonomy.
* CSV/XLSX import and Google Sheets CSV import.

It can be used as:

* Shortcode: [hy_homes_search_filter]
* Shortcode: [hy_homes_property_results]
* Shortcode: [hy_homes_recent_properties_carousel]
* Shortcode: [hy_homes_property_detail]
* Shortcode: [hy_homes_random_banners]
* Elementor widget: HY Homes Search Filter
* Elementor widget: HY Homes Property Results
* Elementor widget: HY Homes Recent Properties Carousel
* Elementor widget: HY Homes Property Detail
* Elementor widget: HY Homes Random Banners
* WPBakery element: HY Homes Search Filter
* WPBakery element: HY Homes Property Results
* WPBakery element: HY Homes Recent Properties Carousel
* WPBakery element: HY Homes Property Detail
* WPBakery element: HY Homes Random Banners

== Shortcode Example ==

[hy_homes_search_filter results_url="/properties/" neighborhood_source="auto" move_in_options="Immediate|Next 2 weeks|Next month"]

[hy_homes_property_results posts_per_page="8"]

[hy_homes_recent_properties_carousel title="Explore Our Available Places" posts_per_page="12" columns="4"]

[hy_homes_property_detail related_per_page="4"]

[hy_homes_random_banners]

== Visual HTML Guide ==

Open shortcodes-preview.html to see a static GitHub-friendly visual guide for every shortcode.

== Spreadsheet Import ==

Go to HY Homes Syd > Import Excel / Sheets.

Recommended columns:

action,type,id,slug,title,description,neighborhood,room_type,bedrooms,bathrooms,street,address,price,availability,price_suffix,status,move_in,detail_url,featured_image_url,gallery_media,map_embed_url,whatsapp_phone,image_url,button_url

Use type=property for properties and type=banner for carousel banners. The availability column controls the property card label and the move-in/search filter. Leave action empty to create/update. Use action=delete with an id or slug to move an item to Trash. Google Sheets must be published or shared as a readable CSV link.

Use hy-homes-syd-import-template.csv as a downloadable import template.

== Changelog ==

= 1.1.3 =
* Added an Auto/Manual neighborhood source option to Elementor and WPBakery search elements.
* Search filter now defaults to neighborhoods created in HY Homes Syd > Localidades.

= 1.1.2 =
* Improved admin layout for neighborhood and location banner screens.
* Added bilingual Spanish/English labels to property, neighborhood and banner admin fields.

= 1.1.1 =
* Improved property admin form layout and field alignment.

= 1.1.0 =
* Added HY Homes Syd admin panel.
* Added editable location banner content type.
* Added CSV/XLSX and Google Sheets import for properties and banners.
* Added spreadsheet delete/trash support by id or slug.

= 1.0.0 =
* Initial search filter and property results elements.
