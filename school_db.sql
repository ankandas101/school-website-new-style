-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2026 at 07:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_info_links`
--

CREATE TABLE `academic_info_links` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(500) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_info_links`
--

INSERT INTO `academic_info_links` (`id`, `title`, `url`, `sort_order`, `status`) VALUES
(2, 'ক্লাস রুটিন', 'routine.php', 0, 1),
(3, 'শিক্ষার্থী তথ্য', 'student_info.php', 0, 1),
(4, 'ফলাফল', 'result.php', 0, 1),
(5, 'ব্যবস্থাপনা কমিটি', 'management_committee.php', 0, 1),
(6, 'ভর্তির তথ্য', 'admission.php', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_superadmin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `name`, `email`, `is_superadmin`, `created_at`) VALUES
(2, 'ankandas', '$2y$10$AX3o45F7qu1PuiTH9Poft.ouISFCnmLxEJGil81hZWO243rKloBa2', 'Ankan Das', 'ankan.cse22@gmail.com', 1, '2025-07-18 08:52:08'),
(5, 'testuser', '$2y$10$Jy6m36p.en2n1AQkz7inpeQpIMss7.O7WTjNH6TYmxYwvJDISscBy', 'testuser', 'testuser@gmail.com', 0, '2026-06-21 16:04:39');

-- --------------------------------------------------------

--
-- Table structure for table `admission_info`
--

