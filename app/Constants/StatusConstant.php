<?php
namespace App\Constants;


class StatusConstant
{
    // 系统错误
    const ServerError = -1;
    // 成功
    const Success = 0;
    // 资源或路径错误
    const NotFoundError = 1;
    // 参数验证错误
    const ValidateError = 2;
    // 错误请求或糟糕的请求
    const BadError = 3;
    // 认证错误
    const AuthError = 4;
    // 权限错误
    const ForbiddenError = 5;
    // 第三方错误
    const ThirdPartError = 6;
}
