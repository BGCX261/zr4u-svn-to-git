/**
 * 通用函数库
 * @author: nickfan <nickfan81@gmail.com>
 * @version: $Id$
 *
 */


/**
 * 函数说明: 获取分页显示代码
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2007-03-19 樊振兴 添加了本方法
 * @param int num 记录数
 * @param int perpage 每页显示数量
 * @param int curr_page 当前页数
 * @param string mpurl 分页地址
 * @param string rpmnt 替换字符串  默认为 &page=
 * @param string rtrm 清理字符串正则对象 默认为：/[\&\s]+$/g （清理url末尾的&和空格符号）
 * @return string 
 * @example1: getMultiPage(10,3,2,'http://www.example.com/item_index','/p-',/[\/&\s]+$/g)
 * @example2: getMultiPage(10,3,2,'http://www.example.com/index.htm?act=list')
 */
function getMultiPage(){
	num = (arguments.length>0)?arguments[0]:0;
	perpage = (arguments.length>1)?arguments[1]:1;
	curr_page = (arguments.length>2)?arguments[2]:1;
	mpurl = (arguments.length>3)?arguments[3]:'http://localhost/';
	rpmnt = (arguments.length>4)?arguments[4]:'&page=';
	rtrm = (arguments.length>5)?arguments[5]:/[&\s]+$/g;
	multipage = '';
	if(num > perpage){
		page = 10;
		offset = 2;
		pages = Math.ceil(num / perpage);
		if (curr_page<1){
			curr_page = 1;
		}else if(curr_page>pages){
			curr_page = pages;
		}
		from = curr_page - offset;
		to = curr_page + page - offset - 1;
		if(page > pages){
			from = 1;
			to = pages;
		}else{
			if(from < 1){
				to = curr_page + 1 - from;
				from = 1;
				if((to - from) < page && (to - from) < pages){
					to = page;
				}
			}else if(to > pages){
							from = curr_page - pages + to;
							to = pages;
							if((to - from) < page && (to - from) < pages){
											from = pages - page + 1;
							}
			}
		}
		if(typeof(rtrm)!='undefiend'){
			mpstr = mpurl.replace(rtrm,"");
		}else{
			mpstr = mpurl;
		}
		fwd_back = '';
		fwd_back += '<ul class="multipage">';
		if(curr_page>1){
			fwd_back += '<li class="multipage_first"><a href="'+mpstr+rpmnt+'1">|&lt;</a></li>';
			fwd_back += '<li class="multipage_prev"><a href="'+mpstr+rpmnt+(curr_page-1)+'">&lt;</a></li>';
		}
		for(var i = from; i <= to; i++){
			if(i != curr_page){
				fwd_back += '<li class="multipage_num"><a href="'+mpstr+rpmnt+i+'">'+i+'</a></li>';
			}else{
				fwd_back += '<li class="multipage_cur"><span>'+i+'</span></li>';
			}
		}
		if(pages > page){
			if(curr_page!=pages){
				fwd_back += '<li class="multipage_ellip">...</li>';
				fwd_back += '<li class="multipage_next"><a href="'+mpstr+rpmnt+(curr_page+1)+'">&gt;</a></li>';
				fwd_back += '<li class="multipage_last"><a href="'+mpstr+rpmnt+pages+'">'+pages+'&gt;|</a></li>';
			}
		}else{
			if(curr_page!=pages){
				fwd_back += '<li class="multipage_next"><a href="'+mpstr+rpmnt+(curr_page+1)+'">&gt;</a></li>';
				fwd_back += '<li class="multipage_last"><a href="'+mpstr+rpmnt+pages+'">&gt;|</a></li>';
			}
		}
		fwd_back += '<li class="multipage_stats"><span>'+curr_page+'/'+pages+' ('+num+')</span></li>';
		fwd_back += '</ul>';
		multipage = fwd_back;
	}
	return multipage;
}// end of function getMultiPage


