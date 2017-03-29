<?php
class ControllerToolSitemap extends Controller{
    public function index(){
        $this->load->model('catalog/product');
        $products = $this->model_catalog_product->getProducts([]);
        /*print '<pre>';
        print_r($results);
        exit;*/

        $doc = new DOMDocument('1.0');
        $urlset = $doc->createElement("urlset");
        $doc->appendChild($urlset);

        foreach ($products as $product){
            $url = $doc->createElement('url');
            $urlset->appendChild($url);

            $location_value = 'http://http://localhost/index.php?route=product/product&product_id='.$product['product_id'];
            $location_value = str_replace('&', '&amp;', $location_value);
            $location = $doc->createElement('loc', $location_value);
            $url->appendChild($location);

            $lastmod = $doc->createElement('lastmod', date('Y-m-d', time()));
            $url->appendChild($lastmod);

            $changefreq = $doc->createElement('changefreq', 'monthly');
            $url->appendChild($changefreq);

            $priority = $doc->createElement('priority', '0.8');
            $url->appendChild($priority);
        }
        $this->response->addHeader('Content-Type: application/xml');
        $this->response->setOutput($doc->saveXML());
    }
}