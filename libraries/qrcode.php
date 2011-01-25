<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * * Qrcode 
 * *
 * * @package MojoMotor
 * * @subpackage Addons
 * * @author Fumito Mizuno
 * * @link http://mojomotor.php-web.net/
 * * @license Apache License v2.0
 * * @copyright 2010 Fumito Mizuno
 * *
 * * Licensed under the Apache License, Version 2.0 (the "License");
 * * you may not use this file except in compliance with the License.
 * * You may obtain a copy of the License at
 * *
 * * http://www.apache.org/licenses/LICENSE-2.0
 * *
 * * Unless required by applicable law or agreed to in writing, software
 * * distributed under the License is distributed on an "AS IS" BASIS,
 * * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * * See the License for the specific language governing permissions and
 * * limitations under the License.
 * *
 * * USAGE
 * * {mojo:qrcode:create}
 * * {mojo:qrcode:create size="200"}
 * * {mojo:qrcode:create errorlevel="M"}
 * * Size can be between 1 and 600.
 * * Errorlevel can be L, M, Q, or H.s
 */
class Qrcode
{
    var $addon;
    var $_page = "top";
    var $_size = 150;
    var $_errorlevel = "L";
    var $_url;
    var $_urlformat = 'http://chart.apis.google.com/chart?chs=%1$sx%1$s&cht=qr&chld=%2$s&chl=%3$s';
    var $addon_version = 1.0;

    /**
     * __construct 
     * 
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->addon =& get_instance();
        $this->addon->load->helper(array('page', 'array'));
        $this->addon->load->model(array('page_model'));
    }

    /**
     * create 
     * 
     * @param array $template_data 
     * @access public
     * @return void
     */
    function create($template_data = array())
    {
        $qrlink = $this->_get_qrurl($template_data['parameters']['page'],$template_data['parameters']['size'],$template_data['parameters']['errorlevel']);
        return '<img src="' . $qrlink . '">';
    }
    function _get_qrurl($page="",$size="",$errorlevel="")
    {
        if ( $page == "current" )
        {
            $this->_page = "current";
        }
        if ( $size >=1 && $size <= 600 )
        {
            $this->_size = $size;
        }
        if ( in_array($errorlevel,array("L","M","Q","H")))
        {
            $this->_errorlevel = $errorlevel;
        }
    $this->_url = $this->_dataurl($this->_page);
    return sprintf($this->_urlformat, $this->_size, $this->_errorlevel, $this->_url);
    }

    /**
     * _dataurl 
     * 
     * @param mixed $page 
     * @access protected
     * @return void
     */
    function _dataurl($page)
    {
        if ( $page == "current" )
        {
            return $this->_get_current_url();
        }
        else
        {
            return site_url();
        }
    }

    /**
     * _get_current_url 
     * 
     * @access protected
     * @return void
     */
    function _get_current_url()
    {
        return site_url('page/' . $this->addon->mojomotor_parser->url_title);
    }

    function test()
    {
        $this->addon->load->library('unit_test');
        $this->addon->unit->run($this->_get_current_url(),'http://localhost/index.php/page/about','PASS on "about" page, else Fail');
        $this->addon->unit->run($this->_dataurl(),site_url());
        $this->addon->unit->run($this->_dataurl("current"),$this->_get_current_url());
        $this->addon->unit->run($this->_get_qrurl(),'is_string');
        echo $this->addon->unit->report();
    }
}
?>
