<?php
if(!empty($_GET["url"])){
$url = $_GET["url"];
$preg = "/http[s]?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is";
if(!preg_match($preg,$url)){
echo '你输入的链接地址不合法，如正确,请带上http或https';
exit;
}
}else{
    echo "木有参数,请求时请带上?url=你的链接";
    exit;
}
if(!empty($_GET["photo"])){
$photo = $_GET["photo"];
}else{
$photo = "./bj.jpg";
}
?>
<!DOCTYPE HTML>
<head>
<meta charset="utf-8">
<title>H5播放器 - 雨晨风蓝</title>
<meta name="Robots" contect="all">
<meta name="Author" contect="徐惟康">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<link rel="stylesheet" href="./video.css">
<link rel="stylesheet" href="https://cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.css">
<script type="text/javascript" src="https://upcdn.b0.upaiyun.com/libs/jquery/jquery-2.0.3.min.js"></script>
</head>
<body>
<div class="player" id="videobox">
        <video id="myVideo"  preload="auto" poster="<?=$photo ?>">
            <source type="video/mp4">
        </video>
        <div class="controls" style="display: none;">
            <a href="javascript:;" class="switch fa fa-play"></a>
            <a href="javascript:;" class="expand fa fa-snowflake-o"></a>
            <div class="progress">
                <div class="loaded"></div>
                <div class="line"></div>
                <div class="bar"></div>
            </div>
            
            <div class="timer">
                <span class="current">00:00:00</span> /
                <span class="total">00:00:00</span>
            </div>
            <div class="xw-xunhuan">
                <a href="javascript:;" class="xwxunhuan">循环播放</a>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
    var url = '<?=$url ?>';
    ####以下可丢到加密平台加密####
    var nowurl = window.location.host;#获取host
    var player = document.querySelector(".player");
    var video = document.querySelector("video");
    var isPlay = document.querySelector(".switch");
    var expand = document.querySelector(".expand");
    var controls = document.querySelector(".controls");
    var expandfull = document.querySelector(".expandfull");
    var xwxunhuan = document.querySelector(".xwxunhuan");
    var progress = document.querySelector(".progress");
    var loaded = document.querySelector(".progress > .loaded");
    var currPlayTime = document.querySelector(".timer > .current");
    var totalTime = document.querySelector(".timer > .total");
    #控制域名
    #if(nowurl!="api.xuvce.com" && nowurl!="api.yuwind.com" && nowurl!="api.nuoyis.com"){
    #alert("你的行为很危险，请到https://api.xuvce.com/xwapi/videoplayer/?url="+url+"访问本页");
    #player.style.display = 'none';
    #}else{
    var xwrequest = xwvideoCORS('get', url);
    function xwvideoCORS(method, url){
     var xwvideoapi = new XMLHttpRequest();
     if('withCredentials' in xwvideoapi){
         xwvideoapi.open(method, url, true);
     }else if(typeof XDomainRequest != 'undefined'){
         var xwvideoapi = new XDomainRequest();
         xwvideoapi.open(method, url);
     }else{
         xwvideoapi = null;
     }
     xwvideoapi.responseType = "blob";
     return xwvideoapi;
    }
     
    window.URL = window.URL || window.webkitURL;
    xwrequest.onload = function() {
    xwrequest.crossOrigin = '*';
    if (this.status == 200) {
    var blob = this.response;
    video.onload = function(e) {
    window.URL.revokeObjectURL(video.src);
    };
    video.src = window.URL.createObjectURL(blob);
    }
    }
    
    xwrequest.send();


 
    video.oncanplay = function(){
        //显示视频
        this.style.display = "block";
        controls.style.display = "block";
        //显示视频总时长
        totalTime.innerHTML = getFormatTime(this.duration);
    };
    
    //播放按钮控制
    isPlay.onclick = function(){
        if(video.paused) {
            video.play();
        } else {
            video.pause();
        }
        this.classList.toggle("fa-pause");
    };


    xwxunhuan.onclick = function(){
        if(video.loop == true){
            video.loop = false;
            xwxunhuan.innerHTML="循环播放";
        }else{
	         video.loop = true;
	         xwxunhuan.innerHTML="取消循环";
        }
        
    };
    
    //播放进度
    video.ontimeupdate = function(){
        var currTime = this.currentTime,    //当前播放时间
            duration = this.duration;       // 视频总时长
        //百分比
        var pre = currTime / duration * 100 + "%";
        //显示进度条
        loaded.style.width = pre;

        //显示当前播放进度时间
        currPlayTime.innerHTML = getFormatTime(currTime);
    };

    //跳跃播放
    progress.onclick = function(e){
        var event = e || window.event;
        video.currentTime = (event.offsetX / this.offsetWidth) * video.duration;
    };

    //播放完毕还原设置
    video.onended = function(){
        var that = this;
        //切换播放按钮状态
        isPlay.classList.remove("fa-pause");
        isPlay.classList.add("fa-play");
        //进度条为0
        loaded.style.width = 0;
        //还原当前播放时间
        currPlayTime.innerHTML = getFormatTime();
        //视频恢复到播放开始状态
        that.currentTime = 0;
    };
    
    expand.onclick = function(){
        var xwplayer = player.srcElement||player.target;
        FullScreen(xwplayer);
    };
    
    function FullScreen(xwplayer){
         var isFullscreen=document.fullScreen||document.mozFullScreen||document.webkitIsFullScreen;
         if(!isFullscreen){
         if (player.requestFullscreen) {
               player.requestFullscreen();
        } else if (player.mozRequestFullScreen) {
               player.mozRequestFullScreen();
        } else if (player.webkitRequestFullScreen) {
               player.webkitRequestFullScreen();
        }
         }else{
           if (document.exitFullscreen) {
                document.exitFullscreen();
         } else if (document.mozExitFullScreen) {
                document.mozExitFullScreen();
         } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
           }
         }
    };

    function getFormatTime(time) {
        var time = time || 0;

        var h = parseInt(time/3600),
            m = parseInt(time%3600/60),
            s = parseInt(time%60);
        h = h < 10 ? "0"+h : h;
        m = m < 10 ? "0"+m : m;
        s = s < 10 ? "0"+s : s;

        return h+":"+m+":"+s;
    };
#}
</script>
</body>
</html>
