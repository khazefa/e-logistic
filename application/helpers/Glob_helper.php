<?php if(!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * This function is used to print the content of any data
 */
function pre($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * This function used to get the CI instance
 */
if(!function_exists('get_instance'))
{
    function get_instance()
    {
        $CI = &get_instance();
    }
}

/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 */
if(!function_exists('getHashedPassword'))
{
    function getHashedPassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }
}

if(!function_exists('verifyHashedPassword'))
{
    /**
     * This function used to generate the hashed password
     * @param {string} $plainPassword : This is plain text password
     * @param {string} $hashedPassword : This is hashed password
     */
    function verifyHashedPassword($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword) ? true : false;
    }
}

if(!function_exists('getBrowserAgent'))
{
    /**
     * This method used to get current browser agent
     */
    function getBrowserAgent()
    {
        $CI = get_instance();
        $CI->load->library('user_agent');

        $agent = '';

        if ($CI->agent->is_browser())
        {
            $agent = $CI->agent->browser().' '.$CI->agent->version();
        }
        else if ($CI->agent->is_robot())
        {
            $agent = $CI->agent->robot();
        }
        else if ($CI->agent->is_mobile())
        {
            $agent = $CI->agent->mobile();
        }
        else
        {
            $agent = 'Unidentified User Agent';
        }

        return $agent;
    }
}


if(!function_exists('setProtocol'))
{
    /**
    * This method used to set email protocol
    */
    function setProtocol()
    {
        $CI = &get_instance();
        
        $config = Array(
            'protocol' => PROTOCOL,
            'smtp_host' => SMTP_HOST,
            'smtp_port' => SMTP_PORT,
            'smtp_user' => SMTP_USER,
            'smtp_pass' => SMTP_PASS,
            'mailtype'  => 'html', 
            'charset'   => 'iso-8859-1',
            'wordwrap'   => TRUE
        );
        
        $CI->load->library('email', $config);
        $CI->email->set_newline("\r\n");
        
        return $CI;
    }
}

if(!function_exists('emailConfig'))
{
    function emailConfig()
    {
        $CI = &get_instance();
        $CI->load->library('email');
        $config['protocol'] = PROTOCOL;
        $config['smtp_host'] = SMTP_HOST;
        $config['smtp_port'] = SMTP_PORT;
        $config['mailpath'] = MAIL_PATH;
        $config['charset'] = 'UTF-8';
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;
    }
}

if(!function_exists('resetPasswordEmail'))
{
    function resetPasswordEmail($detail)
    {
        $data["data"] = $detail;
        
        $CI = setProtocol();
        
        $CI->email->from(EMAIL_FROM, FROM_NAME);
        $CI->email->subject("Reset Password");
        $CI->email->message($CI->load->view('email/resetPassword', $data, TRUE));
        $CI->email->to($detail["email"]);
        $status = $CI->email->send();
        
        return $status;
    }
}

if(!function_exists('setFlashData'))
{
    function setFlashData($status, $flashMsg)
    {
        $CI = get_instance();
        $CI->session->set_flashdata($status, $flashMsg);
    }
}

if( !function_exists('load_day') ) {
    /**
     * Load Day Option
     *
     * Write option with day value
     *
     * @return	option day value
     */
    function load_day($value = "") {

        //Loop Day
        for ($i=0; $i <= 31; $i++) {
            if ($i == 0) {
                echo '<option value="">Day</option>';
            }else{
                if ( !empty($value) && $value == $i) {
                    echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
                }else{
                    echo '<option value="'.$i.'">'.$i.'</option>';
                }
            }
        }
    }
}

if( !function_exists('load_month') ) {
    /**
     * Load Month Option
     *
     * Write option with month value
     *
     * @return	option month value
     */
    function load_month($value = "") {

        //Loop Month
        for ($i=0; $i <= 12; $i++) {
            if ($i == 0) {
                echo '<option value="">Month</option>';
            }else{
                if ( !empty($value) && changeMonth(2, $value) == $i ) {
                    echo '<option value="'.$i.'" selected="selected">'.changeMonth(1, $i).'</option>';
                }else{
                    echo '<option value="'.$i.'">'.changeMonth(1, $i).'</option>';
                }
            }
        }

    }
}

if( !function_exists('load_year') ) {
    /**
     * Load Year Option
     *
     * Write option with year value
     *
     * @return	option year value
     */
    function load_year($value = "") {
        //Setting Year
        $maxYear 	= date('Y', strtotime('+1 years'));
        $minYear 	= date('Y', strtotime('-20 years'));

        //Loop year
        for ( $i = $maxYear; $i != $minYear; $i-- ) {
            if ( $i == $maxYear ) {
                echo '<option value="">Year</option>';
            }else{
                if ( !empty($value) && $value == $i) {
                    echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
                }else{
                    echo '<option value="'.$i.'">'.$i.'</option>';
                }
            }
        }
    }
}

