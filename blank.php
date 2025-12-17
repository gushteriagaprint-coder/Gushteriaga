<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<?php
	if (isset($_POST["submit"])) {
		$name = $_POST['name'];
		$email = $_POST['email'];
		$message = $_POST['message'];
		$human = intval($_POST['human']);
		$from = 'Относно'; 
		$to = 'gushteriaga.print@gmail.com'; 
		$subject = 'Съобщение от форма за контакт ';
		
		$body ="от: $name\n E-Mail: $email\n Съобщение:\n $message\n От IP:\n {$_SERVER['REMOTE_ADDR']}\r\n\r\n От адрес:  {$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}";

		// Check if name has been entered
		if (!$_POST['name']) {
			$errName = 'Моля, въведете вашето име.';
		}
		
		// Check if email has been entered and is valid
		if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$errEmail = 'Моля въведете валиден имейл!';
		}
		
		//Check if message has been entered
		if (!$_POST['message']) {
			$errMessage = 'Моля въведете вашето съобщение';
		}
		//Check if simple anti-bot test is correct
		if ($human !== 5) {
			$errHuman = 'Вашият отговор е грешен!';
		}

// If there are no errors, send the email
if (!$errName && !$errEmail && !$errMessage && !$errHuman) {
	if (mail ($to, $subject, $body, $from)) {
		$result='<div class="alert alert-success">Благодарим ви! Ще ви отговорим възможно най-скоро.</div>';
	} else {
		$result='<div class="alert alert-danger">Съжаляваме, появи се грешка при изпращането на писмото. Моля, опитайте отново по-късно.</div>';
	}
}
	}
?>
<?php
// ------------------------------------------------------------

header('Content-Type: text/html; charset=UTF-8');

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_regex_encoding('UTF-8');

// ------------------------------------------------------------ 

// if the from is loaded from WordPress form loader plugin,
// the phpfmg_display_form() will be called by the loader
if( !defined('FormmailMakerFormLoader') ){
    # This block must be placed at the very top of page.
    # --------------------------------------------------
	require_once( dirname(__FILE__).'/form.lib.php' );
    phpfmg_display_form();
    # --------------------------------------------------
};
function phpfmg_form( $sErr = false ){
		$style=" class='form_text' ";
?>
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>
        <!-- Title -->
        <title>Контакти | Гущеряга</title>
        <!-- Meta -->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <!-- Favicon -->
       <link rel="shortcut icon" href="http://gushteriaga.com/" />
        <link rel="shortcut icon" type="image/png" href="http://gushteriaga.com/G_logo.png"/>
        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.css" >
        <!-- Template CSS -->
        <link rel="stylesheet" href="assets/css/animate.css" >
        <link rel="stylesheet" href="assets/css/font-awesome.css" >
        <link rel="stylesheet" href="assets/css/nexus.css" >
        <link rel="stylesheet" href="assets/css/responsive.css">
        <!-- Google Fonts-->
        <link href="http://fonts.googleapis.com/css?family=Roboto+Condensed:400,300" rel="stylesheet" type="text/css">
        <link href="contact/validate.css" rel="stylesheet" type="text/css" media="all"/>
<style>
#mouse {
	cursor:pointer;
}
</style>
    </head>
    <body>
        <div id="body-bg">
        <!-- Phone/Email -->
            <div id="pre-header" class="background-gray-lighter">
             <!-- Button trigger modal -->

                <div class="container no-padding">
                    <div class="row"> 
                    <div class="col-sm-6 padding-vert-5"></div>
                    <div class="col-sm-6 text-right padding-vert-5">
                    <a id="mouse" data-toggle="modal" data-target="#myModal">
  <strong>Запитване</strong>
</a>

<!-- Modal Core -->
<div class="col-sm-6 text-left padding-vert-5">
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h2 class="modal-title" id="myModalLabel">Запитване за цена</h2>
        <p>* задължителни полета</p>
      </div>
      <div class="modal-body">
      <!--Start Modal Body-->
        <div id='frmFormMailContainer'>
                            
