#mo-common

`composer require 'mo/mo-common:dev-master'`.  

> 当前目录结构, 均属于MoCommon空间.

```
src/  
  
    DataState/  # 数据库一些状态类型的值, 文件命名尽量和表名和字段挂钩.  

    Payment/   # 支付相关.  

    STBase.php  # DataState/ 中的文件均继承此类.  

    Support/    # 基础设施部分, 相对通用or独立.  
    
    Smscode/    # 短信类

    WeChat/     # 放置基础的wechat操作类.  

```
