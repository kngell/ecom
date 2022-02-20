<?php

declare(strict_types=1);
class VisitorsManager extends Model
{
    protected $_table = 'visitors';
    protected $_colID = 'vID';
    protected $_colIndex = 'cookies';
    protected $_modelName;

    //=======================================================================
    //construct
    //=======================================================================

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
        $this->_modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->_table))) . 'Manager';
    }

    //=======================================================================
    //General getters
    //=======================================================================
    // Get IP
    public function getByIp($ip)
    {
        $data_query = ['where' => ['ipAddress' => $ip], 'return_mode' => 'class', 'class' => get_class($this)];

        return $this->findfirst($data_query);
    }

    //get nb visitor
    public function get_hits()
    {
        $conditions = ['select' => 'hits', 'return_type' => 'single'];
        $nb_hits = $this->find($conditions);

        return $nb_hits;
    }

    //get Gender percentage
    public function genderPer()
    {
        $this->_table = 'utilisateur';
        $conditions = ['select' => 'gender, COUNT(*) as number', 'group_by' => 'gender'];
        $result = $this->find($conditions)->get_results();

        return $result;
    }

    //=======================================================================
    //Statistics - counter
    //=======================================================================

    //Count Users
    public function TotalCountUsers()
    {
        return $this->TotalCount('utilisateur');
    }

    //Count Visitors
    public function TotalCountVisitors()
    {
        return $this->TotalCount('visitors');
    }

    // Count total verified users
    public function VerifiedUsers($status)
    {
        $this->_table = 'utilisateur';
        $conditions = ['where' => ['Verified' => $status], 'return_type' => 'count'];
        $count = $this->find($conditions)->get_results();

        return $count;
    }

    //=======================================================================
    //General setters
    //=======================================================================

    //=======================================================================
    //General Operations
    //=======================================================================

    //Manage visitors
    public function manageVisitors(array $params = [])
    {
        $vd = H_visitors::getVisitorData($params['ip'] ?? '91.173.88.22');
        if ($this->cookie->exists(VISITOR_COOKIE_NAME)) {
            $query_data = [
                'where' => ['cookies' => $this->cookie->get(VISITOR_COOKIE_NAME), 'ipAddress' => $params['ip']],
                'op' => 'OR',
                'return_mode' => 'class',
            ];
            $v_data = $this->getAllItem($query_data);
            if ($v_data->count() == 0) {
                return $this->add_new_visitor($vd);
                // $v_data->ipAddress = $v_data->ipAddress != $params['ip'] ? $params['ip'] : $v_data->ipAddress;
            }
            if ($v_data->count() === 1) {
                $v_data = current($v_data->get_results());
                $v_data->id = $v_data->{$this->_colID};
                $v_data->hits = $v_data->hits + 1;
                if (is_array($vd) && count($vd) > 0) {
                    $fields = H::getObjectProperties($v_data);
                    $vd = $this->container->make(Request::class)->transform_keys($vd, H_visitors::new_IpAPI_keys());
                    if (!$fields == $vd) {
                        $v_data->assign($vd);
                        $v_data->useragent = $v_data->useragent != Session::uagent_no_version() ? Session::uagent_no_version() : $v_data->useragent;
                    }
                } else {
                    $v_data->ipAddress = $vd;
                }
                if ($save = $v_data->save()) {
                    return $save;
                }
            } else {
                $this->clean_visitor_database('ip');
            }
        }

        return $this->add_new_visitor($vd);
    }

    //Add new visitor
    public function add_new_visitor(mixed $data)
    {
        $cookies = $this->get_unique('cookies');
        if (is_array($data) && count($data) > 0) {
            $this->assign($this->container->make(Input::class)->transform_keys($data, H_visitors::new_IpAPI_keys()));
        } else {
            $this->ipAddress = $data;
        }
        Cookies::set(VISITOR_COOKIE_NAME, $cookies, COOKIE_EXPIRY);
        $this->cookies = $cookies;
        $this->useragent = Session::uagent_no_version();
        $this->hits++;
        if ($save = $this->save()) {
            return $save ? $this : null;
        }

        return false;
    }

    //Check visitors data on data base
    public function clean_visitor_database($by, $params = [])
    {
        switch ($by) {
            case 'cookies':
                $delete = $this->delete('', ['cookies' => Cookies::get(VISITOR_COOKIE_NAME)]);
            break;
            default:
                $delete = $this->delete(['ipAddress' => $this->ipAddress ?? H_visitors::getIP()]);
        }

        return $delete;
    }

    //update nb visitors
    public function update_visitors()
    {
        $this->id = $this->vID;
        $this->hits = $this->get_hits();
        //$hits = $this->save($data, $cond);
        $hits = $this->save('vID');

        return $hits;
    }

    //User/period
    public function usersPerPeriod()
    {
        $this->_table = 'SELECT gender, COUNT(*) as number, MONTH(registerDate) as Month FROM Utilisateur WHERE registerDate ';
        $this->_table .= '>= CURDATE() - INTERVAL 6 MONTH GROUP BY YEAR(registerDate), MONTH(registerDate), gender ASC';
        $result = $this->find()->get_results();

        return $result;
    }

    //User/period
    public function visitorsByCtry()
    {
        $this->_table = 'SELECT countryCode, COUNT(*) as number FROM visitors WHERE date_enreg ';
        $this->_table .= '>= CURDATE() - INTERVAL 12 MONTH GROUP BY countryCode ASC';
        $result = $this->find()->get_results();

        return $result;
    }

    //Users verified and Unverified Percentage
    public function verifiedPer()
    {
        $this->_table = 'utilisateur';
        $conditions = ['select' => 'verified, COUNT(*) as number', 'group_by' => 'verified'];
        $result = $this->find($conditions)->get_results();

        return $result;
    }

    //=======================================================================
    //Posts Maanagement indicators
    //=======================================================================

    //Count Users
    public function TotalCountPosts()
    {
        return $this->TotalCount('posts');
    }

    //=======================================================================
    //Feedback Maanagement indicators
    //=======================================================================
    public function TotalCountFeedback()
    {
        return $this->TotalCount('feedback');
    }

    //=======================================================================
    //Feedback Maanagement indicators
    //=======================================================================
    public function TotalCountNotification()
    {
        return $this->TotalCount('notification');
    }
}