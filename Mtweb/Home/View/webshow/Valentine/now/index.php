<?php
include "./model/DBTool.php";

function echoList( mysqli $db ) {

    $sql = "SELECT * FROM v_anchor_gift_ranking";
    $result = $db->query( $sql ) or die( "db error" );

    $index = 0;
    if( $result ) {
        while( list( $uid, $gift_count, $last_time, $nick_name, $image_url ) = $result->fetch_row() ) {
            $index++;
            echoRowData( $index, $uid, $gift_count, $last_time, $nick_name, $image_url );
        }
        $result->close();
        $db->next_result();
    }
}

function echoRowData( $index, $uid, $gift_count, $last_time, $nick_name, $image_url ) {

    echo '<div class="table-content"><div class="name-content">';
    echo "<img src=\"$image_url\" />";
    echo '<div class="name-text">';
    echo "<span>$nick_name</span>";
    echo "<span>ID:".$uid."</span>";
    echo "</div>";
    echo "</div>";
    echoRankingHTML( $index );
    echo "<div class=\"number\">";
    echo "<span>X$gift_count</span>";
    echo "</div>";
    echoCashCountHTML( $index );
    echo '</div>';
}

function echoRankingHTML( $index ) {
    if( $index == 1 ) {
        $rankingData = "<img src=\"/Public/Home/images/valentine/01.png\"  alt=\"\" />";
    } elseif( $index == 2 ) {
        $rankingData = "<img src=\"/Public/Home/images/valentine/02.png\"  alt=\"\" />";
    } elseif( $index == 3 ) {
        $rankingData = "<img src=\"/Public/Home/images/valentine/03.png\"  alt=\"\" />";
    } else {
        $rankingData = "<span>$index</span>";
    }

    echo "<div class=\"ranking\">";
    echo "$rankingData";
    echo "</div>";
}

function echoCashCountHTML( $index ) {

    echo "<div class=\"reward\">";
    if( $index == 1 ) {
        $count = 600;
        echo "<span>" . $count . "现金</span>";
        echo "<span>热玩置顶6H</span>";
    } elseif( $index == 2 ) {
        $count = 400;
        echo "<span>" . $count . "现金</span>";
        echo "<span>热玩置顶4H</span>";
    } elseif( $index == 3 ) {
        $count = 200;
        echo "<span>" . $count . "现金</span>";
        echo "<span>热玩置顶2H</span>";
    } elseif( $index == 4 ) {
        $count = 100;
        echo "<span class=\"cash_number\">" . $count . "现金</span>";
    } elseif( $index == 5 ) {
        $count = 50;
        echo "<span class=\"cash_number\">" . $count . "现金</span>";
    } else {
        $count = 25;
        echo "<span class=\"cash_number\">" . $count . "现金</span>";
    }

    echo "</div>";
}