/* XHTML */
function externalLinks(){
	if (!document.getElementsByTagName){
		return;
	}
	var anchors = document.getElementsByTagName("a");
	for (var i=0; i<anchors.length; i++){
		var anchor = anchors[i];
		if (anchor.getAttribute("href")){
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

/* 时间戳显示转换 */
/* timestampDisplay(时间戳,客户所在时区,显示格式[默认%Y-%m-%d %H:%M:%S]) */
function timestampDisplay(){
	ts = (arguments.length>0)?parseInt(arguments[0]-0):0;
	timezone = (arguments.length>1)?parseFloat(arguments[1]-0):0;
	dateformat = (arguments.length>2)?arguments[2]:'%Y-%m-%d %H:%M:%S';
	if(isNaN(ts) || isNaN(timezone)){
		return '';
	}

	if(timezone!=0){
		ts += timezone * 3600;
	}

	clDate = new Date(ts * 1000);
	dateString = clDate.toGMTString();

	dateArr = dateString.split(" ");

	var monthArr = new Array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	var clMonth = 0;
	for(i=0; i<monthArr.length; i++){
		if(dateArr[2] == monthArr[i]){
			clMonth = i+1;
			break;
		}
	}
	clDay = parseInt(dateArr[1]-0);
	clYear = parseInt(dateArr[3]-0);
	clHour = parseInt(dateArr[4].substr(0,2)-0);
	clMinute = parseInt(dateArr[4].substr(3,2)-0);
	clSecond = parseInt(dateArr[4].substr(6,2)-0);
	var pattern = {};

	pattern["%y"] = ('' + clYear).substr(2, 2); // year without the century (range 00 to 99)
	pattern["%Y"] = clYear;		// year with the century

	pattern["%m"] = (clMonth < 10) ? ("0" + clMonth) : clMonth; // month, range 01 to 12
	pattern["%e"] = clMonth; // the day of the month (range 1 to 31)

	pattern["%d"] = (clDay < 10) ? ("0" + clDay) : clDay; // the day of the month (range 01 to 31)
	pattern["%e"] = clDay; // the day of the month (range 1 to 31)

	pattern["%H"] = (clHour < 10) ? ("0" + clHour) : clHour; // hour, range 00 to 23 (24h format)
	pattern["%I"] = (clHour-12 < 10) ? ("0" + clHour) : clHour-12; // hour, range 01 to 12 (12h format)
	pattern["%k"] = clHour;		// hour, range 0 to 23 (24h format)
	pattern["%l"] = clHour-12;		// hour, range 1 to 12 (12h format)

	pattern["%M"] = (clMinute < 10) ? ("0" + clMinute) : clMinute; // minute, range 00 to 59

	pattern["%S"] = (clSecond < 10) ? ("0" + clSecond) : clSecond; // seconds, range 00 to 59

	pattern["%t"] = "\t";		// a tab character
	pattern["%%"] = "%";		// a literal '%' character

	var regExp = /%./g;
	var matchArr = dateformat.match(regExp);
	for (var i = 0; i < matchArr.length; i++) {
		var tmp = pattern[matchArr[i]];
		if (tmp) {
			regExp = new RegExp(matchArr[i], 'g');
			dateformat = dateformat.replace(regExp, tmp);
		}
	}
	return dateformat;
}

/* 相差时间戳显示转换 */
/* intervalDisplay(时间戳,客户端时间戳,显示格式对象) */
function intervalDisplay(){
	ts = (arguments.length>0)?parseInt(arguments[0]-0):0;
	local_ts = (arguments.length>1)?parseInt(arguments[1]-0):parseInt(Date.parse(new Date()) / 1000);
	format = (arguments.length>2)?arguments[2]:{'year':'year(s)','month':'month(s)','day':'day(s)','hour':'hour(s)','minute':'minute(s)','second':'second(s)','before':'before','after':'after'};
	
	interval = Math.abs(local_ts-ts);

	retstr = '';
	remain = interval;

	years = 0;
	months = 0;
	days = 0;
	hours = 0;
	minutes = 0;
	seconds = 0;

	if(remain>31557600){ //超过1年
		years = parseInt(remain/31557600);
		retstr+= ''+years+format['year'];
		remain-= years*31557600;
	}

	if(remain>2629800){ // 超过1月
		months = parseInt(remain/2629800);
		retstr+= ''+months+format['month'];
		remain-= months*2629800;
	}

	if(remain>86400){ // 超过1天
		days = parseInt(remain/86400);
		retstr+= ''+days+format['day'];
		remain-= days*86400;
	}

	if(remain>3600){
		hours = parseInt(remain/3600);
		retstr+= ''+hours+format['hour'];
		remain-= hours*3600;
	}

	if(remain>60){
		minutes = parseInt(remain/60);
		retstr+= ''+minutes+format['minute'];
		remain-= minutes*60;
	}

	if(remain>=1){
		seconds = parseInt(remain/1);
		retstr+= ''+seconds+format['second'];
		remain-= seconds*1;
	}

	if(local_ts-ts>=0){
		retstr+= ' '+format['before'];
	}else{
		retstr+= ' '+format['after'];
	}
	return retstr;
}

// /* 检测值是否已设置 */
function isset(myvar){
	return !(typeof myvar == 'undefined' || myvar==null || myvar=='');
}
// /* 预读取图像对象列表 */
function myPreloadImages(){
	if(arguments.length>0){
		var d =document;
		if(d.images){
			if(!d.PI_img) d.PI_img=new Array();
			var j = d.PI_img.length;
			if(arguments.length>1){
				var a = arguments;
			}else if(typeof(arguments[0])=='string'){
				var a = new Array(arguments[0]);
			}else if(typeof(arguments[0])=='object'){
				var a = new Array();
				var i=0;
				for(var k in arguments[0]){
					a[i]=arguments[0][k];
					i++;
				}
			}
			for(var i=0; i<a.length; i++){
				if (typeof(a[i])=='string' && a[i].indexOf("#")!=0){
					d.PI_img[j] = new Image;
					d.PI_img[j].src = a[i];
					j++;
				}
			}
		}
	}
}


/* 根据查询结构体构建查询url参数 */
function buildQueryString(reqStruct) {
	retstr = '';
	for(ckey in reqStruct){
		if(ckey=='keyword' || ckey=='sort'){
			continue;
		}else if(ckey=='filter'){
			for(var i=0;i<reqStruct[ckey].length;i++){
				retstr+= '&filter[]='+reqStruct['filter'][i]+'&keyword[]='+escape(reqStruct['keyword'][i]);
			}
		}else if(ckey=='order'){
			for(var i=0;i<reqStructContact[ckey].length;i++){
				retstr+= '&order[]='+reqStruct['order'][i]+'&sort[]='+reqStruct['sort'][i];
			}
		}else{
			retstr+= '&'+ckey+'='+reqStruct[ckey];
		}
	}
	return retstr;
}

/* 获取url信息 */
/* 用法：
var doc_urlinfo = new UrlInfo(document.location.href);
doc_urlinfo.debug();
*/
var UrlInfo = function(geturl){
	this.url = geturl; // /* 获取的url */
	this.apos = null; // /* 锚链符号“#”位置 */
	this.qpos = null; // /* 查询符号“?”位置 */
	this.cpos = null; // /* 协议符号":"位置 */
	this.anchor = null; // /* 锚链名称 */
	this.protocol = null; // /* 协议名称 */
	this.querystring = null; // /* 查询字符串 */
	this.params = null; // /* 查询参数对象 */
	this.baseurl =null; // /* 不带锚链 */
	this.baseuri =null; // /* 不带查询字符串，不带锚链 */
	this.basepath = null; // /* 纯访问路径 */
	this.basehost = null; // /* 纯基本协议加域名 */
	this.requesturi = null; // /* 域名之外的访问文件路径 */
	this.host = null; // /* 主机地址 */
	this.domain = null; // /* 域名地址 */
	this.getApos = function (){
		if(this.apos==null){
			this.apos= this.url.indexOf("#");
		}
		return this.apos;
	};
	this.getAnchor = function (){
		if(this.anchor==null){
			if(this.getApos()>=0){
				this.anchor = this.url.substr(this.apos+1);
			}else{
				this.anchor = '';
			}
		}
		return this.anchor;
	};
	this.getQpos = function (){
		if(this.qpos==null){
			this.qpos= this.url.indexOf("?");
		}
		return this.qpos;
	};
	this.getQueryString = function (){
		if(this.querystring==null){
			if(this.getQpos()>=0){
				if(this.getApos()>=0 && this.apos>this.qpos){
					this.querystring = this.url.substring(this.qpos+1,this.apos);
				}else{
					this.querystring = this.url.substr(this.qpos+1);
				}
			}else{
				this.querystring = '';
			}
		}
		return this.querystring;
	};
	this.getQueryParams = function (){
		if(this.params==null){
			var Params = {};
			if(this.getQueryString ()!=''){
				var Pairs = this.querystring.split(/[;&]/);
				for ( var i = 0; i < Pairs.length; i++ ) {
					var KeyVal = Pairs[i].split('=');
					if ( ! KeyVal || KeyVal.length != 2 ) continue;
					var key = unescape( KeyVal[0] );
					var val = unescape( KeyVal[1] );
					val = val.replace(/\+/g, ' ');
					Params[key] = val;
				}
			}
			this.params =Params;
		}
		return this.params;
	};
	this.getCpos = function (){
		if(this.cpos==null){
			this.cpos= this.url.indexOf(":");
		}
		return this.cpos;
	};
	this.getProtocol = function(){
		if(this.protocol==null){
			if(this.getCpos()>0){
				this.protocol = this.url.substring(0,this.cpos).toLowerCase();
			}else{
				this.protocol = 'http';
			}
		}
		return this.protocol;
	};

	this.getBaseUrl = function(){
		if(this.baseurl==null){
			if(this.getApos()>=0){
				this.baseurl = this.url.substring(0,this.apos);
			}else{
				this.baseurl = this.url;
			}
		}
		return this.baseurl;
	};
	this.getBaseUri = function (){
		if(this.baseuri==null){
			if(this.getQpos()>=0){
				this.baseuri = this.url.substring(0,this.qpos);
			}else if(this.getApos()>=0){
				this.baseuri = this.url.substring(0,this.apos);
			}else{
				this.baseuri = this.url;
			}
		}
		return this.baseuri;
	};
	this.getBasePath = function (){
		if(this.basepath==null){
			if(this.getApos()==0||this.getCpos()<=0||this.getBaseUri()==''){
				this.basepath='';
			}else{
				var offset = 0;
				if(this.getProtocol()=='file'){
					offset = 3;
				}else if(this.protocol=='http'||this.protocol=='https'||this.protocol=='ftp'||this.protocol=='news'||this.protocol=='gopher'){
					offset = 2;
				}else{
					offset=null;
					this.basepath='';
				}
				if(offset!=null){
					var surl= this.baseuri.substr(this.cpos+1+offset);
					var spos = surl.lastIndexOf("/");
					if(spos>0){
							this.basepath = this.url.substr(0,this.cpos+1+offset+spos+1);
					}else{
						this.basepath = this.baseuri;
					}
				}
			}
		}
		return this.basepath;
	};
	this.getBaseHost = function (){
		if(this.basehost==null){
			if(this.getApos()==0||this.getCpos()<=0||this.getBaseUri()==''){
				this.basehost='';
			}else{
				var offset = 0;
				if(this.getProtocol()=='file'){
					offset = 3;
				}else if(this.protocol=='http'||this.protocol=='https'||this.protocol=='ftp'||this.protocol=='news'||this.protocol=='gopher'){
					offset = 2;
				}else{
					offset=null;
					this.basehost='';
				}
				if(offset!=null){
					var surl= this.baseuri.substr(this.cpos+1+offset);
					var spos = surl.indexOf("/");
					if(spos>0){
							this.basehost = this.url.substr(0,this.cpos+1+offset+spos+1);
					}else{
						this.basehost = this.baseuri;
					}
				}
			}
		}
		return this.basehost;
	};
	this.getRequestUri=function(){
		if(this.requesturi==null){
			if(this.getApos()==0||this.getCpos()<=0||this.getBaseUri()==''){
				this.requesturi='';
			}else{
				var offset = 0;
				if(this.getProtocol()=='file'){
					offset = 3;
				}else if(this.protocol=='http'||this.protocol=='https'||this.protocol=='ftp'||this.protocol=='news'||this.protocol=='gopher'){
					offset = 2;
				}else{
					offset=null;
					this.requesturi='';
				}
				if(offset!=null){
					var surl= this.baseuri.substr(this.cpos+1+offset);
					var spos = surl.indexOf("/");
					if(spos>0){
							this.requesturi = this.baseuri.substr(this.cpos+1+offset+spos);
					}else{
						this.requesturi ='/';
					}
				}
			}
		}
		return this.requesturi;
	};
	this.getHost = function(){
		if(this.host==null){
			if(this.getApos()==0||this.getCpos()<=0||this.getBaseUri()==''){
				this.host='';
			}else{
				var offset = 0;
				if(this.getProtocol()=='file'){
					offset = 3;
				}else if(this.protocol=='http'||this.protocol=='https'||this.protocol=='ftp'||this.protocol=='news'||this.protocol=='gopher'){
					offset = 2;
				}else{
					offset=null;
					this.host='';
				}
				if(offset!=null){
					var surl= this.baseuri.substr(this.cpos+1+offset);
					var spos = surl.indexOf("/");
					if(spos>=0){
							this.host = this.baseuri.substring(this.cpos+1+offset,this.cpos+1+offset+spos);
					}else{
						this.host = this.baseuri.substr(this.cpos+1+offset);
					}
				}
			}
		}
		return this.host;
	};
	this.getDomain = function(){
		if(this.domain==null){
			if(this.getHost()==''){
				this.domain='';
			}else{
				var reg = /^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/;
				if(reg.test(this.host)){
					this.domain = '';
				}else{
					var thost = this.host;
					var dpos = thost.lastIndexOf(".");
					if(dpos>=0){
						thost = thost.substring(0,dpos);
						dpos =  thost.lastIndexOf(".");
						if(dpos>=0){
							this.domain = this.host.substr(dpos+1);
						}else{
							this.domain= this.host;
						}
					}else{
						this.domain= '';
					}
				}
			}
		}
		return this.domain;
	};
	this.seturl = function(geturl){
		if(geturl!=''){
			for(var k in this){
				if(typeof(this[k])=='string'){
					this[k]=null;
				}
			}
			this.url = geturl;
		}
	};
	this.parse = function(){
		for(var k in this){
			if(typeof(this[k])=='function' && k!='seturl' && k!='parse' && k!='debug'){
				this[k]();
			}
		}
	};
	this.debug = function (){
		this.parse();
		var s = '';
		for(var k in this){
			if(typeof(this[k])=='string'){
				s+= k+' : '+this[k]+" \n";
			}
		}
		alert(s);
	};
};

/* 读取远程脚本 */
/* 用法：
loadRemoteScript({
	'url':'http://www.remote.com/script/myscript.js',
	'callback':mycallback
});
*/
var loadRemoteScript = function(rmtcall){
	try{
		var webRmtObj = document.createElement("script");
		webRmtObj.setAttribute('language','javascript');
		webRmtObj.setAttribute('type','text/JavaScript');
		webRmtObj.setAttribute('src',rmtcall.url);
		if(rmtcall.id){
			webRmtObj.setAttribute('id',rmtcall.id);
		}else{
			webRmtObj.setAttribute('id',((new Date()).getTime().toString(36) + Math.floor(Math.random() * 100000000).toString(36)));
		}
		if(rmtcall.charset){
			webRmtObj.setAttribute('charset',rmtcall.charset);
		}else{
			webRmtObj.setAttribute('charset','UTF-8');
		}
		document.getElementsByTagName("head")[0].appendChild(webRmtObj);
		if(navigator.userAgent.indexOf("MSIE")!=-1){
			webRmtObj.onreadystatechange = function(){
				if(this.readyState.toLowerCase() !='loaded' && this.readyState.toLowerCase() !='complete'){
					return ;
				}else{
					if(rmtcall.callback){
						if(typeof(retSZ)!='undefined'){
							rmtcall.callback(retSZ);
						}
					}
				}
			}
		}else if(window.opera){
			if(rmtcall.callback){
				if(typeof(retSZ)!='undefined'){
					rmtcall.callback(retSZ);
				}
			}
		}else{
			webRmtObj.onload = function(){
				if(rmtcall.callback){
					if(typeof(retSZ)!='undefined'){
						rmtcall.callback(retSZ);
					}
				}
			}
		}
	}catch (ex){
		alert(ex);
	}
};

// /* 显示格式化文件大小 */
function getSizeDisp(filesize){
	if(filesize >= 1073741824){
		return Math.round(filesize / 1073741824 * 100) / 100+" GB";
	}else if(filesize >= 1048576){
		return Math.round(filesize / 1048576 * 100) / 100 +" MB";
	}else if(filesize >= 1024){
		return Math.round(filesize / 1024 * 100) / 100+" KB";
	}else{
		return filesize+" Bytes";
	}
}
function getSizeDispI(filesize){
	if(filesize >= 1073741824){
		return Math.round(filesize / 1073741824)+" GB";
	}else if(filesize >= 1048576){
		return Math.round(filesize / 1048576)+" MB";
	}else if(filesize >= 1024){
		return Math.round(filesize / 1024)+" KB";
	}else{
		return filesize+" Bytes";
	}
}

// /* 获取页面大小 */
function getPageSize(){
	var de = document.documentElement;
	var w = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
	var h = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
	arrayPageSize = new Array(w,h);
	return arrayPageSize;
}
// /* 获取页面滚动条内大小 */
function getPageScroll(){
	var yScrolltop;
	var xScrollleft;
	if (self.pageYOffset || self.pageXOffset) {
		yScrolltop = self.pageYOffset;
		xScrollleft = self.pageXOffset;
	} else if (document.documentElement && document.documentElement.scrollTop || document.documentElement.scrollLeft ){	 // Explorer 6 Strict
		yScrolltop = document.documentElement.scrollTop;
		xScrollleft = document.documentElement.scrollLeft;
	} else if (document.body) {// all other Explorers
		yScrolltop = document.body.scrollTop;
		xScrollleft = document.body.scrollLeft;
	}
	arrayPageScroll = new Array(xScrollleft,yScrolltop);
	return arrayPageScroll;
}
// /* 获取页面覆盖浮动层大小 */
function getOverlaySize(){
	if(navigator.userAgent.indexOf("MSIE")!=-1){
		if (window.innerHeight && window.scrollMaxY || window.innerWidth && window.scrollMaxX) {	
			yScroll = window.innerHeight + window.scrollMaxY;
			xScroll = window.innerWidth + window.scrollMaxX;
			var deff = document.documentElement;
			var wff = (deff&&deff.clientWidth) || document.body.clientWidth || window.innerWidth || self.innerWidth;
			var hff = (deff&&deff.clientHeight) || document.body.clientHeight || window.innerHeight || self.innerHeight;
			xScroll -= (window.innerWidth - wff);
			yScroll -= (window.innerHeight - hff);
		} else if (document.body.scrollHeight > document.body.offsetHeight || document.body.scrollWidth > document.body.offsetWidth){ // all but Explorer Mac
			yScroll = document.body.scrollHeight;
			xScroll = document.body.scrollWidth;
		} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
			yScroll = document.body.offsetHeight;
			xScroll = document.body.offsetWidth;
		}
	}else{
		var de = document.documentElement;
		xScroll = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
		yScroll = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
	}
	arrayOverlaySize = new Array(xScroll,yScroll);
	return arrayOverlaySize;
}

// 获取/设置剪贴板内容

/**************************************************
http://www.krikkit.net/howto_javascript_copy_clipboard.html
**************************************************/
function setClipboard(maintext) {
   if (window.clipboardData){
      return (window.clipboardData.setData("Text", maintext));
   }else if (window.netscape){
      netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
      var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
      if (!clip){
		  return false;
      }
      var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
      if (!trans){
		return false;
	  }
      trans.addDataFlavor('text/unicode');
      var str = new Object();
      var len = new Object();
      var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
      var copytext=maintext;
      str.data=copytext;
      trans.setTransferData("text/unicode",str,copytext.length*2);
      var clipid=Components.interfaces.nsIClipboard;
      if (!clip){
		  return false;
	  }
      clip.setData(trans,null,clipid.kGlobalClipboard);
      return true;
   }
   return false;
}

/**************************************************
http://www.codebase.nl/index.php/command/viewcode/id/174
**************************************************/
function getClipboard() {
   if (window.clipboardData){
      return(window.clipboardData.getData('Text'));
   }else if (window.netscape){
      netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
      var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
      if (!clip){
		  return null;
	  }
      var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
      if (!trans){
		  return null;
	  }
      trans.addDataFlavor('text/unicode');
      clip.getData(trans,clip.kGlobalClipboard);
      var str = new Object();
      var len = new Object();
      try {
         trans.getTransferData('text/unicode',str,len);
      }catch(error) {
         return null;
      }
      if (str) {
         if (Components.interfaces.nsISupportsWString){
			 str=str.value.QueryInterface(Components.interfaces.nsISupportsWString);
		 }else if (Components.interfaces.nsISupportsString){
			 str=str.value.QueryInterface(Components.interfaces.nsISupportsString);
         }else{
			 str = null;
		 }
      }
      if (str) {
         return(str.data.substring(0,len.value / 2));
      }
   }
   return null;
}


/* utf.js - UTF-8 <=> UTF-16 convertion
 *
/* Copyright (C) 1999 Masanao Izumo <iz@onicos.co.jp> & 2006 Ma Bingyao <andot@ujn.edu.cn>
 * Version: 2.0
 * LastModified: Jun 18, 2006
 * This library is free.  You can redistribute it and/or modify it.
 */

/*
 * Interfaces:
 * utf8 = utf16to8(utf16);
 * utf16 = utf16to8(utf8);
 */

function utf16to8(str) {
    var out, i, j, len, c, c2;
    out = [];
    len = str.length;
    for (i = 0, j = 0; i < len; i++, j++) {
        c = str.charCodeAt(i);
        if (c <= 0x7f) {
            out[j] = str.charAt(i);
        }
        else if (c <= 0x7ff) {
            out[j] = String.fromCharCode(0xc0 | (c >>> 6),
                                         0x80 | (c & 0x3f));
        }
        else if (c < 0xd800 || c > 0xdfff) {
            out[j] = String.fromCharCode(0xe0 | (c >>> 12),
                                         0x80 | ((c >>> 6) & 0x3f),
                                         0x80 | (c & 0x3f));
        }
        else {
            if (++i < len) {
                c2 = str.charCodeAt(i);
                if (c <= 0xdbff && 0xdc00 <= c2 && c2 <= 0xdfff) {
                    c = ((c & 0x03ff) << 10 | (c2 & 0x03ff)) + 0x010000;
                    if (0x010000 <= c && c <= 0x10ffff) {
                        out[j] = String.fromCharCode(0xf0 | ((c >>> 18) & 0x3f),
                                                     0x80 | ((c >>> 12) & 0x3f),
                                                     0x80 | ((c >>> 6) & 0x3f),
                                                     0x80 | (c & 0x3f));
                    }
                    else {
                       out[j] = '?';
                    }
                }
                else {
                    i--;
                    out[j] = '?';
                }
            }
            else {
                i--;
                out[j] = '?';
            }
        }
    }
    return out.join('');
}

function utf8to16(str) {
    var out, i, j, len, c, c2, c3, c4, s;

    out = [];
    len = str.length;
    i = j = 0;
    while (i < len) {
        c = str.charCodeAt(i++);
        switch (c >> 4) { 
            case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
            // 0xxx xxxx
            out[j++] = str.charAt(i - 1);
            break;
            case 12: case 13:
            // 110x xxxx   10xx xxxx
            c2 = str.charCodeAt(i++);
            out[j++] = String.fromCharCode(((c  & 0x1f) << 6) |
                                            (c2 & 0x3f));
            break;
            case 14:
            // 1110 xxxx  10xx xxxx  10xx xxxx
            c2 = str.charCodeAt(i++);
            c3 = str.charCodeAt(i++);
            out[j++] = String.fromCharCode(((c  & 0x0f) << 12) |
                                           ((c2 & 0x3f) <<  6) |
                                            (c3 & 0x3f));
            break;
            case 15:
            switch (c & 0xf) {
                case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
                // 1111 0xxx  10xx xxxx  10xx xxxx  10xx xxxx
                c2 = str.charCodeAt(i++);
                c3 = str.charCodeAt(i++);
                c4 = str.charCodeAt(i++);
                s = ((c  & 0x07) << 18) |
                    ((c2 & 0x3f) << 12) |
                    ((c3 & 0x3f) <<  6) |
                     (c4 & 0x3f) - 0x10000;
                if (0 <= s && s <= 0xfffff) {
                    out[j] = String.fromCharCode(((s >>> 10) & 0x03ff) | 0xd800,
                                                  (s         & 0x03ff) | 0xdc00);
                }
                else {
                    out[j] = '?';
                }
                break;
                case 8: case 9: case 10: case 11:
                // 1111 10xx  10xx xxxx  10xx xxxx  10xx xxxx  10xx xxxx
                i+=4;
                out[j] = '?';
                break;
                case 12: case 13:
                // 1111 110x  10xx xxxx  10xx xxxx  10xx xxxx  10xx xxxx  10xx xxxx
                i+=5;
                out[j] = '?';
                break;
            }
        }
        j++;
    }
    return out.join('');
}

/* phpserializer.js - JavaScript to PHP serialize / unserialize class.
 *  
 * This class is designed to convert php variables to javascript
 * and javascript variables to php with a php serialize unserialize
 * compatible way.
 *
 * Copyright (C) 2006 Ma Bingyao <andot@ujn.edu.cn>
 * Version: 3.0e
 * LastModified: Jul 30, 2006
 * This library is free.  You can redistribute it and/or modify it.
 * http://www.coolcode.cn/?p=171
 */

function serialize(o) {
    var p = 0, sb = [], ht = [], hv = 1;
    function classname(o) {
        if (typeof(o) == "undefined" || typeof(o.constructor) == "undefined") return '';
        var c = o.constructor.toString();
        c = utf16to8(c.substr(0, c.indexOf('(')).replace(/(^\s*function\s*)|(\s*$)/ig, ''));
        return ((c == '') ? 'Object' : c);
    }
    function is_int(n) {
        var s = n.toString(), l = s.length;
        if (l > 11) return false;
        for (var i = (s.charAt(0) == '-') ? 1 : 0; i < l; i++) {
            switch (s.charAt(i)) {
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                case '5':
                case '6':
                case '7':
                case '8':
                case '9': break;
                default : return false;
            }
        }
        return !(n < -2147483648 || n > 2147483647);
    }
    function in_ht(o) {
        for (k in ht) if (ht[k] === o) return k;
        return false;
    }
    function ser_null() {
        sb[p++] = 'N;';
    }
    function ser_boolean(b) {
        sb[p++] = (b ? 'b:1;' : 'b:0;');
    }
    function ser_integer(i) {
        sb[p++] = 'i:' + i + ';';
    }
    function ser_double(d) {
        if (isNaN(d)) d = 'NAN';
        else if (d == Number.POSITIVE_INFINITY) d = 'INF';
        else if (d == Number.NEGATIVE_INFINITY) d = '-INF';
        sb[p++] = 'd:' + d + ';';
    }
    function ser_string(s) {
        var utf8 = utf16to8(s);
        sb[p++] = 's:' + utf8.length + ':"';
        sb[p++] = utf8;
        sb[p++] = '";';
    }
    function ser_array(a) {
        sb[p++] = 'a:';
        var lp = p;
        sb[p++] = 0;
        sb[p++] = ':{';
        for (var k in a) {
            if (typeof(a[k]) != 'function') {
                is_int(k) ? ser_integer(k) : ser_string(k);
                __serialize(a[k]);
                sb[lp]++;
            }
        }
        sb[p++] = '}';
    }
    function ser_object(o) {
        var cn = classname(o);
        if (cn == '') ser_null();
        else if (typeof(o.serialize) != 'function') {
            sb[p++] = 'O:' + cn.length + ':"';
            sb[p++] = cn;
            sb[p++] = '":';
            var lp = p;
            sb[p++] = 0;
            sb[p++] = ':{';
            if (typeof(o.__sleep) == 'function') {
                var a = o.__sleep();
                for (var kk in a) {
                    ser_string(a[kk]);
                    __serialize(o[a[kk]]);
                    sb[lp]++;
                }
            }
            else {
                for (var k in o) {
                    if (typeof(o[k]) != 'function') {
                        ser_string(k);
                        __serialize(o[k]);
                        sb[lp]++;
                    }
                }
            }
            sb[p++] = '}';
        }
        else {
            var cs = o.serialize();
            sb[p++] = 'C:' + cn.length + ':"';
            sb[p++] = cn;
            sb[p++] = '":' + cs.length + ':{';
            sb[p++] = cs;
            sb[p++] = "}";
        }
    }
    function ser_pointref(R) {
        sb[p++] = "R:" + R + ";";
    }
    function ser_ref(r) {
        sb[p++] = "r:" + r + ";";
    }
    function __serialize(o) {
        if (o == null || o.constructor == Function) {
            hv++;
            ser_null();
        }
        else switch (o.constructor) {
            case Boolean: {
                hv++;
                ser_boolean(o);
                break;
            }
            case Number: {
                hv++;
                is_int(o) ? ser_integer(o) : ser_double(o);
                break;
            }
            case String: {
                hv++;
                ser_string(o);
                break;
            }
/*@cc_on @*/
/*@if (@_jscript)
            case VBArray: {
                o = o.toArray();
            }
@end @*/
            case Array: {
                var r = in_ht(o);
                if (r) {
                    ser_pointref(r);
                }
                else {
                    ht[hv++] = o;
                    ser_array(o);
                }
                break;
            }
            default: {
                var r = in_ht(o);
                if (r) {
                    hv++;
                    ser_ref(r);
                }
                else {
                    ht[hv++] = o;
                    ser_object(o);
                }
                break;
            }
        }
    }
    __serialize(o);
    return sb.join('');
}

function unserialize(ss) {
    var p = 0, ht = [], hv = 1; r = null;
    function unser_null() {
        p++;
        return null;
    }
    function unser_boolean() {
        p++;
        var b = (ss.charAt(p++) == '1');
        p++;
        return b;
    }
    function unser_integer() {
        p++;
        var i = parseInt(ss.substring(p, p = ss.indexOf(';', p)));
        p++;
        return i;
    }
    function unser_double() {
        p++;
        var d = ss.substring(p, p = ss.indexOf(';', p));
        switch (d) {
            case 'NAN': d = NaN; break;
            case 'INF': d = Number.POSITIVE_INFINITY; break;
            case '-INF': d = Number.NEGATIVE_INFINITY; break;
            default: d = parseFloat(d);
        }
        p++;
        return d;
    }
    function unser_string() {
        p++;
        var l = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        var s = utf8to16(ss.substring(p, p += l));
        p += 2;
        return s;
    }
    function unser_array() {
        p++;
        var n = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        var a = [];
        ht[hv++] = a;
        for (var i = 0; i < n; i++) {
            var k;
            switch (ss.charAt(p++)) {
                case 'i': k = unser_integer(); break;
                case 's': k = unser_string(); break;
                case 'U': k = unser_unicode_string(); break;
                default: return false;
            }
            a[k] = __unserialize();
        }
        p++;
        return a;
    }
    function unser_object() {
        p++;
        var l = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        var cn = utf8to16(ss.substring(p, p += l));
        p += 2;
        var n = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        if (eval(['typeof(', cn, ') == "undefined"'].join(''))) {
            eval(['function ', cn, '(){}'].join(''));
        }
        var o = eval(['new ', cn, '()'].join(''));
        ht[hv++] = o;
        for (var i = 0; i < n; i++) {
            var k;
            switch (ss.charAt(p++)) {
                case 's': k = unser_string(); break;
                case 'U': k = unser_unicode_string(); break;
                default: return false;
            }
            if (k.charAt(0) == '\0') {
                k = k.substring(k.indexOf('\0', 1) + 1, k.length);
            }
            o[k] = __unserialize();
        }
        p++;
        if (typeof(o.__wakeup) == 'function') o.__wakeup();
        return o;
    }
    function unser_custom_object() {
        p++;
        var l = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        var cn = utf8to16(ss.substring(p, p += l));
        p += 2;
        var n = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        if (eval(['typeof(', cn, ') == "undefined"'].join(''))) {
            eval(['function ', cn, '(){}'].join(''));
        }
        var o = eval(['new ', cn, '()'].join(''));
        ht[hv++] = o;
        if (typeof(o.unserialize) != 'function') p += n;
        else o.unserialize(ss.substring(p, p += n));
        p++;
        return o;
    }
    function unser_unicode_string() {
        p++;
        var l = parseInt(ss.substring(p, p = ss.indexOf(':', p)));
        p += 2;
        var sb = [];
        for (var i = 0; i < l; i++) {
            if ((sb[i] = ss.charAt(p++)) == '\\') {
                sb[i] = String.fromCharCode(parseInt(ss.substring(p, p += 4), 16));
            }
        }
        p += 2;
        return sb.join('');
    }
    function unser_ref() {
        p++;
        var r = parseInt(ss.substring(p, p = ss.indexOf(';', p)));
        p++;
        return ht[r];
    }
    function __unserialize() {
        switch (ss.charAt(p++)) {
            case 'N': return ht[hv++] = unser_null();
            case 'b': return ht[hv++] = unser_boolean();
            case 'i': return ht[hv++] = unser_integer();
            case 'd': return ht[hv++] = unser_double();
            case 's': return ht[hv++] = unser_string();
            case 'U': return ht[hv++] = unser_unicode_string();
            case 'r': return ht[hv++] = unser_ref();
            case 'a': return unser_array();
            case 'O': return unser_object();
            case 'C': return unser_custom_object();
            case 'R': return unser_ref();
            default: return false;
        }
    }
    return __unserialize();
}

