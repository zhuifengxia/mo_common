<?php

/**
 * appname_zhaoyisheng_hongbao 表
 * .
 *
 * @farwish
 */

include('model_base.php');

$sql = "SELECT SUM(appname_zhaoyisheng_trade_log.`k_money`) as reward,
       uid,
       appname_m_members.`k_money` as k_reward
  FROM `appname_zhaoyisheng_trade_log`
  LEFT JOIN `appname_m_members` ON `appname_zhaoyisheng_trade_log`.`uid`= appname_m_members.id
 where appname_zhaoyisheng_trade_log.`question_type`= 7
   and type= 2
   and appname_zhaoyisheng_trade_log.`k_money`> 0
 GROUP BY uid";

$result = $di->get('db')->query($sql);

$data = $result->fetchAll();

$failed_num = 0;

if ($data && is_array($data)) {

    $i = 0;
    $s = 0;

    echo "共有 " . count($data) . " 条数据.\n";
    $db = $di->get('db');

    foreach ($data as $v) {

        $db->begin();
        $sql = "UPDATE appname_m_members SET k_money= k_money+{$v['reward']}  WHERE id = {$v['uid']}";
        $bool_1 = $di->get('db')->execute($sql);

        $gold_log_data = [
            'role_id' => $v['uid'],
            'type' => 25,
            'gold' => $v['reward'],
            'surplus_gold' => $v['k_reward']+$v['reward'],
            'addtime' => time(),
        ];

        $bool_2 = $db->insert(
            "appname_zhaoyisheng_gold_log",
            array_values($gold_log_data),
            array_keys($gold_log_data)
        );

        if ($bool_1 && $bool_2) {
            $db->commit();
            $s++;

        } else {
            $db->rollback();
            $failed_num++;
            $member_ids .= $v['uid'] . ",";
        }
    }

    if ($failed_num) {
        echo "失败次数: " . $failed_num . "\n";

        echo "操作失败的用户id: " . rtrim($member_ids, ',') . "\n";
    }

    echo "pay_to_see return Complete! 共成功{$s} 次，失败 {$failed_num} 次.\n";
}


