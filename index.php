<?php
	$pageName = 'Home';
	require_once 'secured/assets/config/config.php';
	require_once 'assets/config/functions.php';
	require_once 'assets/config/variables.php';

	$language = checkCookieLanguage();
	if (!isset($_COOKIE['cookiesAccept'])){$cookiesAccept = "N/A";} else {$cookiesAccept = getCookieValue('cookiesAccept', 'N/A');}
	$theme = $systemData['theme'];
?>






<?php
// Configuration - Replace with your own values
define('RECAPTCHA_SECRET_KEY', 'AIzaSyDsFKOUMyXWxLItyQrmIDGnxxeNMk2mFus');
define('TO_EMAIL', 'your-email@example.com');  // Where form submissions go
define('FROM_EMAIL', 'noreply@example.com');   // Should match your domain
define('SUBJECT_PREFIX', 'Contact Form: ');    // Email subject prefix

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize form inputs
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    // Validate inputs
    $errors = [];
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if (empty($subject)) $errors[] = 'Subject is required';
    if (empty($message)) $errors[] = 'Message is required';
    if (empty($recaptcha_response)) $errors[] = 'Please complete the reCAPTCHA';

    // Validate reCAPTCHA if no other errors
    if (empty($errors)) {
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_data = [
            'secret' => RECAPTCHA_SECRET_KEY,
            'response' => $recaptcha_response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];

        $recaptcha_options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($recaptcha_data)
            ]
        ];

        $recaptcha_context = stream_context_create($recaptcha_options);
        $recaptcha_result = file_get_contents($recaptcha_url, false, $recaptcha_context);
        $recaptcha_json = json_decode($recaptcha_result);

        if (!$recaptcha_json->success || $recaptcha_json->score < 0.5) {
            $errors[] = 'reCAPTCHA verification failed';
        }
    }

    // Send email if no errors
    if (empty($errors)) {
        $full_subject = SUBJECT_PREFIX . $subject;
        $email_body = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";
        $headers = "From: " . FROM_EMAIL . "\r\n" .
                   "Reply-To: $email\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        if (mail(TO_EMAIL, $full_subject, $email_body, $headers)) {
            $success = true;
        } else {
            $errors[] = 'Failed to send email. Please try again later.';
        }
    }
}
?>





<!DOCTYPE html>
<html lang="<?php echo $language;?>">

<head>
	<?php require_once 'templates/com_head.php';?>






    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .error { color: red; }
        .success { color: green; }
    </style>






</head>

<body>
    <div class="container-xxl bg-white p-0">
		<?php require_once 'templates/com_spinner.php';?>
        <div class="container-xxl position-relative p-0" id="home">
			<?php require_once 'templates/com_navbar.php';?>

            <div class="container-xxl bg-primary hero-header">
                <div class="container">
                    <div class="row g-5 align-items-center">
                        <div class="col-lg-6 text-center text-lg-start">
                            <div class="position-relative w-100 mt-3">
                                <input class="form-control border-0 rounded-pill w-100 ps-4 pe-5" type="text" placeholder="Package/Order Tracking Number" style="height: 58px;">
                                <button type="button" class="btn btn-primary rounded-pill py-2 px-3 shadow-none position-absolute top-0 end-0 m-2">Track</button>
                            </div>
                            <br/>
                            <h1 class="text-white mb-4 animated slideInDown">Global Marketplace</h1>
<!--
                            <p class="text-white pb-3 animated slideInDown">The Marketplace Ecosystem Dashboard is a centralized, web-based interface designed to provide administrators, managers, and authorized personnel with a comprehensive, real-time overview and control point for all facets of the multi-instance marketplace platform.</p>
