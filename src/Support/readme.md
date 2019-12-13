## 基础设施部分, 通用.

```
Codes.php           # 通用状态类
CreateQRCode.php    # 两个图片合成为一个图片类
EncryptTool.php     # 加密
ExportExcel.php     # 导出数据到excel,使用此需要安装phpoffice类库,在根目录运行`composer require phpoffice/phpspreadsheet`
Helper.php          # 通用类，大概包括订单生成；密码生成；两点之间距离；接口响应格式；验证手机号；验证银行卡；随机字符串/验证码；客户端ip地址；删除文件夹文件；生成token；字符串去符号；截取字符串；是否微信打开判断；等
MapsTool.php          # 微信经纬度转换为百度经纬度，计算两点之间距离
```