<form name="frmFormMail" id="frmFormMail" target="submitToFrame" class="login-page" action='<?php echo PHPFMG_ADMIN_URL . '' ; ?>' method='post' enctype='multipart/form-data' onsubmit='return fmgHandler.onSubmit(this);'>
<input type='hidden' name='formmail_submit' value='Y'>
<input type='hidden' name='mod' value='ajax'>
<input type='hidden' name='func' value='submit'>
<ol class='phpfmg_form' >
<li class='field_block' id='field_0_div'><div class='col_label'>
	<label class='form_field' for="email">Вашият E-mail</label> <label class='form_required' >*</label> </div>
    <div class="row margin-bottom-20">
      <div class="col-md-6 col-md-offset-0 col-lg-9">
      <div class="input-group">
       <div class="input-group-addon">
        <i class="fa fa-envelope-o">
        </i>
       </div>
	<input type="text" name="field_0"  id="field_0" placeholder="example@domain.com" value="<?php  phpfmg_hsc("field_0", ""); ?>" class='form-control'>
	<div id='field_0_tip' class='instruction'></div>
	</div></div>
</li>

<li class='field_block' id='field_1_div'><div class='col_label'>
	<label class='form_field'>Печатно изделие:</label> <label class='form_required' >&nbsp;</label> </div>
	<div class="row margin-bottom-20">
      <div class="col-md-6 col-md-offset-0 col-lg-9">
      <div class="input-group">
       <div class="input-group-addon">
        <i class="fa-file-image-o">
        </i>
       </div>
	<input type="text" name="field_1"  id="field_1" placeholder="флаер" value="<?php  phpfmg_hsc("field_1", ""); ?>" class='form-control'>
	<div id='field_1_tip' class='instruction'></div>
	</div></div>
</li>

<li class='field_block' id='field_2_div'><div class='col_label'>
	<label class='form_field'>Обрязан формат: </label> <label class='form_required' >*</label> </div>
	<div class="row margin-bottom-20">
      <div class="col-md-6 col-md-offset-0 col-lg-9">
      <div class="input-group">
       <div class="input-group-addon">
        см
       </div>
	<input type="text" name="field_2"  id="field_2" placeholder="10х10 см" value="<?php  phpfmg_hsc("field_2", ""); ?>" class='form-control'>
	<div id='field_2_tip' class='instruction'></div>
	</div></div>
</li>
<li class='field_block' id='field_3_div'><div class='col_label'>
	<label class='form_field'>Цветност:</label> <label class='form_required' >*</label> </div>
	<div class="row margin-bottom-20">
      <div class="col-md-6 col-md-offset-0 col-lg-9">
       <div class="input-group">
       <div class="input-group-addon">
        <i class="fa-eye">
        </i>
       </div>
    	<input type="text" name="field_3"  id="field_3" placeholder="пълноцветно" value="<?php  phpfmg_hsc("field_3", ""); ?>" class='form-control'>
	<div id='field_3_tip' class='instruction'></div>
	</div>
</li>
<li class='field_block' id='field_5_div'><div class='col_label'>
	<label class='form_field'>Изберете:</label> <label class='form_required' >*</label> </div>
	<div class="col-md-6 col-md-offset-0 col-lg-9">
	<?php phpfmg_radios( 'field_5', "Едностранно|Двустранно" );?>
	<div id='field_5_tip' class='instruction'></div>
	</div>
</li>
<li class='field_block' id='field_4_div'><div class='col_label'>
	<label class='form_field'>Единични бройки:</label> <label class='form_required' >*</label> </div>
	<div class="row margin-bottom-20">
      <div class="col-md-6 col-md-offset-0 col-lg-9">
      <div class="input-group">
       <div class="input-group-addon">
        <i class="fa-pencil">
        </i>
       </div>
	<input type="text" name="field_4"  id="field_4" placeholder="2000" value="<?php  phpfmg_hsc("field_4", ""); ?>" class='form-control'>
	<div id='field_4_tip' class='instruction'></div>
	</div></div>
</li>

