<?php

/**
 * appname_zhaoyisheng_hongbao 表
 * .
 *
 * @farwish
 */

include('model_base.php');

$sql = "SELECT SUM(`hongbao_money`) as total_income,
       `role_id`
  from `appname_zhaoyisheng_hongbao`
 GROUP BY `role_id`";

$result = $di->get('db')->query($sql);

$data = $result->fetchAll();

if ($data && is_array($data)) {

    $i = 0;
    $s = 0;

    foreach ($data as $v) {
        $total_income = $v['total_income']*100;
        $sql = "UPDATE appname_m_members SET total_income='{$total_income}'  WHERE id = {$v['role_id']}";
        $bool = $di->get('db')->execute($sql);
        if (! $bool) {
            $i++;
        }else{
            $s++;
        }
    }

    echo "hongbao count Complete! 共成功{$s} 次，失败 {$i} 次.\n";
}


$sql = "SELECT SUM(`hongbao_money`) as total_income,
       `role_id`
  from `appname_zhaoyisheng_hongbao`
  where `type` = 1
 GROUP BY `role_id`";

$result = $di->get('db')->query($sql);

$data = $result->fetchAll();

if ($data && is_array($data)) {

    $i = 0;
    $s = 0;

    foreach ($data as $v) {
        $k_total_income = $v['total_income']*100;
        $sql = "UPDATE appname_m_members SET k_income='{$k_total_income}'  WHERE id = {$v['role_id']}";
        $bool = $di->get('db')->execute($sql);
        if (! $bool) {
            $i++;
        }else{
            $s++;
        }
    }

    echo "k_hongbao count Complete! 共成功{$s} 次，失败 {$i} 次.\n";
}


$sql = "SELECT SUM(`hongbao_money`) as total_income,
       `role_id`
  from `appname_zhaoyisheng_hongbao`
  where `type` = 2
 GROUP BY `role_id`";

$result = $di->get('db')->query($sql);

$data = $result->fetchAll();

if ($data && is_array($data)) {

    $i = 0;
    $s = 0;

    foreach ($data as $v) {
        $cash_total_income = $v['total_income']*100;
        $sql = "UPDATE appname_m_members SET cash_income='{$cash_total_income}'  WHERE id = {$v['role_id']}";
        $bool = $di->get('db')->execute($sql);
        if (! $bool) {
            $i++;
        }else{
            $s++;
        }
    }

    echo "cash_hongbao count Complete! 共成功{$s} 次，失败 {$i} 次.\n";
}


//$sql = "SELECT SUM(`gold`) as total_kmoney,  `role_id` from `appname_zhaoyisheng_gold_log` WHERE `type` !=9 GROUP BY `role_id`";
//
//$result = $di->get('db')->query($sql);
//
//$data = $result->fetchAll();
//
//if ($data && is_array($data)) {
//
//    $i = 0;
//    $s = 0;
//
//    foreach ($data as $v) {
//        $sql = "UPDATE appname_m_members SET total_kmoney='{$v['total_kmoney']}' WHERE id = {$v['role_id']}";
//        $bool = $di->get('db')->execute($sql);
//        if (! $bool) {
//            $i++;
//        }else{
//            $s++;
//        }
//    }
//
//    echo "k_money count Complete! 共成功{$s} 次，失败 {$i} 次.\n";
//}
