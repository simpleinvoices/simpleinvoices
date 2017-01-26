<?php

// Cronlog runs outside of sessions and triggered by Cron
// Manually set the domain_id class member before using class methods
class CronLog
{
    public static function insert(PdoDb $pdoDb, $domain_id, $cron_id, $run_date) {
        $pdoDb->setFauxPost(array("domain_id" => $domain_id,
                                  "cron_id"   => $cron_id,
                                  "run_date"  => $run_date));
        return $pdoDb->request("INSERT", "cron_log");
    }

    public static function check(PdoDb $pdoDb, $domain_id, $cron_id, $run_date) {
        $pdoDb->addSimpleWhere('cron_id', $cron_id, "AND");
        $pdoDb->addSimpleWhere("run_date", $run_date, "AND");
        $pdoDb->addSimpleWhere("domain_id", $domain_id);
        $pdoDb->addToFunctions("COUNT(*) AS count");
        $result = $pdoDb->request("SELECT", "cron_log");
        return $result[0]['count'];
    }

    public static function select(PdoDb $pdoDb, $domain_id) {
        $pdoDb->addSimpleWhere("domain_id", $domain_id);
        $pdoDb->setOrderBy(array(array("run_date", "DESC"), array("id", "DESC")));
        $result = $pdoDb->request("SELECT", "cron_log");
        return $result;
    }
}
