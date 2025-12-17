<?php
	if (isset($_POST["submit"])) {
		$name = $_POST['name'];
		$email = $_POST['email'];
		$message = $_POST['message'];
		$response = $_POST["g-recaptcha-response"];
		$from = 'Относно'; 
		$to = 'gushteriaga.office@gmail.com'; 
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
		
// If there are no errors, send the email
if (!$errName && !$errEmail && !$errMessage) {
	if (mail ($to, $subject, $body, $from)) {
		$result='<div class="alert alert-success">Благодарим ви! Ще ви отговорим възможно най-скоро.</div>';
	} else {
		$result='<div class="alert alert-danger">Съжаляваме, появи се грешка при изпращането на писмото. Моля, опитайте отново по-късно.</div>';
	}
}
	}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>
        <!-- Title -->
        <title>Контакт с нас | Гущеряга</title>
        <!-- Meta -->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="author" content="BootstrapBay.com">
        <meta name="description" content="Печатница Гущеряга предлага офсетов печат и дизайн на рекламни и офис материали, на пълната гама от хартии, картони и пликове.Специфични фирмени формуляри от химизирана хартия със номерация и перфорация.">
        <meta name="author" content="">
        <meta name="keywords" content="Печатница, Печатница Гущеряга, печат, пликове, визитки, фактури, бланки, брошури, формуляри, набор, сгъване, картички, покани, дизайн, графичен дизайн, уеб дизайн, предпечат, календари, фирмени календари, офсетов печат, номерация, перфорация, флаери, листовки, електронен монтаж на файлове, подложки за хранене"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <!-- Favicon -->
        <link rel="shortcut icon" href="http://gushteriaga.com/" />
        <link rel="shortcut icon" type="image/png" href="http://gushteriaga.com/G_logo.png"/>
        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.css">
        <!-- Template CSS -->
        <link rel="stylesheet" href="assets/css/animate.css">
        <link rel="stylesheet" href="assets/css/font-awesome.css">
        <link rel="stylesheet" href="assets/css/nexus.css">
        <link rel="stylesheet" href="assets/css/responsive.css">
        <link rel="stylesheet" href="assets/css/custom.css">
        <!-- Google Fonts-->
        <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet"> 
        <link rel="stylesheet" href="assets/css/font-awesome.css">
        <script src="https://www.google.com/recaptcha/api.js?render=6Lf7BlsmAAAAADyhdj1W3YjMZQs7xDXEqLxEGAuu"></script>
    </head>
    <body>
        <div id="body-bg">
            <!-- Phone/Email -->
            <div id="pre-header" class="background-gray-lighter">
                <div class="container no-padding">
                    <div class="row hidden-xs"> </div>
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
                                        <a href="index.html" class="fa-home ">НАЧАЛО</a>
                                    </li>
                                    <li>
                                        <a href="services.html" class="fa-files-o">УСЛУГИ</a>
                                    </li>
                                    <li>
                                       <a href="portfolio.html" class="fa-th ">ГАЛЕРИЯ</a>
                                    </li>
                                    <li>
                                        <a href="contact.php" class="fa-location-arrow active">КОНТАКТ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                                                <div class="col-md-4 no-padding">
                       <ul class="social-icons pull-right">
                                <li class="social-tumblr">
                                    <a href="pages-faq.html" target="_self" title="GDPR"></a>
                                </li></ul>      

                    </div></div>
                </div>
            </div>
            <!-- End Top Menu -->
            <!-- === END HEADER === -->
            <!-- === BEGIN CONTENT === -->
            <div id="content">
              <div class="container background-white">
                    <div class="row margin-vert-30">
                        <!-- Main Column -->
                      <div class="col-md-9 col-lg-5">
                            <!-- Main Content -->
                            <div class="headline">
                                <h2>Пишете ни</h2>
                            </div>
                            <br>
                            <!-- Contact Form -->
                          <form class="form-horizontal" role="form" method="post" action="contacts.php">
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
        <!--Captcha-->
            <div class="g-recaptcha" data-sitekey="6Lf7BlsmAAAAADyhdj1W3YjMZQs7xDXEqLxEGAuu"data-callback='onSubmit' data-action='submit'></div>
            <?php echo "<p class='text-danger'>$errMessage</p>";?></div>
            <p></p>
 <!--End Captcha-->
            <p><button type="submit" id="submit" name="submit" class="btn btn-primary">Изпрати</button>
            if($recaptchaError){
    print "<div class='error'>$recaptchaError</div>";
}
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
                           
                    <!---->
                    <hr>
                      <div class="row animate fadeInUp">
                          <p class="text-center margin-bottom-0">С попълването на регистрационната форма за запитване, Вие се съгласявате Вашите лични данни, изисквани от нас да бъдат обработвани за целите на подаване и изпълнение на възложената ни поръчка в съответствие с Политиката ни за защита на личните данни, достъпна за Вас от сайта ни, който ползвате за подаване на запитване. Вашите лични данни ще бъдат обработвани само и единствено за целите на производство на продуктите ни и предоставянето на услугите ни. В политиката ни за защита на личните данни, сме Ви уведомили за правата Ви като физически лица в съответствие с Общия регламент за защита на данните (Регламент (ЕС) 2016/679GDPR).</p>
                      </div>
                            <hr class="margin-bottom-5">
                            <!---->
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
                            <span class="fa-envelope"></span><a href="mailto:gushteriaga.office@gmail.com" id="mail">gushteriaga.office@gmail.com</a></h3>
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
                        <div id="footermenu" class="col-md-8"> 
                        <ul class="list-unstyled list-inline">
                                <li>
                                    <a href="pages-faq.html" target="_self">Общ регламент за защита на личните данни (GDPR)</a>
                                </li>
                                </ul></div>
                        <!-- End Footer Menu -->
                        <!-- Copyright -->
                        <div id="copyright" class="col-md-4">
                            <p class="pull-right">&copy; 2022 Гущеряга ЕООД</p>
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