<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
          xmlns:xhtml="http://www.w3.org/1999/xhtml">

      <url>
          <loc><![CDATA[<?= $restaurant->restaurant_domain ?>]]></loc>
          <priority>0.5</priority>
      </url>

      <!-- categories -->
      <?php foreach($categories as $category) { ?>
          <url>
              <loc><![CDATA[<?php if($category->slug) {
                      echo $restaurant->restaurant_domain . '/category/' . $category->slug;
                  } else {
                      echo $restaurant->restaurant_domain . '/product-list/' . $category->item_uuid;
                  } ?>]]></loc>
              <priority>0.5</priority>
          </url>
      <?php } ?>

      <!-- products -->
      <?php foreach($products as $product) { ?>
      <url>
        <loc><![CDATA[<?php if($product->slug) {
                echo $restaurant->restaurant_domain . '/' . $product->slug;
            } else {
                echo $restaurant->restaurant_domain . '/product/' . $product->item_uuid;
            } ?>]]></loc>
        <priority>0.5</priority>
      </url>
      <?php } ?>

      <url>
          <loc><![CDATA[<?= $restaurant->restaurant_domain .'/order-status'; ?>]]></loc>
          <priority>0.5</priority>
      </url>


  </urlset>