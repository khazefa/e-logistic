<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*  edit_column callback function in Codeigniter (Ignited Datatables)
*
* Grabs a value from the edit_column field for the specified field so you can
* return the desired value.
*
* @access   public
* @return   mixed
*/

if ( ! function_exists('check_status'))
{
    function check_status($status = '')
    {
        return ($status == 1) ? 'Active' : 'Inactive';
    }
}

if ( ! function_exists('check_gender'))
{
    function check_gender($gender = '')
    {
        return ($gender == 'M') ? 'Male' : 'Female';
    }
}

if ( ! function_exists('fix_first_name'))
{
    function fix_first_name($val = '')
    {
        return ucwords(strtolower($val));
    }
}

if ( ! function_exists('get_media_path'))
{
    function get_media_path($pict = '')
    {
        if($pict != ''){
            $output = "<img src=".base_url()."uploads/photos/".$pict." style='margin-left:auto;margin-right:auto;max-width:72px;display:block;'>";
        }else{
            $output = "<img src=".base_url()."assets/images/no-image.png style='margin-left:auto;margin-right:auto;display:block;'>";
        }
        return $output;
    }
}

if ( ! function_exists('get_slider_path'))
{
    function get_slider_path($pict = '')
    {
        if($pict != ''){
            $output = "<img src=".base_url()."uploads/photos/".$pict." style='margin-left:auto;margin-right:auto;max-width:150px;display:block;'>";
        }else{
            $output = "<img src=".base_url()."assets/images/no-image.png style='margin-left:auto;margin-right:auto;display:block;'>";
        }
        return $output;
    }
}

if ( ! function_exists('get_custom_buttons'))
{
    function get_custom_buttons($page = '', $id = '')
    {
//        $ci = & get_instance();
        $html = "<div class='actions' align='center'>";
        $html .= "<a href=".base_url().$page."/edit/".$id.">Edit</a> | ";
//        $html .= "<a href=".base_url().$page."/detail/".$id.">Detail</a> | ";
        $html .= "<a href=".base_url().$page."/delete/".$id.">Delete</a>";
        $html .= "</div>";

        return $html;
    }
}

/* End of file MY_datatable_helper.php */
/* Location: ./application/helpers/MY_datatable_helper.php */
