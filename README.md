# Lumen PHP Framework

版本 Laravel Framework Lumen (6.3.3) (Laravel Components ^6.0)

Laravel6.x中文 [开发手册](https://learnku.com/docs/laravel/6.x)

Lumen6.x中文 [开发手册](https://learnku.com/docs/lumen/6.x)

lumen7  [启动模板](https://github.com/Jiannei/lumen-api-starter)

### 概况

- 规范统一的响应结构
- 使用 jwt-auth 方式授权
- 合理有效地『Repository & Service』架构设计
- 使用guzzlehttp/guzzle做API发送（Laravel7中内置） [文档](https://guzzle-cn.readthedocs.io/zh_CN/latest/)

### 统一的响应结构

> status—— 状态值，0为成功，-1为系统错误，>1为业务错误，错误码可自定义`App\Constants\StatusConstant`
> message—— 当状态值为非0时有效，用于显示错误信息。成功显示`Success`
> data—— 包含响应的 body。状态值为非`0` 时，data返回错误原因或异常名称（取决于是否开启debug模式）

#### 说明

整体响应结构设计参考如上，相对严格地遵守了 RESTful 设计准则，返回合理的 HTTP 状态码。

- data:
  - 查询单条数据时直接返回对象结构，减少数据层级；
  - 查询列表数据时返回数组结构；
  - 创建或更新成功，返回修改后的数据；（也可以不返回数据直接返回空对象）
  - 删除成功时返回空对象
- status:
  - \>0，客户端（前端）出错，HTTP 状态响应码在 400-499 之间。如，传入错误参数，访问不存在的数据资源等
  - -1，服务端（后端）出错，HTTP 状态响应码在 500-599 之间。如，代码语法错误，空对象调用函数，连接数据库失败，undefined index 等
  - 0，success, HTTP 响应状态码一般为2XX，用来表示业务处理成功。
- message: 描述执行的请求操作处理的结果。

### 错误和异常

`App\Exceptions\Handler`

```
protected function prepareJsonResponse($request, Exception $exception)
{
    // ajax请求
    // 需要自定义处理的框架异常
    if ($report = ExceptionReport::shouldReport($request, $exception)) {
    	return $report->report();
    }
    // 无法预计的异常和自定义错误（继承RenderException），检查开启debug决定是否对外暴露错误
    return $this->fail(
        $exception instanceof RenderException ? $exception->getStatus() : StatusConstant::ServerError,
        $exception instanceof RenderException ? $exception->getMessage() : 'Server Error',
        $exception instanceof RenderException ? $exception->getCode() : HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
        env('APP_DEBUG') ? $this->convertExceptionToArray($exception) : null
    );
}
```

`App\Exceptions\ExceptionReport`中doReport是需要进行自定义处理的框架异常，否则一律按系统异常处理

```php
protected $doReport = [
    AuthenticationException::class => ['status' => StatusConstant::AuthError, 'message' => 'Token is Invalid', 'code' => 401],
    NotFoundHttpException::class => ['status' => StatusConstant::NotFoundError, 'message' => 'Not Found', 'code' => 404],
    MethodNotAllowedHttpException::class => ['status' => StatusConstant::NotFoundError, 'message' => 'Method Not Allow', 'code' => 405],
];
```

如有更好的解决方案可以提出

### Repository & Service 模式架构

使用依赖注入进行调用

架构可以查看参考中的文档

#### 建议

一个控制器单一注入一个Service

Service间可互相调用

一个Service单一注入一个Repository

一个Repository单一注入一个Model

#### 使用

为了方便Repository使用model字段，可以安装第三方包`barryvdh/laravel-ide-helper`

1.  `composer require barryvdh/laravel-ide-helper --dev`本地安装ide-helper

2. `bootstrap\app.php`

    将`// $app->register(Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);`进行反注释

3. 切换到命令行

   `php artisan ide-helper:models`

### 其他

公司IDP有相关计划，看到Laravel China的[帖子](https://learnku.com/articles/45311#reply145129)，也决定撸一个LTS版的

#### 参考

- [是时候使用 Lumen 7 + API Resource 开发项目了！](https://learnku.com/articles/45311#replies)
- [如何使用 Repository 模式](https://www.kancloud.cn/curder/laravel/408484)
- [如何使用 Service 模式](https://www.kancloud.cn/curder/laravel/408485)
- [RESTful API 最佳实践](https://learnku.com/articles/13797/restful-api-best-practice)
- [RESTful 服务最佳实践](https://www.cnblogs.com/jaxu/p/7908111.html)