if( !function_exists('changeMonth') ) {
/**
* Change Month
*
* Change month number to string or month string to number
*
* Type 1 => number to string
* Type 2 => string to number
* @return	month 1 => Januari ||| month january => 1
*/
    function changeMonth($type = 1, $value = "")
    {
        if ($type == 1) {
            $arrMonth = array(1 => "January",
                                2 => "February",
                                3 => "March",
                                4 => "April",
                                5 => "May",
                                6 => "June",
                                7 => "July",
                                8 => "August",
                                9 => "September",
                                10 => "October",
                                11 => "November",
                                12 => "December"
                        );
            if ($value == 0){
                    return $arrMonth;
            }else{
                    return $arrMonth[$value];
            }
        }else{
            $arrMonth = array("January" => 1,
                                "February" => 2,
                                "March" => 3,
                                "April" => 4,
                                "May" => 5,
                                "June" => 6,
                                "July" => 7,
                                "August" => 8,
                                "September" => 9,
                                "October" => 10,
                                "November" => 11,
                                "December" => 12
                        );
            if ($value == ""){
                    return $arrMonth;
            }else{
                    return $arrMonth[$value];
            }
        }
    }
}

if( !function_exists('generateRandomString') ) {
    /**
     * Generate Random String
     *
     * Generate Random String to give name / code to value (Default length : 10)
     *
     * @return	encrypted string
     */
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}

if( !function_exists('cms_encrypt') ) {
    /**
     * Encrypt Standart CMS
     *
     * Encrypt some word / value to parse in another controller, view or model
     *
     * @return	encrypted string
     */
    function cms_encrypt($string = "")
    {
        $key = "1234567890royal0987654321";
        $iv = mcrypt_create_iv(
            mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC),
            MCRYPT_DEV_URANDOM
        );

        $encrypted = base64_encode(
            $iv .
            mcrypt_encrypt(
                MCRYPT_RIJNDAEL_128,
                hash('sha256', $key, true),
                $string,
                MCRYPT_MODE_CBC,
                $iv
            )
        );

        return urlencode($encrypted);
    }
}

if( !function_exists('cms_decrypt') ) {
    /**
     * Decrypt Standart CMS
     *
     * Decrypt some word / value to parse in another controller, view or model
     *
     * @return	decrypted string
     */
    function cms_decrypt($string = "")
    {
        $key = "1234567890royal0987654321";
        $data = base64_decode(urldecode($string));
        $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));

        $decrypted = rtrim(
            mcrypt_decrypt(
                MCRYPT_RIJNDAEL_128,
                hash('sha256', $key, true),
                substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)),
                MCRYPT_MODE_CBC,
                $iv
            ),
            "\0"
        );

        return $decrypted;
    }
}

if( !function_exists('mask_email') ) {
    /**
     * Masking Email
     *
     * Masking email for secret the email address
     *
     * @return	email with mask * . example : abcdlkjlkjk@hotmail.com -> abc********@*****il.com
     */
    function mask_email($email = "")
    {
        $em   = explode("@",$email);
        $last = end($em);
        $name = implode(array_slice($em, 0, count($em)-1), '@');
        $len  = floor(strlen($name)/3);
        $xxx  = floor(strlen($last)/2);

        return substr($name,0, $len) . str_repeat('*', strlen($name)-$len) . "@" . str_repeat('*', $xxx). substr($last,$xxx, strlen($last));

    }
}

if ( ! function_exists('format_rupiah'))
{
    function format_rupiah($value)
    {
        $a=(string)$value; //membuat $value menjadi string
        $len=strlen($a); //menghitung panjang string $a
     
        if ( $len <=18 )
        {
            $ratril=$len-3-1;
            $ramil=$len-6-1;
            $rajut=$len-9-1; //untuk mengecek apakah ada nilai ratusan juta (9angka dari belakang)
            $juta=$len-12-1; //untuk mengecek apakah ada nilai jutaan (6angka belakang)
            $ribu=$len-15-1; //untuk mengecek apakah ada nilai ribuan (3angka belakang)
     
            $angka='';
            for ($i=0;$i<$len;$i++)
            {
                if ( $i == $ratril )
                {
                    $angka=$angka.$a[$i].".";
                }
                else if ($i == $ramil )
                {
                    $angka=$angka.$a[$i].".";
                }
                else if ( $i == $rajut )
                {
                    $angka=$angka.$a[$i]."."; //meletakkan tanda titik setelah 3angka dari depan
                }
                else if ( $i == $juta )
                {
                    $angka=$angka.$a[$i]."."; //meletakkan tanda titik setelah 6angka dari depan
                }
                else if ( $i == $ribu )
                {
                    $angka=$angka.$a[$i]."."; //meletakkan tanda titik setelah 9angka dari depan
                }
                else
                {
                    $angka=$angka.$a[$i];
                }
            }
        }
        return $angka.",-";
    }
}

