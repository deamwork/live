<?php
//Management password
$livepass = 'thisisatest';
//site info
$site_name = 'Deamwork';
$site_url = 'https://live.deamwork.com/';
$home_url = 'https://www.deamwork.com/';
//stream server domain or ip
//$stream_server = '0.0.0.0';
//$stream_server = 'live.example.com';
$stream_server = '0.0.0.0';
//live app name
$live_app = 'live';

/*
 * Following items PLEASE DO NOT MODIFY
 * Vars
 */
$managepass = $_POST['managepass'];
$live_switch = $_POST['live_switch'];
$lockfile = 'status.lock';
$islive = getLiveStatus();
$live_hash = $_POST['live_hash'];

/*
 * Function liveSwitch
 * @string $switch
 * return bool
 */
function liveSwitch($switch,$livehash=0){
    if ($switch == 'on'){
        updateLiveStatus('on',$livehash);
        return true;
    } else if($switch == 'off'){
        updateLiveStatus('off');
        return true;
    } else{
        return false;
    }
}

/*
 * Function getLiveStatus
 * return bool
 */
function getLiveStatus(){
    global $lockfile;
    if (file_exists($lockfile)==true){
        return true;
    }else{
        return false;
    }
}

/*
 * Function getLiveHash
 * return mixed
 */
function getLiveHash(){
    global $lockfile;
    $hash=file_get_contents($lockfile);
    if($hash){
        return $hash;
    } else {
        return null;
    }
}

/*
 * Function updateLiveStatus
 * @bool $switch
 * @string/null $livehash
 * return null
 */
function updateLiveStatus($switch,$livehash=0){
    global $lockfile;
    if ($switch == 'on'){
        $lock=fopen($lockfile,"w");
        file_put_contents($lockfile,$livehash);
        fclose($lock);
    } elseif($switch == 'off'){
        unlink($lockfile);
    }
}

/*
 * Management panel logic
 * 
 */
if($managepass == $livepass){
    if ($live_switch == 'on') {
        $result=liveSwitch('on',$live_hash);
    } else if ($live_switch == 'off'){
        $result=liveSwitch('off');
    } else {
        echo 'Live status:'.getLiveStatus();
    }
}

/*
 * Output management panel
 * 
 */
if(isset($_GET['manage']) && $_GET['manage']=='yes'){
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Live Controller</title>
        <script src="//cdn.bootcss.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <link href="//cdn.bootcss.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $home_url; ?>"><?php echo $site_name; ?></a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="<?php echo $site_url; ?>">Live</a></li>
                    <li class="active"><a href="<?php echo $site_url; ?>">Live Control</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container theme-showcase" style="margin-top: 30px">
        <div class="page-header">
            <h2><?php echo $site_name; ?> LIVE</h2>
        </div>
    <div>
            <form method="post" action="index.php">
                <label for="managepass">Management password:</label>
                <input type="password" name="managepass" id="managepass" /><br />
                <label>Live switch:</label>
                <input type="radio" name="live_switch" id="live_on" value="on" />On
                <input type="radio" name="live_switch" id="live_off" value="off" checked="checked" />Off<br />
                <label for="livehash">Live Hash:</label>
                <input type="text" name="live_hash" id="livehash" /><br />
                <input type="submit" value="Update" />
            </form>
        </div>
    </body>
    </html>
<?php
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="//cdn.bootcss.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/video-js.min.css" rel="stylesheet">
    <script src="assets/video.min.js"></script>
        <title><?php echo $site_name; ?> LIVE</title>
        <style type="text/css">
            label {font-size: 18px;}
            body {padding: 10px;}
            div.help {line-height: 32px; font-size: 14px;}
        </style>
        <script language="JavaScript">
            videojs.setGlobalOptions({
          flash: {
            swf: 'assets/video-js.swf'
          }
        });
        </script>
    </head>
    <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
    <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $home_url; ?>/"><?php echo $site_name; ?></a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?php echo $site_url; ?>">LIVE</a></li>
                    <li><a href="<?php echo $site_url; ?>?manage=yes" style="color:#222222;">Manage</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container theme-showcase" style="margin-top: 30px">
        <div class="page-header">
            <h2><?php echo $site_name; ?> LIVE</h2>
        </div>
    <div class="video" id="Player">
                <?php
                if ($islive == true) {
                    echo '<video id="my-video" class="video-js" controls preload="auto" width="940" height="540" poster="ready_poster.png" data-setup="{}">';
                    echo '<source src="rtmp://'.$stream_server.'/'.$live_app.'/' . getLiveHash() . '" type="rtmp/m3u8">';
                    echo '<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p></video>';
                } else {
                    echo '<h1 align="center">Live is OFF</h1>';
                }
                ?>
        </div>
    <footer class="footer ">
      <div class="container">
        <hr/>
        <div class="row footer-bottom">
          <ul class="list-inline text-center">
            <li>&copy; 2016 <a href="<?php echo $home_url; ?>" target="_blank"><?php echo $site_name; ?></a> All rigths reserved.</li><li>Powered by <a href="https://www.deamwork.com/" target="_blank">Deamwork</a> Live Service</li>
          </ul>
        </div>
      </div>
    </footer>
    <script src="//cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    </body>
    </html>
<?php 
}
?>
