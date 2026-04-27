<?php
require_once 'models/MenuModel.php';

class MenuController {
    private $menuModel;

    public function __construct() {
        $this->menuModel = new MenuModel();
    }

    public function index() {
        $category = isset($_GET['kategori']) ? strtolower(trim($_GET['kategori'])) : 'semua';
        $search   = isset($_GET['q'])        ? trim($_GET['q'])                     : '';

        // Sanitasi kategori — hanya izinkan nilai valid
        $allowed = ['semua', 'makanan', 'minuman', 'dessert', 'paket'];
        if (!in_array($category, $allowed)) {
            $category = 'semua';
        }

        $menus        = $this->menuModel->getAll($category, $search);
        $current_page = 'menu';

        require_once 'views/layouts/header.php';
        require_once 'views/pages/menu.php';
        require_once 'views/layouts/footer.php';
    }
}
