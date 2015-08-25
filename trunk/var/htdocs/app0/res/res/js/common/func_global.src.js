/*
 * 
 * @author
 * @version
 * @copyright
 */

/*  兼容  */
function returnSelf(e) {
    if(e == null) e = window.event;
   return (typeof e.target != 'undefined') ? e.target : e.srcElement;
}

/* 数组 */
Array.prototype.indexOf = function(o)
{
	for(var i=0;i<this.length;i++)
	{
		if(this[i]==o){
			return i;
		}
	}
	return -1;
}

Array.prototype.lastIndexOf = function(o)
{
	for(var i=this.length-1;i>=0;i--)
	{
		if(this[i]==o){
			return i;
			}
	}
	return -1;
}

Array.prototype.insert = function(o,i)
{
	var l=this.length;
	return this.slice(0,i).concat(o).concat(this.slice(i,l));
}

Array.prototype.insertBefore = function(o,o2)
{
	var i=this.indexOf(o2);
	if(i== -1){
		return this.concat(o2);
	}
	return this.insert(o,i)
}

Array.prototype.remove=function(o)
{
	var i=this.indexOf(o)
	if(i== -1)	return this
	return this.removeAt(i)
}

Array.prototype.removeAt = function(i)
{
	var l=this.length;
	return this.slice(0,i).concat(this.slice(i+1,l));
}

Array.prototype.contains=function(o)
{
	return this.indexOf(o)!= -1;
}

Array.prototype.random = function ()
{
	return this.sort(function(){return Math.random()*new Date%3-1;});
}

Array.prototype.compare = function (a)
{
	return this.toString().match(new RegExp("("+a.join("|")+")", "g"));
}

// /* 字符串  */
String.prototype.ltrim=function()
{
	return this.replace(/(^\s+)/g,"");
}

String.prototype.rtrim=function()
{
	return this.replace(/\s+$/g,"");
}

String.prototype.trim=function()
{
	return this.replace(/(^\s+)|\s+$/g,"");
}

/*** 统计指定字符出现的次数 ***/
String.prototype.Occurs = function(ch) {
//  var re = eval("/[^"+ch+"]/g");
//  return this.replace(re, "").length;
  return this.split(ch).length-1;
}

/*** 检查是否由数字组成 ***/
String.prototype.isDigit = function() {
  var s = this.Trim();
  return (s.replace(/\d/g, "").length == 0);
}

/*** 检查是否由数字字母和下划线组成 ***/
String.prototype.isAlpha = function() {
  return (this.replace(/\w/g, "").length == 0);
}

/*** 检查是否为数 ***/
String.prototype.isNumber = function() {
  var s = this.Trim();
  return (s.search(/^[+-]?[0-9.]*$/) >= 0);
}

/*** 简单的email检查 ***/
String.prototype.isEmail = function() {
  var strr;
  var mail = this;
  var re = /(\w+@\w+\.\w+)(\.{0,1}\w*)(\.{0,1}\w*)/i;
  re.exec(mail);
  if(RegExp.$3!="" && RegExp.$3!="." && RegExp.$2!="."){
	  strr = RegExp.$1+RegExp.$2+RegExp.$3;
  }
  else
  {
	  if(RegExp.$2!="" && RegExp.$2!="."){
		  strr = RegExp.$1+RegExp.$2;
	  }
	  else{
		  strr = RegExp.$1;
	  }
  }
  return (strr==mail);
}

// /* 是否为日期格式 参数 (string)format 年Y|y 月m|n 日d|j，分隔符 -/.,(空格)  格式 eg1:Y-m-d eg2: m d, y 参数 (bool)allownull 是否允许为空或0格式(0000-00-00)  */
String.prototype.isDateFormat = function()
{
    var format=(arguments.length>0)?arguments[0]:'Y-m-d';
    var allownull=(arguments.length>1)?arguments[1]:false;

    // /* 如果值长度为0，返回是否允许为空 */
    if(this.length==0)
    {
        return (allownull==true);
    }

    // /* 格式正则 */
    var fregexp = /^(y|Y|m|n|d|j)([-/., ])(y|Y|m|n|d|j)([-/., ])(y|Y|m|n|d|j)$/g;

    // /* 如果格式正则不通过提示错误 */
    if(!fregexp.test(format))
    {
        alert('FORMAT define invalid.');
        return false;
    }

    // /* 解析格式正则要求 */
    var farr = fregexp.exec(format);

    fp1 = RegExp.$1;    // /* 格式位置1 */
    fs1 = RegExp.$2;    // /* 格式分隔符1 */
    fp2 = RegExp.$3;    // /* 格式位置2 */
    fs2 = RegExp.$4;    // /* 格式分隔符2 */
    fp3 = RegExp.$5;    // /* 格式位置3 */

    // /* 值正则 */
    var dregexps = "([0-9]{1,4})["+fs1+"]([0-9]{1,4})["+fs2+"]([0-9]{1,4})";
    var dregexp = new RegExp(dregexps,"g");

    // /* 检测值是否通过格式正则检测 */
    if(dregexp.test(this)==false)
    {
        return false;
    }

    // /* 解析值正则要求 */
    var darr = dregexp.exec(this);

    dp1 = RegExp.$1;    // /* 值位置1 */
    dp2 = RegExp.$2;    // /* 值位置2 */
    dp3 = RegExp.$3;    // /* 值位置3 */

    var year,month,day; // /* 申明值变量year,month,day */
    var fy,fm,fd;       // /* 申明格式变量fy,fm,fd */

    // /* 定位 值变量year,month,day 格式变量fy,fm,fd */
    if((fp1=='Y'||fp1=='y') && (fp2=='m'||fp2=='n') && (fp3=='d'||fp3=='j'))
    {
        year=dp1;
        month=dp2;
        day=dp3;
        fy=fp1;
        fm=fp2;
        fd=fp3;
    }
    else if((fp1=='m'||fp1=='n') && (fp2=='d'||fp2=='j') && (fp3=='Y'||fp3=='y'))
    {
        year=dp3;
        month=dp1;
        day=dp2;
        fy=fp3;
        fm=fp1;
        fd=fp2;
    }
    else if((fp1=='d'||fp1=='j') && (fp2=='m'||fp2=='n') && (fp3=='Y'||fp3=='y'))
    {
        year=dp3;
        month=dp2;
        day=dp1;
        fy=fp3;
        fm=fp2;
        fd=fp1;
    }
    else if((fp1=='Y'||fp1=='y') && (fp2=='d'||fp2=='j') && (fp3=='m'||fp3=='n'))
    {
        year=dp1;
        month=dp3;
        day=dp2;
        fy=fp1;
        fm=fp3;
        fd=fp2;
    }
    else if((fp1=='m'||fp1=='n') && (fp2=='Y'||fp2=='y') && (fp3=='d'||fp3=='j'))
    {
        year=dp2;
        month=dp1;
        day=dp3;
        fy=fp2;
        fm=fp1;
        fd=fp3;
    }
    else if((fp1=='d'||fp1=='j') && (fp2=='Y'||fp2=='y') && (fp3=='m'||fp3=='n'))
    {
        year=dp2;
        month=dp3;
        day=dp1;
        fy=fp2;
        fm=fp3;
        fd=fp1;
    }
    else
    {
        // /* 格式定义有误 */
        alert('FORMAT define invalid.');
        return false;  
    }

    // /* 值的基本年月日格式正则 */
    var fr_y4 = /^[0-9]{4}$/g;
    var fr_y2 = /^[0-9]{2}$/g;
    var fr_m2 = /^[0-1]{1}[0-9]{1}$/g;
    var fr_m1 = /^[1]{0,1}[0-9]{1}$/g;
    var fr_d2 = /^[0-3]{1}[0-9]{1}$/g;
    var fr_d1 = /^[1-3]{0,1}[0-9]{1}$/g;

    // /* 年月日值格式正则检测是否通过 */
    if( (fy=='Y' && !fr_y4.test(year)) || (fy=='y' && !fr_y2.test(year)) || (fm=='m' && !fr_m2.test(month)) || (fy=='n' && !fr_m1.test(month)) || (fy=='d' && !fr_d2.test(day)) || (fy=='j' && !fr_d1.test(day)) )
    {
        return false;
    }

    // /* 如果允许为空值日期 */
    if(allownull==true)
    {
        if(((fy=='Y' && year=='0000')||(fy=='y' && year=='00')) && ((fm=='m' && month=='00')||(fm=='n' && month=='0')) && ((fd=='d' && day=='00')||(fd=='j' && day=='0')))
        {
            return true;
        }
    }
    // /* 日期的常规检测 */
	if (month < 1 || month > 12) return false;
	if (day < 1 || day > 31) return false;
	if ((month == 4 || month == 6 || month == 9 || month == 11) &&(day == 31)) 
		return false;
    
	if (month == 2) {
		var leap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
		if (day>29 || (day == 29 && !leap)) return false;
    }
	return true;
}

