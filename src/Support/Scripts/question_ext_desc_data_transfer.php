<?php

/**
 * appname_zhaoyisheng_question_log 表
 *  desc 字段数据迁移至 description 字段.
 *
 * @farwish
 */

include('model_base.php');

$sql = "SELECT * FROM appname_zhaoyisheng_question_ext";

$result = $di->get('db')->query($sql);

$data = $result->fetchAll();

if ($data && is_array($data)) {

    $i = 0;

    foreach ($data as $v) {
        if ($v['desc']) {
            $sql = "UPDATE appname_zhaoyisheng_question_ext SET description='{$v['desc']}' WHERE id = {$v['id']}";
            $bool = $di->get('db')->execute($sql);
            
            if (! $bool) {
                $i++;
            }
        }
    }

    echo "Complete! 共失败 {$i} 次.\n";
}