-->
                            <p class="text-white pb-3 animated slideInDown">The Marketplace Ecosystem Dashboard is a central, web-based interface for authorized personnel, offering real-time overview and control of the multi-instance marketplace platform.</p>
                        </div>
                        <div class="col-lg-6 text-center text-lg-start">
                            <img class="img-fluid rounded animated zoomIn" src="assets/img/hero.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->


        <!-- Feature Start -->
        <div class="container-xxl py-6">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="feature-item bg-light rounded text-center p-5">
                            <i class="fa fa-4x fa-puzzle-piece text-primary mb-4"></i>
                            <h5 class="mb-3">App Integration</h5>
                            <p class="m-0">Connect third-party tools and take advantage of the complete set of integrated modules.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="feature-item bg-light rounded text-center p-5">
                            <i class="fa fa-4x fa-screwdriver-wrench text-primary mb-4"></i>
                            <h5 class="mb-3">Maintenance</h5>
                            <p class="m-0">Customize product variants, pricing rules, and inventory thresholds dynamically.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="feature-item bg-light rounded text-center p-5">
                            <i class="fa fa-4x fa-chart-line text-primary mb-4"></i>
                            <h5 class="mb-3">Reports</h5>
                            <p class="m-0">Generate AI-driven analytics for sales, customer behavior, and profit margins.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Feature End -->


        <!-- About Start -->
        <div class="container-xxl py-6" id="about">
            <div class="container">
                <div class="row g-5 flex-column-reverse flex-lg-row">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <h1 class="mb-4">Manage & Push Your Business To The Next Level</h1>
                        <p class="mb-4">Taking your business to the next level requires a multifaceted approach, focusing on strategic management, innovative growth, and operational excellence. Here are key areas to consider for significant expansion and sustainable success:</p>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="ms-4">
                                <h5>Strategic Planning & Vision</h5>
                                <p class="mb-0">Refine Your Vision and Mission, Conduct a Comprehensive SWOT Analysis, Set SMART Goals, Develop a Growth Strategy.</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="ms-4">
                                <h5>Optimize Operations & Efficiency</h5>
                                <p class="mb-0">Streamline Processes, Invest in Technology, Enhance Supply Chain Management, Focus on Quality Control.</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="ms-4">
                                <h5>Financial Management & Resource Allocation</h5>
                                <p class="mb-0">Robust Financial Planning, Secure Funding for Growth, Strategic Investment, Risk Management</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="ms-4">
                                <h5>Marketing & Sales Acceleration</h5>
                                <p class="mb-0">Deepen Market Understanding, Strengthen Your Brand Identity, Diversify Marketing Channels, Optimize Sales Processes, Prioritize Customer Experience</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="ms-4">
                                <h5>Human Capital & Talent Development</h5>
                                <p class="mb-0">Attract and Retain Top Talent, Invest in Employee Training and Development, Empower Your Team, Foster a Strong Company Culture.</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="ms-4">
                                <h5>Innovation & Adaptability</h5>
                                <p class="mb-0">Embrace Continuous Innovation, Monitor Industry Trends, Be Agile and Adaptable, Explore New Markets and Partnerships.</p>
                            </div>
                        </div>
                        <a href="" class="btn btn-primary py-sm-3 px-sm-5 rounded-pill mt-3">Read More</a>
                    </div>
                    <div class="col-lg-6">
                        <img class="img-fluid rounded wow zoomIn" data-wow-delay="0.5s" src="assets/img/about.png">
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->


        <!-- Overview Start -->
        <div class="container-xxl bg-light my-6 py-5" id="overview">
            <div class="container">
                <div class="row g-5 py-5 align-items-center">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <img class="img-fluid rounded" src="assets/img/overview-1.png">
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="d-flex align-items-center mb-4">
                            <h1 class="mb-0">01</h1>
                            <span class="bg-primary mx-2" style="width: 30px; height: 2px;"></span>
                            <h5 class="mb-0">Embrace Continuous Innovation</h5>
                        </div>
                        <p class="mb-4">Encourage a culture of experimentation and improvement. Regularly seek new ways to improve products, services, and processes.</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>Fully customizable</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>User friendly interface</p>
                        <p class="mb-0"><i class="fa fa-check-circle text-primary me-3"></i>More effective & poerwfull</p>
                    </div>
                </div>
                <div class="row g-5 py-5 align-items-center flex-column-reverse flex-lg-row">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="d-flex align-items-center mb-4">
                            <h1 class="mb-0">02</h1>
                            <span class="bg-primary mx-2" style="width: 30px; height: 2px;"></span>
                            <h5 class="mb-0">Monitor Industry Trends</h5>
                        </div>
                        <p class="mb-4">Stay informed about technological advancements, market shifts, and competitive landscapes.</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>Fully customizable</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>User friendly interface</p>
                        <p class="mb-0"><i class="fa fa-check-circle text-primary me-3"></i>More effective & poerwfull</p>
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <img class="img-fluid rounded" src="assets/img/overview-2.png">
                    </div>
                </div>
                <div class="row g-5 py-5 align-items-center">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <img class="img-fluid rounded" src="assets/img/overview-2.png">
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="d-flex align-items-center mb-4">
                            <h1 class="mb-0">03</h1>
                            <span class="bg-primary mx-2" style="width: 30px; height: 2px;"></span>
                            <h5 class="mb-0">Agile and Adaptable</h5>
                        </div>
                        <p class="mb-4">The business environment is constantly changing. Be prepared to pivot your strategies and operations in response to new opportunities or challenges.</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>Fully customizable</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>User friendly interface</p>
                        <p class="mb-0"><i class="fa fa-check-circle text-primary me-3"></i>More effective & poerwfull</p>
                    </div>
                </div>
                <div class="row g-5 py-5 align-items-center flex-column-reverse flex-lg-row">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="d-flex align-items-center mb-4">
                            <h1 class="mb-0">04</h1>
                            <span class="bg-primary mx-2" style="width: 30px; height: 2px;"></span>
                            <h5 class="mb-0">Explore New Markets and Partnerships</h5>
                        </div>
                        <p class="mb-4">Look for opportunities to expand into new geographical markets or form strategic alliances with other businesses.</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>Fully customizable</p>
                        <p><i class="fa fa-check-circle text-primary me-3"></i>User friendly interface</p>
                        <p class="mb-0"><i class="fa fa-check-circle text-primary me-3"></i>More effective & poerwfull</p>
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <img class="img-fluid rounded" src="assets/img/overview-3.png">
                    </div>
                </div>
            </div>
        </div>
        <!-- Overview End -->


        <!-- Advanced Feature Start -->
        <div class="container-xxl py-6" id="features">
            <div class="container">
                <div class="mx-auto text-center wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Advanced Features</h1>
                    <p class="mb-5">Transform fragmented workflows into a cohesive powerhouse. Prime Marketplace merges 20+ critical modules into a single dashboard, automating processes from sales to supply chain while offering 24/7 expert tech support to ensure seamless adoption and immediate ROI.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="advanced-feature-item text-center rounded py-5 px-4">
                            <i class="fa fa-edit fa-3x text-primary mb-4"></i>
                            <h5 class="mb-3">App Integration</h5>
                            <p class="m-0">Connect third-party tools like CRM, ERPm Accounting, HR effortlessly for real-time data synchronization.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="advanced-feature-item text-center rounded py-5 px-4">
                            <i class="fa fa-sync fa-3x text-primary mb-4"></i>
                            <h5 class="mb-3">Options Maintenance</h5>
                            <p class="m-0">Customize product variants, pricing rules, and inventory thresholds dynamically.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="advanced-feature-item text-center rounded py-5 px-4">
                            <i class="fa fa-laptop fa-3x text-primary mb-4"></i>
                            <h5 class="mb-3">Options Report</h5>
                            <p class="m-0">Generate AI-driven analytics for sales, customer behavior, and profit margins.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                        <div class="advanced-feature-item text-center rounded py-5 px-4">
                            <i class="fa fa-draw-polygon fa-3x text-primary mb-4"></i>
                            <h5 class="mb-3">Drag & Drop Technology</h5>
                            <p class="m-0">Build workflows, dashboards, and product listings visually and it need no coding needed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Advanced Feature End -->


        <!-- Facts Start -->
        <div class="container-xxl bg-primary my-6 py-6 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.1s">
                        <i class="fa fa-cogs fa-3x text-white mb-3"></i>
                        <h1 class="mb-2" data-toggle="counter-up">7264</h1>
                        <p class="text-white mb-0">Active Install</p>
                    </div>
                    <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.3s">
                        <i class="fa fa-users fa-3x text-white mb-3"></i>
                        <h1 class="mb-2" data-toggle="counter-up">6521</h1>
                        <p class="text-white mb-0">Satisfied Clients</p>
                    </div>
                    <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.5s">
                        <i class="fa fa-certificate fa-3x text-white mb-3"></i>
                        <h1 class="mb-2" data-toggle="counter-up">729</h1>
                        <p class="text-white mb-0">Award Wins</p>
                    </div>
                    <div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.7s">
                        <i class="fa fa-quote-left fa-3x text-white mb-3"></i>
                        <h1 class="mb-2" data-toggle="counter-up">5917</h1>
                        <p class="text-white mb-0">Clients Reviews</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Facts End -->


        <!-- Process Start -->
        <div class="container-xxl py-6">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <img class="img-fluid rounded" src="assets/img/process.png">
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                        <h1 class="mb-4">3 Simple Steps to LaunchSimple Steps to Launch</h1>
<!--
                        <p class="mb-4">Launching a new product, service, or initiative can seem like a daunting task, but by breaking it down into a series of simple, manageable steps, you can significantly increase your chances of success. This guide outlines a streamlined approach to help you navigate the complexities of a launch, ensuring a smooth and effective introduction to your target audience.Phase 1: Preparation and Planning.</p>
