#yk-common

`composer require 'yk/yk-common:dev-master'`.  

> 当前目录结构, 均属于YkCommon空间.

```
src/  
    Business/   # 和业务紧密相关的部分, 非通用.  

    DataState/  # 数据库一些状态类型的值, 文件命名尽量和表名和字段挂钩.  

    Services/   # 服务操作.  

    STBase.php  # DataState/ 中的文件均继承此类.  

    Support/    # 基础设施部分, 相对通用or独立.  

    Traits/     # 共用部分.  

    WeChat/     # 放置基础的wechat操作类.  

```
