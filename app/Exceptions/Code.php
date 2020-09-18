<?php

namespace App\Exceptions;


class Code
{
    // 成功 Success
    const Success = 0;
    // 规则 状态码+服务模块+错误系列号 Rule statusCode+ServerModule+ErrorNumber
    // 实例 10 -> 基础框架 Example 10 -> Own System
    // 系统服务错误
    const OwnServer = 10999;
    // 未认证
    const Authenticate = 10001;
    // 不存在该路径
    const NotFound = 10002;
    const ModelNotFound = 10003;
    const MethodNotFound = 10004;
    // 权限校验不通过
    const Authorization = 10005;
    // 参数验证错误
    const Validate = 10006;
}
