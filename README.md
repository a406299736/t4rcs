# t4rcs
风险控制嵌入类

使用composer管理
安装方式：
```apacheconf
composer require a406299736/t4rcs
```

1. 如何调用限流
```text
使用use引入T4RCSRouteLimiter并实现相关抽象方法后，调用allowed()方法即可, 返回true时即为通过;false时为被限流;
```

2. 如何调用账户风险等级
```text
使用use引入T4rcsRiskLevel并实现相关抽象方法
risk方法需要appName参数，必填项，并返回风险对象(RiskLevelData)或null值，是否存在风险为该对象的isRisk方法或业务自定是否存在风险(各个维度风险等级为该对象属性值)
三个维度分别：用户，网络和设备，分别调用相关with方法，不能同时为空或不传。
```