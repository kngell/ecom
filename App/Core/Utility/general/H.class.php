<?php

declare(strict_types=1);
class H
{
    //=======================================================================
    //Pages
    //=======================================================================
    //current Page
    public static function currentPage()
    {
        $currentPage = $_SERVER['REQUEST_URI'];
        if ($currentPage == '/index.php' || $currentPage == PROOT || $currentPage == PROOT . 'home/index') {
            return PROOT . 'home';
        }
        return $currentPage;
    }

    public static function isCurrentPage(string $page)
    {
        global $currentPage;
        if (!empty($page) && strpos($page, ':id') > -1) {
            $test = str_replace(':id', '', $page);

            return strpos($currentPage, $page) > -1;
        }

        return $page == $currentPage;
    }

    //=======================================================================
    //Get Object Properties
    //=======================================================================
    public static function getObjectProperties($obj)
    {
        return get_object_vars($obj);
    }

    public static function currentUser()
    {
        return AuthManager::$currentLoggedInUser;
    }

    //=======================================================================
    //Timer / Dates
    //=======================================================================
    public static function time_in_ago($timestamps)
    {
        date_default_timezone_set('Europe/Amsterdam');

        $timestamps = strtotime($timestamps) ? strtotime($timestamps) : $timestamps;

        $time = time() - $timestamps;

        switch ($time) {
                //secondes
            case $time < 60:
                return 'Just Now!';
                break;
                //minutes
            case $time >= 60 && $time < 3600:
                return (round($time / 60) == 1) ? 'a minutes ago' : round($time / 60) . ' minutes ago';
                break;
                //hours
            case $time >= 3600 && $time < 86400:
                return (round($time / 3600) == 1) ? 'an hours ago' : round($time / 3600) . ' hours ago';
                break;
                //days
            case $time >= 86400 && $time < 604800:
                return (round($time / 86400) == 1) ? 'a day ago' : round($time / 86400) . ' days ago';
                break;
                //weeks
            case $time >= 604800 && $time < 2600640:
                return (round($time / 604800) == 1) ? 'a week ago' : round($time / 604800) . ' weeks ago';
                break;
                //month
            case $time >= 2600640 && $time < 31207680:
                return (round($time / 2600640) == 1) ? 'a month ago' : round($time / 2600640) . ' months ago';
                break;
                //years
            case $time >= 31207680:
                return (round($time / 31207680) == 1) ? 'a year ago' : round($time / 31207680) . ' years ago';
                break;
        }
    }

    public static function getdysplayAllConditions($data, $type = '')
    {
        $cond = [];
        switch ($data) {
            case 'feedback':
                $cond = ['return_mode' => 'class', 'where' => ['replied' => !1]];
                break;
            case 'notification':
                $cond = ['return_mode' => 'class', 'where' => ['type' => $type]];
                break;

            default:
                $cond = ['return_mode' => 'class'];
                break;
        }

        return $cond;
    }

    public static function keysArr($table)
    {
        switch ($table) {
            case 'categories':
                return ['ecategorie' => 'categorie'];
                break;

            default:
                return ['epostID' => 'postID', 'etitle' => 'postTitle', 'e_postContent' => 'postContent', 'eauthor' => 'postAuthor', 'epost_img' => 'postImg', 'epost_status' => 'postStatus'];
                break;
        }
    }

    public static function usersCharts($data)
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $month = array_values(array_unique(array_column($data, 'Month')));
        $gender = array_filter(array_unique(array_column($data, 'gender')));
        $number = [];
        $monthStr = [];
        $i = 0;
        foreach ($month as $key => $mois) {
            $items = array_filter($data, function ($elts) use ($mois) {
                return $elts['Month'] == $mois;
            });
            if ((int) $month[$i] - 1 == 0) {
                $monthStr[] = $months[(int) $month[$i] - 1];
            }
            if (!in_array($months[(int) $month[$i] - 1], $months)) {
                $monthStr[] = $months[(int) $month[$i] - 1];
            }
            $i++;
            foreach ($items as $key => $value) {
                if (!empty($value['gender'])) {
                    $g = array_column($items, 'gender');
                    if (in_array('Male', $g) && in_array('Female', $g)) {
                        $number[$value['gender']][] = (int) $value['number'];
                    } else {
                        $number['Male'][] = (in_array('Male', $g) && $value['gender'] == 'Male') ? (int) $value['number'] : 0;
                        $number['Female'][] = (in_array('Female', $g) && $value['gender'] == 'Female') ? (int) $value['number'] : 0;
                    }
                } else {
                    if (empty($number)) {
                        $number = array_fill_keys($gender, [0]);
                    }
                }
            }
        }

        return ['month' => $monthStr, 'gender' => array_keys($number), 'number' => $number];
    }

    public static function visitorsCharts($data)
    {
        $vsData = [];
        if ($data != '') {
            foreach ($data as $key => $value) {
                if (!empty($value['countryCode']) && $value['countryCode'] != '-') {
                    $vsData[] = (object) $value;
                }
            }
        }

        return $vsData;
    }

    public static function cleanGenderData($data)
    {
        $vsData = [];
        foreach ($data as $key => $value) {
            if (!empty($value['gender'])) {
                $vsData[] = (object) $value;
            }
        }

        return $vsData;
    }

    public static function setQueryData($by_user)
    {
        if ($by_user) {
            return  ['where' => ['userID' => AuthManager::currentUser()->userID], 'return_mode' => 'class'];
        } else {
            return ['return_mode' => 'class'];
        }
    }

    public static function get_Newkeys($m, $frm_name)
    {
        switch ($m->get_tableName()) {
            case 'candidatures':
                return ['email' => 'remail'];
                break;
            case 'formations_inscriptions':
                return ['email' => 'femail'];
                break;
            case 'newsletters':
                if ($frm_name == 'newsletterinputSidebar-frm') {
                    return ['email' => 'emails'];
                } else {
                    return ['email' => 'emailf'];
                }
                break;
            case 'formations_inscriptions':
                return ['email' => 'femail'];
                break;
            case 'comments':
                // return ['message'=>'messageR'];
                break;
            case 'contacts':
                return ['email' => 'emailc'];
                break;
            case 'users':
                if ($frm_name == 'user-ckeckout-frm') {
                    return ['email' => 'chk-email', 'firstName' => 'chk-firstName', 'lastName' => 'chk-lastName'];
                }

                break;
            default:
                return;
                break;
        }
    }
}