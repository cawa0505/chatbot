<?php

$messages = [

    'intro1' => <<<EOF
CommuneChatbot 是一个多轮对话开发框架, 开源, 语言是 PHP. 可在语音或文字通讯平台上, 开发多轮对话应用. 旨在让工程师开发多轮对话应用时, 像过去开发 web 网站, app 一样得心应手.
EOF
    ,
    'intro2' => <<<EOF
本项目的重点, 在于用工程化的手段为"复杂多轮对话"提供了一套解决方案. 相比现阶段对话机器人通常只实现一阶多轮对话, CommuneChatbot 能实现N阶多轮对话分形式嵌套, 解决由此产生的各种 "复杂多轮对话问题", 从而搭建多轮交互能力更完整的对话机器人.
EOF
    ,
    'intro3' => <<<EOF
此外本项目也致力实现工业级可用的对话机器人框架, 以良好的工程性作为首要原则. 为实现高性能使用了 swoole + hyperf 的协程IO客户端; 设计了流畅的, 易于开发和阅读的api; 并且方方面面都考虑了高度组件化, 可配置. 
EOF
    ,
    'intro4' => <<<EOF
本项目着重于多轮对话管理, 并将 NLU (自然语言单元, 负责语音转换, 意图解析, 实体解析, 文本输出等) 作为第三方资源引用, 从而具备自然语言的能力.
EOF
    ,
    'intro5' => <<<EOF
CommuneChatbot 的应用方向可能有:

- 对话式OS
- 智能客服
- 在线问答
- 对话式交互游戏
- 智能音箱
- 语音机器人
- 对话式运维机器人
- 等等
EOF
    ,

    'chatbot' => [
        // chatbot
        'info1' => '对话机器人是在语音平台 (例如小度, 小米, 天猫精灵等智能音箱), 即时通讯平台 (qq, 微信等) 上运行的机器人. 能在对话交互中听从用户的命令, 提供各种信息或执行各种任务.',

        'info2' => '早期的对话机器人, 主要出现在聊天场景, 例如早期的小冰. 随着自然语言技术的发展, 出现了越来越多的拟人对话机器人, 例如智能客服, AI电销等.

而 "siri" 和日渐流行的智能音箱, 智能蓝牙耳机等, 则可以操作其它智能软件和硬件. 从而初步具备了 "对话操作系统" 的雏形.',

        'info3' => '对话机器人未来发展方向, 应该是类似钢铁侠中"贾维斯"和"星期五"那样的智能助理, 从而带来全新的生产力. 这是由于:

- 语言是人类传递出信息最高效的方式
- 语音交互可以解放人的眼睛和双手',

        'info4' => '这在技术上需要在 "语音识别", "语义识别", "语言输出", "对话管理", "物联网交互" 等各个方面技术都充分发展.',

        'info5' => 'CommuneChatbot 项目致力于 "对话管理" 的领域, 希望在解决 "复杂多轮对话" 难题的前提下, 能快速开发出各种基于对话交互的应用, 探索对话交互的各种可能性.',

        // chatbot.interface
        'interface' => [

            'info1' => '现阶段在许多用户理解中, 对话机器人就是用来聊天的. 我个人认为对话机器人的本质是一种"交互方式", 和 "键盘 + 命令行", "鼠标 + 窗口", "触屏" 本质是一样的.

类似 "鼠标+键盘" 开创了桌面电脑的时代, "触屏" 开创了移动互联的时代, 我认为基于 "语音" 的对话也可能开启下一个时代.',

            'info2' => '而这些交互形式的本质, 都是让人去便捷地使用机器. "触屏" 空间上解放了人, 让人在马桶上也能操作智能设备. 而未来 "语音" 则能在姿势上解放人, 解放人的眼睛和双手, 通过智能无线耳机等设备, 与周围所有的智能设备互动.

所以本人认为 "聊天" 对于对话机器人而言, 不是充分必要的功能. 关键在于交互, 能够快速开发出各种应用, 操纵各种设备.',

            'info3' => '半个世纪来关于机器人的科幻设想, 许多语音交互还是基于关键字或指定语法的, 甚至有语音使用手册. 所以以交互为目的, 不该忘记了人自己超强的学习能力. 在对话交互发展的当前阶段, 有许多领域工程开发可以走在机器学习的前面.'

        ],

        // chatbot.classification
        'classification' => [
            'info1' => <<<EOF
对话交互按功能可分为两大类:
- 闲聊型
- 任务型

闲聊型是目前最常见的对话机器人, 它能与用户进行无目的的闲聊.

而任务型机器人, 需要识别用户意图后有所作为. 例如苹果的 siri  就可以执行语音搜索, 而智能音箱可以用语音播放歌曲.
EOF
            ,
            'info2' => <<<EOF
按完成一个任务需要的对话轮次计, 又可分为:
- 单轮对话
- 一阶多轮对话
- N阶多轮对话
EOF
            ,
            'info3' => '现在主流的基于填写意图词槽的方式实现的多轮对话, 是所谓"一阶多轮对话", 从头到尾按顺序执行. 
        
而所谓N阶多轮对话, 则是指:

- 分型嵌套: 多轮对话任何一个环节, 都可以用另一个多轮对话来实现
- 分支与循环: 多轮对话拥有像编程语言一样最基本的 if 分支, 和loop循环.

由此带来各种远超一阶多轮对话的复杂问题. 这些也是 CommuneChatbot 项目试图解决的重点.',

        ],

        // chatbot.app
        'app' => [
            'info1' => <<<EOF
对话交互目前有两个关键的方向, 一个是拟人的交互, 一个是人机交互.

拟人的对话应用比较常见的如:

- 智能客服
- AI电销

由于可以取代一定人力, 又属于自然语言技术的主战场, 因此正在飞速发展中.
EOF
            ,
            'info2' => <<<EOF
而 CommuneChatbot 项目更偏向另一个领域, 将对话作为人机交互的一种形式, 进一步发展为语音OS. 例如 :

- 桌面机器人 (如siri, 小娜等)
- 智能家居语音中控
- 运维对话机器人 (例如hubot)
- 智能音箱中控 (例如小度)

就像触屏普及一样, 如果语音OS普及了, 一些应用 (指令, 写作, 审查, 浏览...) 都会生衍生出响应的语音控制手段, 而且很可能像浏览器标准那样走向通用化, 本地化.
EOF
            ,
            'demo' => <<<EOF
CommuneChatbot 现阶段的 Demo 有:

- web端 : https://communechatbot.com
- 微信公众号 : CommuneChatbot
- 小度音箱 : 对音箱说 "打开三国群英传"
EOF
            ,

        ],

        //chatbot.advantages
        'advantages' => '
对话交互, 最大的优势在于, 语言是人类传递信息最有效的形式. 表现在应用上, 则是可以通过语言直达意图, 而不需要层层点选.

语音形式的对话交互, 更可以解放人的姿势(双眼和手), 带来实在的生产力提升.

人类用语言做信息输出, 优势很大; 但作为输入, 它传递信息远没有视觉高效. 因此未来人机交互形式一定是多模式的, 而语言将成为重要的控制手段.',
    ],

    'wheels' => <<<EOF
commune/chatbot 项目基于 php 进行开发, 使用的主要第三方组件如下:

- php >= 7.2
- swoole >= 4.3 : 用于搭建高性能的服务
- hyperf >= 1.0 : swoole 的开发框架, 较为规范, 尤其提供了多种协程客户端
- rasa : 作为系统默认的 NLU 中间
- symfony : 提供大量优秀的组件库. 
- illuminate : 提供大量优秀的组件库
- easywechat : 提供微信端和拼音包. 
- webpack + vue + vuetify : 用于搭建默认的web端页面

更详细的引用情况请看 composer.json 与 package.json
EOF
    ,

    'howtouse' => <<<EOF
本项目目前还在开发 beta 版中, 文档还没有完善. 官方网站是 https://communechatbot.com/

github :

- chatbot : 核心开发框架, 地址 https://github.com/thirdgerb/chatbot
- studio-hyperf :  基于 hyperf 开发的工作站, 地址 https://github.com/thirdgerb/studio-hyperf
EOF
    ,

    'special' => [
        'info1' => <<<EOF
开发对话机器人, 极简而言有以下模块:

- 通信平台 : 例如微信, qq, 小度音箱等.
- 输入解析 : 例如将语音转化为文字
- 服务端架构 : 搭建分布式服务器
- 语义解析 : 将文字解析成意图 (intent) 与实体 (entity)
- 对话管理 : 负责管理多轮对话
- 业务逻辑 : 实现各种业务, 例如查询天气, 购物, 查询知识库等.
- 生成输出 : 合成语音, 操作物联网设备, 渲染多媒体呈现

CommuneChatbot 项目作为开发框架, 主要负责以下领域:

- 服务端架构
- 对话管理
EOF
        ,
        'info2' => <<<EOF
CommuneChatbot 对其它所有模块的功能做了基本的抽象. 理论上可以对接各种通信平台, 输入输出组件, 自然语言单元, 业务逻辑服务等.

在基本功能之外, CommuneChat 项目有两个主要的特点:

- 面向工业级可用的工程设计.
- 实现对复杂多轮对话的管理.
EOF
        ,

        'production' => [
            'api' => <<<EOF
CommuneChatbot 是以工业级可用为目标进行开发的. 设计指标包括高性能, 流畅的api, 面向接口编程, 高度组件化, 高度可配置, 测试驱动开发. 
EOF
            ,
            'server' => <<<EOF
它基于 swoole 4.3 以上版本搭建服务端通信, 使用swoole + hyperf 的协程客户端, 拥有很好的并发性能. 请求的生命周期基于管道化的中间件, 可以按需拆卸或安装.

核心代码 API 都设计了优雅的链式调用, 基于强类型约束开发. 设计时把IDE支持效果, 代码可读性放在首要位置.
EOF
            ,
            'ioc' => <<<EOF
项目使用双容器策略实现请求级容器 (协程级) 的相互隔离, 从而在并发协程请求下使用相互隔离的控制反转容器( IOC ). 绝大多数调用处都实现了依赖注入, 面向接口编程成为最基本的开发规范.
EOF
            ,
            'component' => <<<EOF
基于依赖注入, 项目实现了高度的组件化. 不仅核心模块, 连多轮对话本身也可以拆分成类似前端项目的 widget, 按需组合. Demo 中的 "情景游戏", "闲聊", "NLU", 等各种功能全都是独立的组件.

您现在看到的 "项目介绍" 也是一个完全独立的组件. 具体可以查看 https://github.com/thirdgerb/chatbot/tree/master/src/Components.
EOF
            ,
            'config' => <<<EOF
项目基于配置中心抽象层策略, 通过多种存储介质管道式嵌套, 实现了数据源高度可替换, 可在线动态修改的配置层. 包括 "闲聊", "情景游戏", 以及当前的 "项目介绍" 在内, 都可以基于配置文件生成.

当前模块的配置在: https://github.com/thirdgerb/chatbot/tree/master/src/Components/SimpleWiki/resources/wiki/demo
EOF
            ,
            'ddd' => <<<EOF
项目的作者非常重视测试驱动, 但由于个人开发工作量太大, 目前单元测试覆盖非常有限. 项目也还需要经过工业级的生产环境来锤炼, 改进. 
EOF
            ,


            ],

        
    ],

    'conversation' => [
        'multiTurn' => <<<EOF
对话最基本的是一问一答型, 比如闲聊, 我们称之为单轮对话.

有些对话需要多个单轮对话构成 (例如问人名字, 性别, 年龄等), 然后再做出回应, 这是 "一阶多轮对话". 每个 "单轮对话" 构成 "多轮对话" 的一个环节.
EOF
        ,
        'common' => <<<EOF
现阶段常见的对话系统, 大多是 "一阶多轮对话". 用户说话时, 有个 "完全开放域" 的意图匹配, 一旦匹配到任务意图后, 又会进入 "完全封闭域" 的填槽式多轮对话.

现实中的对话, 往往由很多个一阶多轮对话, 像分形那样相互嵌套构成. 从而衍生出更多复杂的问题.
EOF
        ,
        'nLevels' => <<<EOF
首先, 对话的某个环节可能依赖另一个多轮对话, 例如购买外卖, 需要告知地址. 这是 "嵌套". 之后又去购买衣物, 就不必再问地址了, 这是 "上下文记忆". 每个多轮对话都可以嵌套若干个多轮对话, 而它们又嵌套别的多轮对话, 从而构成 "N阶多轮对话".
EOF
        ,

        'branches' => <<<EOF
而且像编程语言一样, 多轮对话每一个环节都可能出现 "分支" 和 "循环". 例如看到男孩问 "最近玩了什么游戏", 看到女孩问 "最近买了什么衣服"; 或者是听对方讲一个故事, 要用 "嗯啊哦" 来表示 "请继续", 又或说 "不想听" 退出, 说 "告诉我结局" 跳过流程
EOF
        ,

        'hangOn' => <<<EOF
有了 "分支" 和 "循环", 接下来就会出现 "挂起", "跳出", "切换", "等待" 等各种对话里的现象.

比如聊到一半, 突然说 "你刚才提到的张三现在什么工作? ", 回答完后对方要求 "继续刚才的话题", 这就涉及了 "挂起" 和 "回归".

再比如说 "你稍等, 我算好了告诉你", 这涉及阻塞; 而说 "您点的菜十分钟后上桌, 请问还有什么需求吗?", 这就是 "异步" 非阻塞的对话.

进一步的, 还有 "让出", "抢占", "多任务调度" 等一系列令人头疼的问题.
EOF
        ,
        'complexity' => <<<EOF
种种复杂的情形, 构成了 "复杂多轮对话" 问题. 在编程实现上, 还会衍生出很多复杂的工程技术问题. 例如对话状态的分布式存储, 分布式一致性, 异常处理, 对话流程可回溯 (回到上一步), 无用信息可以垃圾回收等等...

CommuneChatbot 项目就是用工程化的手段, 来解决以上复杂多轮对话问题的开发框架.
EOF
        ,


        'engineering' => [
            'twoWays' => <<<EOF
多轮对话的实现有两种大的思路.

一种是工程思路, 仍像开发 web 网站, app 服务端一样开发对话机器人. CommuneChatbot 就是这种思路.

另一种是机器学习的思路, 整理足够多实际场景的语料, 根据上下文训练出可多轮对话的模型. rasa core 就是这种思路.
EOF
            ,

            'noSilverBullet' => <<<EOF
在有大量语料, 并且试图拟人的场景下, 机器学习非常适合. 但 NLP 的技术还远未成熟, 机器学习对于对话管理而言也非银弹.

除去冷启动, 快速开发, 可测试迭代这些工程特点不说. 有一些场景可能目前还不合适机器学习的方式去解决, 却又是非常必要的.

EOF
            ,

            'complexProblems' => <<<EOF
首先就是双工的场景, 不是用户一问一答决定对话走向, 服务端也会因为 N 个状态因子的变化, 产生 M 种引导对话走向的需要. 这就很难靠语料库去遍历它们.

而遇到多轮对话出现 "多任务调度", "挂起", "回溯", "回归", "嵌套", "异常处理", "抢占", "异步", "阻塞" 等各种在工程方法上的典型问题, 对于现阶段的 NLU 而言也难于解决.
EOF
            ,

            'hardApi' => <<<EOF
此外, 对于业务方而言, 把多轮对话逻辑交给云端的 NLU, 双方有非常大量的数据交互是在同步成百上千种状态, 而各种分支逻辑的开发只能靠费解的配置去实现. 反而远没有工程手段方便直观了.
EOF
            ,

            'engineerBetter' => <<<EOF
最后, 目前的自然语言技术并未完全成熟, 而且高度依赖词典和标注语料的质量, 有一定的人力成本, 也难免出现各种语义匹配上的问题 ( 举一个例子, 用"谓词"表示肯定, 比如 "要不要加冰", 回答 "加", 表示肯定 ).

而且对于现在的语义理解技术, 即便是非常简洁的命令, 也需要人工生产大量语料来容错. 有时用 AI 的人力成本反而高于工程师. 

这些情况下, 工程手段能提供各种粗暴快捷的解决方案作为补充, 有时反而能更快地解决问题.
EOF
            ,

            'coding' => '其实 "多轮对话" 和其它所有多轮人机交互形式本质是一样的. 别的交互能用图灵完备的编程语言来描述, "多轮对话" 也完全可以. 机器学习彻底取代工程手段的那天, 也等于彻底取代了掌握编程语言的工程师, 这个情况还会稍晚一点才发生.',

        ],


    ],

];

return [

    'demo' => [
        'simpleWiki' => $messages,
    ]

];
