function initViewport()
{
    if(/Android (\d+\.\d+)/.test(navigator.userAgent))
    {
        var version = parseFloat(RegExp.$1);

        if(version>2.3)
        {
            var phoneScale = parseInt(window.screen.width)/750;
            document.write('<meta name="viewport" content="width=750, minimum-scale = '+ phoneScale +', maximum-scale = '+ phoneScale +', target-densitydpi=device-dpi">');

        }
        else
        {
            document.write('<meta name="viewport" content="width=750, target-densitydpi=device-dpi">');    
        }
    }
    else if(navigator.userAgent.indexOf('iPhone') != -1)
    {
        var phoneScale = parseInt(window.screen.width)/750;
        document.write('<meta name="viewport" content="width=750, initial-scale=' + phoneScale +', user-scalable=no" /> ');         //0.75   0.82
    }
    else 
    {
        document.write('<meta name="viewport" content="width=750, initial-scale=0.426666666" /> ');         //0.75   0.82

    }
}