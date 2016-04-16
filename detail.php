<?php
require_once(dirname(__FILE__) . "/common/Cookies.class.php");
require_once(dirname(__FILE__) . "/common/Request.class.php");
require_once(dirname(__FILE__) . "/common/JindowinConfig.class.php");
require_once(dirname(__FILE__) . "/common/CheckUserLogin.class.php");

$myCookie = new Cookies();
$uid = $myCookie->get("uid");
$uname = $myCookie->get("uname");
$id = isset($_GET['id']) ? $_GET['id'] : "";
$date = isset($_GET['date']) ? $_GET['date'] : "";
if (empty($id) && empty($date)) {
    return;
}
$url = JindowinConfig::$requireUrl . "news/1/news_detail.fcgi";
$result = RequestUtil::get($url,
    array(
        "user_id" => $_SESSION['user_id'],      //用户唯一标识
        "token" => $_SESSION['token'],          //用户登录标识
        "news_id" => $id,
        "date" => urlencode($date)
    ));
$jsonresult = json_decode($result, true);

if ($jsonresult['status'] == "0") {
    return;
}

//if (empty($jsonresult['result'][0]['detail'])) {
//    echo("<div class=\"spinner\"><div class=\"double-bounce1\"></div><div class=\"double-bounce2\"></div></div><div class=\"tips\">筋斗云正在帮你跳转到原文</div><style>.spinner{width:60px;height:60px;position:relative;margin:100px auto}.tips{margin:0 auto;width:200px;height:50px;font-family:Microsoft YaHei,arial,sans-serif,\"微软雅黑\";margin-top:-90px;color:#a1a1a1}.double-bounce1,.double-bounce2{width:100%;height:100%;border-radius:50%;background-color:#005cb7;opacity:0.6;position:absolute;top:0;left:0;-webkit-animation:bounce 2.0s infinite ease-in-out;animation:bounce 2.0s infinite ease-in-out}.double-bounce2{-webkit-animation-delay:-1.0s;animation-delay:-1.0s}@-webkit-keyframes bounce{0%,100%{-webkit-transform:scale(0.0)}50%{-webkit-transform:scale(1.0)}}@keyframes bounce{0%,100%{transform:scale(0.0);-webkit-transform:scale(0.0)}50%{transform:scale(1.0);-webkit-transform:scale(1.0)}}</style>");
//    header("Location: " . $jsonresult['result'][0]['url']);
//    exit;
//}
//echo  $result;

$releaseUrl = JindowinConfig::$requireUrl . "news/1/related_news.fcgi";//获取相关联的新闻
$releaseResult = RequestUtil::get($releaseUrl,
    array(
        "user_id" => $_SESSION['user_id'],
        "token" => $_SESSION['token'],
        "news_id" => $id,
        "news_date" => urlencode($date)
    ));
$releaseJsonResult = json_decode($releaseResult);

$referenceUrl = JindowinConfig::$requireUrl . "news/1/news_reference.fcgi";//获取关联股票/新闻
$referenceResult = RequestUtil::get($referenceUrl,
    array(
        "user_id" => $_SESSION['user_id'],
        "token" => $_SESSION['token'],
        "news_id" => $id,
        "news_date" => urlencode($date)
    ));
$referenceJsonResult = json_decode($referenceResult);
//echo $referenceResult;
//echo $_SESSION['user_id'] . "|" . $_SESSION['token'];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>快讯</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css?v=1.0" rel="stylesheet">
    <link href="css/bootstrap-material-design.min.css?v=1.0" rel="stylesheet">
    <link href="css/ripples.min.css?v=1.0" rel="stylesheet">
    <link href="css/jquery.dropdown.min.css?v=1.0" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css?v=1.0">
    <link rel="stylesheet" href="css/style.min.css?v=1.0">
    <link rel="stylesheet" href="css/animate.min.css?v=1.0">
    <link rel="stylesheet" href="css/iconfont.min.css?v=1.0">
    <link rel="stylesheet" href="css/jquery.typeahead.min.css?v=1.0">
