<?php
// Current Year
$current_year = date("Y");

// Copyright text
$copyright_text = "&copy; $current_year Ankan Das. All rights reserved.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Best School Management System in Bangladesh by KhulnaDevs (Ankan Das)</title>
    <meta name="description" content="Ankan Das KhulnaDevs এর তৈরি, বাংলাদেশ এর অন্যতম সেরা স্কুল ম্যানেজমেন্ট সিস্টেম। ankandas.me and KhulnaDevs is software company in Khulna bangladesh" />
    <meta name="keywords" content="Khulna Devs,ankandas.me, best School Management System Bangladesh, স্কুল ম্যানেজমেন্ট সিস্টেম, School ERP, স্কুল সফটওয়্যার, অনলাইন স্কুল ম্যানেজমেন্ট" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="assets/images/favicon.ico">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS (must be after Bootstrap) -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --accent-color: #f59e0b;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --bg-light: #f8fafc;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.7;
            color: var(--text-primary);
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 8px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
            margin-bottom: 60px;
            box-shadow: var(--shadow-lg);
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: normal;
        }

        .page-header p {
            font-size: 1.125rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .main-content {
            padding: 0 0 80px 0;
        }

        .review-section {
            background: white;
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .review-section:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
            gap: 16px;
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            box-shadow: var(--shadow-md);
        }

        .section-header h2 {
            font-size: 1.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .section-header h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .content-wrapper {
            color: var(--text-secondary);
            font-size: 1.125rem;
        }

        .content-wrapper p {
            margin-bottom: 16px;
        }

        .content-wrapper strong {
            color: var(--text-primary);
            font-weight: 600;
        }

        .contact-section {
            background: white;
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: var(--bg-light);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .contact-details {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .contact-details .label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: normal;
        }

        .contact-details a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 1.125rem;
            transition: color 0.3s ease;
        }

        .contact-details a:hover {
            color: var(--primary-dark);
        }

        .homepage-link {
            text-align: center;
            margin: 60px 0;
        }

        .btn-homepage {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 16px 32px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.125rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
            border: none;
            cursor: pointer;
        }

        .btn-homepage:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            color: white;
            text-decoration: none;
        }

        .btn-homepage i {
            font-size: 1.25rem;
        }

        footer {
            background: white;
            text-align: center;
            padding: 40px 0;
            color: var(--text-secondary);
            font-size: 1rem;
            border-top: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
           
            .container {
                padding: 0 16px;
            }
            .page-header h1 {
                font-size: 2rem;
            }

            .page-header p {
                font-size: 1rem;
            }

            .review-section,
            .contact-section {
                padding: 24px;
                margin-bottom: 24px;
            }

            .section-header {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }

            .icon-wrapper {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .section-header h2 {
                font-size: 1.5rem;
            }

            .section-header h3 {
                font-size: 1.25rem;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .btn-homepage {
                padding: 14px 24px;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .page-header h1 {
                font-size: 1.75rem;
            }

            .review-section,
            .contact-section {
                padding: 20px;
            }

            .content-wrapper {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
   <div class="page-header">
        <div class="container">
            <h1>KhulnaDevs-এর সাথে আমাদের অভিজ্ঞতা</h1>
            <p>Our Feedback & Recommendation </p>
        </div>
    </div>




    <div class="main-content">
        <div class="container">
            <div class="review-bangla">
             
            
    <div class='review-section'>
    <div class='section-header'>
        <div class='icon-wrapper'>
            <i class='fas fa-code'></i>
        </div>
        <h2>KhulnaDevs কেমন সার্ভিস দিয়েছে?</h2>
    </div>
    <div class='content-wrapper'>
    <p>সরকারি নির্দেশনা অনুযায়ী আমাদের নতুন মাদ্রাসার ওয়েবসাইট তৈরির জন্য আমরা KhulnaDevs-কে বেছে নিয়েছি । তাদের পেশাদারিত্ব, দ্রুত সাপোর্ট এবং কাস্টমাইজড ডিজাইন অসাধারণ।বিশেষ করে অংকন ভাইয়া আমাদের সকল সমস্যা গুলো নিজে সমাধান করে দিয়েছেন ।  আমাদের প্রয়োজন অনুযায়ী সুন্দর, ব্যবহারবান্ধব এবং মোবাইল-ফ্রেন্ডলি ওয়েবসাইট তৈরি করেছে আমরা ১০০ ভাগ সন্তুষ্ট । আমাদের অভিজ্ঞতা অনুযায়ী<strong> KhulnaDevs </strong>সত্যিই সেরা ওয়েব ডেভেলপমেন্ট পার্টনার। আপনার স্কুল বা মাদ্রাসার জন্য একটি আধুনিক, নিরাপদ সমাধান চাইলে তাহলে এই <strong>স্কুল ম্যানেজমেন্ট সফটওয়্যার</strong> ব্যবহার করে দেখতে পারেন। </p>
    </div>
</div>


            </div>

            <div class="review-english">
            <div class='review-section'>
    <div class='section-header'>
        <div class='icon-wrapper'>
            <i class='fas fa-laptop-code'></i>
        </div>
        <h2>Why choose KhulnaDevs ?</h2>
    </div>
    <div class='content-wrapper'>
    <p>We have chosen KhulnaDevs to create our new Madrasa website as per the government guidelines. Their professionalism, fast support and customized design are amazing. Especially <a href="https://www.facebook.com/ankandas.fb">Ankan</a> solved all our problems himself. We are 100% satisfied with the beautiful, user-friendly and mobile-friendly website created as per our needs. According to our experience, <strong> KhulnaDevs </strong> is truly the best web development partner. If you want a modern, secure solution for your school or Madrasa, then you can try this <strong>School Management Software</strong>.</p>    </div>
</div>
            </div>

            <div class="contact-info">
            <div class='contact-section'>
    <div class='section-header'>
        <div class='icon-wrapper'>
            <i class='fas fa-address-book'></i>
        </div>
        <h3>Contact Information</h3>
    </div>
    <div class='contact-grid'>
        <div class='contact-item'>
            <div class='contact-icon'>
                <i class='fas fa-phone'></i>
            </div>
            <div class='contact-details'>
                <span class='label'>Phone</span>
                <a href='tel:+8801745009934'>+880 1745-009934</a>
            </div>
        </div>
        <div class='contact-item'>
            <div class='contact-icon'>
                <i class='fab fa-facebook'></i>
            </div>
            <div class='contact-details'>
                <span class='label'>Facebook</span>
                <a href='https://www.facebook.com/ankandas.fb' target='_blank'>www.facebook.com/ankandas.fb</a>
            </div>
        </div>
    </div>
</div>
            </div>

            <div class="homepage-link">
                <a href="/" class="btn-homepage">
                    <i class="fas fa-home"></i>
                হোমপেজ দেখুন
                </a>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <?php echo $copyright_text; ?>
        </div>
    </footer>

</body>
</html>
