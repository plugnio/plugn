<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
          xmlns:xhtml="http://www.w3.org/1999/xhtml">

      <!-- products -->
      <?php foreach($products as $product) { ?>
      <url>
        <loc><![CDATA[<?= $product->restaurant->restaurant_domain . '/product/' . $product->item_uuid ?>]]></loc>
        <priority>0.5</priority>
      </url>
      <?php } ?>

  </urlset>
