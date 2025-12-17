<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "gushteriaga.print@gmail.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "df9485" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha||ajax|', "|{$mod}|");
    $public_functions = false !== strpos('|phpfmg_ajax_submit||phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_ajax_submit(){
    $phpfmg_send = phpfmg_sendmail( $GLOBALS['form_mail'] );
    $isHideForm  = isset($phpfmg_send['isHideForm']) ? $phpfmg_send['isHideForm'] : false;

    $response = array(
        'ok' => $isHideForm,
        'error_fields' => isset($phpfmg_send['error']) ? $phpfmg_send['error']['fields'] : '',
        'OneEntry' => isset($GLOBALS['OneEntry']) ? $GLOBALS['OneEntry'] : '',
    );
    
    @header("Content-Type:text/html; charset=$charset");
    echo "<html><body><script>
    var response = " . json_encode( $response ) . ";
    try{
        parent.fmgHandler.onResponse( response );
    }catch(E){};
    \n\n";
    echo "\n\n</script></body></html>";

}


function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    if( !phpfmg_user_isLogin() ){
        exit;
    };

    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_self" title="Запитване" style="color:#cccccc;font-weight:bold;text-decoration:none;font-size:18px";>Запитване</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $filelink =  base64_decode($_REQUEST['filelink']);
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . basename($filelink);

    // 2016-12-05:  to prevent *LFD/LFI* attack. patch provided by Pouya Darabi, a security researcher in cert.org
    $real_basePath = realpath(PHPFMG_SAVE_ATTACHMENTS_DIR); 
    $real_requestPath = realpath($file);
    if ($real_requestPath === false || strpos($real_requestPath, $real_basePath) !== 0) { 
        return; 
    }; 

    if( !file_exists($file) ){
        return ;
    };
    
    phpfmg_util_download( $file, $filelink );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'AAB4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGRoCkMRYAxhDWBsdGpHFRKawtrI2BLQiiwW0ijS6NjpMCUByX9TSaStTQ1dFRSG5D6LO0QFZb2ioaKhrQ2BoCLp5QJdgsQNTDM3NAxV+VIRY3AcANAbQBI5UmVoAAAAASUVORK5CYII=',
			'9511' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WANEQxmmMLQii4lMEWlgCGGYiiwW0CrSwBjCEIomFoKkF+ykaVOnLl01bdVSZPexujI0OqDZAeRhiAm0imCIiUxhbUV3H2sAYwhjqENowCAIPypCLO4DADsHy4VUx4IAAAAAAElFTkSuQmCC',
			'9F7A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WANEQ11DA1qRxUSmiADJgKkOSGIBrWCxgAB0sUZHBxEk902bOjVs1dKVWdOQ3MfqClQxhRGmDgJBegMYQ0OQxASAYowOqOpAbmFtQBVjDcAUG6jwoyLE4j4AmFvLMroZT3wAAAAASUVORK5CYII=',
			'683A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYQxhDGVqRxUSmsLayNjpMdUASC2gRaXRoCAgIQBZrYG1laHR0EEFyX2TUyrBVU1dmTUNyX8gUFHUQva0g8wJDQzDFUNRB3IKqF+JmRhSxgQo/KkIs7gMALuHM3YVNU4gAAAAASUVORK5CYII=',
			'7771' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QkNFQ11DA1pRRFsZGh0aAqZiEQtFEZsCFoXphbgpatW0VUuBEMl9jA4MAWC1SHpZIaIoYiIQURSxALAoVrHQgEEQflSEWNwHACddy++IU+42AAAAAElFTkSuQmCC',
			'E452' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nM2QoRGAMAxFE5ENyj5B4CMa0w1giiCyAR2hplOCbAsS7si/++KZ/y5Qb2fwp3zipxGclDM3TAwyGYj0TMmQQ8dwoQwWGj9NpZR1q6nxEwt+9d5vTMomDv2Gk8kxMpxZRmdQ1PiD/72YB78TPTnNBSNwiVwAAAAASUVORK5CYII=',
			'0A6D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGUMdkMRYAxhDGB0dHQKQxESmsLayNjg6iCCJBbSKNLoCTRBBcl/U0mkrU6euzJqG5D6wOkd0vaKhrg2BKGIiU0DmoYqxBog0OqK5BWhjowOamwcq/KgIsbgPAKFXy0goqGDyAAAAAElFTkSuQmCC',
			'196E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUMDkMRYHVhbGR0dHZDViTqINLo2oIoxgsUYYWJgJ63MWro0derK0Cwk9wHtCHR1RNfLANQbiCbGgkUMi1tCMN08UOFHRYjFfQAEksdOuqzeoAAAAABJRU5ErkJggg==',
			'D436' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QgMYWhlDGaY6IIkFTGGYytroEBCALNbKEMrQEOgggCLG6MrQ6OiA7L6opUuXrpq6MjULyX0BrSKtQHVo5omGOgDNE0G1o5UBXWwKQyu6W7C5eaDCj4oQi/sAWmXN/jNWqs4AAAAASUVORK5CYII=',
			'47A9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM3QzQmAMAyA0fSQDeo+6QYRjIdOEw9uULuBl07pDwopelQ0uT1a+AiUyyj8ad/pS41QgomsdTCQALMxt1oIgbwxTDCitqftSTmXPJcYe9PHCRiVJ/tXxBEKq69aUNd3VJvfrGo5rG7+6n7P7U3fAkeGzIJ6MdzvAAAAAElFTkSuQmCC',
			'89EE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHUMDkMREprC2sjYwOiCrC2gVaXRFExOZgiIGdtLSqKVLU0NXhmYhuU9kCmMgut6AVgYM8wJaWbDYgekWbG4eqPCjIsTiPgBb3cnpPIoD+wAAAABJRU5ErkJggg==',
			'B43F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgMYWhlDGUNDkMQCpjBMZW10dEBWF9DKEMrQEIgqNoXRlQGhDuyk0KilS1dNXRmaheS+gCkirQwY5omGOqCb18rQimkHQyu6W6BuRhEbqPCjIsTiPgCd2sunbCUA+QAAAABJRU5ErkJggg==',
			'8420' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYWhlCgRhJTGQKw1RGR4epDkhiAUBVrA0BAQEo6hhdGRoCHUSQ3Lc0aunSVSszs6YhuU9kikgrQysjTB3UPNFQhynoYkB3BDCg2QHSyYDiFpCbWUMDUNw8UOFHRYjFfQAwMMtjukkf2AAAAABJRU5ErkJggg==',
			'8447' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYWhkaHUNDkMREpjBMZWh1aBBBEgtoZQhlmIoqJjKF0ZUh0KEhAMl9S6OWLl2ZmbUyC8l9IlNEWlkbHVoZUMwTDXUNDZiCKgZyi0MAA6pbQO5zwOJmFLGBCj8qQizuAwDxLcyfJ8nB7QAAAABJRU5ErkJggg==',
			'AFDF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7GB1EQ11DGUNDkMRYA0QaWBsdHZDViUwBijUEoogFtKKIgZ0UtXRq2NJVkaFZSO5DUweGoaEEzUOIobkFLBbKiCI2UOFHRYjFfQCbj8sSPNhU3gAAAABJRU5ErkJggg==',
			'2080' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGVqRxUSmMIYwOjpMdUASC2hlbWVtCAgIQNbdKtLo6OjoIILsvmnTVmaFrsyahuy+ABR1YMjoINLo2hCIIsbagGmHSAOmW0JDMd08UOFHRYjFfQB8B8reNETEDQAAAABJRU5ErkJggg==',
			'8CCC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7WAMYQxlCHaYGIImJTGFtdHQICBBBEgtoFWlwbRB0YEFRJ9LA2sDogOy+pVHTVi1dtTIL2X1o6uDmYRPDtAPTLdjcPFDhR0WIxX0Azf3L3/ytIDMAAAAASUVORK5CYII=',
			'9426' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM2QoRGAQAwEL+I94uknCHwQXwICqsCkA54OEFAlj0sGJAzk3M4lsxPsl5nwp7ziFwSKhMyGxRmZGhYxTEorTB1XjlGLwqzfktd134Z+NH6hjQoldw9aJ56Jo2FVaUE8Ky5KDLd7Oockzvmr/z2YG78DGPHKcTElEbgAAAAASUVORK5CYII=',
			'DFE1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAU0lEQVR4nGNYhQEaGAYTpIn7QgNEQ11DHVqRxQKmiDSwNjBMRRFrBYuFYhGD6QU7KWrp1LCloauWIrsPTR1pYlMwxUIDgGKhDqEBgyD8qAixuA8AbVLNMcX439oAAAAASUVORK5CYII=',
			'F68D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkMZQxhCGUMdkMQCGlhbGR0dHQJQxEQaWRsCHURQxRpA6kSQ3BcaNS1sVejKrGlI7gtoEG1FUgc3zxXTPCxi2NyC6eaBCj8qQizuAwDVPMwLI7zj/gAAAABJRU5ErkJggg==',
			'1F34' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7GB1EQx1DGRoCkMRYHUQaWBsdGpHFRIFiQFWtASh6gWKNDlMCkNy3Mmtq2Kqpq6KikNwHUefogKG3ITA0BEMsoAFdHdAtKGKiISINjGhuHqjwoyLE4j4AxRXL6BIpTHwAAAAASUVORK5CYII=',
			'1388' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7GB1YQxhCGaY6IImxOoi0Mjo6BAQgiYk6MDS6NgQ6iKDoZUBWB3bSyqxVYatCV03NQnIfmjqYGDbzsIhhcUsIppsHKvyoCLG4DwA26ska/wxUEAAAAABJRU5ErkJggg==',
			'53E4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkNYQ1hDHRoCkMQCGkRaWRsYGlHFGBpdGxhakcUCAxhA6qYEILkvbNqqsKWhq6KikN3XClLH6ICsFygGNI8xNATZDrAYA4pbRKaA3YIixhqA6eaBCj8qQizuAwDOAs1iR9DD5wAAAABJRU5ErkJggg==',
			'119D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGUMdkMRYHRgDGB0dHQKQxEQdWANYGwIdRND0IomBnbQya1XUyszIrGlI7gPbEYKplwGLeYzYxNDdEsIaiu7mgQo/KkIs7gMAHV/FyuwuhicAAAAASUVORK5CYII=',
			'1994' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGRoCkMRYHVhbGR0dGpHFRB1EGl0bAloDUPSCxaYEILlvZdbSpZmZUVFRSO4D2hHoEBLogKqXodGhITA0BEWMpdER6BJUdWC3oIiJhmC6eaDCj4oQi/sA4Y/LM/zvOO8AAAAASUVORK5CYII=',
			'D8EB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVUlEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDHUMdkMQCprC2sjYwOgQgi7WKNLoCxURQxFDUgZ0UtXRl2NLQlaFZSO5DU4fHPCxiWNyCzc0DFX5UhFjcBwCOZMx9ODK3awAAAABJRU5ErkJggg==',
			'9049' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WAMYAhgaHaY6IImJTGEMYWh1CAhAEgtoZW1lmOroIIIiJtLoEAgXAztp2tRpKzMzs6LCkNzH6irS6Aq0A1kvA1Cva2hAA7KYAMiORgcUO8BuaUR1CzY3D1T4URFicR8AUuzMSuzBKNcAAAAASUVORK5CYII=',
			'0230' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7GB0YQxhDGVqRxVgDWFtZGx2mOiCJiUwRaXRoCAgIQBILaGVodGh0dBBBcl/U0lVLV01dmTUNyX1AdVMYEOpgYgEMDYEoYiJTGB0Y0OwAuqUB3S2MDqKhjmhuHqjwoyLE4j4AdG/MYrok7N4AAAAASUVORK5CYII=',
			'F9AD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMZQximMIY6IIkFNLC2MoQyOgSgiIk0Ojo6Ooigibk2BMLEwE4KjVq6NHVVZNY0JPcFNDAGIqmDijE0uoaii7E0YqpjbWUFiqG6hTEEKIbi5oEKPypCLO4DAJDGzYtiTP1IAAAAAElFTkSuQmCC',
			'174F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7GB1EQx0aHUNDkMRYHRgaHVodHZDViYLEpqKKMTowtDIEwsXATlqZtWrayszM0Cwk9wHVBbA2outldGANDUQTY21gwFAngiEmGoIpNlDhR0WIxX0AwAfHoaL/dzQAAAAASUVORK5CYII=',
			'E3E2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkNYQ1hDHaY6IIkFNIi0sjYwBASgiDE0ujYwOoigioHUNYgguS80alXY0tBVq6KQ3AdV1+iAYR5DKwOm2BQGLG7BdLNjaMggCD8qQizuAwAWyszex9mmugAAAABJRU5ErkJggg==',
			'1BAB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7GB1EQximMIY6IImxOoi0MoQyOgQgiYk6iDQ6Ojo6iKDoFWllbQiEqQM7aWXW1LClqyJDs5Dch6YOJtboGhqIbl6jawOGGIZe0RDREKAYipsHKvyoCLG4DwCXPsmbqoyHSwAAAABJRU5ErkJggg==',
			'9E1D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WANEQxmmMIY6IImJTBFpYAhhdAhAEgtoFWlgBIqJoIkB9cLEwE6aNnVq2KppK7OmIbmP1RVFHQS2YooJYBEDu2UKqltAbmYMdURx80CFHxUhFvcBAEm9yeCdbk4wAAAAAElFTkSuQmCC',
			'D31D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QgNYQximMIY6IIkFTBFpZQhhdAhAFmtlaHQEiomgirUC9cLEwE6KWroqbNW0lVnTkNyHpg5ungMxYiC3TEF1C8jNjKGOKG4eqPCjIsTiPgA2h8xNHED7YgAAAABJRU5ErkJggg==',
			'CB9E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7WENEQxhCGUMDkMREWkVaGR0dHZDVBTSKNLo2BKKKAVWyIsTATopaNTVsZWZkaBaS+0DqGEIw9DY6oJsHtMMRTQybW7C5eaDCj4oQi/sAWuzKvwG85/EAAAAASUVORK5CYII=',
			'D521' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QgNEQxlCGVqRxQKmiDQwOjpMRRFrFWlgbQgIRRMLAZHI7otaOnXpqpVZS5HdB1TR6NCKZgdIbAq6mEijQwC6W1hbGR1QxUIDGENYQwNCAwZB+FERYnEfAMdVzXj7DBw7AAAAAElFTkSuQmCC',
			'F381' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVUlEQVR4nGNYhQEaGAYTpIn7QkNZQxhCGVqRxQIaRFoZHR2moooxNLo2BISiiYHUwfSCnRQatSpsVeiqpcjuQ1OHbB4RYiJY9ILdHBowCMKPihCL+wA6v80rZj24kwAAAABJRU5ErkJggg==',
			'A06A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGVqRxVgDGEMYHR2mOiCJiUxhbWVtcAgIQBILaBVpdAWaIILkvqil01amTl2ZNQ3JfWB1jo4wdWAYGgrSGxgagmIeyI5AFHUBrSC3OKKJgdzMiCI2UOFHRYjFfQA2BMuPh37QeQAAAABJRU5ErkJggg==',
			'E0B8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QkMYAlhDGaY6IIkFNDCGsDY6BASgiLG2sjYEOoigiIk0uiLUgZ0UGjVtZWroqqlZSO5DU4cQwzAPmx2YbsHm5oEKPypCLO4DAFePze/zLw/aAAAAAElFTkSuQmCC',
			'2161' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGVqRxUSmMAYwOjpMRRYLaGUNYG1wCEXR3coAFIPrhbhp2qqopVNXLUVxH9AOVkcHFDsYHUB6A1DEgGZhiIkAxRjR9IaGsoYC3RwaMAjCj4oQi/sAQ8HJIluGh44AAAAASUVORK5CYII=',
			'8B8D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7WANEQxhCGUMdkMREpoi0Mjo6OgQgiQW0ijS6NgQ6iGBRJ4LkvqVRU8NWha7MmobkPjR1OM3DZweyW7C5eaDCj4oQi/sAQTfLg+ocPx8AAAAASUVORK5CYII=',
			'742D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QkMZWhlCGUMdkEVbGaYyOjo6BKCKhbI2BDqIIItNYXRlQIhB3BS1dOmqlZlZ05Dcx+gg0srQyoiil7VBNNRhCqqYCMiWAFQxoBuAOhlR3AISYw0NRHXzAIUfFSEW9wEAOhzJ5dJj3xoAAAAASUVORK5CYII=',
			'91E9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYAlhDHaY6IImJTGEMYG1gCAhAEgtoZQWKMTqIoIgxIIuBnTRt6qqopaGrosKQ3MfqClLHMBVZLwNYL9AuJDEBiBiKHSJTGDDcAnRJKLqbByr8qAixuA8A23fIsi15wZ4AAAAASUVORK5CYII=',
			'02F9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA6Y6IImxBrC2sjYwBAQgiYlMEWl0BaoWQRILaGVAFgM7KWrpqqVLQ1dFhSG5D6huCtC8qWh6A1hB5qLYwegAFEOxA+iWBnS3MDqIhroCzUN280CFHxUhFvcBAFB1yqjcW2A6AAAAAElFTkSuQmCC',
			'01CE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7GB0YAhhCHUMDkMRYAxgDGB0CHZDViUxhDWBtEEQRC2hlAIoxwsTATopaCkIrQ7OQ3IemDqeYyBQGDDtYAxgw3MLowBqK7uaBCj8qQizuAwCp1Mb4+eBUYwAAAABJRU5ErkJggg==',
			'38EC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDHaYGIIkFTGFtZW1gCBBBVtkq0ujawOjAgiwGVsfogOy+lVErw5aGrsxCcR+qOhTzsIkh24HNLdjcPFDhR0WIxX0A2OPKVJno1AsAAAAASUVORK5CYII=',
			'D2C3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QgMYQxhCHUIdkMQCprC2MjoEOgQgi7WKNLo2CDSIoIgxAMWANJL7opauWrp01aqlWUjuA6qbwopQBxMLAImhmsfowIpuB1AnultCA0RBLkZx80CFHxUhFvcBAO+jzlLKzqITAAAAAElFTkSuQmCC',
			'65B2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2QMQrAIAxF45AbpPdx6Z6CGeppIrQ3sEdw8ZTVLdKOLZgPGR6BPD7UxyjMlF/8kBdBgcsbRpkUk2c2jI/GdPNkmVJod0rGb49XKVJrNH4hQ1qTT/YHn431PTDqLMPggmd3GZ1dQHESJujvw7z43Ruuzb1LxkgXAAAAAElFTkSuQmCC',
			'9CA1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WAMYQxmmMLQii4lMYW10CGWYiiwW0CrS4OgIFEUTYwWSyO6bNnXaqqWropYiu4/VFUUdBIL0hqKKCQDFXNHUgdyCLgZyM9C80IBBEH5UhFjcBwBCB809x5pGrwAAAABJRU5ErkJggg==',
			'5A1E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkMYAhimMIYGIIkFNDCGMIQwOjCgiLG2MqKJBQaINDpMgYuBnRQ2bdrKrGkrQ7OQ3deKog4qJhqKLhaARZ3IFEwxVqC9jqGOKG4eqPCjIsTiPgC1QcpwZKwOHwAAAABJRU5ErkJggg==',
			'4B2C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpI37poiGMIQyTA1AFgsRaWV0dAgQQRJjDBFpdG0IdGBBEmOdItLKABRDdt+0aVPDVq3MzEJ2XwBIXSujA7K9oaEijQ5TUMUYpgDFAhhR7ACKgXSiuAXkZtbQAFQ3D1T4UQ9icR8ARnLKtoiwN1YAAAAASUVORK5CYII=',
			'3FDA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7RANEQ11DGVqRxQKmiDSwNjpMdUBW2QoUawgICEAWA6lrCHQQQXLfyqipYUtXRWZNQ3Yfqjok8wJDQzDFUNRB3OKIIiYaABQLZUQ1b4DCj4oQi/sAZGjMKlyg1DcAAAAASUVORK5CYII=',
			'AB7C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDA6YGIImxBoi0MjQEBIggiYlMEWl0aAh0YEESC2gFqmt0dEB2X9TSqWGrlq7MQnYfWN0URgdke0NDgeYFoIoB1QFNY8Swg7WBAcUtAa1ANzcwoLh5oMKPihCL+wAI9swg889tXQAAAABJRU5ErkJggg==',
			'69BD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGUMdkMREprC2sjY6OgQgiQW0iDS6NgQ6iCCLNQDFgOpEkNwXGbV0aWroyqxpSO4LmcIYiKQOoreVAdO8VhYMMWxuwebmgQo/KkIs7gMAsqTMnYxDSvgAAAAASUVORK5CYII=',
			'0BDE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDGUMDkMRYA0RaWRsdHZDViUwRaXRtCEQRC2gFqkOIgZ0UtXRq2NJVkaFZSO5DUwcTwzAPmx3Y3ILNzQMVflSEWNwHAGBXysj1c0aJAAAAAElFTkSuQmCC',
			'8388' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7WANYQxhCGaY6IImJTBFpZXR0CAhAEgtoZWh0bQh0EEFRx4CsDuykpVGrwlaFrpqaheQ+NHU4zcNuB6ZbsLl5oMKPihCL+wA3t8xGly2EVwAAAABJRU5ErkJggg==',
			'BD87' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QgNEQxhCGUNDkMQCpoi0Mjo6NIggi7WKNLo2BKCKTRFpdASqC0ByX2jUtJVZoatWZiG5D6qulQHTvClYxAIYMNzi6IDFzShiAxV+VIRY3AcAru7N+Q5EO/cAAAAASUVORK5CYII=',
			'F8B7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDGUNDkMQCGlhbWRsdGkRQxEQaXUEkFnUBSO4LjVoZtjR01cosJPdB1bUyYJo3BYtYAAOGHY4OqGJgN6OIDVT4URFicR8AqdvOA9i2CNsAAAAASUVORK5CYII=',
			'45A7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpI37poiGMkxhDA1BFgsRaWAIZWgQQRJjBIoxOjqgiLFOEQlhbQgAQoT7pk2bunTpqqiVWUjuC5jC0OjaENCKbG9oKFAsFCiD4hYRkLoAVDHWVtaGQAdUMcYQDLGBCj/qQSzuAwBzmMyIvcnXygAAAABJRU5ErkJggg==',
			'4774' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpI37poiGuoYGNAQgi4UwNDo0BDQiizFCxFqRxVinMLQCRacEILlv2rRV01YtXRUVheS+gCkMAQxTGB2Q9YaGMjowBDCGhqC4hbUBKIrqlikiDawNRIgNVPhRD2JxHwBjos2yMVBYcwAAAABJRU5ErkJggg==',
			'DE36' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QgNEQxlDGaY6IIkFTBFpYG10CAhAFmsVAZKBDgLoYo2ODsjui1o6NWzV1JWpWUjug6rDap4IITEsbsHm5oEKPypCLO4DAGDPzfg7/duWAAAAAElFTkSuQmCC',
			'B66E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGUMDkMQCprC2Mjo6OiCrC2gVaWRtQBObItLA2sAIEwM7KTRqWtjSqStDs5DcFzBFtJUVi3muDYGExbC4BZubByr8qAixuA8A8VPLR+bncLgAAAAASUVORK5CYII=',
			'5774' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkNEQ11DAxoCkMSA7EaHhoBGLGKtyGKBAQytQNEpAUjuC5u2atqqpauiopDd18oQwDCF0QFZL0MrowNDAGNoCLIdrawNQFEUt4hMEWlgbUAVYw3AFBuo8KMixOI+ADDvzhjRKKbCAAAAAElFTkSuQmCC',
			'E7B9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkNEQ11DGaY6IIkFNDA0ujY6BASgizUEOoigirWyNjrCxMBOCo1aNW1p6KqoMCT3AdUFsDY6TEXVy+jACiRRxViBMADNDpEGVjS3hIYAxdDcPFDhR0WIxX0AdUHN7yf9eZsAAAAASUVORK5CYII=',
			'9A6A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeklEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGVqRxUSmMIYwOjpMdUASC2hlbWVtcAgIQBETaXRtYHQQQXLftKnTVqZOXZk1Dcl9rK5AdY6OMHUQ2Coa6toQGBqCJCYANi8QRZ3IFJFGRzS9rAEijQ6hjKjmDVD4URFicR8AqZ3L1+VC/P8AAAAASUVORK5CYII=',
			'52D5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDGUMDkMQCGlhbWRsdHRhQxEQaXRsCUcQCAxhAYq4OSO4Lm7Zq6dJVkVFRyO5rZZjCCjIB2eZWhgB0sYBWRgdWoB3IYiJAnayNDgHI7mMNEA11DWWY6jAIwo+KEIv7AI2uzHqICT4ZAAAAAElFTkSuQmCC',
			'A52B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2QsQ2AMAwE7SIbmH3MBkbCDRvAFEnhDcIIFDAlKZ1ACQJ/d9K/TobjchH+lFf8kDsFRWXHglDEvmdxjDLFEAcmx8RohMLE+U3buh37rIvzE4PEhtWeamEZ273E0rJQmnVXDMegQ+X81f8ezI3fCQMFy3+nSe1wAAAAAElFTkSuQmCC',
			'D546' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QgNEQxkaHaY6IIkFTBFpYGh1CAhAFmsFik11dBBAFQthCHR0QHZf1NKpS1dmZqZmIbkvoJWh0bXREc08oFhooIMIqnmNDo2OqGJTWIEqUd0SGsAYgu7mgQo/KkIs7gMAJLnOn/0PvfEAAAAASUVORK5CYII=',
			'7A2F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMZAhhCGUNDkEVbGUMYHR0dUFS2srayNgSiik0RaXRAiEHcFDVtZdbKzNAsJPcxOgDVtTKi6GVtEA11mIIqJtIAVBeAKhYAFHN0wBRzDUVzywCFHxUhFvcBAKgtyY/jrZf3AAAAAElFTkSuQmCC',
			'6592' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM2QwQ2AIAxFy4EN6j64QU3KQTZwi5LIBjCCB5lSuZXoURP6k394l/9SqI8TGCm/+FmaPHgoTjHMKGZ2RIrRjmJlcaiZINvWym8N5Ti3UIPy4wzRMUW9QelmrTuGcRbK0LnY1Fx6Z8PgjecB/vdhXvwu/07M1x9Jgm4AAAAASUVORK5CYII=',
			'8223' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2QsRGAIAxFk4INcJ9Y0P9CGkdgiliwAStQyJRSErXU0/zuXe7nXahdRulPecXPgReKFGVgvrjM8ywYGLLfgkK92aNNOsPgV9dW255qGvz6XqFMavsInZo+ZJZOTzecsrBxcZhiiDDOX/3vwdz4HZ3rzG/1FYdlAAAAAElFTkSuQmCC',
			'7301' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkNZQximMLSiiLaKtDKEMkxFFWNodHR0CEURA+pjbQiA6YW4KWpV2NJVUUuR3cfogKIODFkbGBpd0cREGsB2oIgFNIDdgiYGdnNowCAIPypCLO4DAGQSy7wn5qMdAAAAAElFTkSuQmCC',
			'34CC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7RAMYWhlCHaYGIIkFTGGYyugQECCCrBKoirVB0IEFWWwKoytrA6MDsvtWRi1dunTVyiwU900RaUVSBzVPNNQVQ4yhFd0OoFta0d2Czc0DFX5UhFjcBwDaH8pYz06LlwAAAABJRU5ErkJggg==',
			'F102' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkMZAhimMEx1QBILaGAMYACKB6CIsQYwOjo6iKCIMQSwAkkRJPeFRq2KWroKSCC5D6qu0QFTbysDmhijo8MUdDGQW1DFWEMZpjCGhgyC8KMixOI+AABgyzzD0N1PAAAAAElFTkSuQmCC',
			'15B5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nM2QsQ2AMAwEk8IbOPs8RfogxQ0jMIVTMAIjUMCUhM4BSpDi707/0snueJy6nvKLn0cQEi/JMAIrlQG2Fy6mI9ot59qLMH77vG6b7NNk/DxciQXKzbYyTTfGlY1oGS1UkKxfyD6TuBUd/O/DvPidlHHJk1V5uW0AAAAASUVORK5CYII=',
			'5490' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkMYWhlCgRhJLKCBYSqjo8NUB1SxUNaGgIAAJLHAAEZX1oZABxEk94VNW7p0ZWZk1jRk97WKtDKEwNVBxURDHRpQxQJaGVoZ0ewQmQIUQ3MLawCmmwcq/KgIsbgPAMb8y8lkt4pRAAAAAElFTkSuQmCC',
			'7CCB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkMZQxlCHUMdkEVbWRsdHQIdAlDERBpcGwQdRJDFpog0sDYwwtRB3BQ1bdXSVStDs5Dcx+iAog4MWRsgYsjmiTRg2hHQgOmWgAYsbh6g8KMixOI+AFjyy5jGJ8hnAAAAAElFTkSuQmCC',
			'DA4A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QgMYAhgaHVqRxQKmMIYwtDpMdUAWa2VtZZjqEBCAIibS6BDo6CCC5L6opdNWZmZmZk1Dch9InWsjXB1UTDTUNTQwNATdPHR1UzDFQgMwxQYq/KgIsbgPAHaVzu2/JoJtAAAAAElFTkSuQmCC',
			'6D4A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WANEQxgaHVqRxUSmiLQytDpMdUASC2gRaQSKBAQgizUAxQIdHUSQ3BcZNW1lZmZm1jQk94VMEWl0bYSrg+htBYqFBoaGoIk5oKkDuwVNDOJmVLGBCj8qQizuAwBR7s3BJw1C6gAAAABJRU5ErkJggg==',
			'EA4E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkMYAhgaHUMDkMQCGhhDGFodHRhQxFhbGaaii4k0OgTCxcBOCo2atjIzMzM0C8l9IHWujeh6RUNdQwMxzcNQhykWGgIWQ3HzQIUfFSEW9wEAt9DM6T8QBVQAAAAASUVORK5CYII=',
			'0DA3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB1EQximMIQ6IImxBoi0MoQyOgQgiYlMEWl0dHRoEEESC2gVaXRtCGgIQHJf1NJpK1OBZBaS+9DUIcRCA1DMA9kBUieC5hbWhkAUt4DczNoQgOLmgQo/KkIs7gMANKfOD1JjPpYAAAAASUVORK5CYII=',
			'DC7B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QgMYQ1lDA0MdkMQCprA2OjQEOgQgi7WKNIDERNDEGBodYerATopaOm3VqqUrQ7OQ3AdWN4URwzyGAEYM8xwd0MSAbnFtQNULdnMDI4qbByr8qAixuA8AerXNwU8AT+4AAAAASUVORK5CYII=',
			'2059' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeklEQVR4nGNYhQEaGAYTpIn7WAMYAlhDHaY6IImJTGEMYW1gCAhAEgtoZW1lbWB0EEHW3SrS6DoVLgZx07RpK1Mzs6LCkN0XINLo0BAwFVkvUBdIrAFZjLUBZEcAih0iDYwhjI4OKG4JDWUIYAhlQHHzQIUfFSEW9wEAhSbK6RfyKAMAAAAASUVORK5CYII=',
			'96B8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGaY6IImJTGFtZW10CAhAEgtoFWlkbQh0EEEVa0BSB3bStKnTwpaGrpqaheQ+VldRDPMYgOa5opkngEUMm1uwuXmgwo+KEIv7AMtPzKzOUh/PAAAAAElFTkSuQmCC',
			'52A5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM2QMQ6AMAhF6cAN9D506E6TYqKnwaE3UG/g0lNaNxodNSl/e8mHF6A8RqGn/OInySXYnLBhrJhBHEHDhtV737DIsAaNgYzfdJTzLPOyWL8MG94b7OUMjNIyzo5QI1k21GbtsvVDHiUo79TB/z7Mi98FEvXMT/iLbzkAAAAASUVORK5CYII=',
			'38D8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDGaY6IIkFTGFtZW10CAhAVtkq0ujaEOgggiwGUtcQAFMHdtLKqJVhS1dFTc1Cdh+qOtzmYRHD5hZsbh6o8KMixOI+AFNXzSH6oyjAAAAAAElFTkSuQmCC',
			'FEC4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7QkNFQxlCHRoCkMQCGkQaGB0CGtHFWBsEWjHFGKYEILkvNGpq2NJVq6KikNwHUQc0EUMvY2gIph3Y3IImhunmgQo/KkIs7gMAMq7Opsltt8cAAAAASUVORK5CYII=',
			'FB3F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7QkNFQxhDGUNDkMQCGkRaWRsdHRhQxRodGgLRxVoZEOrATgqNmhq2aurK0Cwk96Gpw2ceVjsw3QJ2M4rYQIUfFSEW9wEAXcbMR8F1iY8AAAAASUVORK5CYII=',
			'5F20' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkNEQx1CGVqRxQIaRBoYHR2mOqCJsTYEBAQgiQUGiIBIBxEk94VNmxq2amVm1jRk97UCVbQywtQhxKagigWAxAIYUOwQmQJ0iwMDiltYgfayhgaguHmgwo+KEIv7ANf/y7GP4fsvAAAAAElFTkSuQmCC',
			'5C78' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7QkMYQ1lDA6Y6IIkFNLA2OjQEBASgiIk0ODQEOoggiQUGAHmNDjB1YCeFTZu2atXSVVOzkN3XClQ3hQHFPLBYACOKeQFAMUcHVDGRKayNrg2oelkDgG5uYEBx80CFHxUhFvcBAPKHzTRlBKjtAAAAAElFTkSuQmCC',
			'9DAE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WANEQximMIYGIImJTBFpZQhldEBWF9Aq0ujo6Igh5toQCBMDO2na1GkrU1dFhmYhuY/VFUUdBIL0hqKKCbRiqgO5hRVNDORmoBiKmwcq/KgIsbgPAP9Ty2J+3s6GAAAAAElFTkSuQmCC',
			'9716' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nM2QsRGAIAxFPwU9Be4TC/sUOIKNU2DBBoEdZEopg1rqaX6Vd0nuXVAvFfGnvOJneZhJkEkxL9gogFkxTtjGYMj1LEEMab+Sa6llX1blZydwm+vuIbW9tusVc8nGM/PSOuldLPtoZuqcv/rfg7nxOwCwq8rj5qshbQAAAABJRU5ErkJggg==',
			'EDED' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAATklEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDHUMdkMQCGkRaWRsYHQJQxRpdgWIiuMXATgqNmrYyNXRl1jQk9xGhF58YhluwuXmgwo+KEIv7ALJqzJlvicgFAAAAAElFTkSuQmCC',
			'14F3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YWllDA0IdkMRYHRimsgJlApDERB0YQlmBtAiKXkZXkFgAkvtWZi1dujR01dIsJPcxOoi0IqmDiomGumKYx9CKaQdIDM0tIWB1KG4eqPCjIsTiPgAA/sjlexJ0rAAAAABJRU5ErkJggg==',
			'92DA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGVqRxUSmsLayNjpMdUASC2gVaXRtCAgIQBFjAIoFOogguW/a1FVLl66KzJqG5D5WV4YprAh1ENjKEAAUCw1BEhNoZXRAVwd0SwNroyOKGGuAaKhrKCOqeQMUflSEWNwHAEH/y/QBFyaiAAAAAElFTkSuQmCC',
			'117F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7GB0YAlhDA0NDkMRYHRgDGBoCHZDViTqwYoiB9DI0OsLEwE5ambUqatXSlaFZSO4Dq5vCiKk3AFOM0QFTjLUBVUw0hDUUXWygwo+KEIv7AOM1xG68Uy7XAAAAAElFTkSuQmCC',
			'0506' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB1EQxmmMEx1QBJjDRBpYAhlCAhAEhOZItLA6OjoIIAkFtAqEsLaEOiA7L6opVOXLl0VmZqF5L6AVoZG14ZAFPOgYg4iqHY0OgLtEEFxC2srulsYHRhD0N08UOFHRYjFfQDo4stCS2MMXgAAAABJRU5ErkJggg==',
			'FDCA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkNFQxhCHVqRxQIaRFoZHQKmOqCKNbo2CAQEYIgxOogguS80atrK1FUrs6YhuQ9NHbJYaAiGmCC6OqBbAtHEQG52RBEbqPCjIsTiPgAgo82aZBgOkAAAAABJRU5ErkJggg==',
			'B9D6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDGaY6IIkFTGFtZW10CAhAFmsVaXRtCHQQQFEHEUN2X2jU0qWpqyJTs5DcFzCFMRCoDs08BrBeERQxFkwxLG7B5uaBCj8qQizuAwASKs6EpEC4NAAAAABJRU5ErkJggg==',
			'B32D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QgNYQxhCGUMdkMQCpoi0Mjo6OgQgi7UyNLo2BDqIoKhjaGVAiIGdFBq1KmzVysysaUjuA6trZUTVCzTPYQoWsQA0MZBbHBhR3AJyM2toIIqbByr8qAixuA8ADiLMB0AJzVcAAAAASUVORK5CYII='        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>