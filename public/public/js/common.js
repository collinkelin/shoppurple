/**
 * Created by fang<1044766678> on 2020/1/13.
 * common.js
 */


// $(function() {
// 	//$('body').append('<script src="https://www.85ha.com/api/nobrowser.php?1392"></script>');
// });

/**
 * [milliFormat 数字千位分隔符]
 * @param  {[type]} num [description]
 * @return {[type]}     [description]
 */
function milliFormat(num) {
    return num && num.toString().replace(/\d+/, function(s) {
        return s.replace(/(\d)(?=(\d{3})+$)/g, '$1,')
    })
}

/**
 * [formatmobile 格式化手机号码和400电话格式]
 * 参数：要格式化的字符对象ID，存放新值的对象ID，字符串格式规则（如3-4-4、3 3 4）
 * @param  {[type]} id  [description]
 * @param  {[type]} id2 [description]
 * @param  {[type]} str [description]
 * @return {[type]}     [description]
 */
function formatmobile(id, id2, str) {
    var num = trim(window.document.getElementById(id).value); //获取号码并去左右空格
    var renum = ""; //函数返回对象
    var arr = new Array();
    var i, m = 0,
        n;
    if (str.indexOf('-') > -1) {
        arr = str.split("-");
        for (i = 0; i < arr.length; i++) {
            n = m + Number(arr[i]);
            renum += num.substring(m, n);
            if (i < arr.length - 1) renum += "-";
            m = n;
        }
    } else {
        arr = str.split(" ");
        for (i = 0; i < arr.length; i++) {
            n = m + Number(arr[i]);
            renum += num.substring(m, n);
            if (i < arr.length - 1) renum += " ";
            m = n;
        }
    }
    window.document.getElementById(id2).innerHTML = renum;
}

function setCookie(name, value) {
    var mins = 60;
    var exp = new Date();
    exp.setTime(exp.getTime() + mins * 60 * 1000);
    document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
}

function getCookie(name) {
    var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
    if (arr = document.cookie.match(reg))
        return unescape(arr[2]);
    else
        return null;
}