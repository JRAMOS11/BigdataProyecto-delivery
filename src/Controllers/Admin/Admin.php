<?php
namespace Controllers\Admin;
 
class Admin extends \Controllers\PrivateController
{
    public function run(): void
    {
        $this->requireAuth(\Utilities\Security::getUserRole() === 'admin');
 
        $resumen = \Dao\AdminDao::getResumen();
        \Utilities\Site::addLink('public/css/admin.css');
        \Views\Renderer::render('admin/admin', $resumen);
    }
}