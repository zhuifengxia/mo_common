<?php

/**
 * 新手奖, 补给部分用户的20kb.
 *
 * 如果需要更改sql的 id 范围:
 * 12060 < id < 12018
 *
 * @farwish
 */

include('model_base.php');

// TODO 注意id范围.
$sql = "SELECT id, addtime FROM appname_m_members WHERE id >= 12060 AND id <= 12018";

$result = $di->get('db')->query($sql);

$data = $result->fetchAll();

$failed_num = 0;
$member_ids = '';

if ($data) {
    echo "共有 " . count($data) . " 条数据.\n";

    $db = $di->get('db');

    foreach ($data as $val) {

        $db->begin();

        $sql = "UPDATE appname_m_members SET k_money = k_money + 20 WHERE id = '{$val['id']}'";

        $bool_1 = $db->execute($sql);

        $gold_log_data = [
            'role_id' => $val['id'],
            'type' => 4,
            'gold' => 20,
            'surplus_gold' => 20,
            'addtime' => $val['addtime'],
        ];
        
        $bool_2 = $db->insert(
            "appname_zhaoyisheng_gold_log",
            array_values($gold_log_data), 
            array_keys($gold_log_data) 
        );

        if ($bool_1 && $bool_2) {
            $db->commit();
        } else {
            $db->rollback();
            $failed_num++;			
            $member_ids .= $val['id'] . ",";
        }
    }

    if ($failed_num) {
        echo "失败次数: " . $failed_num . "\n";

        echo "操作失败的用户id: " . rtrim($member_ids, ',') . "\n";
    }

    echo "Complete.\n";
} else {
    echo "没有符合要求的数据.\n";
}