/*** 简单的日期检查，成功返回日期对象 ***/

String.prototype.isDate = function() {
  var p;
  var re1 = /(\d{4})[./-](\d{1,2})[./-](\d{1,2})$/;
  var re2 = /(\d{1,2})[./-](\d{1,2})[./-](\d{2})$/;
  var re3 = /(\d{1,2})[./-](\d{1,2})[./-](\d{4})$/;
  if(re1.test(this)) {
    p = re1.exec(this);
    return new Date(p[1],p[2],p[3]);
  }
  if(re2.test(this)) {
    p = re2.exec(this);
    return new Date(p[3],p[1],p[2]);
  }
  if(re3.test(this)) {
    p = re3.exec(this);
    return new Date(p[3],p[1],p[2]);
  }
  return false;
}

/*** 返回字节数 ***/
String.prototype.lenb = function() {
  return this.replace(/[^\x00-\xff]/g,"**").length;
}

/*** 是否通过自定义正则效验 ***/
String.prototype.regMatch = function (pat)
{
    //如果值为空，通过校验
    if (this.length == 0){
    	return true;
    }
    var pattern = new RegExp(pat,"gi");
    if (pattern.test(this)){
    	return true;
    }else{
    	return false;
    }
}

/*** 检查是否有列表中的字符字符 ***/
String.prototype.isInList = function(list) {
  var re = eval("/["+list+"]/");
  return re.test(this);
}

/*** 检查是否包含汉字***/
String.prototype.hasChinese = function() {
  return (this.length != this.replace(/[^\x00-\xff]/g,"**").length);
}
/*** 检查是否汉字 encode=GB2312情况下适用***/
String.prototype.isChinese = function ()
{
    //如果值为空，通过校验
    if (this.length == 0){
    	return true;
    }
    var pattern = /^([\u4E00-\u9FA5]|[\uFE30-\uFFA0])* $/gi;
    if (pattern.test(this)){
    	return true;
    }else{
    	return false;
    }
}

// 日期
Date.prototype.isWeekend = function()
{
	return this.getDay()%6 ? false : true;
}

Date.prototype.getMDate = function()
{
	return (new Date(this.getFullYear(), this.getMonth()+1, 0).getDate());
}

// 数值
Number.prototype.format = function(len)
{
	return ((new Array(len).join("0")+(this|0)).slice(-len));
}

// Randomizer 随机数rand()
rnd.today=new Date();
rnd.seed=rnd.today.getTime();

function rnd() {
	rnd.seed = (rnd.seed*9301+49297) % 233280;
	return rnd.seed/(233280.0);
};


function rand(number) {
	return Math.ceil(rnd()*number);
};
// end randomizer. -->

/* XHTML */
function externalLinks()
{ 
	if (!document.getElementsByTagName) return;
	var anchors = document.getElementsByTagName("a"); 
	for (var i=0; i<anchors.length; i++)
	{ 
		var anchor = anchors[i]; 
		if (anchor.getAttribute("href")) 
		{
			var rel = anchor.getAttribute("rel");
			switch(rel)
			{
				case 'external':
					anchor.target = '_blank';
				break;
				case 'top':
					anchor.target = '_top';
				break;
				case 'parent':
					anchor.target = '_parent';
				break;
				case "":
				case null:
				case "undefined":
				case 'self':
				case "tag":
				case "nofollow":
				case "noindex":
				//case "follow":
				//case "index":
				//case "none":
				//case "all":
				break;
				default:
					anchor.target = rel;
			}
		}
	}
}

/* Cookie */
function getCookie( name ) {
	var start = document.cookie.indexOf( name + "=" );
	var len = start + name.length + 1;
	if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) ) {
		return null;
	}
	if ( start == -1 ) return null;
	var end = document.cookie.indexOf( ';', len );
	if ( end == -1 ) end = document.cookie.length;
	return unescape( document.cookie.substring( len, end ) );
}

function setCookie( name, value, expires, path, domain, secure ) {
	var today = new Date();
	today.setTime( today.getTime() );
	if ( expires ) {
		expires = expires * 1000 * 60 * 60 * 24;
	}
	var expires_date = new Date( today.getTime() + (expires) );
	document.cookie = name+'='+escape( value ) +
		( ( expires ) ? ';expires='+expires_date.toGMTString() : '' ) + //expires.toGMTString()
		( ( path ) ? ';path=' + path : '' ) +
		( ( domain ) ? ';domain=' + domain : '' ) +
		( ( secure ) ? ';secure' : '' );
}

