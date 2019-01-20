<?php
 //自动抓取列表
 function spiderauto()
 {        
    $url = "http://www.shunva.com/?m=vod-type-id-16.html";
     
    if(isset($_GET['page']) && (int)$_GET['page']>=1){ 
        $nexturl = urlpreg($url,$page=(int)$_GET['page']+1);        
    }else{
         $page = 1;
         $nexturl = $url;        
    }       
    spider($nexturl,$page);
    $data['page'] = $page;
    $data['nexturl'] = $nexturl;
    return $data;
}
//抓取列表方法
function spider($url,$page=0)
 {        
     $html = file_get_contents($url);
     $htmlOneLine = preg_replace("/\r|\n|\t/","",$html);
     // 通过 preg_match 函数提取获取页面的标题信息
     preg_match("/<title>(.*)<\/title>/iU",$htmlOneLine,$titleArr);
     // 由于 preg_match 函数的结果是数组的形式        
     $title = $titleArr[1];
     $ulpreg = "/<ul>(.*?)<\/ul>/";
     preg_match($ulpreg,$htmlOneLine,$srcArr);
     $t_img_src_preg="#<a href=\"(.*?)\" target=\"_blank\"><img src=\"(.*?)\" /><h3>(.*?)<\/h3>#";
     preg_match_all($t_img_src_preg,$srcArr['1'],$srcA);
     $www = "http://www.shunva.com";
     $tmp = [];
     foreach($srcA['0'] as $k=>$v){
         $tmp[$k]['title'] = $srcA['3'][$k];
         $tmp[$k]['img']   = $srcA['2'][$k];
         $i =  explode('-',$srcA['1'][$k]);            
         $tmp[$k]['href']  = $www.$srcA['1'][$k];
         $tmp[$k]['id']    = rtrim($i['3'],".html");
     }        
     $newfile = mb_substr($title,0,4,'utf-8').'-'.$page.".json";
     $myfile = fopen($newfile, "w") or die("Unable to open file!");
     $txt = json_encode($tmp);;
     fwrite($myfile, $txt);
     fclose($myfile);             
     return $page+1;
 }
 //url分页规则
 function urlpreg($url,$page){    
    $i = explode('-',$url);
    $tmp = $i;
    $tmp['3']= rtrim($i['3'],".html");
    $tmp[]='pg';
    $tmp[]= $page;
    $j = implode("-",$tmp);
    return $j.".html"; 
 }
 ?>
正在采集：
<?php $data = spiderauto(); echo $data['nexturl']; ?>

<h3 id="js_tt"></h3>

<?php echo $page=$data['page']?$data['page']:1;?>

 <script type="text/javascript">
    var url = "i.php?page=<?php echo $page;?>";
    resetTime(3)
    window.setTimeout("window.location=url",3000); 

    //单纯分钟和秒倒计时
    function resetTime(time){
        var timer=null;
        var t=time;
        var m=0;
        var s=0;
        m=Math.floor(t/60%60);
        m<10&&(m='0'+m);
        s=Math.floor(t%60);
        function countDown(){
        s--;
        s<10&&(s='0'+s);
        if(s.length>=3){
            s=59;
            m="0"+(Number(m)-1);
        }
        if(m.length>=3){
            m='00';
            s='00';
            clearInterval(timer);
        }
            document.getElementById("js_tt").innerHTML="倒计时"+m+"分钟"+s+"秒";
            
            console.log(m+"分钟"+s+"秒");
        }
        timer=setInterval(countDown,1000);
    }
</script>
