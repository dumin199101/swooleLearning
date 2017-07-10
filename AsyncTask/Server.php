<?php
//Swoole解决异步任务投递：
class Server
{
    private $serv;
    private $logFile;

    public function __construct()
    {
        $this->serv = new swoole_server("0.0.0.0", 9501);
        $this->serv->set([
            'worker_num'=>4, // 一般设置为服务器CPU数的1-4倍
            'task_worker_num'=>2,// task进程的数量（一般任务都是同步阻塞的，可以设置为单进程单线程）
            'daemonize'=>false,// 以守护进程执行
        ]);

        $this->logFile = dirname(__DIR__) . '/asyncTaskLog.txt';
        $this->serv->on('start',[$this,'onStart']);
        $this->serv->on('connect',[$this,'onConnect']);
        $this->serv->on('receive',[$this,'onReceive']);
        $this->serv->on('close',[$this,'onClose']);
        $this->serv->on('task',[$this,'onTask']);
        $this->serv->on('finish',[$this,'onFinish']);
        $this->serv->start();
    }

    public function onStart($serv)
    {
        echo "Server Start\n";
    }

    /**
     * @param $serv
     * @param $fd  服务器端对客户端的文件标识
     * @param $from_id 来自reactor线程的id号
     */
    public function onConnect($serv,$fd,$from_id)
    {
        echo "Server Connect Success\n";
    }

    /**
     * @param swoole_server $serv
     * @param $fd
     * @param $from_id
     * @param $data
     * 接收客户端传递过来的消息，投放任务
     */
    public function onReceive(swoole_server $serv,$fd,$from_id,$data)
    {
        $str = PHP_EOL .  "===========Task is Received============" . PHP_EOL;
        $str .= "Get Message From Client {$fd}:[{$data}]";
//        投递一个异步任务到task_worker池中。此函数会立即返回。worker进程可以继续处理新的请求
//        此功能用于将慢速的任务异步地去执行，比如一个聊天室服务器，可以用它来进行发送广播。当任务完成时，在task进程中调用$serv->finish("finish")告诉worker进程此任务已完成。当然swoole_server->finish是可选的。
        $task_id = $serv->task($data);
        echo "Dispath AsyncTask: id={$task_id}\n";
        error_log($str, 3, $this->logFile);
    }

    public function onClose($serv,$fd,$from_id)
    {
        echo "Client Connection Closed\n";
    }


    //处理异步任务
    /**
     * 在task_worker进程内被调用。worker进程可以使用swoole_server_task函数向task_worker进程投递新的任务。当前的Task进程在调用onTask回调函数时会将进程状态切换为忙碌，
     * 这时将不再接收新的Task，当onTask函数返回时会将进程状态切换为空闲然后继续接收新的Task。
     * $task_id是任务ID，由swoole扩展内自动生成，用于区分不同的任务。$task_id和$src_worker_id组合起来才是全局唯一的，不同的worker进程投递的任务ID可能会有相同
     * $src_worker_id来自于哪个worker进程
     * $data 是任务的内容
     */
    public function onTask($serv,$task_id,$from_id,$data)
    {
        $array  = json_decode( $data , true );
        $str    = PHP_EOL . "=========== onTask ============".PHP_EOL;
        $str   .= var_export($array, 1).PHP_EOL;  //var_export()打印变量字符串表示形式，当第二个参数为true时，变为返回值
        error_log($str, 3 , $this->logFile);
        //返回任务执行结果：
        //此函数用于在task进程中通知worker进程，投递的任务已完成。此函数可以传递结果数据给worker进程,使用swoole_server::finish函数必须为Server设置onFinish回调函数。此函数只可用于task进程的onTask回调中swoole_server::finish是可选的。如果worker进程不关心任务执行的结果，不需要调用此函数,在onTask回调函数中return字符串，等同于调用finish
        //发送邮件的任务：
        include_once "SendMail.php";
        $sendMailService = new SendMail();
        if(!$sendMailService->sendMail($array['email'], $array['subject'], $array['content'])){
            echo "邮件发送失败：" . $array['email'] . PHP_EOL;
        }else{
            echo "邮件发送成功了" . PHP_EOL;
        }
        //为了模拟网络延时，延时20s
        sleep(20);
        echo "Deal AsyncTask[id=$task_id]".PHP_EOL;
        $serv->finish("{$task_id} deal OK");
    }

    //处理异步任务的结果
    /**
     * 当worker进程投递的任务在task_worker中完成时，task进程会通过swoole_server->finish()方法将任务处理的结果发送给worker进程
     * $task_id是任务的ID
     * $data是任务处理的结果内容（也就是onTask()函数，中return的值）
     */
    public function onFinish($serv,$task_id,$data)
    {
        $str  = "=========== onFinish ============".PHP_EOL;
        $str .= "Task {$task_id} finish !".PHP_EOL;
        $str .= var_export($data, 1).PHP_EOL;
        error_log($str, 3, $this->logFile);
        echo "AsyncTask[$task_id] Finish".PHP_EOL;
    }
}



$server = new Server();