function deleteCookie( name, path, domain ) {
	if ( getCookie( name ) ) document.cookie = name + '=' +
			( ( path ) ? ';path=' + path : '') +
			( ( domain ) ? ';domain=' + domain : '' ) +
			';expires=Thu, 01-Jan-1970 00:00:01 GMT';
}

/*  页面控制  */

/*  页面转向 [url[,target]]  */
function redirecturl()
{
	url=(arguments.length>0)?arguments[0]:'about:blank';
	target=(arguments.length>1)?arguments[1]:'self';
	if(target.toLowerCase()=='self')
	{
		self.location.href=url;
	}
	else
	{
		top.window[target].location.href=url;
	}
}

/**
 * PopWindow 弹出窗口对象
 * 
 * 
 * @name PopWindow
 * @author nickfan<nickfan81@gmail.com>
 * @last nickfan<nickfan81@gmail.com>
 * @update 2006/07/04 19:10:09
 * @version 0.1 
 * @param object     properties object
 * @return null/true 
 * 
 * usage:
 *	var myWin = new PopWindow({'url':'about:blank','left': 50 ,'top': 50, 'width': 380, 'height' : 240 });
 *    if(myWin==null)
 *    {
 *        alert('popup blocked');
 *        return false;
 *    }
 */
function PopWindow()
{
	this.props = {	'url'           : 'about:blank',
					'name'          : 'window',
//					'width'         : 400,
//					'innerWidth'    : 400,
//					'height'        : 300,
//					'innerHeight'   : 300,
//					'left'          : 0,
//					'screenX'       : 0,
//					'top'           : 0,
//					'screenY'       : 0,
					'titlebar'      : 'no',
					'toolbar'       : 'no',
					'menubar'       : 'no',
					'location'      : 'no',
					'depended'      : 'yes',
					'directories'   : 'no',
					'scrollbars'    : 'yes',
					'resizable'     : 'yes',
					'status'        : 'no',
					'alwaysRaised'  : 'yes',
					'z-look'        : 'yes'};
	for(key in arguments[0])
	{
		this.props[key] = arguments[0][key];
	}
    if(typeof(arguments[0]["width"]) != "undefined")
    {
        if(typeof(arguments[0]["innerWidth"]) == "undefined")
        {
            this.props["innerWidth"] = this.props["width"];
        }
        if(typeof(arguments[0]["left"]) == "undefined")
        {
            if (window.screen)
            {
                var aw = screen.availWidth - 10;
                var xc = (aw - this.props["width"]) / 2;
            }
            else
            {
                var xc = 0;
            }
            this.props["left"] = xc;
            this.props["screenX"] = xc;
        }
        else
        {
            this.props["screenX"] = this.props["left"];
        }
    }
    if(typeof(arguments[0]["height"]) != "undefined")
    {
        if(typeof(arguments[0]["innerHeight"]) == "undefined")
        {
            this.props["innerHeight"] = this.props["height"];
        }
        if(typeof(arguments[0]["top"]) == "undefined")
        {
            if (window.screen)
            {
                var ah = screen.availHeight - 30;
                var yc = (ah - this.props["height"]) / 2;
            }
            else
            {
                var yc = 0;
            }
            this.props["top"] = yc;
            this.props["screenY"] = yc;
        }
        else
        {
            this.props["screenY"] = this.props["top"];
        }
    }

	var str = '';

	for(prop in this.props)
	{
		if(prop != "url" && prop != "name")
		{
			str+= ', ' + prop + ' = ' + this.props[prop];
		}
	}
	str = str.substr(1);
	return window.open(this.props["url"], this.props["name"], str);
}

/**
 * PopModalDialog 弹出模式对话框对象
 * 
 * 
 * @name PopModalDialog
 * @author nickfan<nickfan81@gmail.com>
 * @last nickfan<nickfan81@gmail.com>
 * @update 2006/07/04 19:10:09
 * @version 0.1 
 * @param object     properties object
 * @return null/true 
 * 
 * usage:
 *	var myPop = new PopModalDialog({'url':'about:blank','args': window ,'dialogLeft': 50 ,'dialogTop': 50, 'dialogWidth': 380, 'dialogHeight' : 240 });
 *    if(myPop==null)
 *    {
 *        alert('popup blocked');
 *        return false;
 *    }
 */
function PopModalDialog()
{
	this.props = {	'url'           : 'about:blank',
//					'args'    : null,
//					'dialogWidth'   : '400px',
//					'dialogHeight'  : '300px',
//					'dialogLeft'    : '0px',
//					'dialogTop'     : '0px',
					'center'        : 'yes',
					'help'          : 'no',
					'resizable'     : 'yes',
					'status'        : 'no',
					'scroll'        : 'yes',
					'dialogHide'    : 'no',
					'edge'          : 'raised',
					'unadorned'     : 'no'};
	for(key in arguments[0])
	{
		this.props[key] = arguments[0][key];
	}
	if(typeof(arguments[0]["dialogWidth"]) != "undefined")
	{
        if(typeof(arguments[0]["dialogLeft"]) == "undefined")
        {
            if (window.screen)
            {
                var aw = screen.availWidth - 10;
                var xc = (aw - parseInt(this.props["dialogWidth"])) / 2;
            }
            else
            {
                var xc = 0;
            }
            this.props["dialogLeft"] = xc + 'px';
        }
    }

	if(typeof(arguments[0]["dialogHeight"]) != "undefined")
	{
        if(typeof(arguments[0]["dialogTop"]) == "undefined")
        {
            if (window.screen)
            {
                var ah = screen.availHeight - 30;
                var yc = (ah - parseInt(this.props["dialogHeight"])) / 2;
            }
            else
            {
                var yc = 0;
            }
            this.props["dialogTop"] = yc + 'px';
        }
    }

	var str = '';

	for(prop in this.props)
	{
		if(prop != "url" && prop != "args")
		{
			str+= ', ' + prop + ' = ' + this.props[prop];
		}
	}
	str = str.substr(1);
    if(typeof(this.props["args"]) != "undefined")
    {
        return window.showModalDialog(this.props["url"], this.props["args"], str);
    }
    else
    {
        return window.showModalDialog(this.props["url"], str);
    }
}

