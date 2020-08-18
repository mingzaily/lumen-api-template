<?php

namespace App\Constants;


class ErrCode
{
    // 成功 Success
    const Success = 0;
    // 规则 状态码+服务模块+错误系列号 Rule statusCode+ServerModule+ErrorNumber
    // 实例 00 -> 基础框架 Example 00 -> Own System
    // 系统服务错误
    const OwnServer = 5000099;
    // 未认证
    const Authenticate = 4010001;
    // 不存在该路径
    const NotFound = 4040001;
    const ModelNotFound = 4040002;
    const MethodNotFound = 4050001;
    // 权限校验不通过
    const Authorization = 4030001;
    // 参数验证错误
    const Validate = 4220001;
}
