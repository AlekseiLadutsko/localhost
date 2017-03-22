<?php
class ControllerExtensionModuleUpdateprice extends Controller {
    public function index() {
        $this->load->language('extension/module/updateprice');

        //установка title
        $this->document->setTitle($this->language->get('heading_title'));

        //загрузка модели
        $this->load->model('setting/setting');

        //получаем текст в зависимости от языка
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_admin'] = $this->language->get('entry_admin');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        //создаем массив для хлебных крошек и получаем данные
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/updateprice', 'token=' . $this->session->data['token'], true)
        );

        /* ЧТЕНИЕ ИХ CSV ФАЙЛА
        $filename = 'C:\OpenServer\open_cart\localhost\admin\controller\extension\module\products.csv';
        $handler = fopen($filename, 'r');
        while(!feof($handler)){
            $productData = fgetcsv($handler,0,';');
            if (empty($productData[0])) continue;
            if (strpos($productData[0], 'article') !== FALSE) continue;
            printf('<p>Товар %s с ценой %d в остаке %d</p>'
                , $productData[0]
                , $productData[1]
                , $productData[2]
            );
            $products[] = $productData;
        }
        fclose($handler);

        echo '<pre>';
        var_export($products); exit;*/
        $dir = getcwd();
        $filename = $dir.'\controller\extension\module\products.csv';
        $handler = fopen($filename, 'w+');

        $filter_data = array(
            'sort' => 'name',
            'order' => 'ASC'
        );

        $this->load->model('catalog/product');
        $products = $this->model_catalog_product->getProducts($filter_data);
        fwrite($handler, 'product_id;quantity;price'.PHP_EOL);
        for($i = 0; $i < count($products); $i++){
            foreach ($products[$i] as $k => $p){
                if ($k == 'product_id') {
                    $line = $p.";";
                } elseif ($k == 'quantity') {
                    $line = $p.";";
                } elseif ($k == 'price') {
                    $line = $p . PHP_EOL;
                } else {
                    continue;
                }
                fwrite($handler, $line);
            }
        }
        fclose($handler); exit;

        //загружаем в массив data элементы страницы и передаем во view
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/updateprice', $data));
    }
}