/**
 * PopModelessDialog 弹出非模式对话框对象
 * 
 * 
 * @name PopModelessDialog
 * @author nickfan<nickfan81@gmail.com>
 * @last nickfan<nickfan81@gmail.com>
 * @update 2006/07/04 19:10:09
 * @version 0.1 
 * @param object     properties object
 * @return null/true 
 * 
 * usage:
 *	var myPop = new PopModelessDialog({'url':'about:blank','args': window ,'dialogLeft': 50 ,'dialogTop': 50, 'dialogWidth': 380, 'dialogHeight' : 240 });
 *    if(myPop==null)
 *    {
 *        alert('popup blocked');
 *        return false;
 *    }
 */
function PopModelessDialog()
{
	this.props = {	'url'           : 'about:blank',
//					'args'    : null,
//					'dialogWidth'   : '400px',
//					'dialogHeight'  : '300px',
//					'dialogLeft'    : '0px',
//					'dialogTop'     : '0px',
					'center'        : 'yes',
					'help'          : 'no',
					'resizable'     : 'yes',
					'status'        : 'no',
					'scroll'        : 'yes',
					'dialogHide'    : 'no',
					'edge'          : 'raised',
					'unadorned'     : 'no'};
	for(key in arguments[0])
	{
		this.props[key] = arguments[0][key];
	}
	if(typeof(arguments[0]["dialogWidth"]) != "undefined")
	{
        if(typeof(arguments[0]["dialogLeft"]) == "undefined")
        {
            if (window.screen)
            {
                var aw = screen.availWidth - 10;
                var xc = (aw - parseInt(this.props["dialogWidth"])) / 2;
            }
            else
            {
                var xc = 0;
            }
            this.props["dialogLeft"] = xc + 'px';
        }
    }

	if(typeof(arguments[0]["dialogHeight"]) != "undefined")
	{
        if(typeof(arguments[0]["dialogTop"]) == "undefined")
        {
            if (window.screen)
            {
                var ah = screen.availHeight - 30;
                var yc = (ah - parseInt(this.props["dialogHeight"])) / 2;
            }
            else
            {
                var yc = 0;
            }
            this.props["dialogTop"] = yc + 'px';
        }
    }

	var str = '';

	for(prop in this.props)
	{
		if(prop != "url" && prop != "args")
		{
			str+= ', ' + prop + ' = ' + this.props[prop];
		}
	}
	str = str.substr(1);
    if(typeof(this.props["args"]) != "undefined")
    {
        return window.showModelessDialog(this.props["url"], this.props["args"], str);
    }
    else
    {
        return window.showModelessDialog(this.props["url"], str);
    }
}

// show / hide layer
function opencloselayer(id, show)
{
	var elem = document.getElementById(id);
	if (elem) 
	{
		if (show) 
		{
			elem.style.display = 'block';
			elem.style.visibility = 'visible';
			//elem.filters.alpha.opacity=100;
		} 
		else
		{
			elem.style.display = 'none';
			elem.style.visibility = 'hidden';
			//elem.filters.alpha.opacity=0;
		}
	}
}

// changelayeropacity(IE)
function changelayeropacity(id,opa)
{
  var elem = document.getElementById(id);
  if (elem && opa>0 && opa<=100) 
  {
      elem.filters.alpha.opacity=opa;
  }
}



/**
* reference to PMA
*/

var errorMsg0   = 'strFormEmpty';
var errorMsg1   = 'strNotNumber';
var errorMsg2   = 'strNotValidNumber';


/**
 * Ensures a value submitted in a form is numeric and is in a range
 *
 * @param   object   the form
 * @param   string   the name of the form field to check
 * @param   integer  the minimum authorized value
 * @param   integer  the maximum authorized value
 *
 * @return  boolean  whether a valid number has been submitted or not
 */
function checkFormElementInRange(theForm, theFieldName, min, max)
{
    var theField         = theForm.elements[theFieldName];
    var val              = parseInt(theField.value);

    if (typeof(min) == 'undefined') {
        min = 0;
    }
    if (typeof(max) == 'undefined') {
        max = Number.MAX_VALUE;
    }

    // It's not a number
    if (isNaN(val)) {
        theField.select();
        alert(errorMsg1);
        theField.focus();
        return false;
    }
    // It's a number but it is not between min and max
    else if (val < min || val > max) {
        theField.select();
        alert(val + errorMsg2);
        theField.focus();
        return false;
    }
    // It's a valid number
    else {
        theField.value = val;
    }

    return true;
} // end of the 'checkFormElementInRange()' function

/**
 * Displays an confirmation box before doing some action 
 *
 * @param   object   the message to display 
 *
 * @return  boolean  whether to run the query or not
 */
function confirmAction(theMessage)
{
    // TODO: Confirmation is not required in the configuration file
    // or browser is Opera (crappy js implementation)
    if (typeof(window.opera) != 'undefined') {
        return true;
    }

    var is_confirmed = confirm(theMessage);

    return is_confirmed;
} // end of the 'confirmAction()' function


/**
  * Checks/unchecks all options of a <select> element
  *
  * @param   string   the form name
  * @param   string   the element name
  * @param   boolean  whether to check or to uncheck the element
  *
  * @return  boolean  always true
  */
function setSelectOptions(the_form, the_select, do_check)
{
    var selectObject = document.forms[the_form].elements[the_select];
    var selectCount  = selectObject.length;

    for (var i = 0; i < selectCount; i++) {
        selectObject.options[i].selected = do_check;
    } // end for

    return true;
} // end of the 'setSelectOptions()' function


/**
 * Displays an error message if an element of a form hasn't been completed and
 * should be
 *
 * @param   object   the form
 * @param   string   the name of the form field to put the focus on
 *
 * @return  boolean  whether the form field is empty or not
 */
function emptyFormElements(theForm, theFieldName)
{
    var isEmpty  = 1;
    var theField = theForm.elements[theFieldName];
    // Whether the replace function (js1.2) is supported or not
    var isRegExp = (typeof(theField.value.replace) != 'undefined');

    if (!isRegExp) {
        isEmpty      = (theField.value == '') ? 1 : 0;
    } else {
        var space_re = new RegExp('\\s+');
        isEmpty      = (theField.value.replace(space_re, '') == '') ? 1 : 0;
    }
    if (isEmpty) {
        theForm.reset();
        theField.select();
        alert(errorMsg0);
        theField.focus();
        return false;
    }

    return true;
} // end of the 'emptyFormElements()' function

/**
 * This array is used to remember mark status of rows in browse mode
 */
var marked_row = new Array;


