<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>快讯</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <style>
        html,body{padding: 0;margin: 0;background-color: #f6f7f9;}
        a{color: #1347b3;cursor: pointer;}
        .container,.email-head,.email-info,.welcome{font-family: "microsoft yahei", sans-serif;color: #474747;background-color: #FFFFFF;}
        .container{
            width: 680px;
            height: 582px;
            margin: 0 auto;
            -webkit-box-shadow: 0 8px 8px 0 rgba(0,0,0,0.2),0 8px 8px 0 rgba(0,0,0,0.19);
            box-shadow: 0 8px 8px 0 rgba(0,0,0,0.2),0 8px 8px 0 rgba(0,0,0,0.19);
            border-radius: 1px;
            border: 0;
        }
        .email-head{background: url("http://alpha.miglab.com/imgs/header2.gif");  height: 88px;}
        .content{ width: 596px; height: 437px;padding: 0 42px 0 42px;position: relative;}
        .top-hr{height: 1px;border: none;border-top: 1px solid #eeeeee;margin: 42px 42px 15px 42px;}
        .welcome{font-size: 18px;}
        .email-info{margin-top: 45px;font-size: 14px;text-align:justify; text-justify:inter-ideograph;}
        .active{margin-top: 45px;font-size: 14px;}
        .info{margin-top: 45px;}
        .info p{margin: 5px 0;font-size: 14px;}
        .left-pic{position: absolute;left: 2px;bottom:0;}
        .right-pic{position: absolute;right: 2px;bottom:0;}
    </style>
</head>
<body>
<!--主体内容-->
<div class="container" id="container">
    <div class="email-head"></div>
    <hr class="top-hr">
    <div class="content">
        <span class="welcome">欢迎!</span>
        <div class="email-info">
            现在您可以实时接收订阅的快讯。您可以通过邮件轻松阅读最新资讯，快速查看您所关注的信息！ 我们已经在筋斗云网页为您创建一个账户。您的用户名是：<a>chenliuwen@kunyan-inc.com</a>
        </div>
        <div class="active">
            <a href="#">点击激活您的筋斗云账户并设置密码</a>
        </div>
        <div class="info">
            <p>此致</p>
            <p>筋斗云团队敬上</p>
            <p><a href="http://alpha.miglab.com">http://alpha.miglab.com</a></p>
        </div>
        <div class="left-pic">
            <img src="http://alpha.miglab.com/imgs/cloud1.png" style="width: 152px;height: 83px;">
        </div>
        <div class="right-pic">
            <img src="http://alpha.miglab.com/imgs/cloud2.png" style="width: 125px;height: 93px;">
        </div>
    </div>
</div>
</body>
<script>
    var con=document.getElementById("container");
    var height=window.innerHeight/2-291;
    con.style.cssText = "margin-top:"+height+"px;";
</script>
</html>