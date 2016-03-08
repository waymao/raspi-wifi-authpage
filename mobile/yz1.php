<!DOCTYPE HTML>
<html>
	<head>
		<meta charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<title>hhh</title>
        <link href="css/style.css" rel="stylesheet" type="text/css">
	<link href="jm/jquery.mobile-1.4.5.min.css" rel="stylesheet" type="text/css">
	</head>
    <script src="js/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="jm/jquery.mobile-1.4.5.min.js" type="text/javascript"></script>
<body>

        <div data-role="page" id="main_page" class="main_page" data-theme="b">
            <div data-role="header">
        		<p class=topbottom>Wifi验证</p>
        	</div> 
		<div class="user_information" >
        <?php 
	require("config.php"); 
	require("function.php");
	/* Get information*/
	$user_agent=$_SERVER["HTTP_USER_AGENT"];
	$user_ip=$_SERVER['REMOTE_ADDR'];
	$user_mac=get_mac();
	
	/*Connect to MySQL Database*/
    $mysql=mysqlcon();
	
	
	if ($_SERVER['SERVER_NAME']!="192.168.174.1") {
  header("location:http://192.168.174.1:8080/wifiyz/mobile/yz1.php?add="
    .urlencode($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']));
	access_log($mysql,$user_agent,$user_ip,$user_mac,$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
  exit;
  
}
if(!isset ($_POST['answer'])){  ?>
			
			<div class="client-info">
				<br/>
				<b>如果以下信息正确，请回答问题。如有疑问可咨询管理员15174。</b>
				<p>您的浏览器为  <b><?php echo $user_agent;?> </b></p>
				<p>您的ip地址为  <b> <?php echo $user_ip;?></b></p>
				<p>您的mac地址为   <b> <?php echo $user_mac;?></b></p>
			</div>
			
			<div class="submit" style="margin-top:30px !important">
			您的问题是：
<?php
$query="SELECT t1.* 
FROM que AS t1 JOIN 
(SELECT ROUND(RAND() * ((SELECT MAX(id) FROM que )-(SELECT MIN(id) FROM que ))+ 
(SELECT MIN(id) FROM que)) AS id) AS t2 
WHERE t1.id >= t2.id 
ORDER BY t1.id LIMIT 1;";
$result = $mysql->query($query);
$num_results= $result->num_rows;
for ($i=0; $i < $num_results; $i++){
	$row = $result->fetch_assoc();
	$question = $row['question'];
	echo $question;
}
?>

				<form method="post" action="yz1.php?add=<?php echo urlencode($_GET['add']);?>" style="visibility:visible;">
					<input type="text" name="question"style="visibility: hidden;float:right;" value="<?php echo $question;?>">
                    <p>您的回答： <input type="text" name="answer"></p>
                    <p>您的姓名：
                      <input type="text" name="name">
                    </p>
                    <p>您的学号：
                      <input type="text" name="st-number">
                    </p>
                    <p>您的手机： 
                      <input type="text" name="phone">
                    </p>
        <input type="text" name="mac"style="visibility: hidden;float:right;" value="<?php echo $user_mac;?>">
					<p><input name="submuit" type="submit" class="submit" value="提交" style="font-size:3em;" ></p>
				</form>
			</div>
		</div>
		<a href="#helppage" data-rel="dialog">帮助</a>

<?php
}else{
	echo "<p>";
	if($_POST['name'] !=null and $_POST['phone']!=null and $_POST['mac']!=null){
			$name = $_POST['name'];
			$st_number = $_POST['st-number'];
			$phone = $_POST['phone'];
			$question = $_POST['question'];
			$answer = $_POST['answer'];
			$user_mac=get_mac();
			$checkresult = check_phone($phone);
		if($checkresult){
			$a = substr($phone,0,7);
			$query="SELECT * FROM `shphone` WHERE `phone` = ".$a.";";
			$result=$mysql->query($query);
			$num_results= $result->num_rows;
			if($num_results>0){
				$check_stnum = check_stnum($st_number);
				if($check_stnum){
					$query="SELECT * FROM `que` WHERE `question` = '".$question."' AND `answer` = '".$answer."';";
					$result=$mysql->query($query);
					$num_results= $result->num_rows;
					//echo $query;
					if ($num_results > 0){
						echo "恭喜您！回答正确！";
						INSERT_DATA($mysql,$name,$st_number,$phone,$user_mac,$user_ip);
						exec("sudo iptables -I internet 1 -t mangle -m mac --mac-source ".$user_mac." -j RETURN");
						?>
                        <script type="text/javascript">
                         var alltime = 3;
                         	function setTime() {
                            	if (alltime <= 1) {
                                	clearInterval(s);
									window.parent.location.href = "http://<?php echo $_GET['add'];?>";
  								} else {
   									alltime--;
   									document.getElementById("time").innerHTML =  + alltime + "秒后跳转到您刚才访问的页面";
                                      }
 							}
 							s = setInterval("setTime()", 1000);
                          </script>
                                    <div id="time">3秒后跳转到您刚才访问的页面</div>
  									<br/>
  									<p><a href="http://<?php echo $_GET['add'];?>">若您的浏览器没有跳转，请点击这里</a></p>
   									<br/>
                                    
                        <?php
					}else{
						echo "回答错误！！";
					}
				}else{
					echo "您输入的学号不合法";
				}
			}else{
				echo "暂时不支持非上海手机申请。如有疑问可咨询管理员15174。";
			}
		}else{
			echo "您输入的手机不符合规范。如有疑问可咨询管理员15174。";
		}
	}else{
		echo "您没有输入完整的信息！";
	}
?>
<a href="#main_page">返回</a>

</p>



<?php
}
	access_log($mysql,$user_agent,$user_ip,$user_mac,$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	
?>


                <footer style="text-align:center;" data-role="footer">
    		<p>版权所有©魏亦琛15174。保留所有权利。</br></p>
    		<h1><p style="color:red;">魏亦琛向数学学霸和"你"致敬！</p></h1>
    	</footer>    

</div> 
        <div data-role="page" id="helppage" data-theme="b">
  <div data-role="header">
    <h1>提示</h1>
  </div>

  <div data-role="content">
    <p>请您将这道题目的答案填写到答案框，并附着您的联系方式。</p><br/>
    所有收集的信息都只是用来验证身份，并将尽快删除。</br>
    <p>本程序完全由15174自主研发，除了jQuery以外没有用到任何现成的资源。</p>
    <p>本程序使用的代码有:HTML Javascript CSS jQuery PHP Shell脚本.</p>
    <p>本验证系统建立在Apache2.2.22上。使用Debian系统，以及传说中的神奇、什么事情都能干的树莓派B+。</p>
    <p><a href="#main_page">我知道了</a></p>
  </div>

  <div data-role="footer">
  <p style="color:red;">魏亦琛向学霸和"你"致敬！</p>
  </div>
	</body>
    
</html>