<li class='field_block' id='field_6_div'><div class='col_label'>
	<label class='form_field'>Вид хартия/картон:</label> <label class='form_required' >*</label> </div>
	<div class="row margin-bottom-20">
      <div class="col-md-6 col-md-offset-0 col-lg-9">
      <div class="input-group">
       <div class="input-group-addon">
        <i class="fa-pencil">
        </i>
       </div>
	<input type="text" name="field_6"  id="field_6" placeholder="80 гр офсет" value="<?php  phpfmg_hsc("field_6", ""); ?>" class='form-control'>
	<div id='field_6_tip' class='instruction'></div>
	</div></div>
</li>


<li class='field_block' id='field_7_div'><div class='col_label'>
	<label class='form_field'>Допълнителна информация:</label> <label class='form_required' >&nbsp;</label> </div>
	<div class="row margin-bottom-20">
      <div class="col-md-6 col-md-offset-0 col-lg-9">
      <div class="input-group">
       <div class="input-group-addon">
        <i class="fa-pencil-square-o">
        </i>
       </div>
	<textarea name="field_7" id="field_7" placeholder="сгъване, биговане, щанцоване..." rows=6 cols=25 class='form-control'><?php  phpfmg_hsc("field_7"); ?></textarea>

	<div id='field_7_tip' class='instruction'></div>
	</div></div>
</li>

<li class='field_block' id='field_8_div'><div class='col_label'>
	<label class='form_field'>Изберете файл:</label> <label class='form_required' >&nbsp;</label> </div>
	<div class="row margin-bottom-20">
      <div class="col-md-6 col-md-offset-0 col-lg-9">
	<input type="file" name="field_8"  id="field_8" value="" class='form-control' onchange="fmgHandler.check_upload(this);">
	<div id='field_8_tip' class='instruction'>doc, ai, pdf, jpg, jpeg, png, rar</div>
	</div></div>
</li>
            <li>
            <div class='col_label'>&nbsp;</div>
            <div class='form_submit_block col_field'>
                <input type='submit' value='Изпрати' class='form_button'>
<div class="col-sm-12 col-sm-offset-2"></div>
				<div id='err_required' class="form_error" style='display:none;'>
				    <label class='form_error_title'>Моля, попълнете задължителните полета!</label>
				</div>
                <span id='phpfmg_processing' style='display:none;'>
                    <img id='phpfmg_processing_gif' src='<?php echo PHPFMG_ADMIN_URL . '?mod=image&amp;func=processing' ;?>' border=0 alt='Processing...'> <label id='phpfmg_processing_dots'></label>
                </span>
            </div>
            </li>
</ol>
</form>
</div>
<iframe name="submitToFrame" id="submitToFrame" src="javascript:false" style="position:absolute;top:-10000px;left:-10000px;" /></iframe>
</div>
<!-- end of form container -->
<!-- [Your confirmation message goes here] -->
<div class="row">
<div class="col-lg-8">
<div id='thank_you_msg' style='display: none;'>
<p  align="center" style="float:right">Вашето запитване беше изпратено успешно! <br>Ще ви отговорим възможно най-скоро!</p>
</div>    
</div>
<?php

    phpfmg_javascript($sErr);

}
# end of form

function phpfmg_form_css(){
    $formOnly = isset($GLOBALS['formOnly']) && true === $GLOBALS['formOnly'];
?>
<style type='text/css'>
<?php 
if( !$formOnly ){
    echo"
body{
    margin-left: 25px;
    margin-top: 18px;
    font-size : 13px;
    color : #656565;
    background-color: transparent;
}

select, option{
    font-size:13px;
}
";
}; // if
?>

ol.phpfmg_form{
    list-style-type:none;
    padding:0px;
    margin:0px;
}

ol.phpfmg_form input, ol.phpfmg_form textarea, ol.phpfmg_form select{
    border: 1px solid #ccc;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
}

ol.phpfmg_form li{
    margin-bottom:5px;
    clear:both;
    display:block;
    overflow:hidden;
	width: 100%
}


.form_field, .form_required{
    font-weight : bold;
}

.form_required{
    color:red;
    margin-right:8px;
}

.field_block_over{
}

.form_submit_block{
    padding-top: 3px;
}

.text_box,.text_select {
    height: 32px;
}

.text_box, .text_area, .text_select {
    min-width:160px;
    max-width:300px;
    width: 100%;
    margin-bottom: 10px;
	align-content:center;
}

.text_area{
    height:80px;
}

.form_error_title{
    font-weight: normal;
    color: red;
}

.form_error{
   color: #ff0000;
   font-size: 12px;
   margin-top: 5px;
   margin-bottom: 0;
}

.form_error_highlight{
   border: 1px solid #ff0000;
   color: #0e0e0e;
}

div.instruction_error{
    color: red;
    font-weight:bold;
}

hr.sectionbreak{
    height:1px;
    color: #ccc;
}

#one_entry_msg{
    background-color: #F5EBED;
    border: 1px dashed #ff0000;
    padding: 10px;
    margin-bottom: 10px;
}
#frmFormMailContainer input[type="submit"]{
    padding: 10px 25px; 
    font-weight: bold;
    margin-bottom: 10px;
    background-color: #FAFBFC;
}