if ( ! function_exists('tgl_indo'))
{
    function tgl_indo($tgl)
    {
        $ubah = gmdate($tgl, time()+60*60*8);
        $pecah = explode("-",$ubah);
        $tanggal = $pecah[2];
        $bulan = bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal.' '.$bulan.' '.$tahun;
    }
}

if( ! function_exists('indonesian_date')){

    function indonesian_date ($timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = 'WIB') {
        if (trim ($timestamp) == '')
        {
            $timestamp = time ();
        }
        elseif (!ctype_digit ($timestamp))
        {
            $timestamp = strtotime ($timestamp);
        }
        # remove S (st,nd,rd,th) there are no such things in indonesia :p
        $date_format = preg_replace ("/S/", "", $date_format);
        $pattern = array (
            '/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
            '/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
            '/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
            '/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
            '/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
            '/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
            '/April/','/June/','/July/','/August/','/September/','/October/',
            '/November/','/December/',
        );
        $replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
            'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
            'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
            'Januari','Februari','Maret','April','Juni','Juli','Agustus','Sepember',
            'Oktober','November','Desember',
        );
        $date = date ($date_format, $timestamp);
        $date = preg_replace ($pattern, $replace, $date);
        $date = "{$date} {$suffix}";
        return $date;
    }
}

if ( ! function_exists('bulan'))
{
    function bulan($bln)
    {
        switch ($bln)
        {
            case 1:
                return "Januari";
                break;
            case 2:
                return "Februari";
                break;
            case 3:
                return "Maret";
                break;
            case 4:
                return "April";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Juni";
                break;
            case 7:
                return "Juli";
                break;
            case 8:
                return "Agustus";
                break;
            case 9:
                return "September";
                break;
            case 10:
                return "Oktober";
                break;
            case 11:
                return "November";
                break;
            case 12:
                return "Desember";
                break;
        }
    }
}

if ( ! function_exists('nama_hari'))
{
    function nama_hari($tanggal)
    {
        $ubah = gmdate($tanggal, time()+60*60*8);
        $pecah = explode("-",$ubah);
        $tgl = $pecah[2];
        $bln = $pecah[1];
        $thn = $pecah[0];

        $nama = date("l", mktime(0,0,0,$bln,$tgl,$thn));
        $nama_hari = "";
        if($nama=="Sunday") {$nama_hari="Minggu";}
        else if($nama=="Monday") {$nama_hari="Senin";}
        else if($nama=="Tuesday") {$nama_hari="Selasa";}
        else if($nama=="Wednesday") {$nama_hari="Rabu";}
        else if($nama=="Thursday") {$nama_hari="Kamis";}
        else if($nama=="Friday") {$nama_hari="Jumat";}
        else if($nama=="Saturday") {$nama_hari="Sabtu";}
        return $nama_hari;
    }
}

if ( ! function_exists('hitung_mundur'))
{
    function hitung_mundur($wkt)
    {
        $waktu=array(	365*24*60*60	=> "tahun",
            30*24*60*60		=> "bulan",
            7*24*60*60		=> "minggu",
            24*60*60		=> "hari",
            60*60			=> "jam",
            60				=> "menit",
            1				=> "detik");

        $hitung = strtotime(gmdate ("Y-m-d H:i:s", time () +60 * 60 * 8))-$wkt;
        $hasil = array();
        if($hitung<5)
        {
            $hasil = 'kurang dari 5 detik yang lalu';
        }
        else
        {
            $stop = 0;
            foreach($waktu as $periode => $satuan)
            {
                if($stop>=6 || ($stop>0 && $periode<60)) break;
                $bagi = floor($hitung/$periode);
                if($bagi > 0)
                {
                    $hasil[] = $bagi.' '.$satuan;
                    $hitung -= $bagi*$periode;
                    $stop++;
                }
                else if($stop>0) $stop++;
            }
            $hasil=implode(' ',$hasil).' yang lalu';
        }
        return $hasil;
    }
}

if ( ! function_exists('glob_recursive'))
{
    // Does not support flag GLOB_BRACE
    function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
        {
            $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
        }

        return $files;
    }
}

if(!function_exists('file_handler'))
{
    function human_filesize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }
}

?>