CREATE TABLE `admission_info` (
  `id` int(11) NOT NULL,
  `requirements` longtext NOT NULL,
  `banner` text NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admission_info`
--

INSERT INTO `admission_info` (`id`, `requirements`, `banner`, `updated_at`) VALUES
(1, 'Here\'s a sample admission instruction for the madrasha:\r\n\r\n**Admission Instructions**\r\n\r\nWelcome!  This section provides essential information for prospective students.  Please review the following requirements: [List key requirements here - e.g., age limit, required documents, application process].  We look forward to welcoming new students.', 'assets/images/admission_banner_1757334444_959.png', '2025-09-10 20:37:34');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `complaint_id` varchar(20) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `roll_number` varchar(50) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `complaint_type` varchar(100) NOT NULL,
  `incident_date` date DEFAULT NULL,
  `complaint_details` text NOT NULL,
  `anonymous` tinyint(1) DEFAULT 0,
  `attachment` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `event_date`, `description`, `image`, `created_at`) VALUES
(6, 'বার্ষিক কোরআন প্রতিযোগিতা', '2025-08-27', '১৫ আগস্ট ২০২৫ তারিখে অনুষ্ঠিত হলো আমাদের মাদ্রাসার বার্ষিক কোরআন প্রতিযোগিতা। শিক্ষার্থীরা বিভিন্ন শ্রেণি থেকে অংশগ্রহণ করেছিল। প্রতিযোগিতার মাধ্যমে শিক্ষার্থীদের কোরআন তেলাওয়াত ও হিফজ দক্ষতা উন্নয়নের উদ্দেশ্য ছিল। বিজয়ীদের মধ্যে সনদপত্র ও পুরস্কার বিতরণ করা হয়। শিক্ষক ও অভিভাবকরা অনুষ্ঠানে উপস্থিত ছিলেন এবং শিক্ষার্থীদের উৎসাহিত করেছেন।', 'event_1756321006_578.jpg', '2025-08-27 18:56:46'),
(7, 'বিজ্ঞান মেলা ২০২৫', '2025-08-27', '২০-7-২০২৫ তারিখে মাদ্রাসায় বিজ্ঞান মেলা অনুষ্ঠিত হয়। শিক্ষার্থীরা সৃজনশীল প্রকল্প ও পরীক্ষা উপস্থাপন করেছে। এই মেলার মাধ্যমে শিক্ষার্থীরা হাতে-কলমে শিক্ষা গ্রহণ এবং নতুন উদ্ভাবনী ধারণা বিকাশের সুযোগ পেয়েছে।', 'event_1756321112_861.jpg', '2025-08-27 18:58:32'),
(8, 'ক্রীড়া প্রতিযোগিতা', '2025-08-27', 'মাদ্রাসায় বার্ষিক ক্রীড়া প্রতিযোগিতা অনুষ্ঠিত হয়েছে। বিভিন্ন খেলার মাধ্যমে শিক্ষার্থীরা শারীরিক সক্ষমতা বৃদ্ধি করেছে। প্রতিযোগিতার বিজয়ীদের মধ্যে মেডেল ও সনদপত্র বিতরণ করা হয়েছে। শিক্ষক ও অভিভাবকরা উৎসাহিত করেছেন।', 'event_1756321135_419.jpg', '2025-08-27 18:58:55'),
(9, 'শিক্ষাপ্রতিষ্ঠান এর জন্য সফটওয়্যারের প্রধান ফিচারসমূহ', '2025-08-27', 'বর্তমান ডিজিটাল যুগে প্রতিটি শিক্ষা প্রতিষ্ঠানের জন্য একটি নিজস্ব ওয়েবসাইট থাকা অত্যন্ত গুরুত্বপূর্ণ। এটি শুধুমাত্র তথ্য দেওয়ার মাধ্যম নয়, বরং একটি শিক্ষাপ্রতিষ্ঠানের পরিচয়পত্র। আর এই গুরুত্বপূর্ণ কাজটি সঠিকভাবে সম্পন্ন করতে হলে দরকার অভিজ্ঞ ও বিশ্বস্ত একটি টিম – আর সেখানেই KhulnaDevs সেরা।\r\n\r\n🏆 ১. শিক্ষা প্রতিষ্ঠানভিত্তিক কাস্টম ডিজাইন\r\nপ্রত্যেক স্কুল, কলেজ বা মাদ্রাসার নিজস্ব নিয়ম-কানুন, স্টাইল ও কাঠামো থাকে। আমরা সেই অনুযায়ী কাস্টম ডিজাইন তৈরি করি যা আপনার প্রতিষ্ঠানকে তুলে ধরে সবচেয়ে সুন্দরভাবে।\r\n\r\n📋 ২. DSHE এবং শিক্ষা মন্ত্রণালয়ের গাইডলাইন অনুসরণ\r\nআমাদের তৈরি ওয়েবসাইটগুলো ১০০% সরকারি গাইডলাইন (DSHE) অনুসরণ করে বানানো হয়। ফলে ওয়েবসাইটটি সরকারি রেজিস্ট্রেশন, ইনস্পেকশন, অথবা রিপোর্টে কোনো অসুবিধা সৃষ্টি করে না।\r\n\r\n📲 ৩. মোবাইল ও ডেস্কটপ-ফ্রেন্ডলি ডিজাইন\r\nবর্তমান সময়ে মোবাইলে ওয়েবসাইট ভিজিটই বেশি হয়ে থাকে। আমাদের ডিজাইনগুলো রেসপনসিভ, অর্থাৎ মোবাইল, ট্যাব, ল্যাপটপ – সব ডিভাইসে সুন্দরভাবে দেখায়।\r\n\r\n⚙️ ৪. নোটিশ, রুটিন, রেজাল্ট, গ্যালারি সহ পূর্ণ ফিচার\r\nআমরা এমন একটি পূর্ণাঙ্গ স্কুল ওয়েবসাইট তৈরি করি যেখানে:\r\n\r\nস্কুল নোটিশ যুক্ত করা যাবে,\r\n\r\nরুটিন আপলোড করা যাবে,\r\n\r\nছাত্রছাত্রীদের রেজাল্ট দেখানো যাবে,\r\n\r\nশিক্ষক তালিকা থাকবে,\r\n\r\nএবং গ্যালারির মাধ্যমে ছবি যুক্ত করা যাবে।\r\n\r\n🔒 ৫. ডাটা সিকিউরিটি ও ব্যাকআপ সুবিধা\r\nআপনার স্কুলের গুরুত্বপূর্ণ তথ্য যেন নিরাপদ থাকে, সেই জন্য আমরা নিরাপদ হোস্টিং ও ব্যাকআপ সুবিধা দেই।\r\n\r\n💡 ৬. সহজে আপডেটযোগ্য ও ব্যবহার-বান্ধব প্যানেল\r\nআপনার প্রতিষ্ঠান থেকে খুব সহজেই নিজ হাতে নোটিশ, রুটিন ইত্যাদি আপলোড করতে পারবেন। কোনো প্রোগ্রামিং জানা লাগবে না।\r\n\r\n🛠️ ৭. লাইফটাইম সাপোর্ট ও টেকনিক্যাল সহায়তা\r\nওয়েবসাইট ডেলিভারির পরেও আমরা থাকি পাশে। হোস্টিং, ডোমেইন, বা অন্য যেকোনো সমস্যায় আমাদের সাপোর্ট টিম দ্রুত সাড়া দেয়।\r\n\r\n🎯 আমরা কাদের জন্য কাজ করি?\r\n*সরকারি / বেসরকারি স্কুল\r\n\r\n*কলেজ ও মাদ্রাসা\r\n\r\n*কিন্ডারগার্টেন ও প্রি-স্কুল\r\n\r\n*ট্রেনিং সেন্টার বা কোচিং সেন্টার\r\n\r\n-----------------------------------------------\r\n🔍 সফটওয়্যারের প্রধান ফিচারসমূহঃ\r\n✅ ভিন্ন ভিন্ন ড্যাশবোর্ড সিস্টেম\r\nশিক্ষক, শিক্ষার্থী এবং অভিভাবকদের জন্য আলাদা ইউজার ইন্টারফেস ও ড্যাশবোর্ড সুবিধা।\r\n\r\n✅ অনলাইন ভর্তি সিস্টেম\r\nশিক্ষার্থীরা সরাসরি ওয়েবসাইট থেকে অনলাইনে ভর্তি হতে পারবে। ভর্তি ফি থাকলে তা অনলাইন পেমেন্ট (বিকাশ, রকেট, নগদ, SSLCommerz) এর মাধ্যমে প্রদান করা যাবে।\r\n\r\n✅ স্বয়ংক্রিয় উপস্থিতি গ্রহণ\r\nদৈনিক ক্লাস অনুযায়ী অনলাইনে শিক্ষার্থীদের উপস্থিতি গ্রহণ এবং হিসাব রাখা যাবে।\r\n\r\n✅ বেতন ও ফি ম্যানেজমেন্ট\r\nঅটো ফি জেনারেশন, SMS রিমাইন্ডার এবং পেমেন্ট ট্র্যাকিং সিস্টেম সংযুক্ত।\r\n\r\n✅ এক ক্লিক রিপোর্ট জেনারেশন\r\nফলাফল, উপস্থিতি, বেতন, শিক্ষক পারফরম্যান্সসহ সকল রিপোর্ট প্রিন্টযোগ্য ও PDF আকারে সহজে তৈরি করা যাবে।\r\n\r\n✅ আইডি কার্ড, এডমিট কার্ড ও রেজাল্ট শীট তৈরির সুবিধা\r\nস্বয়ংক্রিয়ভাবে শিক্ষার্থীদের আইডি কার্ড, প্রবেশপত্র ও রেজাল্ট শীট জেনারেট করা যায়।\r\n\r\n✅ নোটিশ ও মেসেজিং সিস্টেম\r\nSMS ও ইমেইলের মাধ্যমে শিক্ষার্থী ও অভিভাবকদের তাৎক্ষণিক বার্তা বা নোটিশ পাঠানো যাবে।\r\n\r\n✅ অনলাইন ক্লাস ও পরীক্ষা সাপোর্ট\r\nGoogle Meet / Zoom ইন্টিগ্রেশন এর মাধ্যমে অনলাইন ক্লাস এবং মূল্যায়ন নেওয়ার সুবিধা।\r\n\r\n✅ SEO ফ্রেন্ডলি ওয়েবসাইট ইন্টিগ্রেশন\r\nআকর্ষণীয়, ইউজার-ফ্রেন্ডলি এবং সার্চ ইঞ্জিন ফ্রেন্ডলি ওয়েবসাইট সংযুক্ত থাকবে।\r\n\r\n✅ অনলাইন পেমেন্ট গেটওয়ে\r\nবিকাশ, রকেট, নগদ, SSLCommerz ইত্যাদি পেমেন্ট গেটওয়ে সংযুক্ত।\r\n\r\n✅ হোস্টেল, ইনভেনটরি ও ট্রান্সপোর্ট ম্যানেজমেন্ট\r\nছাত্রাবাস, মালপত্র এবং যাতায়াত ব্যবস্থাপনার জন্য আলাদা মডিউল।\r\n\r\n📈 ব্যবহার করে আপনার কী লাভ হবে?\r\n🔸 সময় ও কাগজের অপচয় কমবে\r\n🔸 শিক্ষক, শিক্ষার্থী ও অভিভাবকদের মধ্যে যোগাযোগ সহজ হবে\r\n🔸 প্রশাসনিক কার্যক্রম হবে আরও স্বচ্ছ ও দ্রুত\r\n🔸 ছাত্র-ছাত্রীদের পারফরম্যান্স উন্নত হবে\r\n🔸 অভিভাবকরা ঘরে বসে সন্তানের তথ্য পেয়ে যাবেন', 'event_1756321557_748.png', '2025-08-27 19:05:57');

-- --------------------------------------------------------

--
-- Table structure for table `footer_info`
--

CREATE TABLE `footer_info` (
  `id` int(11) NOT NULL DEFAULT 1,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `footer_short` text DEFAULT NULL,
  `footer_logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `footer_info`
--

INSERT INTO `footer_info` (`id`, `address`, `phone`, `email`, `facebook`, `twitter`, `youtube`, `updated_at`, `footer_short`, `footer_logo`) VALUES
(1, 'পোস্ট: হাতীবান্ধা-5030, উপজেলা: হাতীবান্ধা, জেলা: লালমনিরহাট', '01914321948', 'hatibandhaalm122818@gmail.com', 'http://facebook.com/', 'https://twitter.com/', 'https://youtube.com/', '2025-08-27 17:48:09', 'Hatibandha Alim Madrasha (EIIN: 122818), located in Hatibandha Upazila, Lalmonirhat District, is a renowned institution dedicated to promoting Islamic education alongside modern academic knowledge. Since its establishment, the madrasha has been playing a significant role in spreading the light of education, moral values, and social awareness among its students.', 'footer_logo_1756316889_636.png');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `title`, `file`, `status`, `created_at`) VALUES
(2, 'উপবৃত্তি প্রাপ্তির আবেদন ফরম', 'form_1756320272_956.pdf', 1, '2025-08-27 18:44:32');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_photos`
--

CREATE TABLE `gallery_photos` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_photos`
--