#frmFormMailContainer input[type="submit"]:hover{
    background-color: #359FA8;
}

<?php phpfmg_text_align();?>    
</style>

<?php
}
?>
                        <!-- End Register Box -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-simple" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--End Modal Body-->
                        </div>
                        </div>

                    </div>
                </div>
            </div>
            </div>
            <!-- End Phone/Email -->
            <!-- Header -->
            <div id="header">
                <div class="container">
                    <div class="row">
                        <!-- Logo -->
                        <div class="logo">
                            <a href="index.html" title="">
                                <img src="assets/img/logoG.png" alt="Logo" />
                            </a>
                        </div>
                        <!-- End Logo -->
                    </div>
                </div>
            </div>
            <!-- End Header -->
            <!-- Top Menu -->
            <div id="hornav" class="bottom-border-shadow">
                <div class="container no-padding border-bottom">
                    <div class="row">
                        <div class="col-md-8 no-padding">
                            <div class="visible-lg">
                                <ul id="hornavmenu" class="nav navbar-nav">
                                    <li>
                                        <a href="index.html" class="fa-home active">НАЧАЛО</a>
                                    </li>
                                    <li>
                                        <a href="services.html" class="fa-files-o">УСЛУГИ</a>
                                    </li>
                                    <li>
                                       <a href="portfolio.html" class="fa-th ">ГАЛЕРИЯ</a>
                                    </li>
                                    <li>
                                        <a href="contact_embed.html" class="fa-comment ">КОНТАКТ</a>
                                    </li>
                                    <li>
                                   
                                    </li>
                                </ul>
                            </div>
                        </div>                     
  
  
</div>
</div>
            <!-- Contact Form -->
