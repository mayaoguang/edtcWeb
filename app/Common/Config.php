<?php

#阿里云配置
define('ACCESS_KEY_ID', "LTAIzlXDchncqhCV");
define('ACCESS_KEY_SECRET', "hH8gslDYb0hex0cjLOd5w1niWCHsUT");
define('SIGN_NAME', "阿里云短信测试专用");
define('TEMPLATE_CODE', "SMS_138066024");

/*
模版类型:
	验证码
模版名称:
	优e学注册短信模板
模版CODE:
	SMS_138066024
模版内容:
	验证码${code}，感谢您注册优e学，如非本人操作，请忽略本短信。 提示：请勿泄露给他人
*/

define('COIN_RATE', 0.2);
define('FEE', 5);
define('UNLOCK_PERIOD', 86400);		#取消投票解锁期限