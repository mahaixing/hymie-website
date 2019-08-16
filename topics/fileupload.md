
1. 介绍
`\hymie\upload` 命名空间下为文件上传相关类，目前实现普通文件上传功能，七牛云的暂未实现（后续会完成）,上传参考如下代码：

  ```
    class UploadController
    {
        public function doUpload()
        {
            $upload = \hymie\upload\Upload::getUpload('file', ['sub_path'=>'sub1']);
            $result = $upload->doUpload('file');

            return result()->success($result)->setView('uploadOk');
        }
    }
  ```

`\hymie\upload\Upload::getUpload()` 工厂方法默认读取 `config.php` 中上传配置的上传类型获取上传类对象，可接受以下参数:
  
  * `$type` 默认为 `file` 代表普通文件上传， `qiniu ` 代表七牛云上传.
  * `$config` 默认为空数组，上传配配置，如配置项与配置文件中相同，则会使用该数组配置。  
  
  > 普通文件上传时传入的 `$config` 数组可以传递而外的 `sub_path` 参数，用于指定相对于上传主目录的子目录名称。

2. 文件上传返回信息
文件上传返回值:

  * 成功返回信息   

    - `result` 上传结果 true
    - `origFilename` 原始文件名
    - `filemame` 新文件名
    - `fileSize` 文件大小 `KB`
    - `path` 相对于上传主目录的上传路径

  * 失败返回信息

    - `result` 上传结果 false
    - `message` 失败消息