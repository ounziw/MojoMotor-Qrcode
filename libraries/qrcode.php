<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Qrcode
{
    var $addon;
    var $site_structure;
    var $_current_url;
    var $addon_version = 1.0;

    function __construct()
    {
        $this->addon =& get_instance();
        $this->addon->load->helper(array('page', 'array'));
        $this->addon->load->model(array('page_model'));
        $this->site_structure = $this->addon->site_model->get_setting('site_structure');
    }

    function _get_site_url()
    {
        return site_url();
    }

    function _get_current_url()
    {
        return site_url('page/' . $this->addon->mojomotor_parser->url_title);
    }

    function test()
    {
        $this->addon->load->library('unit_test');
        $this->addon->unit->run(site_url(),'http://localhost/');
        $this->addon->unit->run($this->_get_current_url(),'http://localhost/index.php/page/about','PASS on "about" page, else Fail');
        echo $this->addon->unit->report();
    }
}
?>