/**
 * Sets/unsets the pointer and marker in browse mode
 *
 * @param   object    the table row
 * @param   integer  the row number
 * @param   string    the action calling this script (over, out or click)
 * @param   string    the default background color
 * @param   string    the color to use for mouseover
 * @param   string    the color to use for marking a row
 *
 * @return  boolean  whether pointer is set or not
 */
function setPointer(theRow, theRowNum, theAction, theDefaultColor, thePointerColor, theMarkColor)
{
    var theCells = null;

    // 1. Pointer and mark feature are disabled or the browser can't get the
    //    row -> exits
    if ((thePointerColor == '' && theMarkColor == '')
        || typeof(theRow.style) == 'undefined') {
        return false;
    }

    // 2. Gets the current row and exits if the browser can't get it
    if (typeof(document.getElementsByTagName) != 'undefined') {
        theCells = theRow.getElementsByTagName('td');
    }
    else if (typeof(theRow.cells) != 'undefined') {
        theCells = theRow.cells;
    }
    else {
        return false;
    }

    // 3. Gets the current color...
    var rowCellsCnt  = theCells.length;
    var domDetect    = null;
    var currentColor = null;
    var newColor     = null;
    // 3.1 ... with DOM compatible browsers except Opera that does not return
    //         valid values with "getAttribute"
    if (typeof(window.opera) == 'undefined'
        && typeof(theCells[0].getAttribute) != 'undefined') {
        currentColor = theCells[0].getAttribute('bgcolor');
        domDetect    = true;
    }
    // 3.2 ... with other browsers
    else {
        currentColor = theCells[0].style.backgroundColor;
        domDetect    = false;
    } // end 3

    // 3.3 ... Opera changes colors set via HTML to rgb(r,g,b) format so fix it
    if (currentColor.indexOf("rgb") >= 0)
    {
        var rgbStr = currentColor.slice(currentColor.indexOf('(') + 1,
                                     currentColor.indexOf(')'));
        var rgbValues = rgbStr.split(",");
        currentColor = "#";
        var hexChars = "0123456789ABCDEF";
        for (var i = 0; i < 3; i++)
        {
            var v = rgbValues[i].valueOf();
            currentColor += hexChars.charAt(v/16) + hexChars.charAt(v%16);
        }
    }

    // 4. Defines the new color
    // 4.1 Current color is the default one
    if (currentColor == ''
        || currentColor.toLowerCase() == theDefaultColor.toLowerCase()) {
        if (theAction == 'over' && thePointerColor != '') {
            newColor              = thePointerColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor              = theMarkColor;
            marked_row[theRowNum] = true;
            // Garvin: deactivated onclick marking of the checkbox because it's also executed
            // when an action (like edit/delete) on a single item is performed. Then the checkbox
            // would get deactived, even though we need it activated. Maybe there is a way
            // to detect if the row was clicked, and not an item therein...
            // document.getElementById('id_rows_to_delete' + theRowNum).checked = true;
        }
    }
    // 4.1.2 Current color is the pointer one
    else if (currentColor.toLowerCase() == thePointerColor.toLowerCase()
             && (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])) {
        if (theAction == 'out') {
            newColor              = theDefaultColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor              = theMarkColor;
            marked_row[theRowNum] = true;
            // document.getElementById('id_rows_to_delete' + theRowNum).checked = true;
        }
    }
    // 4.1.3 Current color is the marker one
    else if (currentColor.toLowerCase() == theMarkColor.toLowerCase()) {
        if (theAction == 'click') {
            newColor              = (thePointerColor != '')
                                  ? thePointerColor
                                  : theDefaultColor;
            marked_row[theRowNum] = (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])
                                  ? true
                                  : null;
            // document.getElementById('id_rows_to_delete' + theRowNum).checked = false;
        }
    } // end 4

    // 5. Sets the new color...
    if (newColor) {
        var c = null;
        // 5.1 ... with DOM compatible browsers except Opera
        if (domDetect) {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].setAttribute('bgcolor', newColor, 0);
            } // end for
        }
        // 5.2 ... with other browsers
        else {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].style.backgroundColor = newColor;
            }
        }
    } // end 5

    return true;
} // end of the 'setPointer()' function

/*
 * Sets/unsets the pointer and marker in vertical browse mode
 *
 * @param   object    the table row
 * @param   integer   the column number
 * @param   string    the action calling this script (over, out or click)
 * @param   string    the default background color
 * @param   string    the color to use for mouseover
 * @param   string    the color to use for marking a row
 *
 * @return  boolean  whether pointer is set or not
 *
 * @author Garvin Hicking <me@supergarv.de> (rewrite of setPointer.)
 */
function setVerticalPointer(theRow, theColNum, theAction, theDefaultColor1, theDefaultColor2, thePointerColor, theMarkColor) {
    var theCells = null;
    var tagSwitch = null;

    // 1. Pointer and mark feature are disabled or the browser can't get the
    //    row -> exits
    if ((thePointerColor == '' && theMarkColor == '')
        || typeof(theRow.style) == 'undefined') {
        return false;
    }

    if (typeof(document.getElementsByTagName) != 'undefined') {
        tagSwitch = 'tag';
    } else if (typeof(document.getElementById('table_results')) != 'undefined') {
        tagSwitch = 'cells';
    } else {
        return false;
    }

    // 2. Gets the current row and exits if the browser can't get it
    if (tagSwitch == 'tag') {
        theRows     = document.getElementById('table_results').getElementsByTagName('tr');
        theCells    = theRows[1].getElementsByTagName('td');
    } else if (tagSwitch == 'cells') {
        theRows     = document.getElementById('table_results').rows;
        theCells    = theRows[1].cells;
    }

    // 3. Gets the current color...
    var rowCnt         = theRows.length;
    var domDetect      = null;
    var currentColor   = null;
    var newColor       = null;

    // 3.1 ... with DOM compatible browsers except Opera that does not return
    //         valid values with "getAttribute"
    if (typeof(window.opera) == 'undefined'
        && typeof(theCells[theColNum].getAttribute) != 'undefined') {
        currentColor = theCells[theColNum].getAttribute('bgcolor');
        domDetect    = true;
    }
    // 3.2 ... with other browsers
    else {
        domDetect    = false;
        currentColor = theCells[theColNum].style.backgroundColor;
    } // end 3

    var c = null;

    // 4. Defines the new color
    // 4.1 Current color is the default one
    if (currentColor == ''
        || currentColor.toLowerCase() == theDefaultColor1.toLowerCase()
        || currentColor.toLowerCase() == theDefaultColor2.toLowerCase()) {
        if (theAction == 'over' && thePointerColor != '') {
            newColor              = thePointerColor;
        } else if (theAction == 'click' && theMarkColor != '') {
            newColor              = theMarkColor;
            marked_row[theColNum] = true;
        }
    }
    // 4.1.2 Current color is the pointer one
    else if (currentColor.toLowerCase() == thePointerColor.toLowerCase() &&
             (typeof(marked_row[theColNum]) == 'undefined' || !marked_row[theColNum]) || marked_row[theColNum] == false) {
            if (theAction == 'out') {
                if (theColNum % 2) {
                    newColor              = theDefaultColor1;
                } else {
                    newColor              = theDefaultColor2;
                }
            }
            else if (theAction == 'click' && theMarkColor != '') {
                newColor              = theMarkColor;
                marked_row[theColNum] = true;
            }
    }
    // 4.1.3 Current color is the marker one
    else if (currentColor.toLowerCase() == theMarkColor.toLowerCase()) {
        if (theAction == 'click') {
            newColor              = (thePointerColor != '')
                                  ? thePointerColor
                                  : ((theColNum % 2) ? theDefaultColor1 : theDefaultColor2);
            marked_row[theColNum] = false;
        }
    } // end 4

    // 5 ... with DOM compatible browsers except Opera

    for (c = 0; c < rowCnt; c++) {
        if (tagSwitch == 'tag') {
            Cells = theRows[c].getElementsByTagName('td');
        } else if (tagSwitch == 'cells') {
            Cells = theRows[c].cells;
        }

        Cell  = Cells[theColNum];

        // 5.1 Sets the new color...
        if (newColor) {
            if (domDetect) {
                Cell.setAttribute('bgcolor', newColor, 0);
            } else {
                Cell.style.backgroundColor = newColor;
            }
        } // end 5
    } // end for

     return true;
 } // end of the 'setVerticalPointer()' function


