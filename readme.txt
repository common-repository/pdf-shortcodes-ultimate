=== PDF Shortcodes Ultimate ===
Contributors: opendsi
Tags: shortcodes ultimate, pdf, embed, pdf viewer, su_pdf
Requires at least: 3.9
Tested up to: 4.8.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed PDF documents in your article or page with this "PDF" shortcode for Shortcodes Ultimate.


== Description ==

This plugin adds the "PDF" shortcode to the list of shortcodes provided by the Shortcodes Ultimate plugin, under the Media category.

It will let you select a PDF document from your Media manager (or directly enter an URL).

This shortcode, once inserted to your page or article, will render the PDF directly in your page (responsive, 16:9 aspect ratio).
In case the browser does not have a built-in PDF viewer, it will display the fallback link to download the PDF instead.


== Installation ==

Installing "PDF Shortcodes Ultimate Add-on" can be done either by searching for "PDF Shortcodes Ultimate Add-on" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
2. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
3. Activate the plugin through the 'Plugins' menu in WordPress


== Screenshots ==

1. "PDF" shortcode, under the Media category, in Shortcodes Ultimate popup.
2. PDF shortcode options.
3. Choose a PDF document from the Media library.
4. PDF shortcode options: Live Preview.
5. "su_pdf" shortcode inserted into "Sample" page.
6. Sample page displaying our embedded PDF using the browser's built-in PDF viewer.


== Frequently Asked Questions ==

= Does this plugin depend on any others? =

Yes. It depends on the [Shortcodes Ultimate](https://wordpress.org/plugins/shortcodes-ultimate/) plugin. It was tested with Shortcodes Ultimate 4.10.2.


= Does this create new database tables? =

No. There are no new database tables with this plugin.


= Does this load additional JS or CSS files ? =

Yes. It loads the `media-shortcodes.css` file on pages or articles containing the PDF shortcode.
This CSS file is loaded for Media shortcodes by the Shortcodes Ultimate plugin itself.


= Can I use a PDF from my computer? =

Yes. Once on the "PDF" shortcode options screen, click on the "Media manager" button. Now, drag and drop your PDF document on the screen. Once your PDF has finished uploading, click the "Insert" button.


= Can I use a PDF that is already online? =

Yes. Open the online PDF using your browser. Copy the document public URL. Once on the PDF shortcode options screen, paste the PDF URL in the URL field.


= What does the shortcode itself look like? =

Here is an example of PDF shortcode:

`[su_pdf url="https://mysite.org/wordpress/wp-content/uploads/2017/09/my-document.pdf" link="Click here to download PDF"]`


= What does the HTML code produced by the shortcode look like? =

Here is an example of HTML code produced by the PDF shortcode:

`
<div class="su-pdf su-responsive-media-yes">
  <object data="https://mysite.org/wordpress/wp-content/uploads/2017/09/my-document.pdf" type="application/pdf">
    <p>
      <a href="https://mysite.org/wordpress/wp-content/uploads/2017/09/my-document.pdf">
        Click here to download PDF
      </a>
    </p>
  </object>
</div>
`


= Is the plugin translated? =

Yes. It is translated in French (fr_FR).
You will find the translation files in the `lang/` folder.
New translations are welcome at https://translate.wordpress.org/projects/wp-plugins/pdf-shortcodes-ultimate


= Where can I get support? =

https://wordpress.org/support/plugin/pdf-shortcode-ultimate/


== Changelog ==

= 1.0.0 =
* 2017.09.25
* Initial release
