-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 14, 2018 at 08:53 AM
-- Server version: 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mtwitterapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

DROP TABLE IF EXISTS `app_settings`;
CREATE TABLE IF NOT EXISTS `app_settings` (
  `id` varchar(20) NOT NULL,
  `consumer_key` varchar(255) NOT NULL,
  `consumer_secret` varchar(255) NOT NULL,
  `oauth_callback` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `consumer_key`, `consumer_secret`, `oauth_callback`) VALUES
('my_twitter_app', 'at0Hd570r40d3bJieHGX2nUXj', 'RWJK46WlLURyq2V4TwIxbMyYhWrR8DshmJomCt2xUCg74jh8Qf', 'http://127.0.0.1/mytwitterapp/callback.php');

-- --------------------------------------------------------

--
-- Table structure for table `cron_status`
--

DROP TABLE IF EXISTS `cron_status`;
CREATE TABLE IF NOT EXISTS `cron_status` (
  `cron_name` varchar(10) NOT NULL DEFAULT '',
  `cron_state` tinyint(1) NOT NULL DEFAULT '0',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`cron_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cron_status`
--

INSERT INTO `cron_status` (`cron_name`, `cron_state`, `last_updated`) VALUES
('follow', 0, '0000-00-00 00:00:00'),
('tweet', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `favorites_users`
--

DROP TABLE IF EXISTS `favorites_users`;
CREATE TABLE IF NOT EXISTS `favorites_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` varchar(255) DEFAULT NULL,
  `owner_id` varchar(100) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `last_status_id` varchar(50) NOT NULL,
  `screen_name` varchar(255) DEFAULT NULL,
  `profile_image_url` varchar(255) DEFAULT NULL,
  `followers_count` varchar(255) DEFAULT NULL,
  `friends_count` varchar(255) DEFAULT NULL,
  `statuses_count` varchar(255) DEFAULT NULL,
  `date_added` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `favorites_users`
--

INSERT INTO `favorites_users` (`id`, `tid`, `owner_id`, `name`, `last_status_id`, `screen_name`, `profile_image_url`, `followers_count`, `friends_count`, `statuses_count`, `date_added`) VALUES
(1, '632028463', '914409145842970624', 'Mohammed raya', '916931485353107457', 'Mohammedraya2', 'http://abs.twimg.com/sticky/default_profile_images/default_profile_normal.png', '1', '3', '46', '1507448482');

-- --------------------------------------------------------

--
-- Table structure for table `follow_exclusions`
--

DROP TABLE IF EXISTS `follow_exclusions`;
CREATE TABLE IF NOT EXISTS `follow_exclusions` (
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `owner_id` varchar(48) NOT NULL,
  `twitter_id` varchar(48) NOT NULL,
  `delete_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`type`,`owner_id`,`twitter_id`),
  KEY `type` (`type`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fr_632028463`
--

DROP TABLE IF EXISTS `fr_632028463`;
CREATE TABLE IF NOT EXISTS `fr_632028463` (
  `twitter_id` varchar(48) NOT NULL,
  `stp` tinyint(1) NOT NULL DEFAULT '0',
  `ntp` tinyint(1) NOT NULL DEFAULT '0',
  `otp` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`twitter_id`),
  KEY `stp` (`stp`),
  KEY `ntp` (`ntp`),
  KEY `otp` (`otp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fr_914409145842970624`
--

DROP TABLE IF EXISTS `fr_914409145842970624`;
CREATE TABLE IF NOT EXISTS `fr_914409145842970624` (
  `twitter_id` varchar(48) NOT NULL,
  `stp` tinyint(1) NOT NULL DEFAULT '0',
  `ntp` tinyint(1) NOT NULL DEFAULT '0',
  `otp` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`twitter_id`),
  KEY `stp` (`stp`),
  KEY `ntp` (`ntp`),
  KEY `otp` (`otp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fw_632028463`
--

DROP TABLE IF EXISTS `fw_632028463`;
CREATE TABLE IF NOT EXISTS `fw_632028463` (
  `twitter_id` varchar(48) NOT NULL,
  `stp` tinyint(1) NOT NULL DEFAULT '0',
  `ntp` tinyint(1) NOT NULL DEFAULT '0',
  `otp` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`twitter_id`),
  KEY `stp` (`stp`),
  KEY `ntp` (`ntp`),
  KEY `otp` (`otp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fw_914409145842970624`
--

DROP TABLE IF EXISTS `fw_914409145842970624`;
CREATE TABLE IF NOT EXISTS `fw_914409145842970624` (
  `twitter_id` varchar(48) NOT NULL,
  `stp` tinyint(1) NOT NULL DEFAULT '0',
  `ntp` tinyint(1) NOT NULL DEFAULT '0',
  `otp` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`twitter_id`),
  KEY `stp` (`stp`),
  KEY `ntp` (`ntp`),
  KEY `otp` (`otp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `regular_user`
--

DROP TABLE IF EXISTS `regular_user`;
CREATE TABLE IF NOT EXISTS `regular_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `twitter_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `regular_user`
--

INSERT INTO `regular_user` (`id`, `username`, `password`, `email`, `twitter_id`) VALUES
(111, 'nasr1', '$2y$10$EcldPAflhhkGaIxEVQP1NeNpY81z.FFKbqlaEFZkoeAU4oFjstMcO', 'nasr@gmail.com', NULL),
(110, 'nasr', '$2y$10$9/FMME7AMgB0UXUU0P03Se8URPi2Xn5Y1q54wJuinOXjpFsBMNe/2', 'nasr@gmail.com', NULL),
(109, 'admin', '$2y$10$gGRK2PYG92N5JHqRbx8SjuGj6p3b4s/7HXUjVWy2/cO5K2XmCPOaO', 'admin@gmail.com', NULL),
(108, 'mohamed2', '$2y$10$rikW8IZ3dioRrFfNpscNNO/xG4AN54X2Mhb1ep85zHKFnoxssibbS', 'mohamed@test.com', '632028463'),
(107, 'mohamed', '$2y$10$jeXLkp8oLljWolM3zZJkE.2AYvWuLCdJ9/RNgz6XK/bRRnpWL9pGm', 'mohamed@gmail.com', '914409145842970624');

-- --------------------------------------------------------

--
-- Table structure for table `replay_users`
--

DROP TABLE IF EXISTS `replay_users`;
CREATE TABLE IF NOT EXISTS `replay_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` varchar(255) NOT NULL,
  `owner_id` text NOT NULL,
  `last_status_id` varchar(50) NOT NULL,
  `screen_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `profile_image_url` varchar(255) NOT NULL,
  `followers_count` varchar(255) NOT NULL,
  `friends_count` varchar(255) NOT NULL,
  `statuses_count` varchar(255) NOT NULL,
  `date_added` varchar(255) NOT NULL,
  `replay_message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `replay_users`
--

INSERT INTO `replay_users` (`id`, `tid`, `owner_id`, `last_status_id`, `screen_name`, `name`, `profile_image_url`, `followers_count`, `friends_count`, `statuses_count`, `date_added`, `replay_message`) VALUES
(7, '632028463', 'a:1:{i:0;s:18:\"914409145842970624\";}', '1029030350796808192', 'Mohammedraya2', 'Mohammed raya', 'http://abs.twimg.com/sticky/default_profile_images/default_profile_normal.png', '2', '2', '58', '1534175314', 'جزاكم الله خيرا'),
(6, '632028463', 'a:1:{i:0;s:18:\"914409145842970624\";}', '1029030350796808192', 'Mohammedraya2', 'Mohammed raya', 'http://abs.twimg.com/sticky/default_profile_images/default_profile_normal.png', '2', '2', '58', '1534175314', 'Good');

-- --------------------------------------------------------

--
-- Table structure for table `retweets_users`
--

DROP TABLE IF EXISTS `retweets_users`;
CREATE TABLE IF NOT EXISTS `retweets_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` varchar(255) DEFAULT NULL,
  `owner_id` varchar(100) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `last_status_id` varchar(50) NOT NULL,
  `screen_name` varchar(255) DEFAULT NULL,
  `profile_image_url` varchar(255) DEFAULT NULL,
  `followers_count` varchar(255) DEFAULT NULL,
  `friends_count` varchar(255) DEFAULT NULL,
  `statuses_count` varchar(255) DEFAULT NULL,
  `date_added` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `retweets_users`
--

INSERT INTO `retweets_users` (`id`, `tid`, `owner_id`, `name`, `last_status_id`, `screen_name`, `profile_image_url`, `followers_count`, `friends_count`, `statuses_count`, `date_added`) VALUES
(8, '632028463', '914409145842970624', 'Mohammed raya', '916930281482915841', 'Mohammedraya2', 'http://abs.twimg.com/sticky/default_profile_images/default_profile_normal.png', '1', '3', '45', '1507448349');

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_tweets`
--

DROP TABLE IF EXISTS `scheduled_tweets`;
CREATE TABLE IF NOT EXISTS `scheduled_tweets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` varchar(48) NOT NULL,
  `tweet_content` text NOT NULL,
  `tweet_media` varchar(255) NOT NULL,
  `time_to_post` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  KEY `time_to_post` (`time_to_post`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `scheduled_tweets`
--

INSERT INTO `scheduled_tweets` (`id`, `owner_id`, `tweet_content`, `tweet_media`, `time_to_post`) VALUES
(1, '914409145842970624', 'Hello World', '0', '11-08-2018 7:37 pm');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_name` varchar(255) NOT NULL,
  `target_twitter_id` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task_name`, `target_twitter_id`, `created_at`) VALUES
(4, 'replay_mohamed_raya', 'a:1:{i:0;s:18:\"914409145842970624\";}', '2018-08-13 11:02:21'),
(19, 'replay1', 'a:1:{i:0;s:18:\"914409145842970624\";}', '2018-08-13 18:39:00'),
(18, 'test', 'test', '2018-08-13 18:36:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` varchar(255) NOT NULL,
  `oauth_token` varchar(255) NOT NULL,
  `oauth_token_secret` varchar(255) NOT NULL,
  `oauth_verifier` varchar(255) NOT NULL,
  `profile_image_url` text NOT NULL,
  `screen_name` varchar(255) NOT NULL,
  `followers_count` int(10) NOT NULL,
  `friends_count` int(10) NOT NULL,
  `statuses_count` int(10) NOT NULL,
  `auto_follow` tinyint(1) DEFAULT '0',
  `auto_unfollow` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `favourites_count` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `oauth_token`, `oauth_token_secret`, `oauth_verifier`, `profile_image_url`, `screen_name`, `followers_count`, `friends_count`, `statuses_count`, `auto_follow`, `auto_unfollow`, `created_at`, `favourites_count`, `name`) VALUES
('914409145842970624', '914409145842970624-wIrM5sO1lVhudny1Rardc8Cd7S6WYUH', 'JPGJiQ6uezM0Erpp6pYvgMTljYsQCC0aOAcOef6SilvnI', '2WREYqoCU1pWpb1snwwbDrdbA18AmAAp', 'http://abs.twimg.com/sticky/default_profile_images/default_profile_normal.png', 'marious63744111', 0, 0, 43, 0, 0, '2017-10-01 10:38:24', 1, 'marious'),
('632028463', '632028463-f2XQcVHj5BMbtXIUtTKBMSXSBogkw4RTutUAEw5x', 'V3sbtAX2AQOnML5DXXU5ZtVCocGI9CXmqQPQyGSOaBE4X', 'Fugj661xI2Xbb3zVp18LqKDOPXW4USmP', 'http://abs.twimg.com/sticky/default_profile_images/default_profile_normal.png', 'Mohammedraya2', 2, 2, 55, 0, 0, '2012-07-10 16:29:49', 3, 'Mohammed raya');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_cache`
--

DROP TABLE IF EXISTS `user_cache`;
CREATE TABLE IF NOT EXISTS `user_cache` (
  `twitter_id` varchar(48) NOT NULL,
  `owner_id` varchar(100) NOT NULL,
  `screen_name` varchar(32) NOT NULL,
  `actual_name` varchar(255) NOT NULL,
  `profile_image_url` mediumtext NOT NULL,
  `tweet_count` int(11) NOT NULL DEFAULT '0',
  `profile_created` datetime NOT NULL,
  `description` mediumtext NOT NULL,
  `location` varchar(128) NOT NULL,
  `time_zone` varchar(64) DEFAULT NULL,
  `background_image_url` mediumtext NOT NULL,
  `background_color` varchar(8) NOT NULL,
  `last_status` mediumtext NOT NULL,
  `last_status_date` datetime NOT NULL,
  `last_status_device` varchar(255) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `protected_ac` tinyint(1) NOT NULL DEFAULT '0',
  `is_suspended` tinyint(1) NOT NULL DEFAULT '0',
  `followers_count` int(11) NOT NULL DEFAULT '0',
  `friends_count` int(11) NOT NULL DEFAULT '0',
  `last_updated` datetime NOT NULL,
  PRIMARY KEY (`twitter_id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_cache`
--

INSERT INTO `user_cache` (`twitter_id`, `owner_id`, `screen_name`, `actual_name`, `profile_image_url`, `tweet_count`, `profile_created`, `description`, `location`, `time_zone`, `background_image_url`, `background_color`, `last_status`, `last_status_date`, `last_status_device`, `verified`, `protected_ac`, `is_suspended`, `followers_count`, `friends_count`, `last_updated`) VALUES
('461481428', '914409145842970624', 'alaa_saeed88', 'علاء سعيد', 'https://pbs.twimg.com/profile_images/905204114094792708/8utCf9KH_normal.jpg', 39476, '2012-01-11 23:12:41', 'صحفي في جريدة الجزيرة  Instagram alaasaeed88 سناب شات  httpstco7SMeArbWW6', 'جدة, المملكة العربية السعودية', 'Riyadh', 'https://abs.twimg.com/images/themes/theme1/bg.png', 'C0DEED', 'تابع حساب fuchsKSA وادعم كهربا بالتصوير مع العلبة في هاشتاق نجومفوكسسوبرسين لتدخل في السحب على ٤ ايفون ٨  او ت httpstcoDcAVJZKy4K', '2017-10-06 22:17:11', '<a target=\"_blank\" href=\"http://twitter.com/download/iphone\" rel=\"nofollow\">Twitter for iPhone</a>', 1, 0, 0, 1259835, 867, '2017-10-06 22:50:20'),
('297419374', '914409145842970624', 'RaghdaaElSaeed', 'رغدة السعيد ', 'https://pbs.twimg.com/profile_images/883444447773700096/-4gEU_LQ_normal.jpg', 169303, '2011-05-12 15:50:25', 'Writer Dotmsr ex Anchor of Nawaembas MBCMASR Body Language expertPublic Speaker Awarded fromArab center httpstcoqVwGruGROX مدونة', 'Egypt', 'Greenland', 'https://pbs.twimg.com/profile_background_images/413778576/Blue_eyes.jpg', 'C0DEED', 'RT DianaQuo ياكش يعدوا الجمايل بس httpstcop6YEz6ixHH', '2017-10-06 22:44:16', '<a target=\"_blank\" href=\"http://twitter.com/download/iphone\" rel=\"nofollow\">Twitter for iPhone</a>', 1, 0, 0, 86422, 989, '2017-10-06 22:50:20'),
('2465436362', '914409145842970624', 'liferdefempire', 'شامل نيوز ', 'https://pbs.twimg.com/profile_images/888885592263716864/TUChXiBF_normal.jpg', 49573, '2014-04-27 04:55:52', 'خدمة إخبارية شاملة و رسمية على مدار الساعة الكويت ومرخصة من وزارة الإعلام', 'للإعلانات 90948204', 'Kuwait', 'https://pbs.twimg.com/profile_background_images/463944442806693889/rI7BjYB5.jpeg', 'C0DEED', 'تويتر بدأ بوقف الحسابات التي لم توثق برقم هاتف أو الايميل او حساب غير نشط لفترة طويلة راح تلاحظون الفولورز ينقص عند httpstco3SZOumSgMB', '2017-10-06 22:48:34', '<a target=\"_blank\" href=\"http://twitter.com/download/iphone\" rel=\"nofollow\">Twitter for iPhone</a>', 1, 0, 0, 1945295, 1617496, '2017-10-06 22:50:20'),
('245177226', '914409145842970624', 'WajdWaqfi', 'Wajd Waqfi وجد وقفي', 'https://pbs.twimg.com/profile_images/834368244697985025/xnyqOytM_normal.jpg', 12718, '2011-01-31 04:14:54', 'Architect Washington Correspondent for AlJazeera wfocus on USMiddle East Comments r my own تعليقاتي ارائي RTs not necessarily endorsements', 'Washington DC, The World', 'Eastern Time (US & Canada)', 'https://abs.twimg.com/images/themes/theme4/bg.gif', '0099B9', 'بإمكان الكوليرا ان تقتل المصاب خلال ساعات اذا لم تتم معالجتهب اليمن يتوقع ان يصل عدد الإصابات الى مليون هذا العام httpstco8miz3Fzu4D', '2017-10-06 16:53:30', '<a target=\"_blank\" href=\"http://twitter.com/download/iphone\" rel=\"nofollow\">Twitter for iPhone</a>', 1, 0, 0, 67208, 960, '2017-10-06 22:50:20'),
('316053407', '914409145842970624', '7sainaljassmi', 'Aljassmi حسين الجسمي', 'https://pbs.twimg.com/profile_images/682509702832484352/cSDm_1Fz_normal.jpg', 5734, '2011-06-12 23:36:45', 'Official Twitter Worldwide Agent is mayarabbas', 'United Arab Emirates', 'Abu Dhabi', 'https://pbs.twimg.com/profile_background_images/504425676/j.jpg', '2B313D', 'جديدنا  رسمنالك صورة في بالنا \nحتبقي حته م الجنة \nإهداء إلى مصر الحبيبة \nكلمات تامر حسين\nألحان  عمرو مصطفى\n\nhttpstcoPolkntIpVx', '2017-10-06 13:49:38', '<a target=\"_blank\" href=\"http://twitter.com/download/iphone\" rel=\"nofollow\">Twitter for iPhone</a>', 1, 0, 0, 5227337, 17, '2017-10-06 22:50:20'),
('148711787', '914409145842970624', 'AlArabiya', 'قناة العربية', 'https://pbs.twimg.com/profile_images/818820656687120386/QHuE_1yq_normal.jpg', 135624, '2010-05-27 15:11:19', 'العربية أنتعرفأكثر', 'Dubai', 'Abu Dhabi', 'https://pbs.twimg.com/profile_background_images/378800000046810638/d6e5159fda44b545470044a2d876dbd7.jpeg', 'DBE9ED', 'مصر الصحافيون مطالبون بعدم إرتداء الجينز httpstcoWk7yOUIxEq العربية', '2017-10-06 22:22:13', '<a target=\"_blank\" href=\"https://www.alarabiya.net\" rel=\"nofollow\">Alarabiya Social Media Poster</a>', 1, 0, 0, 13236915, 35, '2017-10-06 22:50:20'),
('59096159', '914409145842970624', 'mnajjar76', 'محمد النجار', 'https://pbs.twimg.com/profile_images/885253284608462850/gMfRbbYD_normal.jpg', 9222, '2009-07-22 13:57:15', 'صحفي اردني والمسؤول السابق لقسم السوشال ميديا في قناة الجزيرة ajarabic  عمل مراسلا للجزيرة نت من الاردن وغطى احداثا في سوريا ومصر ولبنان', '', 'Athens', 'https://abs.twimg.com/images/themes/theme8/bg.gif', '000000', 'مخابرات مصر وإعلاميوها يتخابرون مع حماس الإرهابية httpstcoCX3ljTCDzR via ajarabic المصالحةالفلسطينية حماس غزة', '2017-10-04 17:39:03', '<a target=\"_blank\" href=\"http://twitter.com\" rel=\"nofollow\">Twitter Web Client</a>', 1, 0, 0, 8156, 3429, '2017-10-06 22:50:20'),
('632028463', '914409145842970624', 'Mohammedraya2', 'Mohammed raya', 'https://abs.twimg.com/sticky/default_profile_images/default_profile_normal.png', 42, '2012-07-10 16:29:49', '', '', NULL, 'https://abs.twimg.com/images/themes/theme1/bg.png', 'C0DEED', 'Mohammedraya2 ampx1F601', '2017-10-01 15:15:23', '<a target=\"_blank\" href=\"http://127.0.0.1/itweets/\" rel=\"nofollow\">test_twitter_bots</a>', 0, 0, 0, 1, 3, '2017-10-06 22:50:20'),
('2946753318', '914409145842970624', 'monther72', 'منذر آل الشيخ مبارك', 'https://pbs.twimg.com/profile_images/908710811454947328/h93JYY_f_normal.jpg', 33631, '2014-12-28 22:20:53', 'لاتغضب لاتغضب لاتغضب وإذا غضبت فلا ترضى  رحم الله الملك فيصل  ', '', NULL, 'https://abs.twimg.com/images/themes/theme1/bg.png', 'C0DEED', 'YoucefOunis justkhaild YaMhna saberkamatsho mmajmi781 jamalrayyan قلت لك لديك مشكلة في الفهم العلماء قد أخذو httpstcoZBkrI5CMtE', '2017-10-06 22:34:39', '<a target=\"_blank\" href=\"http://twitter.com/download/iphone\" rel=\"nofollow\">Twitter for iPhone</a>', 1, 0, 0, 51766, 136, '2017-10-06 22:50:20'),
('705726065', '914409145842970624', 'falsubaie86', 'فارس السبيعي', 'https://pbs.twimg.com/profile_images/754177034834837508/IqPrI2Ai_normal.jpg', 17166, '2013-10-09 02:30:38', 'صحفي بالشرق الأوسط وعضو هيئة الصحفيين السعوديين  أعلى وسام شرف وأغلى نوط فخر عندما تلقيت إتصال سيدي ومليكي الغالي خادم الحرمين الشريفين ', 'riyadh', NULL, 'https://abs.twimg.com/images/themes/theme1/bg.png', 'C0DEED', 'horaisah بدع مثله اي نجم كبير لكني اتحدث عن من يبالغون في مدحه مره اسطوره ومره ظاهره   ربما مشكلتي اني عاصرت الظاهره مارادونا', '2017-10-06 20:27:16', '<a target=\"_blank\" href=\"http://twitter.com/download/android\" rel=\"nofollow\">Twitter for Android</a>', 1, 0, 0, 25254, 776, '2017-10-06 22:50:20'),
('398084737', '914409145842970624', 'BilalAhmadAllam', 'بلال أحمد علام', 'https://pbs.twimg.com/profile_images/857593796699324416/T32i2FhM_normal.jpg', 4949, '2011-10-25 17:13:50', 'Egyptian Soccer Commentator mbcprosports', '', 'Muscat', 'https://abs.twimg.com/images/themes/theme1/bg.png', 'C0DEED', 'hasanalnaqour mosabalamar بركات دعاءك يا حاج حسن ', '2017-10-06 18:53:47', '<a target=\"_blank\" href=\"http://twitter.com/download/iphone\" rel=\"nofollow\">Twitter for iPhone</a>', 1, 0, 0, 529106, 860, '2017-10-06 22:50:20'),
('149842253', '914409145842970624', 'twando_com', 'Twando Official', 'https://pbs.twimg.com/profile_images/2127352494/tw_normal.jpg', 62, '2010-05-30 14:03:36', 'Twando is a comprehensive Twitter application with full support for auto follow auto DM scheduledrecurring tweets imagevideo posting and much more', 'UK', NULL, 'https://abs.twimg.com/images/themes/theme16/bg.gif', '9AE4E8', 'SonOCronus Please email a few more details of the changes youd like to see here as not quite sure what you mean httpstcojIaYGGHe28', '2017-08-22 22:25:57', '<a target=\"_blank\" href=\"http://twitter.com\" rel=\"nofollow\">Twitter Web Client</a>', 0, 0, 0, 41883, 41717, '2017-10-06 22:50:20');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