<!-- End Contact Form -->
    </div>
            <!-- End Top Menu -->
            <!-- === END HEADER === -->
            <div id="content">
            
              <div class="container background-white">
                    <div class="row margin-vert-30">
                        <!-- Main Column -->
                      <div class="col-md-9 col-lg-5">
                            <!-- Main Content -->
                            <div class="headline">
                                <h2>Пишете ни</h2>
                            </div>
                            <!-- Contact Form -->
                            <!-- Contact Form -->
                          <form class="form-horizontal" role="form" method="post" action="blank.php">
            <label for="name" >Вашето име:
            <span class="color-red">*</span></label>
            <div class="row margin-bottom-20">
                <div class="col-md-6 col-md-offset-0 col-lg-9">
                    <input class="form-control" type="text" id="name" name="name" placeholder="Вашето име" value="<?php echo htmlspecialchars($_POST['name']); ?>">
                    <?php echo "<p class='text-danger'>$errName</p>";?>
                </div>
            </div>
            <label for="email">Вашият имейл:
                <span class="color-red">*</span>
            </label>
            <div class="row margin-bottom-20">
                <div class="col-md-6 col-md-offset-0 col-lg-9">
                    <input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com" value="<?php echo htmlspecialchars($_POST['email']); ?>">
                    <?php echo "<p class='text-danger'>$errEmail</p>";?>
                </div>
            </div>
            <label for="message">Съобщение:</label>
                <span class="color-red">*</span>
            <div class="row margin-bottom-20">
                <div class="col-md-8 col-md-offset-0 col-lg-10">
                    <textarea rows="6" class="form-control" name="message"><?php echo htmlspecialchars($_POST['message']);?></textarea>
                    <?php echo "<p class='text-danger'>$errMessage</p>";?>
                </div>
            </div>
            
            <label for="human">2 + 3 = ?</label>
            <div class="row margin-bottom-20">
                <div class="col-md-8 col-md-offset-0 col-lg-10">
                    <input type="text" class="form-control" id="human" name="human" placeholder="Вашият отговор">
                        <?php echo "<p class='text-danger'>$errHuman</p>";?>
                </div>
            </div>
            <p><button type="submit" id="submit" name="submit" class="btn btn-primary">Изпрати</button>
            <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <?php echo $result; ?>	
                    </div>
                </div>
            </form>
            
                            <!-- End Contact Form -->
                            <!-- End Main Content -->
                        </div>
                        <!-- End Main Column -->
                        <!-- Side Column -->
                        <div class="col-md-3">
                            <!-- Recent Posts -->
                            <!-- About -->
                            <div class="panel panel-default">
                                <div class="panel-body">
 <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2931.804979973996!2d23.36037000000001!3d42.70785000000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40aa8f7bbbd91483%3A0x85c542deddf0b45a!2z0JPQo9Cp0JXQoNCv0JPQkA!5e0!3m2!1sbg!2sus!4v1402658303234" style="overflow: hidden; height: 500 px; width: 100%; position: relative;" width="345" height="500" frameborder="0" ></iframe>
                                </div>
                            </div>
                            <!-- End About -->
                            
                            <!-- End recent Posts -->
                        </div>
                            <!-- End recent Posts -->
                        </div>
                        <!-- End Side Column -->
                    </div>
                </div>
            <!-- === END CONTENT === -->
            <!-- === BEGIN FOOTER === -->
            <div id="base">
                <div class="container bottom-border padding-vert-30">
                    <div class="row">
                        <!-- Disclaimer -->
                        <div class="col-md-4">
                            <h3 class="class margin-bottom-10">
                                <span class="fa-phone"></span>0898 624 236</h3>
                        </div>
                        <!-- End Disclaimer -->
                        <!-- Contact Details -->
                        <div class="col-md-4">
                            <h3 class="margin-bottom-10">
                            <span class="fa-envelope"></span><a href="mailto:office@gushteriaga.com" id="mail">office@gushteriaga.com</a></h3>
                        </div>
                        <!-- End Contact Details -->
                        <!-- Sample Menu -->
                        <div class="col-md-4 margin-bottom-20">
                            <h3 class="margin-bottom-10"><span class="fa-link"></span>София, България</h3>
                            <div class="clearfix">
                            </div>
                        </div>
                        <!-- End Sample Menu -->
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <div id="footer" class="background-grey">
                <div class="container">
                    <div class="row">
                        <!-- Footer Menu -->
                        <div id="footermenu" class="col-md-8"> </div>
                        <!-- End Footer Menu -->
                        <!-- Copyright -->
                        <div id="copyright" class="col-md-4">
                            <p class="pull-right">&copy; 2024 Гущеряга ЕООД</p>
                        </div>
                        <!-- End Copyright -->
                    </div>
                </div>
            </div>
            <!-- End Footer -->
            <!-- JS -->
            <script type="text/javascript" src="assets/js/jquery.min.js"></script>
            <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="assets/js/scripts.js"></script>
            <!-- Isotope - Portfolio Sorting -->
            <script type="text/javascript" src="assets/js/jquery.isotope.js"></script>
            <!-- Mobile Menu - Slicknav -->
            <script type="text/javascript" src="assets/js/jquery.slicknav.js"></script>
            <!-- Animate on Scroll-->
            <script type="text/javascript" src="assets/js/jquery.visible.js" charset="utf-8"></script>
            <!-- Sticky Div -->
            <script type="text/javascript" src="assets/js/jquery.sticky.js" charset="utf-8"></script>
            <!-- Slimbox2-->
            <script type="text/javascript" src="assets/js/slimbox2.js" charset="utf-8"></script>
            <!-- Modernizr -->
            <script src="assets/js/modernizr.custom.js" type="text/javascript"></script>
            <!-- End JS -->
         <!--Responsive menu-->
<script>
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>
<!--END Responsive menu-->
    </body>
</html>
<!-- === END FOOTER === -->