<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Env;
use think\facade\Cache;
use think\Model;
use think\db;
// +----------------------------------------------------------------------
// | Swoole设置 php think swoole:server 命令行下有效
// +----------------------------------------------------------------------
return [
    // 扩展自身配置
    'host'         => '0.0.0.0', // 监听地址
    'port'         => 9508, // 监听端口
    'type'         => 'socket', // 服务类型 支持 socket http server
    'mode'         => '', // 运行模式 默认为SWOOLE_PROCESS
    'sock_type'    => '', // sock type 默认为SWOOLE_SOCK_TCP
    'swoole_class' => '', // 自定义服务类名称

    // 可以支持swoole的所有配置参数
    'daemonize'    => false,
    'pid_file'     => Env::get('runtime_path') . 'swoole_server.pid',
    'log_file'     => Env::get('runtime_path') . 'swoole_server.log',

    // 事件回调定义
    'onOpen'       => function ($server, $request) {
        $cache = Cache::init();
        $redis = $cache->handler();
        $redis->sAdd('fd',$request->fd);
        $fds = $redis->smembers('fd');
        $res = db('chat_room')->select();
        foreach ($fds as $fd){
            $server->push($fd,'<p style="margin-left:300px;height:10px;line-height:10px;">👏欢迎上线</p>');
        }
        if($res){
            foreach($res as $v){
                $server->push($request->fd,$v['userName'].': '.$v['text']);
            }
        }
        echo "server: handshake success with fd{$request->fd}\n";
    },

    'onMessage' => function ($server, $frame) {
        global $redis;
        $cache = Cache::init();
        $redis = $cache->handler();
        $fds = $redis->sMembers('fd');
        $data = [
            'userId'=>4,
            'text'  => $frame->data,
            'addTime' => date('Y-m-d H:i:s')
        ];
        $text = explode('--',$frame->data);
        $sqlData= [
            'userId'      => $text[2],
            'userName'    => $text[1],
            'text'        => $text[0],
            'create_time' => date('Y-m-d H:i:s')
        ];
        db('chat_room')->insert($sqlData);
        foreach ($fds as $fd){
            $server->push($fd,$text[1].':'. $text[0]);
            // $ws->push($fd,file_get_contents('http://imgsrc.baidu.com/imgad/pic/item/267f9e2f07082838b5168c32b299a9014c08f1f9.jpg'),WEBSOCKET_OPCODE_BINARY);
        }
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
//        $server->push($frame->fd, "this is server");
    },

    'onRequest' => function ($request, $response) {
        $response->end("<h1>Hello Swoole. #" . rand(1000, 9999) . "</h1>");
    },

    'onClose' => function ($ser, $fd) {
        global $redis;
        $cache = Cache::init();
        $redis = $cache->handler();
        $redis->sRem('fd',$fd);
        echo "client {$fd} closed\n";
    },
];
