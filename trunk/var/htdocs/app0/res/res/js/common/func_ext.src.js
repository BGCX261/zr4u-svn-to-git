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
