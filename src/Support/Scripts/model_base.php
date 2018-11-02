<?php
/**
 * 连数据库.
 *
 * Usage:
 *  include('model_base.php');
 *  $result = $di->get('db')->query($sql);
 *  $data = $result->fetchAll();
 *
 * @farwish
 */

$db_config = [ 
    'host' => 'localhost',
    'username' => 'admin',   // 更改
    'password' => 'admin', // 更改
    'dbname' => 'kkyisheng_ver001_dev',
    'port' => '3306',
    'charset' => 'utf8',
    'options' => [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC],
];
//$db_config = [
//    'host' => 'rm-uf68w67vk31hw699n.mysql.rds.aliyuncs.com',
//    'username' => 'kkyisheng_1607',   // 更改
//    'password' => 'GTaoS2qyHQD7Mi', // 更改
//    'dbname' => 'kkys',
//    'port' => '3306',
//    'charset' => 'utf8',
//    'options' => [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC],
//];

$bk_config = [
    'host' => 'rm-uf68w67vk31hw699n.mysql.rds.aliyuncs.com',
    'username' => 'kkyisheng_1607',   // 更改
    'password' => 'GTaoS2qyHQD7Mi', // 更改
    'dbname' => 'baike_dev',
    'port' => '3306',
    'charset' => 'utf8',
    'options' => [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC],
];

$di = new Phalcon\Di\FactoryDefault();

$di->set('db', function() use ($db_config) {
    return new \Phalcon\Db\Adapter\Pdo\Mysql($db_config);
});
$di->set('bk_db', function() use ($bk_config) {
    return new \Phalcon\Db\Adapter\Pdo\Mysql($bk_config);
});