INSERT INTO `gallery_photos` (`id`, `image`, `caption`, `status`, `created_at`) VALUES
(21, 'gallery_1781863323_279.jpg', 'prayer', 1, '2026-06-19 10:02:03'),
(22, 'gallery_1781863339_922.jpg', 'program', 1, '2026-06-19 10:02:19'),
(23, 'gallery_1781863348_294.png', 'building', 1, '2026-06-19 10:02:28'),
(24, 'gallery_1781863358_203.jpg', 'fastivle', 1, '2026-06-19 10:02:38');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_videos`
--

CREATE TABLE `gallery_videos` (
  `id` int(11) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_videos`
--

INSERT INTO `gallery_videos` (`id`, `video_url`, `caption`, `status`, `created_at`) VALUES
(1, 'https://www.youtube.com/watch?v=lbOLzhYeePo', 'বাংলা ব্যঞ্জন', 1, '2025-07-17 18:45:00'),
(2, 'https://www.youtube.com/watch?v=lbOLzhYeePo', 'বাংলা ব্যঞ্জন 2', 1, '2025-07-17 18:47:49'),
(3, 'https://www.youtube.com/watch?v=55rdkWRYWsA', 'Our System', 1, '2025-07-17 18:49:36'),
(4, 'https://www.youtube.com/watch?v=_CHQb_zY--U&t=216s', 'Our System Video', 1, '2025-07-17 18:50:09');

-- --------------------------------------------------------

--
-- Table structure for table `important_links`
--

CREATE TABLE `important_links` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(500) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `important_links`
--

