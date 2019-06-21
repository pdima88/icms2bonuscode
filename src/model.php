<?php
namespace pdima88\icms2bonuscode;

use cmsModel;
use cmsEventsManager;

class model extends cmsModel {

    const TABLE_GROUPS = 'bonuscode_groups';
    const TABLE_BONUSCODES = 'bonuscode';
    const TABLE_TYPES = 'bonuscode_types';
    const TABLE_ACTIVATIONS = 'bonuscode_activations';

    function getGroups() {
        $this->orderBy('sortorder', 'asc');
        return $this->get(self::TABLE_GROUPS);
    }

    function getGroupList($none = null, $noneTitle = null) {
        $res = array_column($this->getGroups() ?: [], 'title', 'id');
        if (isset($none)) {
            $res = [$none => $noneTitle] + $res;
        }
        return $res;
    }

    function getGroup($id) {
        return $this->getItemById(self::TABLE_GROUPS, $id);
    }

    function getTypes() {
        return $this->get(self::TABLE_TYPES);
    }

    function getTypeList($none = null, $noneTitle = null) {
        $res = array_column($this->getTypes() ?: [], 'title', 'id');
        if (isset($none)) {
            $res = [$none => $noneTitle] + $res;
        }
        return $res;
    }

    function getComponentList($none = null, $noneTitle = null) {
        $res = [];
        if (isset($none)) {
            $res = [$none => $noneTitle];
        }
        return cmsEventsManager::hook('bonuscode_component_list', $res, []);
    }

    function generateCode() {
        //To Pull 8 Unique Random Values Out Of AlphaNumeric

        //removed number 0, capital o, number 1 and small L
        //Total: keys = 32, elements = 33
        $characters = array(
            "A","B","C","D","E","F","G","H","J","K","L","M",
            "N","P","Q","R","S","T","U","V","W","X","Y","Z",
            "1","2","3","4","5","6","7","8","9");

        //make an "empty container" or array for our keys
        $keys = array();

        //first count of $keys is empty so "1", remaining count is 1-7 = total 8 times
        while(count($keys) < 9) {
            //"0" because we use this to FIND ARRAY KEYS which has a 0 value
            //"-1" because were only concerned of number of keys which is 32 not 33
            //count($characters) = 33
            $x = mt_rand(0, count($characters)-1);
            if(!in_array($x, $keys)) {
                $keys[] = $x;
            }
        }
        $random_chars = ''; $i = 0;
        foreach($keys as $key){
            $random_chars .= $characters[$key]; $i++;
            if ($i == 3 || $i == 6) $random_chars.='-';
        }
        return $random_chars;
    }

    function getBonusCodeType($id) {
        $item = $this->getItemById(self::TABLE_TYPES, $id);
        $itemData = cmsModel::yamlToArray($item['data'] ?? '');
        return $item + $itemData;
    }

    function getByCode($code, $component = null, $type_id = null) {
        $this->join(self::TABLE_TYPES, 't', 't.id = i.type_id');
        if (isset($component)) $this->filterEqual('t.component', $component);
        if (isset($type_id)) $this->filterEqual('t.id', $type_id);
        $this->filterEqual('i.code', $code);
        $bonus = $this->getItem(self::TABLE_BONUSCODES);
        if ($bonus) {
            $bonusType = $this->getBonusCodeType($bonus['type_id']);
            $bonus['type'] = $bonusType;
        }
        return $bonus;
    }

    function checkActive($bonusCode) {
        if (!$bonusCode['is_active']) return false;
        if (!datetime_empty($bonusCode['date_valid'])) {
            if (strtotime($bonusCode['date_valid']) < time()) {
                return false;
            }
        }
        if ($bonusCode['max_activation_count'] != 0 &&
            $bonusCode['total_activation_count'] >= $bonusCode['max_activation_count']) {
            return false;
        }
        return true;
    }

    function getActivation($bonusCodeId, $userId) {
        $this->filterEqual('code_id', $bonusCodeId);
        $this->filterEqual('user_id', $userId);
        return $this->getItem(self::TABLE_ACTIVATIONS);
    }
}