</head>
<body>
<!--头部菜单&搜索框-->
<div class="navbar navbar-inverse hidden-print top-detail-pic">
    <div class="container" style="height: 90px;">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target=".navbar-responsive-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                <img src="imgs/logo.png" style="width: 136px; height: 30px;">
            </a>
        </div>
        <div class="navbar-collapse collapse navbar-responsive-collapse" id="header-right-icon">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <?php
                    if (empty($uname)) {
                        echo " <a id=\"top-user-name\" href=\"javascript:void(0)\"><i class=\"icon iconfont\">&#xe60b;</i>&nbsp;<span>登录</span></a>";
                    } else {
                        echo " <a id=\"top-user-exit\" href=\"javascript:void(0)\"><i class=\"icon iconfont\">&#xe60b;</i>&nbsp;<span>" . $uname . "</span></a>";
                    }
                    ?>
                </li>
                <li><a href="javascript:void(0)" id="wechat"> <i class="icon iconfont">&#xe679;</i>&nbsp;微信关注</a></li>
            </ul>
        </div>
    </div>
</div>

<!--主体内容-->
<div class="container">
    <!--我的快讯模块-->
    <div class="bs-docs-section">
        <div class="jumbotron col-md-8">

            <div class="row">
                <div class="col-md-12">
                    <h4 class="detail-title">
                        <?php echo $jsonresult['result'][0]['title'] ?>
                    </h4>
                    <h6 class="detail-from">
                        来源： <?php echo $jsonresult['result'][0]['from'] ?>
                        &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $jsonresult['result'][0]['time'] ?>
                    </h6>
                    <div class="detail-content">
                        <?php
                            $detail=$jsonresult['result'][0]['detail'];
                            if(!empty($detail)){
                                $detailArray=explode('#$%',$detail);
                                foreach ($detailArray as $item){
                                    echo "<p>".preg_replace('/(\s|\&nbsp\;|　|\xc2\xa0)/', "", strip_tags($item))."</p>";
                                }
                            }
                    ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-9">
                    <?php
                    if (!empty($jsonresult['result'][0]['stock'])) {
                        echo "<div class=\"news-tip-gp\">股票：<span>" . $jsonresult['result'][0]['stock'] . "</span></div>";
                        ?>
                        <?php
                    } ?>
                    <?php
                    if (!empty($jsonresult['result'][0]['indus'])) {
                        echo "<div class=\"news-tip-hy\">行业：<span>" . $jsonresult['result'][0]['indus'] . "</span></div>";
                        ?>
                        <?php
                    } ?>
                    <?php
                    if (!empty($jsonresult['result'][0]['sect'])) {
                        echo "<div class=\"news-tip-gn\">概念：<span>" . $jsonresult['result'][0]['sect'] . "</span></div>";
                        ?>
                        <?php
                    } ?>

                </div>
                <div class="col-md-3 text-right news-bottom-btn">
                    <i class="icon iconfont news-like">&#xe688;</i>
                    <i class="icon iconfont news-unlike">&#xf013b;</i>
                    <i class="icon iconfont news-share">&#xe610;</i>
                </div>
            </div>
            <div class="row up-and-down">
                <div class="col-md-2 col-md-offset-4 text-center look-up">
                    <?php
                    if ($jsonresult['result'][0]['comment_recored'] == 1) {
                        echo "<i class=\"icon iconfont look-up-hover\">&#xe61e;</i>";
                    } else {
                        echo "<i class=\"icon iconfont\" data-set-id='" . $jsonresult['result'][0]['id'] . "' data-set-date='" . $jsonresult['result'][0]['time'] . "'>&#xe61e;</i>";
                    }
                    ?>
                    <p><?php echo $jsonresult['result'][0]['up'] ?>人看涨</p>
                </div>
                <div class="col-md-2 text-center look-down">
                    <?php
                    if ($jsonresult['result'][0]['comment_recored'] == 2) {
                        echo "<i class=\"icon iconfont look-down-hover\">&#xe61d;</i>";
                    } else {
                        echo "<i class=\"icon iconfont\" data-set-id='" . $jsonresult['result'][0]['id'] . "' data-set-date='" . $jsonresult['result'][0]['time'] . "'>&#xe61d;</i>";
                    }
                    ?>
                    <p><?php echo $jsonresult['result'][0]['down'] ?>人看跌</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if ($releaseJsonResult->status == "1") {
                        echo "<h4 class=\"detail-link\">相关阅读：</h4>";
                        foreach ($releaseJsonResult->result as $item) {
                            echo "<div class=\"detail-link-title\"><a href='detail.php?id=" . $item->id . "&date=" . $item->time . "' target='_blank'>" . $item->title . "</a></div>";
                        }
                        ?>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="jumbotron col-md-4 detail-right">
            <?php
            if ($referenceJsonResult->status == "1") {
                if (count($referenceJsonResult->stock) > 0) {
                    echo "<div class=\"panel panel-default\">";
                    echo "<div class=\"panel-heading\"><img src='imgs/rec.png'>关联股票</div>";
                    echo "<div class=\"panel-body\">";
                    foreach ($referenceJsonResult->stock as $item) {
                        echo "<div class=\"container\">";
                        echo "<span class=\"col-md-9\">" . $item->name . "[" . $item->code . "]</span>";
                        echo "<span class=\"col-md-3\"><i class='fa " . ($item->subscribe == "1" ? "fa-check" : "fa-plus") . "' data-user-val=\"" . $item->code . "\" data-user-type=\"stock\"></i></span>";
                        echo "</div>";
                    }
                    echo "</div>";
                    echo "</div>";
                }
                if (count($referenceJsonResult->industry) > 0) {
                    echo "<div class=\"panel panel-default\">";
                    echo "<div class=\"panel-heading\"><img src='imgs/rec.png'>关联行业</div>";
                    echo "<div class=\"panel-body\">";

                    foreach ($referenceJsonResult->industry as $item) {
                        echo "<div class=\"container\">";
                        echo "<span class=\"col-md-9\">" . $item->name . "</span>";
                        echo "<span class=\"col-md-3\"><i class='fa " . ($item->subscribe == "1" ? "fa-check" : "fa-plus") . "' data-user-val=\"" . $item->name . "\" data-user-type=\"industry\"></i></span>";
                        echo "</div>";
                    }

                    echo "</div>";
                    echo "</div>";
                }
                if (count($referenceJsonResult->section) > 0) {
                    echo "<div class=\"panel panel-default\">";
                    echo "<div class=\"panel-heading\"><img src='imgs/rec.png'>关联概念</div>";
                    echo "<div class=\"panel-body\">";

                    foreach ($referenceJsonResult->section as $item) {
                        echo "<div class=\"container\">";
                        echo "<span class=\"col-md-9\">" . $item->name . "</span>";
                        echo "<span class=\"col-md-3\"><i class='fa " . ($item->subscribe == "1" ? "fa-check" : "fa-plus") . "' data-user-val=\"" . $item->name . "\" data-user-type=\"section\"></i></span>";
                        echo "</div>";
                    }

                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
</div>
<!--微信二维码弹出框-->
<div id="wechat-dialog" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">微信关注</h4>
            </div>
            <div class="modal-body text-center">
                <img src="imgs/qrcode.png">
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script src="js/jquery-1.10.2.min.js?v=1.0"></script>
<script src="js/bootstrap.min.js?v=1.0"></script>
<script src="js/ripples.min.js?v=1.0"></script>
<script src="js/material.min.js?v=1.0"></script>
<script src="js/jquery.typeahead.min.js?v=1.0"></script>
<script src="js/jindowin-index.min.js?v=1.0"></script>
<script src="js/jquery.tips.min.js?v=1.0"></script>
<script src="js/jindowin-detail.min.js?v=1.0"></script>