INSERT INTO `important_links` (`id`, `title`, `url`, `sort_order`, `status`) VALUES
(1, 'শিক্ষা মন্ত্রনালয়', 'http://www.moedu.gov.bd/', 6, 1),
(2, 'উচ্চ মাধ্যমিক শিক্ষা বোর্ড, যশোর', 'https://www.jessoreboard.gov.bd/', 5, 1),
(3, 'জাতীয় বিশ্ববিদ্যালয়', 'https://www.nu.ac.bd/', 4, 1),
(4, 'বাংলাদেশ পুলিশ', 'https://www.police.gov.bd/', 3, 1),
(5, 'Fire Service (Emergency)', 'https://fireservice.gov.bd/site/page/7676b3e3-aa06-4214-91b9-17d4cf042b4e/%E0%A6%B8%E0%A6%95%E0%A6%B2-%E0%A6%B8%E0%A7%8D%E0%A6%9F%E0%A7%87%E0%A6%B6%E0%A6%A8%E0%A7%87%E0%A6%B0-%E0%A6%A8%E0%A6%AE%E0%A7%8D%E0%A6%AC%E0%A6%B0', 2, 1),
(7, 'ছুটির তালিকা ২০২৫', 'https://shed.portal.gov.bd/sites/default/files/files/shed.portal.gov.bd/moedu_office_order/18edb4c6_a498_42a4_8dcf_974e859440ac/523.pdf', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `license_info`
--

CREATE TABLE `license_info` (
  `id` int(11) NOT NULL DEFAULT 1,
  `license_to` text DEFAULT NULL,
  `license_date` date DEFAULT NULL,
  `license_domain` varchar(255) NOT NULL DEFAULT 'localhost',
  `license_type` varchar(100) DEFAULT 'Single Domain',
  `license_expiry_date` date DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `support_line` varchar(50) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `license_status` text DEFAULT NULL,
  `LICENSE_KEY` text NOT NULL DEFAULT 'RFRSdVBUZ00zWHdLbENvdWhvTW83dz09'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `license_info`
--

INSERT INTO `license_info` (`id`, `license_to`, `license_date`, `license_domain`, `license_type`, `license_expiry_date`, `company_name`, `company_address`, `support_line`, `facebook`, `email`, `license_status`, `LICENSE_KEY`) VALUES
(1, 'Kamal Kanti Secondary School ', '2025-08-08', 'kamalkatisecondaryschool.edu.bd\n', 'Single Domain', '2025-08-08', 'KhulnaDevs', '29, Sonadanga, Khulna', '01745009934', 'https://www.facebook.com/ankandas.fb', 'info@khlnadevs.com', NULL, 'RFRSdVBUZ00zWHdLbENvdWhvTW83dz09');

-- --------------------------------------------------------

--
-- Table structure for table `management_committee`
--

CREATE TABLE `management_committee` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `sort_order` int(11) DEFAULT 100,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `management_committee`
--

INSERT INTO `management_committee` (`id`, `full_name`, `title`, `image`, `contact_number`, `joining_date`, `sort_order`, `created_at`, `updated_at`) VALUES
(30, 'KRISHAN ROY', 'President', 'default.png', '', '2024-07-21', 1, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(31, 'MD.MOKSED SARDER', 'Education', 'default.png', '01728014625', NULL, 2, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(32, 'MD. PROSAD MONDAL', 'Parents', 'default.png', '', NULL, 3, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(33, 'Md.SANTOSH BACHER', 'Parents', 'default.png', '', NULL, 4, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(34, 'Md.MASUM IQBAL', 'Parents', 'default.png', '', NULL, 5, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(35, 'JOYSNA MONDAL', 'Parents', 'default.png', '', NULL, 6, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(36, 'Baburam Sarker', 'Donor', 'default.png', '', NULL, 7, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(37, 'MD.MOHANANDA SARKER', 'Parents', 'default.png', '', NULL, 8, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(38, 'MD.SANJOY KUMAR DAS', 'President', 'default.png', '01761853505', NULL, 9, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(39, 'Kunu Pada Bachhar', 'Member Secretary', 'default.png', '01715267739', NULL, 10, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(40, 'MD. RADHA KANTA SARKAR', 'Teacher Representative', 'default.png', '01720590281', NULL, 11, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(41, 'Md.ASIT BARAN DHALI', 'Teacher Representative', 'default.png', '01751750886', NULL, 12, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(42, 'Shova Mondal', 'Teacher Representative', 'default.png', '01916758787', NULL, 13, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(43, 'KRISHNA PADA MONDAL', 'Member Secretary', 'default.png', '01309118559', '2022-06-07', 14, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(44, 'MD. ABDUR RASHED', 'Member Secretary', 'default.png', '', '2024-07-21', 15, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(45, 'PALASH KUMAR MONDAL', 'Teacher Representative', 'default.png', '01712336152', '2022-06-07', 16, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(46, 'MD ABDUL MANNAN', 'Teacher Representative', 'default.png', '01718848715', '2022-06-07', 17, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(47, 'SHOVA MONDAL', 'Teacher Representative', 'default.png', '01916758787', '2022-06-07', 18, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(48, 'PROSANTA KUMAR BACHAR', 'Teacher Representative', 'default.png', '', '2024-07-21', 19, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(49, 'MD AKBAR ALI SARDAR', 'Parents', 'default.png', '01728243291', '2022-06-07', 20, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(50, 'SANJOY KUMAR DAS', 'President', 'default.png', '01712096096', '2022-06-07', 21, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(51, 'BABURAM SARDAR', 'Donor', 'default.png', '01720687435', '2022-06-07', 22, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(52, 'MONAJIT MONDAL', 'Parents', 'default.png', '01740944570', '2022-06-07', 23, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(53, 'THAKURDASH SARKER', 'Parents', 'default.png', '01710123326', '2022-06-07', 24, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(54, 'MD SHAHEDUL ISLAM', 'Parents', 'default.png', '01718829673', '2022-06-07', 25, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(55, 'DIPANKAR KUMAR BACHHAR', 'Parents', 'default.png', '', '2024-07-21', 26, '2026-06-22 19:53:12', '2026-06-22 19:53:12'),
(56, 'BEGAM KHATUN', 'Reserved Female Guardian Member', 'default.png', '01762098835', '2022-06-07', 27, '2026-06-22 19:53:12', '2026-06-22 19:53:12');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `type` enum('head_teacher','chairman','about_school') NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `type`, `name`, `photo`, `message`, `updated_at`) VALUES
(12, 'head_teacher', 'A.K.M. Jamser Ali', 'head_teacher_1756319822_342.png', 'বর্তমান যুগে সঠিক শিক্ষা ছাড়া কোনো জাতি অগ্রসর হতে পারে না। হাতীবান্ধা আলিম মাদ্রাসা শিক্ষার্থীদের জন্য কেবল ধর্মীয় জ্ঞান নয়, আধুনিক জ্ঞান-বিজ্ঞান ও প্রযুক্তিগত দক্ষতা অর্জনের ক্ষেত্রও তৈরি করছে। আমাদের লক্ষ্য হচ্ছে শিক্ষার্থীদেরকে আলোকিত মানুষ হিসেবে গড়ে তোলা, যাতে তারা সমাজ, দেশ ও জাতির কল্যাণে অবদান রাখতে পারে।', '2025-08-27 18:37:02'),
(13, 'chairman', 'Md. Rezaul Karim', 'chairman_1756319756_231.jpg', 'হাতীবান্ধা আলিম মাদ্রাসা প্রতিষ্ঠার মূল উদ্দেশ্য ছিল শিক্ষার্থীদের মধ্যে ইসলামী শিক্ষার আলো ছড়িয়ে দেওয়া এবং নৈতিক, সামাজিক ও আধুনিক জ্ঞানচর্চায় তাদের দক্ষ করে গড়ে তোলা। প্রতিষ্ঠার শুরু থেকে আজ অবধি মাদ্রাসাটি এ লক্ষ্যে নিরলসভাবে কাজ করে যাচ্ছে, যা নিঃসন্দেহে আনন্দের বিষয়।', '2025-08-27 18:35:56'),
(14, 'about_school', '', 'about_banner_1756319694_861.png', 'হাতীবান্ধা আলিম মাদ্রাসা, উপজেলা হাতীবান্ধা, জেলা লালমনিরহাট একটি অগ্রণী ধর্মীয় ও সাধারণ শিক্ষাপ্রতিষ্ঠান। প্রতিষ্ঠার পর থেকে মাদ্রাসাটি ইসলামী শিক্ষার প্রসার, চারিত্রিক গুণাবলির বিকাশ এবং আধুনিক জ্ঞানচর্চার মাধ্যমে শিক্ষার্থীদের আলোকিত ও আদর্শ স্কুল আমাদের লক্ষ্য হচ্ছে শিক্ষার্থীদেরকে আলোকিত মানুষ হিসেবে গড়ে তোলা, যাতে তারা সমাজ, দেশ ও জাতির কল্যাণে অবদান রাখতে পারে।', '2026-06-21 17:03:06');

-- --------------------------------------------------------

--
-- Table structure for table `meta_code`
--

CREATE TABLE `meta_code` (
  `id` int(11) NOT NULL,
  `code` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meta_code`
--

INSERT INTO `meta_code` (`id`, `code`, `updated_at`) VALUES
(1, '', '2025-07-19 20:00:16');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `notice_date` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `show_in_ticker` tinyint(1) NOT NULL DEFAULT 1,
  `attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `description`, `notice_date`, `status`, `created_at`, `show_in_ticker`, `attachment`) VALUES
(15, 'বার্ষিক ক্রীড়া প্রতিযোগিতা ২০২৬', 'আগামী সপ্তাহের বুধবার মাদ্রাসার বার্ষিক ক্রীড়া প্রতিযোগিতা অনুষ্ঠিত হতে যাচ্ছে। আগ্রহীদের নাম জমা দেওয়ার অনুরোধ করা হলো।', '2026-01-12', 1, '2026-01-12 04:00:00', 0, 'sports_event_2026.pdf'),
(16, 'শহীদ দিবস ও আন্তর্জাতিক মাতৃভাষা দিবস উদযাপন', '২১শে ফেব্রুয়ারি মহান শহীদ দিবস উপলক্ষে মাদ্রাসায় আলোচনা সভা ও দোয়া মাহফিলের আয়োজন করা হয়েছে।', '2026-02-18', 1, '2026-02-18 02:30:00', 0, NULL),
(17, 'পরীক্ষার ফলাফল প্রকাশ (দাখিল নির্বাচনী পরীক্ষা)', '২০২৬ সালের দাখিল নির্বাচনী পরীক্ষার ফলাফল আগামী পরশু দিন দুপুর ১২:০০ টায় মাদ্রাসার নোটিশ বোর্ডে প্রকাশ করা হবে।', '2026-02-25', 1, '2026-02-25 08:10:22', 1, 'dakhil_result_2026.pdf'),
(18, 'নতুন শিক্ষক নিয়োগ বিজ্ঞপ্তি', 'হাতীবান্ধা আলিম মাদ্রাসার শূন্য পদসমূহে আকর্ষণীয় বেতনে দক্ষ শিক্ষক নিয়োগ দেওয়া হবে। বিস্তারিত দেখুন সংযুক্ত ফাইলে।', '2026-03-01', 1, '2026-03-01 03:15:00', 0, 'job_circular_teachers.pdf'),
(19, 'স্বাধীনতা দিবস উপলক্ষে রচনা প্রতিযোগিতা', '২৬শে মার্চ মহান স্বাধীনতা দিবস উপলক্ষে \"স্বাধীনতার গুরুত্ব\" বিষয়ে একটি উন্মুক্ত রচনা প্রতিযোগিতার আয়োজন করা হয়েছে।', '2026-03-20', 1, '2026-03-20 05:45:12', 0, 'essay_competition_rules.pdf'),
(20, 'গ্রীষ্মকালীন অবকাশ ও নোটিশ', 'তীব্র তাপদাহ এবং সরকারি নির্দেশনা মোতাবেক মাদ্রাসার সকল ক্লাস আগামী ১ সপ্তাহ স্থগিত থাকবে।', '2026-04-15', 1, '2026-04-15 07:00:00', 0, NULL),
(21, 'মাদ্রাসার গভর্নিং বডির জরুরী সভা', 'আগামী শনিবার সকাল ১০:০০ ঘটিকায় মাদ্রাসার কার্যালয়ে গভর্নিং বডির এক জরুরী সাধারণ সভা আহ্বান করা হয়েছে।', '2026-05-02', 1, '2026-05-02 10:20:00', 0, NULL),
(22, 'অনলাইন ক্লাস সংক্রান্ত নির্দেশিকা', 'বিশেষ কারণে সাময়িকভাবে আমাদের কিছু ক্লাস অনলাইনে জুমে (Zoom) নেওয়া হবে। আইডি ও পাসওয়ার্ড ডেসক্রিপশনে দেখুন।', '2026-05-10', 1, '2026-05-10 01:45:30', 1, 'online_class_schedule.pdf'),
(23, 'জাতীয় শোক দিবস ও দোয়া মাহফিল', '১৫ই আগস্ট জাতীয় শোক দিবস উপলক্ষে মাদ্রাসার মিলনায়তনে আলোচনা সভা, কোরআন খতম ও বিশেষ দোয়ার আয়োজন করা হয়েছে।', '2026-08-12', 1, '2026-08-12 04:11:14', 0, NULL),
(24, 'পুরাতন নোটিশ (টেস্টিং আর্কাইভ)', 'এটি একটি পুরাতন নোটিশ যা আর্কাইভ এবং ড্রাফট সিস্টেম পরীক্ষা করার জন্য ডাটাবেজে রাখা হয়েছে।', '2025-12-01', 0, '2025-12-01 06:00:00', 0, NULL),
(25, 'বার্ষিক মিলাদ মাহফিল ও পুরস্কার বিতরণী', 'আগামী মাসের প্রথম সপ্তাহে মাদ্রাসার বার্ষিক মিলাদ মাহফিল এবং কৃতি শিক্ষার্থীদের মাঝে পুরস্কার বিতরণ করা হবে।', '2026-09-05', 1, '2026-09-05 09:30:00', 1, 'annual_program.pdf'),
(26, 'শীতকালীন ছুটির নোটিশ', 'আগামী ২৪শে ডিসেম্বর থেকে শুরু করে ২রা জানুয়ারি পর্যন্ত শীতকালীন ছুটি উপলক্ষে মাদ্রাসার সকল কার্যক্রম বন্ধ থাকবে।', '2026-12-20', 1, '2026-12-20 03:00:00', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `file_type` enum('pdf','image') NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `class_name`, `file_type`, `file_name`, `uploaded_at`) VALUES
(1, 'SSC 2025', 'image', 'result_1752776853_844.png', '2025-07-17 18:27:33');

-- --------------------------------------------------------

--
-- Table structure for table `result_archives`
--

CREATE TABLE `result_archives` (
  `id` int(11) NOT NULL,
  `exam_name` varchar(255) NOT NULL,
  `exam_year` int(11) NOT NULL,
  `total_students` int(11) NOT NULL,
  `total_pass` int(11) NOT NULL,
  `pass_rate` decimal(5,2) NOT NULL,
  `total_gpa5` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `result_archives`
--

INSERT INTO `result_archives` (`id`, `exam_name`, `exam_year`, `total_students`, `total_pass`, `pass_rate`, `total_gpa5`, `created_at`, `updated_at`) VALUES
(2, 'দাখিল', 2022, 50, 6, 12.00, NULL, '2025-09-10 14:36:40', '2025-09-10 14:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `routines`
--

CREATE TABLE `routines` (
  `id` int(11) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `file_type` enum('pdf','image') NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `routines`
--

INSERT INTO `routines` (`id`, `class_name`, `file_type`, `file_name`, `uploaded_at`) VALUES
(3, 'Six', 'image', 'routine_1782046476_915.png', '2026-06-21 12:54:36');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `time_value` varchar(100) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `title`, `time_value`, `sort_order`, `status`, `updated_at`) VALUES
(1, 'ক্লাস শুরু', '০৯:০০ AM', 1, 1, '2026-06-22 14:15:51'),
(2, 'টিফিন বিরতি', '০১:০০ PM - ০২:০০ PM', 2, 1, '2026-06-19 09:30:47'),
(3, 'ক্লাস শেষ', '০৪:০০ PM', 3, 1, '2026-06-19 09:30:47'),
(4, 'অফিস সময়', '০৯:০০ AM - ০৪:০০ PM', 4, 1, '2026-06-22 14:16:04');

-- --------------------------------------------------------

--
-- Table structure for table `school_info`
--

CREATE TABLE `school_info` (
  `id` int(11) NOT NULL DEFAULT 1,
  `school_name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `eiin` varchar(20) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `established` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mpo_code` varchar(255) DEFAULT '',
  `school_code` varchar(255) DEFAULT '',
  `google_map` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school_info`
--

INSERT INTO `school_info` (`id`, `school_name`, `logo`, `banner`, `eiin`, `about`, `updated_at`, `established`, `address`, `phone`, `email`, `mpo_code`, `school_code`, `google_map`) VALUES
(1, 'হাতীবান্ধা আলিম মাদ্রাসা', 'logo_1756316800_176.png', 'banner_1756316800_782.png', '122818', 'হাতীবান্ধা আলিম মাদ্রাসা, উপজেলা হাতীবান্ধা, জেলা লালমনিরহাট একটি অগ্রণী ধর্মীয় ও সাধারণ শিক্ষাপ্রতিষ্ঠান। স্কুলটি  প্রতিষ্ঠার পর থেকে মাদ্রাসাটি ইসলামী শিক্ষার প্রসার, চারিত্রিক গুণাবলির বিকাশ এবং আধুনিক জ্ঞানচর্চার মাধ্যমে শিক্ষার্থীদের আলোকিত ও আদর্শ নাগরিক হিসেবে গড়ে তোলার লক্ষ্যে নিরলসভাবে কাজ করে যাচ্ছে।\nএখানে অভিজ্ঞ ও যোগ্য শিক্ষকমণ্ডলীর তত্ত্বাবধানে শিক্ষার্থীরা কুরআন-হাদিস, আরবি সাহিত্য, ইসলামিক স্টাডিজসহ সাধারণ বিষয়েও শিক্ষা গ্রহণ করে থাকে। একই সাথে আধুনিক তথ্যপ্রযুক্তি ও সমসাময়িক জ্ঞানচর্চার সুযোগ থাকায় শিক্ষার্থীরা নিজেদের যোগ্যতা ও প্রতিভা বিকাশের সুবর্ণ সুযোগ পাচ্ছে।', '2026-06-21 19:14:59', '1979', 'পোস্ট: হাতীবান্ধা-5030, উপজেলা: হাতীবান্ধা, জেলা: লালমনিরহাট', '01309118559', 'hatibandhaalm122818@gmail.com', '8902012202', '1185592', '<div style=\"width: 100%\"><iframe width=\"100%\" height=\"200\" frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=Hatibandha%20Alim%20Madrasah+(Hatibandha%20Alim%20Madrasah)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed\"><a href=\"https://www.mapsdirections.info/fr/calculer-la-population-sur-une-carte\">mesurer la population sur une carte</a></iframe></div>');

-- --------------------------------------------------------

--
-- Table structure for table `school_statistics`
--

CREATE TABLE `school_statistics` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `value` varchar(50) NOT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school_statistics`
--

INSERT INTO `school_statistics` (`id`, `title`, `value`, `suffix`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'শিক্ষার্থী', '500', '+', 1, 1, '2026-06-20 10:56:06', '2026-06-20 10:59:17'),
(2, 'শিক্ষক', '20', '+', 2, 1, '2026-06-20 10:56:06', '2026-06-20 10:59:22'),
(3, 'বছরের ঐতিহ্য', '30', '+', 3, 1, '2026-06-20 10:56:06', '2026-06-21 12:55:36'),
(4, 'পাশের হার', '99', '%', 4, 1, '2026-06-20 10:56:06', '2026-06-20 10:59:31');

-- --------------------------------------------------------

--
-- Table structure for table `seo_settings`
--

CREATE TABLE `seo_settings` (
  `id` int(11) NOT NULL,
  `page` varchar(50) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seo_settings`
--

INSERT INTO `seo_settings` (`id`, `page`, `meta_title`, `meta_description`, `meta_keywords`, `updated_at`) VALUES
(1, 'index', 'Hatibandha Alim Madrasah | Quality Islamic Education, Hatibandha, Lalmonirhat', 'Hatibandha Alim Madrasah, located in Lalmonirhat, provides high-quality Islamic and modern education for students. Learn about admissions, academic programs, and our experienced teachers.', 'Hatibandha Alim Madrasah, KhulnaDevs, Lalmonirhat Madrasah, Islamic Education, Alim Madrasah, Madrasah Admission, Hatibandha Education', '2025-08-27 17:51:20'),
(2, 'about', 'About Hatibandha Alim Madrasah | History & Mission', 'Founded in 1979, Hatibandha Alim Madrasah located in Lalmonirhat offers both Islamic and modern education. Our mission is to provide quality education and foster ethical and moral values in students.', 'Hatibandha Alim Madrasah, KhulnaDevs, Lalmonirhat Madrasah, Islamic Education, Alim Madrasah, Madrasah Admission, Hatibandha Education', '2025-08-27 17:53:13'),
(3, 'contact', 'Contact Hatibandha Alim Madrasah | Phone, Address, Email', 'eiin 122818 , Hatibandha Alim Madrasah. Address: Hatibandha, Lalmonirhat. Connect with us via phone, email, or contact form for any inquiries.', 'Hatibandha Alim Madrasah, KhulnaDevs, Lalmonirhat Madrasah, Islamic Education, Alim Madrasah, Madrasah Admission, Hatibandha Education', '2025-08-27 17:53:08'),
(4, 'notices', 'Notices of Hatibandha Alim Madrasah', 'Hatibandha Alim Madrasah (eiin 122818), located in Lalmonirhat, provides high-quality Islamic and modern education for students. Learn about admissions, academic programs, and our experienced teachers.', 'Hatibandha Alim Madrasah, KhulnaDevs, Lalmonirhat Madrasah, Islamic Education, Alim Madrasah, Madrasah Admission, Hatibandha Education', '2025-08-27 17:53:47'),
(5, 'teachers', 'Teachers of Hatibandha Alim Madrasah', 'Hatibandha Alim Madrasah (eiin 122818), located in Lalmonirhat, provides high-quality Islamic and modern education for students. Learn about admissions, academic programs, and our experienced teachers.', 'Hatibandha Alim Madrasah, KhulnaDevs, Lalmonirhat Madrasah, Islamic Education, Alim Madrasah, Madrasah Admission, Hatibandha Education', '2025-08-27 17:53:57');

-- --------------------------------------------------------

--
-- Table structure for table `sidebar_widgets`
--

CREATE TABLE `sidebar_widgets` (
  `id` int(11) NOT NULL,
  `type` enum('image','html') NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sidebar_widgets`
--

INSERT INTO `sidebar_widgets` (`id`, `type`, `title`, `content`, `sort_order`, `status`, `created_at`) VALUES
(11, 'html', 'Prayer Time', '<iframe src=\"https://timesprayer.com/widgets.php?frame=1&amp;lang=en&amp;name=dhaka\" style=\"border-width: medium; border-style: none; border-color: currentcolor; border-image: initial; overflow: hidden; width: 100%; height: 142px;\"></iframe>', 0, 1, '2026-06-21 19:30:58');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_name`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'turnstile_site_key', 'na', '2025-08-27 14:01:43', '2025-08-27 17:23:01'),
(2, 'turnstile_secret_key', 'na', '2025-08-27 14:01:43', '2025-08-27 17:23:04'),
(3, 'CloudflareTurnstile_Status', '0', '2025-08-27 14:11:35', '2025-08-27 17:22:53'),
(4, 'tinify_compression_status', '1', '2025-12-23 21:23:17', '2025-12-23 21:31:02'),
(5, 'tinify_api_key', 'KFXwzw7QRz96cwd1lYSpw9TlJhmSf92Y', '2025-12-23 21:23:17', '2025-12-23 21:31:02');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption_title` varchar(255) DEFAULT NULL,
  `caption_text` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `image`, `caption_title`, `caption_text`, `sort_order`, `status`, `created_at`) VALUES
(22, 'slider_1781869155_414.jpg', 'Home Slider', 'Hatibandha Alim Madrasha building', 0, 1, '2026-06-19 11:39:15'),
(23, 'slider_1781869163_928.jpg', '', '', 0, 1, '2026-06-19 11:39:23');

-- --------------------------------------------------------

--
-- Table structure for table `student_achievements`
--

CREATE TABLE `student_achievements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_achievements`
--

INSERT INTO `student_achievements` (`id`, `title`, `subtitle`, `sort_order`, `created_at`) VALUES
(1, 'এসএসসি ২০২৪', '100% পাশের হার', 1, '2026-06-24 04:45:33'),
(2, 'জিপিএ-৫ প্রাপ্ত', '২০ জন শিক্ষার্থী', 2, '2026-06-24 04:45:33'),
(3, 'বৃত্তি প্রাপ্ত', '১৫ জন শিক্ষার্থী বৃত্তি পেয়েছে', 3, '2026-06-24 04:45:33');

-- --------------------------------------------------------

--
-- Table structure for table `student_info`
--

CREATE TABLE `student_info` (
  `id` int(11) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `total_students` int(11) NOT NULL,
  `male_students` int(11) NOT NULL,
  `female_students` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_info`
--

INSERT INTO `student_info` (`id`, `class_name`, `total_students`, `male_students`, `female_students`) VALUES
(6, 'Hifz', 50, 40, 10),
(7, 'Fazil', 30, 25, 5);

-- --------------------------------------------------------

--
-- Table structure for table `student_of_the_year`
--

CREATE TABLE `student_of_the_year` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `class` varchar(50) NOT NULL,
  `photo` varchar(255) DEFAULT 'default.png',
  `year` int(4) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_of_the_year`
--

INSERT INTO `student_of_the_year` (`id`, `name`, `class`, `photo`, `year`, `status`, `created_at`) VALUES
(7, 'Sheikh Raihan', 'Three', 'student_of_year_1756319218_859.jpeg', 2025, 1, '2025-08-27 18:26:58'),
(8, 'Tanvir Hasan', 'Four', 'student_of_year_1756319240_869.jpg', 2025, 1, '2025-08-27 18:27:20'),
(9, 'Shahriar', 'Five', 'student_of_year_1756319256_512.jpeg', 2025, 1, '2025-08-27 18:27:36'),
(10, 'Rakib', 'Six', 'student_of_year_1756319285_185.jpeg', 2025, 1, '2025-08-27 18:28:05');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT 'default.png',
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `designation`, `bio`, `photo`, `status`, `created_at`, `phone`, `email`, `sort_order`) VALUES
(10, 'Md. Abdullah Al Mamun', 'Principal', 'Md. Mamun has over 20 years of experience in Islamic education and school administration. He is committed to providing quality education and fostering moral values among students.', 'default.png', 1, '2025-08-27 18:10:09', '017111111111', 'teacher@example.com', 0),
(11, 'Ayesha Sultana', 'Arabic Department', 'Ayesha Sultana specializes in Arabic language and literature with 15 years of teaching experience. She focuses on developing students\' linguistic and analytical skills.', 'default.png', 1, '2025-08-27 18:11:10', '01775457008', 'teacher@example.com', 0),
(12, 'Md. Rezaul Karim', 'Senior Islamic Studies Teacher', 'Md. Karim has been teaching Islamic Studies for 18 years. He emphasizes understanding the Quran and Hadith with practical application in daily life.', 'teacher_1756318325_446.jpg', 1, '2025-08-27 18:12:05', '01745008541', 'teacher@example.com', 0),
(13, 'Md. Jahangir Hossain', 'Mathematics Teacher', 'Md. Hossain has a strong background in mathematics and enjoys making complex concepts easy to understand for students.', 'default.png', 1, '2025-08-27 18:13:23', '01745000000', 'teacher@example.com', 0),
(14, 'Sharmin Akter', 'English Teacher', 'Sharmin Akter is proficient in both Bangla and English literature and language. She integrates modern teaching methods to enhance learning.', 'default.png', 1, '2025-08-27 18:13:48', '0174500964', 'test@gmail.com', 0),
(15, 'Rabeya Khatun', 'Science Teacher', 'Rabeya Khatun has 10 years of experience teaching Physics, Chemistry, and Biology. She emphasizes hands-on experiments and practical learning.', 'default.png', 1, '2025-08-27 18:14:22', '01745889965', 'test@gmail.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `youtube_url` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_info_links`
--
ALTER TABLE `academic_info_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admission_info`
--
ALTER TABLE `admission_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_info`
--
ALTER TABLE `footer_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_photos`
--
ALTER TABLE `gallery_photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_videos`
--
ALTER TABLE `gallery_videos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `important_links`
--
ALTER TABLE `important_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `license_info`
--
ALTER TABLE `license_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `management_committee`
--
ALTER TABLE `management_committee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meta_code`
--
ALTER TABLE `meta_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `result_archives`
--
ALTER TABLE `result_archives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routines`
--
ALTER TABLE `routines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_info`
--
ALTER TABLE `school_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_statistics`
--
ALTER TABLE `school_statistics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seo_settings`
--
ALTER TABLE `seo_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page` (`page`);

--
-- Indexes for table `sidebar_widgets`
--
ALTER TABLE `sidebar_widgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_name` (`setting_name`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_achievements`
--
ALTER TABLE `student_achievements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_info`
--
ALTER TABLE `student_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_of_the_year`
--
ALTER TABLE `student_of_the_year`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_info_links`
--
ALTER TABLE `academic_info_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `admission_info`
--
ALTER TABLE `admission_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gallery_photos`
--
ALTER TABLE `gallery_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `gallery_videos`
--
ALTER TABLE `gallery_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `important_links`
--
ALTER TABLE `important_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `management_committee`
--
ALTER TABLE `management_committee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `meta_code`
--
ALTER TABLE `meta_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `result_archives`
--
ALTER TABLE `result_archives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `routines`
--
ALTER TABLE `routines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `school_statistics`
--
ALTER TABLE `school_statistics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `seo_settings`
--
ALTER TABLE `seo_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sidebar_widgets`
--
ALTER TABLE `sidebar_widgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `student_achievements`
--
ALTER TABLE `student_achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_info`
--
ALTER TABLE `student_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `student_of_the_year`
--
ALTER TABLE `student_of_the_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
