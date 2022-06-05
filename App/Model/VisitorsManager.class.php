<?php

declare(strict_types=1);
class VisitorsManager extends Model
{
    protected string $_table = 'visitors';
    protected string $_colID = 'vID';
    protected string $_colIndex = 'cookies';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function manageVisitors(array $params = [])
    {
        $ipData = H_visitors::getIpData($params['ip'] ?? '91.173.88.22');
        if ($this->cookie->exists(VISITOR_COOKIE_NAME)) {
            $visitorInfos = $this->getVisitorInfos($params['ip'] ?? '91.173.88.22');
            $return_value = match ($visitorInfos->count()) {
                0 => $this->add_new_visitor($ipData),
                1 => $this->updateVisitorInfos($visitorInfos, $ipData),
                default => $this->cleanVisitorsInfos($visitorInfos, $ipData)
            };
        } else {
            $return_value = $this->add_new_visitor($ipData);
        }
        return $return_value ?? false;
    }

    //Add new visitor
    public function add_new_visitor(mixed $data)
    {
        $attr = [];
        if (is_array($data) && count($data) > 0) {
            $attr = $this->response->transform_keys($data, H_visitors::new_IpAPI_keys());
        } else {
            $attr = ['ipAddress' => $data];
        }
        $cookies = $this->getUniqueID('cookies');
        $this->assign(array_merge($attr, ['cookies' => $cookies, 'useragent' => Session::uagent_no_version(), 'hits' => 1]));
        $this->cookie->set($cookies);
        if ($save = $this->save()) {
            return $save;
        }
        return false;
    }

    private function getVisitorInfos(string $ip) : self
    {
        $query_data = $this->table()
            ->where([
                'cookies' => $this->cookie->get(VISITOR_COOKIE_NAME),
                'ip_address|in' => [[$ip, '2', '3'], 'visitors'],
            ])
            ->return('class')
            ->build();
        return $this->getAll($query_data);
    }

    private function updateVisitorInfos(Model $m, mixed $ipData = [])
    {
        /** @var Model */
        $info = current($m->get_results());
        $info->assign(array_merge($info->response->transform_keys(!is_array($ipData) ? ['ip_address' => $ipData] : $ipData, H_visitors::new_IpAPI_keys()), (array) $info));
        if (!$update = $info->update()) {
            throw new BaseRuntimeException('Erreur lors de la mise à jour des données visiteur!');
        }
        return $update ?? null;
    }

    private function cleanVisitorsInfos(Model $m, mixed $ipData)
    {
        $vInfos = $m->get_results();
        if (count($vInfos) > 1) {
            foreach ($vInfos as $info) {
                $info->assign((array) $info);
                $info->getQueryParams()->reset();
                if (!$info->delete()) {
                    throw new BaseRuntimeException('Erreur lors de la mise à jour des données visiteur!');
                }
            }
            return $this->add_new_visitor($ipData);
        }
    }
}