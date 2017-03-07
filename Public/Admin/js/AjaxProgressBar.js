/**
 * Created by 帅 on 2017/3/3.
 */
function progressBarData(url,method,takeOver,cutChapter,evt) {
    var form = document.getElementsByTagName('form')[0];    //初始化时创建form对象
    var data = new FormData(form);    //声明formdata对象
    var xhr = new XMLHttpRequest();    //声明ajax对象
    //判断请求是否成功
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.responseText != '') {
                var info = eval('('+xhr.responseText+')');
                if (info.code != 1) {
                    alert(info.msg);
                } else {
                    cutChapter(info.bookId);
                }
            } else {
                alert('缺少数据！');
            }
        }
        if (xhr.readyState == 3) {
            /*console.log(xhr.responseText);
            if (xhr.responseText != '') {
                var info = eval('('+xhr.responseText+')');
                console.log(info);
                if (info.code != 1) {
                    alert(info.msg);
                } else {
                    alert(info.msg);
                    return location.href = '__CONTROLLER__/b_list';
                }
            }*/
        }
    };
    //进度显示
    xhr.upload.onprogress = takeOver;
    xhr.open(method, url +'?rand='+ Math.floor(1000 * Math.random()));    //加上随机数防止请求缓存的
    xhr.send(data);
    evt.preventDefault();    //阻止浏览器跳转
}
function progressBar(evt) {
    var schedule = Math.floor((evt.loaded/evt.total) * 100)+"%";
    var proportion = document.getElementById('proportion');
    var show = document.getElementById('show');

    show.style.display = 'block';
    proportion.style.width = proportion.innerHTML = schedule;
    if (schedule == '100%') {
        show.style.display = 'none';
    }
}