-->
                        <p class="mb-4">The foundation of any successful launch is thorough preparation. This phase focuses on defining your vision, understanding your market, and laying out a strategic roadmap.</p>
                        <ul class="process mb-0">
                            <li>
                                <span><i class="fa fa-cog"></i></span>
                                <div>
                                    <h5><strong>Define Your Vision and Goals</strong></h5>
                                    <ul>
                                    	<lu>
                                    		What are you launching?
                                    	</lu>
                                    	<lu>
                                    		What problem does it solve or what need does it fulfill?
                                    	</lu>
                                    	<lu>
                                    		What are your key objectives for this launch?
                                    	</lu>
                                    	<lu>
                                    		Who is your target audience?
                                    	</lu>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <span><i class="fa fa-address-card"></i></span>
                                <div>
                                    <h5>Market Research and Competitive Analysis:</h5>
                                    	<ul>
                                    		<lu>
                                    			Understand the current market landscape
                                    		</lu>
                                    		<lu>
                                    			Analyze your competitors
                                    		</lu>
                                    		<lu>
                                    			Identify your unique selling proposition (USP)
                                    		</lu>
                                    	</ul>
                                </div>
                            </li>
                            <li>
                                <span><i class="fa fa-address-card"></i></span>
                                <div>
                                    <h5>Develop a Comprehensive Launch Strategy</h5>
                                    	<ul>
                                    		<lu>
                                    			Marketing Strategy.
                                    		</lu>
                                    		<lu>
                                    			Sales Strategy.
                                    		</lu>
                                    		<lu>
                                    			Operational Plan.
                                    		</lu>
                                    		<lu>
                                    			Risk Assessment and Contingency Planning.
                                    		</lu>
                                    	</ul>
                                </div>
                            </li>
                            <li>
                                <span><i class="fa fa-check"></i></span>
                                <div>
                                    <h5></h5>
                                    <p></p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Process End -->


        <!-- Pricing Start -->
        <div class="container-xxl py-6" id="pricing">
            <div class="container">
                <div class="mx-auto text-center wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Pricing Plan</h1>
                    <p class="mb-5">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore. Clita erat ipsum et lorem et sit, sed stet no labore lorem sit clita duo justo</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="price-item rounded overflow-hidden">
                            <div class="bg-dark p-4">
                                <h4 class="text-white mt-2">Standard</h4>
                                <div class="text-white">
                                    <span class="align-top fs-4 fw-bold">$</span>
                                    <h1 class="d-inline display-6 text-primary mb-0"> 29.99</h1>
                                    <span class="align-baseline">/ Month</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="d-flex justify-content-between mb-3"><span>HTML5 & CSS3</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>Bootstrap v5</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>Responsive Layout</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>Cross-browser Support</span><i class="fa fa-times text-danger pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>Remove Author's Credit</span><i class="fa fa-times text-danger pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>PHP & Ajax Contact Form</span><i class="fa fa-times text-danger pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>6 Months Free Support</span><i class="fa fa-times text-danger pt-1"></i></div>
                                <a href="" class="btn btn-dark rounded-pill py-2 px-4 mt-3">Get Started</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="price-item rounded overflow-hidden">
                            <div class="bg-primary p-4">
                                <h4 class="text-white mt-2">Professional</h4>
                                <div class="text-white">
                                    <span class="align-top fs-4 fw-bold">$</span>
                                    <h1 class="d-inline display-6 text-dark mb-0"> 49.99</h1>
                                    <span class="align-baseline">/ Month</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="d-flex justify-content-between mb-3"><span>HTML5 & CSS3</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>Bootstrap v5</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>Responsive Layout</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>Cross-browser Support</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>Remove Author's Credit</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>PHP & Ajax Contact Form</span><i class="fa fa-times text-danger pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>6 Months Free Support</span><i class="fa fa-times text-danger pt-1"></i></div>
                                <a href="" class="btn btn-primary rounded-pill py-2 px-4 mt-3">Get Started</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="price-item rounded overflow-hidden">
                            <div class="bg-dark p-4">
                                <h4 class="text-white mt-2">Ultimate</h4>
                                <div class="text-white">
                                    <span class="align-top fs-4 fw-bold">$</span>
                                    <h1 class="d-inline display-6 text-primary mb-0"> 79.99</h1>
                                    <span class="align-baseline">/ Month</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="d-flex justify-content-between mb-3"><span>HTML5 & CSS3</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>Bootstrap v5</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>Responsive Layout</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>Cross-browser Support</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>Remove Author's Credit</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>PHP & Ajax Contact Form</span><i class="fa fa-check text-success pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span>6 Months Free Support</span><i class="fa fa-check text-success pt-1"></i></div>
                                <a href="" class="btn btn-dark rounded-pill py-2 px-4 mt-3">Get Started</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pricing End -->


        <!-- Testimonial Start -->
        <div class="container-xxl py-6" id="testimonial">
            <div class="container">
                <div class="mx-auto text-center wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">What Our Clients Say</h1>
                    <p class="mb-5">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore. Clita erat ipsum et lorem et sit, sed stet no labore lorem sit clita duo justo</p>
                </div>
                <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                    <div class="testimonial-item bg-light rounded my-4">
                        <p class="fs-5"><i class="fa fa-quote-left fa-4x text-primary mt-n4 me-3"></i>Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit sed stet lorem sit clita duo justo.</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded-circle" src="assets/img/testimonial-1.jpg" style="width: 65px; height: 65px;">
                            <div class="ps-4">
                                <h5 class="mb-1">Client Name</h5>
                                <span>Profession</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item bg-light rounded my-4">
                        <p class="fs-5"><i class="fa fa-quote-left fa-4x text-primary mt-n4 me-3"></i>Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit sed stet lorem sit clita duo justo.</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded-circle" src="assets/img/testimonial-2.jpg" style="width: 65px; height: 65px;">
                            <div class="ps-4">
                                <h5 class="mb-1">Client Name</h5>
                                <span>Profession</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item bg-light rounded my-4">
                        <p class="fs-5"><i class="fa fa-quote-left fa-4x text-primary mt-n4 me-3"></i>Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit sed stet lorem sit clita duo justo.</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded-circle" src="assets/img/testimonial-3.jpg" style="width: 65px; height: 65px;">
                            <div class="ps-4">
                                <h5 class="mb-1">Client Name</h5>
                                <span>Profession</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Testimonial End -->


        <!-- Contact Start -->
        <div class="container-xxl py-6" id="contact">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <h1 class="mb-3">Get In Touch</h1>
                        <p class="mb-4">We're always eager to connect with you! Whether you have a question, a suggestion, or just want to say hello, we're here to listen. Your feedback is invaluable to us as we strive to improve and better serve our community.</p>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-phone-alt"></i>
                            </div>
                            <div class="ms-3">
                                <p class="mb-2">Call Us</p>
                                <h5 class="mb-0">(630) 401-9125</h5>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="ms-3">
                                <p class="mb-2">Mail Us</p>
                                <h5 class="mb-0">contact@prime-processing.net</h5>
                            </div>
                        </div>
                        <div class="d-flex mb-0">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-map-marker-alt"></i>
                            </div>
                            <div class="ms-3">
                                <p class="mb-2">Our Office</p>
                                <h5 class="mb-0">1575 Fairway Dr. #302, Naperville, IL 60563</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">





    <form method="POST">


                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input id="name" name="name" type="text" class="form-control" placeholder="Name *" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                                        <label for="name">Your Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input id="email" name="email" type="email" class="form-control" placeholder="Your Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                                        <label for="email">Email *</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input id="subject" name="subject" type="text" class="form-control" placeholder="Subject" value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" required>
                                        <label for="subject">Subject *</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea id="message" name="message" class="form-control" placeholder="Leave a message here" style="height: 150px" rows="5" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                                        <label for="message">Message *</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
								        <div class="g-recaptcha" data-sitekey="AIzaSyDsFKOUMyXWxLItyQrmIDGnxxeNMk2mFus"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary rounded-pill py-3 px-5" type="submit">Send Message</button>
                                </div>
                            </div>


    </form>





                    </div>
                </div>
            </div>
        </div>
        <!-- Contact End -->
        

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-body footer wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5 px-lg-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-3">
                        <p class="section-title text-white h5 mb-4">Address<span></span></p>
                        <p><i class="fa fa-map-marker-alt me-3"></i>1575 Fairway Dr, Naperville IL</p>
                        <p><i class="fa fa-phone-alt me-3"></i>(630) 401-9125</p>
                        <p><i class="fa fa-envelope me-3"></i>contact@prime-processing.net</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <p class="section-title text-white h5 mb-4">Quick Link<span></span></p>
                        <a class="btn btn-link" href="">About</a>
                        <a class="btn btn-link" href="">Contact</a>
                        <a class="btn btn-link" href="">Privacy Policy</a>
                        <a class="btn btn-link" href="">Terms & Conditions</a>
                        <a class="btn btn-link" href="">Support</a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <p class="section-title text-white h5 mb-4">Community<span></span></p>
                        <a class="btn btn-link" href="">Career</a>
                        <a class="btn btn-link" href="">Leadership</a>
                        <a class="btn btn-link" href="">Strategy</a>
                        <a class="btn btn-link" href="">History</a>
                        <a class="btn btn-link" href="">Components</a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <p class="section-title text-white h5 mb-4">Newsletter<span></span></p>
                        <p>Inflationary pressures squeezing your margins? You’re not alone. 67% of SMBs report cash flow disruptions this quarter. Here’s how to fight back:</p>
                        <div class="position-relative w-100 mt-3">
                            <input class="form-control border-0 rounded-pill w-100 ps-4 pe-5" type="text" placeholder="Your Email" style="height: 48px;">
                            <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-1 me-2"><i class="fa fa-paper-plane text-primary fs-4"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container px-lg-5">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="https://prime-processing.net">Prime Processing LLC</a>, All Right Reserved. 
							
							<!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
							Designed By <a class="border-bottom" href="https://jeqsolutions.com">JEQ Solutions</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="">Home</a>
                                <a href="">Cookies</a>
                                <a href="">Help</a>
                                <a href="">FQAs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>




<?php
if (existCookie('cookiesAccept')) {
//	echo "<hr/>Cookies are enabled!<br/>Value: ".$_COOKIE['cookiesAccept'];
	// Proceed with cookie-dependent operations
} else {
?>
	<div id="cookies-popup">
		<i class='fas fa-cookie-bite' style='font-size:80px;'></i>
		<p>This website uses cookies to ensure you get the best experience on our website.</p>
		<div class="btn-cont">
			<button onclick="document.location='assets/config/set_cookie.php?selection=all'">Accept All Cookies</button>
			<button onclick="document.location='assets/config/set_cookie.php?selection=necessary'">Necessary Cookies Only</button>
		</div>
		<button onclick="document.location='assets/config/set_cookie.php?selection=necessary'" class="btn-cust">
			Customize Settings
		</button>
	</div>

<?php
//	echo "<hr/>Please enable cookies to use this website!";
//	Show cookie warning message
}
?>




    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/lib/wow/wow.min.js"></script>
    <script src="assets/lib/easing/easing.min.js"></script>
    <script src="assets/lib/waypoints/waypoints.min.js"></script>
    <script src="assets/lib/counterup/counterup.min.js"></script>
    <script src="assets/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="assets/js/main.js"></script>
</body>

</html>