?>

    <!doctype html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>七夕活动|17玩直播</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta content="telephone=no" name="format-detection">

        <link rel="stylesheet" href="/Public/Home/css/reset.css">

        <style>
            html, body {
                width: 100%;
                height: 100%;
                margin: 0 auto;
            }

            .wrap {
                float: left;
                width: 100%;
                height: 56rem;
                padding-top: 12rem;
                background: url('/Public/Home/images/valentine/bg.jpg') 0 0 no-repeat;
                background-size: cover;
            }

            .header-text {
                float: left;
                width: 100%;
                text-align: center;
                padding-bottom: 0px;
            }

            .header-text span {
                float: left;
                width: 100%;
                font-size: 1rem;
                color: #7726b6;
            }

            .active {
                float: left;
                width: 100%;
                background: url('/Public/Home/images/valentine/hd.png') center center no-repeat;
                background-size: 55%;
            }

            .active span {
                display: block;
                width: 100%;
                text-align: center;
                height: 4rem;
                line-height: 4.4rem;
                font-size: 1.5rem;
                color: pink;
            }

            .center-text {
                float: left;
                width: 80%;
                text-align: center;
                padding-bottom: 30px;
                padding: 0 10%;
                margin-bottom: 2rem;
            }

            .center-text p {
                text-indent: 6px;
                font-size: 1rem;
                color: #7726b6;
            }

            .table {
                position: relative;
                float: left;
                margin: 0 10%;
                width: 80%;
                border: 6px solid #ebd1ff;
                font-size: 24px;
            }

            .table-header {
                float: left;
                width: 100%;
                padding: 1.5rem 0 0.5rem;
                background: #fffbe3;
                font-size: 18px;
                text-align: center;
                color: #8e8d84;
            }

            .table-header span {
                float: left;
                width: 20%;
            }

            .table-content {
                float: left;
                width: 100%;
                height: 3rem;
                color: #8e8d84;
                font-size: 12px;
            }

            .table-header .nickname {
                float: left;
                width: 40%;
            }

            .table-content:nth-child(odd) {
                background: #ffeec6;
            }

            .table-content:nth-child(even) {
                background: #fffbe3;
            }

            .table-content .name-content {
                float: left;
                width: 40%;
                padding-top: 14px;
                padding-left: 2%;
            }

            .name-content img {
                float: left;
                width: 20%;
                border-radius: 50%;
            }

            .name-content .name-text {
                float: left;
                width: 68%;
                padding-left: 10px;
            }

            .name-content .name-text span {
                float: left;
                width: 100%;
            }

            .ranking {
                float: left;
                width: 15%;
                height: 3rem;
                line-height: 3rem;
            }

            .ranking span {
                padding-left: 16%;
            }

            .ranking img {
                float: left;
                width: 60%;
                padding: 0.5rem 0%;
            }

            .number {
                float: left;
                width: 20%;
                height: 3rem;
                background: url('/Public/Home/images/valentine/lw.png') left center no-repeat;
                background-size: 39%;
            }

            .number span {
                display: block;
                padding-left: 44%;
                line-height: 3rem;
            }

            .reward {
                float: left;
                width: 23%;
                height: 3rem;
            }

            .reward span {
                display: block;
                width: 100%;
                text-align: center;
            }

            .footer {
                float: left;
                width: 80%;
                padding: 0rem 10%;
                font-size: 1rem;
                padding-top: 1rem;
                color: #7726b6;
            }

            .header-img {
                display: block;
                position: absolute;
                left: 30%;
                top: -1rem;
                width: 40%;
            }

            .cash_number {
                line-height: 3rem;
            }
        </style>
    </head>
<body>
    <div class="wrap">
        <div class="header-text">
            <span>浪漫七夕来临，赶快行动起来</span>
            <span>用“花季单车”来表达你的爱，继续相爱吧</span>
        </div>
        <div class="active">
            <span>活动说明</span>
        </div>
        <div class="center-text">
            <p>活动时间内，签约主播根据收礼物“花季单车”的数量进行排名，并获得相应的奖励。
            </p>
        </div>
        <div class="table">
            <img class="header-img" src="/Public/Home/images/valentine/zbphb-1.png" alt=""/>
            <div class="table-header">
                <span class="nickname">昵称</span>
                <span>名次</span>
                <span>数量</span>
                <span>奖励</span>
            </div>
            <!-- 表格开始 -->
            <?php echoList( $db ) ?>
            <!-- 表格结束 -->
        </div>
        <div class="footer">
            <p>热玩置顶会由专人联系主播沟通发放，原则上热玩每人每日不超过1小时。</p>
            <p>活动时间：8月27日0:00-8月31日23:59</p>
            <p>所有奖励将在活动结束后的5个工作日内发放。</p>
        </div>

    </div>
    <!--    <script src="__JS__/jquery.js" charset="utf-8" type="text/javascript">-->
</script>
</body>
</html>

<?php
$db->close();
?>