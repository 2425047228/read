//页面头部执行的代码,控制页面像素比和rem

var pixclPatio = 1 / window.devicePixelRatio;
document.write('<meta name="viewport" content="width=device-width,initial-scale='+pixclPatio+',minimum-scale='+pixclPatio+',maximum-scale='+pixclPatio+',user-scalable=no" />');

var html = document.getElementsByTagName('html')[0]; 
var pageWidth = html.getBoundingClientRect().width;
html.style.fontSize = pageWidth / 10 + 'px';