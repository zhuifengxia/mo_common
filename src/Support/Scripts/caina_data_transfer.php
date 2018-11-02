<?php

/**
 * appname_zhaoyisheng_cainai_log 数据迁移至 
 * 	wenda_log 表 accept_time 字段.
 *
 * @farwish
 */

include('model_base.php');

$sql = "SELECT cainai_answer_id, addtime FROM appname_zhaoyisheng_cainai_log";

$result = $di->get('db')->query($sql);

$data = $result->fetchAll();

$failed_num = 0;
$cainai_answer_ids = '';

if ($data) {
    echo "共有 " . count($data) . " 条采纳数据.\n";

    foreach ($data as $val) {
        $sql = "UPDATE appname_zhaoyisheng_wenda_log SET accept_time = {$val['addtime']} WHERE id = {$val['cainai_answer_id']}";
        
        $bool = $di->get('db')->execute($sql);

        if (! $bool) {
            $failed_num++;			
            $cainai_answer_ids .= $val['cainai_answer_id'] . ",";
            echo "Execute fail: " . $sql . " ! \n";
        }
    }

    if ($failed_num) {
        echo "失败次数: " . $failed_num . "\n";

        echo "操作失败的cainai_answer_id: " . rtrim($cainai_answer_ids, ',') . "\n";
    }

    echo "Complete.\n";
} else {
    echo "没有要迁移的采纳数据.\n";
}