/**
 * Checks/unchecks all rows
 *
 * @param   string   the form name
 * @param   boolean  whether to check or to uncheck the element
 * @param   string   basename of the element
 * @param   integer  min element count
 * @param   integer  max element count
 *
 * @return  boolean  always true
 */
// modified 2004-05-08 by Michael Keck <mail_at_michaelkeck_dot_de>
// - set the other checkboxes (if available) too
function setCheckboxesRange(the_form, do_check, basename, min, max)
{
    for (var i = min; i < max; i++) {
        if (typeof(document.forms[the_form].elements[basename + i]) != 'undefined') {
            document.forms[the_form].elements[basename + i].checked = do_check;
        }
    }

    return true;
} // end of the 'setCheckboxesRange()' function

/**
* reference to PMA
*/


/*  表单  */

/* 查找元素对象 */
function findObj(n, d)
{
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

/*  遍历表单元素并返回QueryString  */
function foreachform()
{
    var querystring='';
    var theform = (arguments.length>0)?(typeof(arguments[0])=='object'?arguments[0]:document.getElementById(arguments[0])):document.forms[0];
    for (i = 0; i < theform.length; i++) 
    {
		switch(theform.elements[i].tagName)
		{
			case "INPUT":
				switch(theform.elements[i].type)
				{
					case "text":
					case "password":
					case "hidden":
						querystring+='&'+theform.elements[i].name+'='+escape(theform.elements[i].value);
					break;
					case "submit":
					case "reset":
					case "button":
						querystring+='&'+theform.elements[i].name+'='+escape(theform.elements[i].value);
					break;
					case "checkbox":
						if(theform.elements[i].checked)
						{
							querystring+='&'+theform.elements[i].name+'='+escape(theform.elements[i].value);
						}
					break;
					case "radio":
						if(theform.elements[i].checked)
						{
							querystring+='&'+theform.elements[i].name+'='+escape(theform.elements[i].value);
						}
					break;
					case "file":
						querystring+='&'+theform.elements[i].name+'='+escape(theform.elements[i].value);
					break;
					default:  
				}
			break;
			case "SELECT":
				if(theform.elements[i].length>0)
				{
					if(theform.elements[i].getAttribute('multiple'))
					{
						for(var j=0;j<theform.elements[i].length;j++)
						{
							if(theform.elements[i].selectedIndex!=-1)
							{
								if(theform.elements[i].options[j].selected==true)
								{
									querystring+='&'+theform.elements[i].name+'='+escape(theform.elements[i].options[j].value);
								}
							}
						}
					}
					else
					{
						if(theform.elements[i].selectedIndex!=-1)
						{
							querystring+='&'+theform.elements[i].name+'='+escape(theform.elements[i].options[theform.elements[i].selectedIndex].value);
						}
					}
				}
			break;  
			case "TEXTAREA":
						querystring+='&'+theform.elements[i].name+'='+escape(theform.elements[i].value);
			break;   
			default:
			break;
		}
    }
	querystring=querystring.substring(1,querystring.length);
	return querystring;
}


// argument 0 requese form
// argument 1 info div id
// argument 2 response function name

function requestAction()
{
    var thismargestring=foreachform(arguments[0]);
    var theform = document.getElementById(arguments[0]);
	//var thismethod=theform.method;
	var thismethod=theform.attributes["method"].value;
	var thisaction=theform.attributes["action"].value;
    var theinfodiv = document.getElementById(arguments[1]);
	var theresponsefuncname=arguments[2];

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() 
	{
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
		{
            theresponsefuncname(xmlhttp.responseXML,theinfodiv);
        }
    }
    xmlhttp.open(thismethod, thisaction);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xmlhttp.send(thismargestring);
}

/* 获取单选框值 */
function getRadioCheckedValue()
{
    var theradioele = document.getElementsByName(arguments[0]);
	var returnvalue = null;
    for (i = 0; i < theradioele.length; i++) 
    {
		if(theradioele[i].checked)
		{
			returnvalue=theradioele[i].value;
			break;
		}
	}
	return returnvalue;
}

/* 获取复选框值数组 */
function getCheckBoxCheckedValueArray()
{
    var thecheckboxele = document.getElementsByName(arguments[0]);
	var returnarray = new Array();
    for (i = 0; i < thecheckboxele.length; i++) 
    {
		if(thecheckboxele[i].checked)
		{
			returnarray.push(thecheckboxele[i].value);
		}
	}
	return returnarray;
}

/*  批量改变checkBox状态，ele 元素名称/元素，bool 状态  */
function checkboxStatusChange(ele,bool)
{
    var a = (typeof(ele)=='object') ? ele : document.getElementsByName(ele);
    for (var i=0; i<a.length; i++)
    {
        a[i].checked = bool;
    }
}

/*  拷贝元素文字内容  */
function copyElementText(obj)
{
	var thisobj=findObj(obj);
	if (thisobj)
	{ 
		thisobj.select();
		js=obj.createTextRange();
		js.execCommand("Copy");
	}
}

/* 拷贝代码 */
function copycode(obj)
{
	var rng = document.body.createTextRange();
	rng.moveToElementText(obj);
	rng.scrollIntoView();
	rng.select();
	rng.execCommand("Copy");
	rng.collapse(false);
}


function getPos(obj)
{
    obj.focus();
    var workRange=document.selection.createRange();
    obj.select();
    var allRange=document.selection.createRange();
    workRange.setEndPoint("StartToStart",allRange);
    var len=workRange.text.length;
    workRange.collapse(false);
    workRange.select();
    return len;
}

function setCursor(obj,num)
{
	obj.focus();
    range=obj.createTextRange(); 
    range.collapse(true); 
    range.moveStart('character',num); 
    range.select();
}

/**
* added by LxcJie 2004.6.25
* 得到文件的后缀名
* oFile为file控件对象
*/
function getFilePostfix(oFile)
{
    if(oFile == null)
        return null;
    var pattern = /(.*)\.(.*) $/gi;
    if(typeof(oFile) == "object")
    {
        if(oFile.value == null || oFile.value == "")
            return null;
        var arr = pattern.exec(oFile.value);
        return RegExp.$2;
    }
    else if(typeof(oFile) == "string")
    {
        var arr = pattern.exec(oFile);
        return RegExp.$2;
    }
    else
        return null;
}

/*  检测注册名 *** DEPRECATED ***  */
function isRegisterUserName(s) 
{ 
    var patrn=/^[a-zA-Z]{1}([a-zA-Z0-9]|[._]){4,25}$/; 
    if (!patrn.exec(s)) return false 
    return true 
}

/* ctrl+enter / alt+s 提交表单 *** DEPRECATED *** */
function ctlent(obj) {
	if((event.ctrlKey && window.event.keyCode == 13) || (event.altKey && window.event.keyCode == 83)) {
		//if(validate(this.document.input)) 
		this.document.obj.submit();
	}
}


/**
 * 从指定的数组中填充Select控件的options
 * ...
 * 
 * @name functionname
 * @author author<author@example.com>
 * @last author<author@example.com>
 * @update 2000/01/01 00:00:00
 * @version 0.1 
 * @param string/object     Select元素
 * @param array     填充数组
 * @param array     默认option
 * @return void      no returns
 * 
 */
function fillSelectFromArray(theCtrl, itemArray, defaultOpion)
{

	var i, j;
	var selectCtrl= ((typeof(theCtrl)=='object') ? theCtrl : document.getElementById(theCtrl));

	// clear selectCtrl options
	if(selectCtrl.length>0)
	{
		for (i = selectCtrl.options.length; i >= 0; i--)
		{
			selectCtrl.options[i] = null;
		}
	}

	if (defaultOpion == null)
	{
		j = 0;
	}
	else
	{
		selectCtrl.options[0] = new Option(defaultOpion[0],defaultOpion[1]);
		j = 1;
	}

	if (itemArray != null)
	{
		for (i = 0; i < itemArray.length; i++)
		{
			selectCtrl.options[j] = new Option(itemArray[i][0]);
			if (itemArray[i][1] != null)
			{
				selectCtrl.options[j].value = itemArray[i][1];
			}
			j++;
		}
		//selectCtrl.options[0].selected = true;
	}
}


/**
  * copy theobj1 selected option to theobj2 <select> element
  *
  * @param   string   theobj1
  * @param   string   theobj2
  * @param   boolean   trigertext
  * @param   boolean   trigervalue
  *
  * @return  boolean
  */
function copySelectTo(theobj1,theobj2,trigertext,trigervalue)
{
	var obj1=document.getElementById(theobj1);
	var obj2=document.getElementById(theobj2);
	if(obj1.selectedIndex=="-1" || obj1.length<1)
	{
		return false;
	}
	if(trigertext)
	{
		for(var i=0;i<obj2.length;i++)
		{
			if(obj2.options[i].text==obj1.options[obj1.selectedIndex].text)
			{
				return false;
			}
		}
	}
	if(trigervalue)
	{
		for(var i=0;i<obj2.length;i++)
		{
			if(obj2.options[i].value==obj1.options[obj1.selectedIndex].value)
			{
				return false;
			}
		}
	}
	obj2.options[obj2.length]= new Option(obj1.options[obj1.selectedIndex].text,obj1.options[obj1.selectedIndex].value);
	return true;
}

// selectionobj,index
function delSelect(theobj,theindex)
{
	var obj=document.getElementById(theobj);
	if(theindex!='selected')
	{
		if(theindex<0 || theindex>=obj.length)
		{
			return false;
		}
		obj.options[theindex]=null;
		return true;
	}
	else
	{
		if(obj.selectedIndex=="-1")
		{
			return false;
		}
		obj.options[obj.selectedIndex]=null;
		return true;
	}
}
function getSelectedIndex(theobj)
{
	var obj=document.getElementById(theobj);
	if(obj.length<1)
	{
		return false;
	}
	return obj.selectedIndex;
}
// clear selectCtrl options
function clrSelect(theobj)
{
	var obj=document.getElementById(theobj);
	for (var i = obj.options.length; i >= 0; i--)
	{
		obj.options[i] = null;
	}
}

// single input add select option theobj1(select ctrl) theobj2(input ctrl)
function input_add(theobj1,theobj2,trigertext,trigervalue)
{
	var obj1=document.getElementById(theobj1);
	var obj2=document.getElementById(theobj2);
	if((obj2.value == null)||(obj2.value == "")||(obj2.value == "undefined")||(obj2.value.length == 0))
	{
		return false;
	}
	if(trigertext)
	{
		for(var i=0;i<obj1.length;i++)
		{
			if(obj1.options[i].text==obj2.value)
			{
				return false;
			}
		}
	}
	if(trigervalue)
	{
		for(var i=0;i<obj1.length;i++)
		{
			if(obj1.options[i].value==obj2.value)
			{
				return false;
			}
		}
	}
	obj1.options[obj1.length]= new Option(obj2.value,obj2.value);
}
// double input add select option theobj1(select ctrl) theobj2(input ctrl)=new option.text theobj3(input ctrl)=new option.value trigertext=boolean trigervalue=boolean
function option_add(theobj1,theobj2,theobj3,trigertext,trigervalue)
{
	var obj1=document.getElementById(theobj1);
	var obj2=document.getElementById(theobj2);
	var obj3=document.getElementById(theobj3);
	if((obj2.value == null)||(obj2.value == "")||(obj2.value == "undefined")||(obj2.value.length == 0))
	{
		obj2.focus();
		return false;
	}
	if((obj3.value == null)||(obj3.value == "")||(obj3.value == "undefined")||(obj3.value.length == 0))
	{
		obj3.focus();
		return false;
	}

	if(trigertext)
	{
		// disallow same options(text)
		for(var i=0;i<obj1.length;i++)
		{
			if(obj1.options[i].text==obj2.value)
			{
				return false;
			}
		}
	}
	
	if(trigervalue)
	{
		// disallow same options(value)

		for(var i=0;i<obj1.length;i++)
		{
			if(obj1.options[i].value==obj3.value)
			{
				return false;
			}
		}
	}

	obj1.options[obj1.length]= new Option(obj2.value,obj3.value);
}
function option_paste(theobj1,theobj2,theobj3)
{
	var obj1=document.getElementById(theobj1);
	var obj2=document.getElementById(theobj2);
	var obj3=document.getElementById(theobj3);
	if(obj1.selectedIndex=="-1")
	{
		return false;
	}
	obj2.value=obj1.options[obj1.selectedIndex].text;
	obj3.value=obj1.options[obj1.selectedIndex].value;
}
function input_paste(theobj1,theobj2)
{
	var obj1=document.getElementById(theobj1);
	var obj2=document.getElementById(theobj2);
	if(obj1.selectedIndex=="-1")
	{
		return false;
	}
	obj2.value=obj1.options[obj1.selectedIndex].value;
}
function margeselectionstring()
{
	var currentlist= ((typeof(arguments[0])=='object') ? arguments[0] : document.getElementById(arguments[0]));
	var margestring='';
	var chainstr=arguments[1];
	var margetype=arguments[2];
	if(margetype=='text')
	{
		for(var i=0;i<currentlist.length;i++)
		{
			margestring+=currentlist.options[i].text+chainstr;
		}
		margestring=margestring.substring(0,margestring.length-1);
	}
	else if(margetype=='value')
	{
		for(var i=0;i<currentlist.length;i++)
		{
			margestring+=currentlist.options[i].value+chainstr;
		}
		margestring=margestring.substring(0,margestring.length-1);
	}
	return margestring;
}

function syncsel(theobj1,theobj2)
{
	var obj1=document.getElementById(theobj1);
	var obj2=document.getElementById(theobj2);
	if(obj1.selectedIndex=="-1" || obj1.length<1 || obj1.length!=obj2.length)
	{
		return false;
	}
	obj2.options[obj1.selectedIndex].selected=true;
}

function optionstohiddenarray(formobjname,selectobjname,hiddenobjname)
{
	var theformobj=document.getElementById(formobjname);
	var theselectobj=document.getElementById(selectobjname);
	if(theselectobj.length<1)
	{
		return false;
	}
	else
	{
		for(var i=0;i<theselectobj.length;i++)
		{
			var theoptions=document.createElement('input');
			theoptions.type='hidden';
			theoptions.name=hiddenobjname;
			theoptions.id=hiddenobjname;
			theoptions.value=theselectobj.options[i].value;
			theformobj.appendChild(theoptions);
		}
	}
}


// isEmpty

function isEmpty(theobj)
{
	var obj=document.getElementById(theobj);
	return ((obj.value == null)||(obj.value == "")||(obj.value == "undefined")||(obj.value.length == 0)); 
}

// isWhitespace

function isWhitespace(theobj)
{
	var obj=document.getElementById(theobj);
	var whitespace = " \t\n\r";
	var i;
	for (i = 0; i < obj.value.length; i++)
	{ 
		var c = obj.value.charAt(i);
		if (whitespace.indexOf(c) >= 0) 
		{
			return true;
		}
	}
	return false;
}

// isOverflow
function isOverflow(theobj,lenmin,lenmax)
{
	var obj=document.getElementById(theobj);
	return ((obj.value.length < lenmin)||(obj.value.length > lenmax)); 
}

// isCharsIn

function isCharsIn(theobj, charin)
{
	var obj=document.getElementById(theobj);
	var i;
	for (i = 0; i < obj.value.length; i++)
	{ 
		var c = obj.value.charAt(i);
		if (charin.indexOf(c) == -1) return false;
	}
	return true;
}

// isCharsEx

function isCharsEx(theobj, charex)
{
	var obj=document.getElementById(theobj);
	var i,c;
	for (i = 0; i < obj.value.length; i++)
	{ 
		c = obj.value.charAt(i);
		if (charex.indexOf(c) > -1) 
		return c;
	}
	return "";
}

// confirmfield
function confirmfield(theobj1,theobj2)
{
	var obj1=document.getElementById(theobj1);
	var obj2=document.getElementById(theobj2);
	return (obj1.value == obj2.value);
}

/* getstrlen */
function strlen(theobj)
{
	var obj=document.getElementById(theobj);
	var i;
	var len;
	len = 0;
	for (i=0;i<obj.value.length;i++)
	{
		if (obj.value.charCodeAt(i)>255) len+=2; else len++;
	}
	return len;
}

// application functions

// disable/enable field
function switchIt(theobj,state)
{
	var obj=document.getElementById(theobj);
	obj.disabled =!state;
}
// change field value

function changeIt(theobj,val)
{
	var obj=document.getElementById(theobj);
	obj.value =val;
}

// switchCheckboxState
function switchCheckboxState(theobj,state)
{
	var obj=document.getElementById(theobj);
	if(state)
	{
		obj.checked =true;
	}
	else
	{
		obj.checked =false;
	}
}

// resetRadio

function resetRadio(obj)
{
	var thisobj = document.getElementsByName(obj);
	for(i=0;i<thisobj.length;i++) thisobj[i].checked=false;
}

// setRadioChecked

function setRadioChecked(obj,j)
{
	var thisobj = document.getElementsByName(obj);
	for(i=0;i<thisobj.length;i++) thisobj[i].checked=false;
	thisobj[j].checked=true;
}


// checkRadioState

function checkRadioState(theobj)
{
	var obj = document.getElementsByName(theobj);
	flag=false;
	for(i=0;i<obj.length;i++) obj[i].checked?flag=true:'';
	if(!flag)
	{
		return false;
	}
	else
	{
		return true;
	}
}

// checkCheckbox

function checkCheckbox(theobj)
{
	var obj = document.getElementsByName(theobj);
	flag=false;
	obj.checked?flag=true:'';
	if(!flag)
	{
		return false;
	}
	else
	{
		return true;
	}
}
