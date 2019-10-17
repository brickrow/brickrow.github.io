
//all globals should go here
var old_type = '';		//used for old_type of cc in validation
var modals = {};		//global of all modals used in a site

var pkd_loc = { httppath: "https://www.brickrow.com/", locale: "en_US", currency_loc: "en-US", currency: "USD" };

//cc globals
var pay_type = '', is_valid = false, $cc_num = '';

//allowed data variables through ajax template
var allowed_tpl_ajax_data = ['sku', 'order_item_id', 'tpl'];

var pkd_gateway = "Square";
var currency = 'USD';

//cart errors/messages
var general_cart_error = "Oops, something went wrong. Please try again.";
var general_ajax_error = "Oops, there was an error with the page request.";
var delete_confirm_msg = "Are you sure you want to remove this item?";
var general_liability_error = "We are unable to process your credit card. Please try again or enter a different payment method.";
var credit_card_expired_error = "Credit card has expired.";
var credit_card_select_error = "Please select a credit card.";
var credit_card_required_error = "Please complete all credit card fields.";
var missing_address_id_error = "Please select an existing address or add a new address.";
var cart_item_label = "(%d item)";
var cart_item_label_plural = "(%d items)";
var valid_amount_error = "Please enter a valid amount.";
var valid_credit_card_error = "Please enter a valid credit card.";
var paypal_signin_required_error = "You must sign into PayPal for payment.";
var gw_default_edit_label = "Edit gift options";
var gw_default_label = "Make it a Gift";
var profile_delete_confirm = "Are you sure you want to delete this profile?"
var square_verify_error = "Please verify that your billing information is correct. If you continue to have problems, please contact us.";
var cart_hide_label = "Hide Cart";
var cart_show_label = "Show Cart";
var purchase_order_num_error = "Please enter a purchase order number.";
var paypal_general_error = "There was an error communicating with PayPal. Please try again, or contact us if you continue to have problems.";
var expires_text = "Expires";
var cart_cc_ending_in_text = "ending in";
var po_number_text = "Purchase Order Number";
var payment_form_confirm = "You are about to submit a payment in the amount of %s. Are you sure?";

//general labels, errors, messages
var required_fields_error = "Please fill in all required fields.";
var wish_remove_confirm = "Are you sure you want to remove this item?";
var close_label = "Close";
var loading_label = "Loading...";
var previous_label = "Previous";
var next_label = "Next";
var primary_label = "Primary";
var secondary_label = "Secondary";
var sidebar_primary_label = "Primary Sidebar";
var sidebar_secondary_label = "Secondary Sidebar";
var sidebar_label = "Sidebar";
var slider_next_label = "Show Next Slide";
var slider_prev_label = "Show Previous Slide";
var gallery_view_image_alt = "Image %1$d of %2$d for %3$s";
var gallery_view_image_aria = "View Image %1$d of %2$d for %3$s";
var show_all_label = "Show All";

var wizard_finish_label = "Save and Close";
var google_recaptcha_response = "Google ReCaptcha Response";

//custom lightbox stuff
//used with custom lightbox code
var last_max_height = 0;		//this is used for adding the inline max-height back onto image after it has been shrunk
var enlarge_enabled = false;		//will check on open if the enlarge/shrink toggle is enabled
var boxed_enabled = false;		//will check on open of the popup is boxed
var custom_title_src = 'title';		//should be an attribute on the element selecting or a custom function
//magnific popup language
var magnific_popup_config = {
	tClose: close_label,
	tLoading: loading_label,
	gallery: {
		tPrev: previous_label,
		tNext: next_label,
		tCounter: "%curr% of %total%"
	},
	image: {
		tError: "<a href=\"%url%\">The image</a> could not be loaded."
	},
	ajax: {
		tError: "<a href=\"%url%\">The content</a> could not be loaded."
	}}

function uniqid() {
	function s4() {
		return Math.floor((1 + Math.random()) * 0x10000)
			.toString(16)
			.substring(1);
	}
	return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
		s4() + '-' + s4() + s4() + s4();
}

function append_tn_modal(callback) {
	if($('#topic-notification-list-wrap').length == 0) {
		$.ajax({
			url: "/modal-topic-notification.php",
			success: function (data) {
				$('#lyr-topic .modal-body').remove();
				$('#lyr-topic .modal-content').append(data);

				if ( $.isFunction(callback) ) {
					callback.call({
						data:data
					});
				}
			},
			dataType: 'html'
		});
	} else {
		if ( $.isFunction(callback) ) {
			callback.call();
		}
	}
}
function pkd_init_card_validator() {
	if($('#fld_cardNumber').length > 0) {
		$cc_num = $('#fld_cardNumber');
		$('#fld_cardNumber').validateCreditCard(function (result) {
			$(this).parent().removeClass('valid not-valid ' + old_type);
			old_type = (result.card_type == null ? '' : result.card_type.name);
			$('.card-number-validator').data('type', old_type);
			is_valid = (result.valid && result.length_valid && result.luhn_valid);
			$(this).parent().addClass((result.card_type == null ? '' : result.card_type.name) + (is_valid ? ' valid' : ' not-valid'));
			if($("#cc_type").length > 0 && result.card_type != null) {
				$("#cc_type").val(result.card_type.name);
			}
		}, {accept: ['visa','mastercard','discover','amex']});
	}
}
/* global window, exports, define */

!function() {
    'use strict'

    var re = {
        not_string: /[^s]/,
        not_bool: /[^t]/,
        not_type: /[^T]/,
        not_primitive: /[^v]/,
        number: /[diefg]/,
        numeric_arg: /[bcdiefguxX]/,
        json: /[j]/,
        not_json: /[^j]/,
        text: /^[^\x25]+/,
        modulo: /^\x25{2}/,
        placeholder: /^\x25(?:([1-9]\d*)\$|\(([^)]+)\))?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-gijostTuvxX])/,
        key: /^([a-z_][a-z_\d]*)/i,
        key_access: /^\.([a-z_][a-z_\d]*)/i,
        index_access: /^\[(\d+)\]/,
        sign: /^[+-]/
    }

    function sprintf(key) {
        // `arguments` is not an array, but should be fine for this call
        return sprintf_format(sprintf_parse(key), arguments)
    }

    function vsprintf(fmt, argv) {
        return sprintf.apply(null, [fmt].concat(argv || []))
    }

    function sprintf_format(parse_tree, argv) {
        var cursor = 1, tree_length = parse_tree.length, arg, output = '', i, k, ph, pad, pad_character, pad_length, is_positive, sign
        for (i = 0; i < tree_length; i++) {
            if (typeof parse_tree[i] === 'string') {
                output += parse_tree[i]
            }
            else if (typeof parse_tree[i] === 'object') {
                ph = parse_tree[i] // convenience purposes only
                if (ph.keys) { // keyword argument
                    arg = argv[cursor]
                    for (k = 0; k < ph.keys.length; k++) {
                        if (arg == undefined) {
                            throw new Error(sprintf('[sprintf] Cannot access property "%s" of undefined value "%s"', ph.keys[k], ph.keys[k-1]))
                        }
                        arg = arg[ph.keys[k]]
                    }
                }
                else if (ph.param_no) { // positional argument (explicit)
                    arg = argv[ph.param_no]
                }
                else { // positional argument (implicit)
                    arg = argv[cursor++]
                }

                if (re.not_type.test(ph.type) && re.not_primitive.test(ph.type) && arg instanceof Function) {
                    arg = arg()
                }

                if (re.numeric_arg.test(ph.type) && (typeof arg !== 'number' && isNaN(arg))) {
                    throw new TypeError(sprintf('[sprintf] expecting number but found %T', arg))
                }

                if (re.number.test(ph.type)) {
                    is_positive = arg >= 0
                }

                switch (ph.type) {
                    case 'b':
                        arg = parseInt(arg, 10).toString(2)
                        break
                    case 'c':
                        arg = String.fromCharCode(parseInt(arg, 10))
                        break
                    case 'd':
                    case 'i':
                        arg = parseInt(arg, 10)
                        break
                    case 'j':
                        arg = JSON.stringify(arg, null, ph.width ? parseInt(ph.width) : 0)
                        break
                    case 'e':
                        arg = ph.precision ? parseFloat(arg).toExponential(ph.precision) : parseFloat(arg).toExponential()
                        break
                    case 'f':
                        arg = ph.precision ? parseFloat(arg).toFixed(ph.precision) : parseFloat(arg)
                        break
                    case 'g':
                        arg = ph.precision ? String(Number(arg.toPrecision(ph.precision))) : parseFloat(arg)
                        break
                    case 'o':
                        arg = (parseInt(arg, 10) >>> 0).toString(8)
                        break
                    case 's':
                        arg = String(arg)
                        arg = (ph.precision ? arg.substring(0, ph.precision) : arg)
                        break
                    case 't':
                        arg = String(!!arg)
                        arg = (ph.precision ? arg.substring(0, ph.precision) : arg)
                        break
                    case 'T':
                        arg = Object.prototype.toString.call(arg).slice(8, -1).toLowerCase()
                        arg = (ph.precision ? arg.substring(0, ph.precision) : arg)
                        break
                    case 'u':
                        arg = parseInt(arg, 10) >>> 0
                        break
                    case 'v':
                        arg = arg.valueOf()
                        arg = (ph.precision ? arg.substring(0, ph.precision) : arg)
                        break
                    case 'x':
                        arg = (parseInt(arg, 10) >>> 0).toString(16)
                        break
                    case 'X':
                        arg = (parseInt(arg, 10) >>> 0).toString(16).toUpperCase()
                        break
                }
                if (re.json.test(ph.type)) {
                    output += arg
                }
                else {
                    if (re.number.test(ph.type) && (!is_positive || ph.sign)) {
                        sign = is_positive ? '+' : '-'
                        arg = arg.toString().replace(re.sign, '')
                    }
                    else {
                        sign = ''
                    }
                    pad_character = ph.pad_char ? ph.pad_char === '0' ? '0' : ph.pad_char.charAt(1) : ' '
                    pad_length = ph.width - (sign + arg).length
                    pad = ph.width ? (pad_length > 0 ? pad_character.repeat(pad_length) : '') : ''
                    output += ph.align ? sign + arg + pad : (pad_character === '0' ? sign + pad + arg : pad + sign + arg)
                }
            }
        }
        return output
    }

    var sprintf_cache = Object.create(null)

    function sprintf_parse(fmt) {
        if (sprintf_cache[fmt]) {
            return sprintf_cache[fmt]
        }

        var _fmt = fmt, match, parse_tree = [], arg_names = 0
        while (_fmt) {
            if ((match = re.text.exec(_fmt)) !== null) {
                parse_tree.push(match[0])
            }
            else if ((match = re.modulo.exec(_fmt)) !== null) {
                parse_tree.push('%')
            }
            else if ((match = re.placeholder.exec(_fmt)) !== null) {
                if (match[2]) {
                    arg_names |= 1
                    var field_list = [], replacement_field = match[2], field_match = []
                    if ((field_match = re.key.exec(replacement_field)) !== null) {
                        field_list.push(field_match[1])
                        while ((replacement_field = replacement_field.substring(field_match[0].length)) !== '') {
                            if ((field_match = re.key_access.exec(replacement_field)) !== null) {
                                field_list.push(field_match[1])
                            }
                            else if ((field_match = re.index_access.exec(replacement_field)) !== null) {
                                field_list.push(field_match[1])
                            }
                            else {
                                throw new SyntaxError('[sprintf] failed to parse named argument key')
                            }
                        }
                    }
                    else {
                        throw new SyntaxError('[sprintf] failed to parse named argument key')
                    }
                    match[2] = field_list
                }
                else {
                    arg_names |= 2
                }
                if (arg_names === 3) {
                    throw new Error('[sprintf] mixing positional and named placeholders is not (yet) supported')
                }

                parse_tree.push(
                    {
                        placeholder: match[0],
                        param_no:    match[1],
                        keys:        match[2],
                        sign:        match[3],
                        pad_char:    match[4],
                        align:       match[5],
                        width:       match[6],
                        precision:   match[7],
                        type:        match[8]
                    }
                )
            }
            else {
                throw new SyntaxError('[sprintf] unexpected placeholder')
            }
            _fmt = _fmt.substring(match[0].length)
        }
        return sprintf_cache[fmt] = parse_tree
    }

    /**
     * export to either browser or node.js
     */
    /* eslint-disable quote-props */
    if (typeof exports !== 'undefined') {
        exports['sprintf'] = sprintf
        exports['vsprintf'] = vsprintf
    }
    if (typeof window !== 'undefined') {
        window['sprintf'] = sprintf
        window['vsprintf'] = vsprintf

        if (typeof define === 'function' && define['amd']) {
            define(function() {
                return {
                    'sprintf': sprintf,
                    'vsprintf': vsprintf
                }
            })
        }
    }
    /* eslint-enable quote-props */
}(); // eslint-disable-line

function alertPrint(){var whichPlatform=navigator.userAgent;if(whichPlatform.indexOf("mac")!=-1){alert("Your browser does not support automatic printing. Please press command + P on your keyboard to print.");return true}else{alert("Your browser does not support automatic printing. Please press control + P on your keyboard to print.");return true}}function printFrame(frm){if(window.print){window.parent.eval(frm).focus();window.print()}else{alertPrint()}}function printPage(){if(window.print){window.print()}else{alertPrint()}}function MM_swapImgRestore(){var i,x,a=document.MM_sr;for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++)x.src=x.oSrc}function MM_preloadImages(){var d=document;if(d.images){if(!d.MM_p)d.MM_p=new Array;var i,j=d.MM_p.length,a=MM_preloadImages.arguments;for(i=0;i<a.length;i++)if(a[i].indexOf("#")!=0){d.MM_p[j]=new Image;d.MM_p[j++].src=a[i]}}}function MM_findObj(n,d){var p,i,x;if(!d)d=document;if((p=n.indexOf("?"))>0&&parent.frames.length){d=parent.frames[n.substring(p+1)].document;n=n.substring(0,p)}if(!(x=d[n])&&d.all)x=d.all[n];for(i=0;!x&&i<d.forms.length;i++)x=d.forms[i][n];for(i=0;!x&&d.layers&&i<d.layers.length;i++)x=MM_findObj(n,d.layers[i].document);return x}function MM_swapImage(){var i,j=0,x,a=MM_swapImage.arguments;document.MM_sr=new Array;for(i=0;i<a.length-2;i+=3)if((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x;if(!x.oSrc)x.oSrc=x.src;x.src=a[i+2]}}function CurrencyFormatted(amount){var i=parseFloat(amount);if(isNaN(i)){i=0}var minus="";if(i<0){minus="-"}i=Math.abs(i);i=parseInt((i+.005)*100);i=i/100;s=new String(i);if(s.indexOf(".")<0){s+=".00"}if(s.indexOf(".")==s.length-2){s+="0"}s=minus+s;return s}function changeQtyForm(frm,val){if(val>0)frm.submit();else{setTimeout(function(){var test=confirm("Are you sure you want to remove this item from your cart?");if(test)frm.submit()},200)}}function changeForm(f){f.submit()}function enableField(f){f.disabled=false}function disableField(f){f.disabled=true}function clearField(){for(var i=0;i<arguments.length;i++){arguments[i].value=""}}function clearForm(formObj){with(formObj){for(var i=0;i<elements.length;i++){fldObj=elements[i];if(fldObj.type=="checkbox"||fldObj.type=="radio"){fldObj.checked=false}if(fldObj.type=="text"||fldObj.type=="password"||fldObj.type=="textarea"){fldObj.value=""}if(fldObj.type=="select-one"){fldObj.selectedIndex=0}}}}function m_showDiv(a){var d=document;d.getElementById(a).style.display="block"}function m_hideDiv(a){var d=document;d.getElementById(a).style.display="none"}function setHValue(frm,fld){with(frm){mailToUser.value=fld.value.length}}function chngCountryFld(frm,selcountry,fldstate,clearstate,fldprov,clearprov,stateReqBill,stateReqShip){if(selcountry!=""&&selcountry!="??"){if(selcountry=="US"){fldprov.value=clearprov;disableField(fldprov);enableField(fldstate);if(stateReqBill==1)document.getElementById("state_req").style.display="inline";if(stateReqShip==1)document.getElementById("state_req2").style.display="inline";fldstate.selectedIndex=0}else{fldstate.selectedIndex=clearstate;disableField(fldstate);enableField(fldprov);if(stateReqBill==1)document.getElementById("state_req").style.display="none";if(stateReqShip==1)document.getElementById("state_req2").style.display="none";fldprov.value=""}}}function highlightmetasearch(fld){fld.select();fld.focus()}function copymetasearch(fld){highlightmetasearch(fld);textRange=fld.createTextRange();textRange.execCommand("RemoveFormat");textRange.execCommand("Copy")}
/*
 * Browser Detection
 * ï¿½ 2010 DevSlide Labs 
 * 
 * Visit us at: www.devslide.com/labs
 */

var notSupportedBrowsers = [];
var displayPoweredBy = false;
var noticeLang = 'professional';
var noticeLangCustom = null;
var supportedBrowsers = [];

var BrowserDetection = {
	init: function(){
		if(notSupportedBrowsers == null || notSupportedBrowsers.length < 1){
			notSupportedBrowsers = this.defaultNotSupportedBrowsers;
		}
		
		this.detectBrowser();
		this.detectOS();
		
		if(this.browser == '' || this.browser == 'Unknown' || this.os == '' || 
		   this.os == 'Unknown' || this.browserVersion == '' || this.browserVersion == 0)
		{
			return;
		}
		
		// Check if this is old browser
		var oldBrowser = false;
		for(var i = 0; i < notSupportedBrowsers.length; i++){
			if(notSupportedBrowsers[i].os == 'Any' || notSupportedBrowsers[i].os == this.os){
				if(notSupportedBrowsers[i].browser == 'Any' || notSupportedBrowsers[i].browser == this.browser){
					if(notSupportedBrowsers[i].version == "Any" || this.browserVersion <= parseFloat(notSupportedBrowsers[i].version)){
						oldBrowser = true;
						break;
					}
				} 
			}
		}
		
		if(oldBrowser){
			this.displayNotice();
		}
	},
	
	getEl: function(id){ return window.document.getElementById(id); },
	getElSize: function(id){ 
		var el = this.getEl(id); 
		if(el == null){ return null; } 
		return { 'width': parseInt(el.offsetWidth), 'height': parseInt(el.offsetHeight) }; 
	},
	getWindowSize: function(){
		if(typeof window.innerWidth != 'undefined'){
			//console.log("width: " + parseInt(window.innerWidth) + "height: "  + parseInt(window.innerHeight));
			return {'width': parseInt(window.innerWidth), 'height': parseInt(window.innerHeight)};
		} else {
			if(window.document.documentElement.clientWidth != 0){
				//console.log("width: " + parseInt(window.document.documentElement.clientWidth) + "height: "  + parseInt(window.document.documentElement.clientHeight));
				return {'width': parseInt(window.document.documentElement.clientWidth), 'height': parseInt(window.document.documentElement.clientHeight)};
			} else {
				return {'width': parseInt(window.document.body.clientWidth), 'height': parseInt(window.document.body.clientHeight)};
			}
		}
	},
	positionNotice: function(){
		var noticeSize = this.getElSize('browser-detection');
		//console.log(noticeSize.height);
		var windowSize = this.getWindowSize();
		//console.log(windowSize.height);
		var noticeEl = this.getEl('browser-detection');
		
		if(noticeEl == null || noticeSize == null || windowSize == null || !windowSize.width || !windowSize.height){ return; }
		noticeEl.style.left = (windowSize.width - noticeSize.width) / 2 + "px";
		
		var offset = (this.browser == "MSIE" && this.browserVersion < 7) ? (window.document.documentElement.scrollTop != 0 ? window.document.documentElement.scrollTop : window.document.body.scrollTop) : 0;
		noticeEl.style.top = (windowSize.height - noticeSize.height - 20 + offset)/2 + "px";
		this.noticeHeight = noticeSize.height;
	},
	
	displayNotice: function(){
		if(this.readCookie('bdnotice') == 1){
			return;
		}
		
		this.writeNoticeCode();
		this.positionNotice();
		
		var el = this;
		window.onresize = function(){ el.positionNotice(); };
		if(this.browser == "MSIE" && this.browserVersion < 7){
			window.onscroll = function(){ el.positionNotice(); };
		}
		
		this.getEl('browser-detection-close').onclick = function(){ el.remindMe(false); };
		this.getEl('browser-detection-remind-later').onclick = function(){ el.remindMe(false); };
		this.getEl('browser-detection-never-remind').onclick = function(){ el.remindMe(true); };
	},
	
	remindMe: function(never){
		this.writeCookie('bdnotice', 1, never == true ? 365 : 7);
		this.getEl('browser-detection').style.display = 'none';
		this.getEl('black_overlay').style.display = 'none';
	},
	
	writeCookie: function(name, value, days){
		var expiration = ""; 
		if(parseInt(days) > 0){
			var date = new Date();
			date.setTime(date.getTime() + parseInt(days) * 24 * 60 * 60 * 1000);
			expiration = '; expires=' + date.toGMTString();
		}
		
		document.cookie = name + '=' + value + expiration + '; path=/';
	},
	
	readCookie: function(name){
		if(!document.cookie){ return ''; }
		
		var searchName = name + '='; 
		var data = document.cookie.split(';');
		
		for(var i = 0; i < data.length; i++){
			while(data[i].charAt(0) == ' '){
				data[i] = data[i].substring(1, data[i].length);
			}
			
			if(data[i].indexOf(searchName) == 0){ 
				return data[i].substring(searchName.length, data[i].length);
			}
		}
		
		return '';
	},
	
	writeNoticeCode: function(){
		var title = '';
		var notice = '';
		var selectBrowser = '';
		var remindMeLater = '';
		var neverRemindAgain = '';
		
		var browsersList = null;		
		var code = '<div id="black_overlay"></div><div id="browser-detection"><a href="javascript:;" id="browser-detection-close">Close</a>';
		
		if(noticeLang == 'custom' && noticeLangCustom != null){
			title = noticeLangCustom.title;
			notice = noticeLangCustom.notice;
			selectBrowser = noticeLangCustom.selectBrowser;
			remindMeLater = noticeLangCustom.remindMeLater;
			neverRemindAgain = noticeLangCustom.neverRemindAgain;
		} else {
			var noticeTextObj = null;
			eval('noticeTextObj = this.noticeText.' + noticeLang + ';');
			
			if(!noticeTextObj){
				noticeTextObj = this.noticeText.professional;
			}
			
			title = noticeTextObj.title;
			notice = noticeTextObj.notice;
			selectBrowser = noticeTextObj.selectBrowser;
			remindMeLater = noticeTextObj.remindMeLater;
			neverRemindAgain = noticeTextObj.neverRemindAgain;
		}
		
		notice = notice.replace("\n", '</p><p class="bd-notice">');
		notice = notice.replace("{browser_name}", (this.browser + " " + this.browserVersion));
		
		code += '<p class="bd-title">' + title + '</p><p class="bd-notice">' + notice + '</p><p class="bd-notice"><b>' + selectBrowser + '</b></p>';
		
		if(supportedBrowsers.length > 0){
			browsersList = supportedBrowsers;
		} else {
			browsersList = this.supportedBrowsers;
		}
		
		code += '<ul class="bd-browsers-list">';
		for(var i = 0; i < browsersList.length; i++){
			code += '<li class="' + browsersList[i].cssClass + '"><a href="' + browsersList[i].downloadUrl + '" target="_blank">' + browsersList[i].name + '</a></li>';
		}		
		code += '</ul>';
		
		if(displayPoweredBy){
			code += '<div class="bd-poweredby">Powered by <a href="http://www.devslide.com/labs/browser-detection" target="_blank">DevSlide Labs</a></div>';
		}
		
		code += '<ul class="bd-skip-buttons">';
		code += '<li><button id="browser-detection-remind-later" type="button">' + remindMeLater + '</button></li>';
		code += '<li><button id="browser-detection-never-remind" type="button">' + neverRemindAgain + '</button></li>';
		code += '</ul>';
		code += '</div>';
		window.document.body.innerHTML += code;
	},

	detectBrowser: function(){
		this.browser = '';
		this.browserVersion = 0;
		
		if(/Opera[\/\s](\d+\.\d+)/.test(navigator.userAgent)){
			this.browser = 'Opera';
		} else if(/MSIE (\d+\.\d+);/.test(navigator.userAgent)){
			this.browser = 'MSIE';
		} else if(/Navigator[\/\s](\d+\.\d+)/.test(navigator.userAgent)){
			this.browser = 'Netscape';
		} else if(/Chrome[\/\s](\d+\.\d+)/.test(navigator.userAgent)){
			this.browser = 'Chrome';
		} else if(/Safari[\/\s](\d+\.\d+)/.test(navigator.userAgent)){
			this.browser = 'Safari';
			/Version[\/\s](\d+\.\d+)/.test(navigator.userAgent);
			this.browserVersion = new Number(RegExp.$1);
		} else if(/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)){
			this.browser = 'Firefox';
		}
		
		if(this.browser == ''){
			this.browser = 'Unknown';
		} else if(this.browserVersion == 0) {
			this.browserVersion = parseFloat(new Number(RegExp.$1));
		}		
	},
	
	// Detect operation system
	detectOS: function(){
		for(var i = 0; i < this.operatingSystems.length; i++){
			if(this.operatingSystems[i].searchString.indexOf(this.operatingSystems[i].subStr) != -1){
				this.os = this.operatingSystems[i].name;
				return;
			}
		}
		
		this.os = "Unknown";
	},
	
	//	Variables
	noticeHeight: 0,
	browser: '',
	os: '',
	browserVersion: '',
	supportedBrowsers: [
	       { 'cssClass': 'firefox', 'name': 'Mozilla Firefox', 'downloadUrl': 'http://www.getfirefox.com/' },
	       { 'cssClass': 'chrome', 'name': 'Google Chrome', 'downloadUrl': 'http://www.google.com/chrome/' },
	       { 'cssClass': 'msie', 'name': 'Internet Explorer', 'downloadUrl': 'http://www.getie.com/' },
	       { 'cssClass': 'opera', 'name': 'Opera', 'downloadUrl': 'http://www.opera.com/' },
	       { 'cssClass': 'safari', 'name': 'Apple Safari', 'downloadUrl': 'http://www.apple.com/safari/' }
	],
	operatingSystems: [
           { 'searchString': navigator.platform, 'name': 'Windows', 'subStr': 'Win' },
           { 'searchString': navigator.platform, 'name': 'Mac', 'subStr': 'Mac' },
           { 'searchString': navigator.platform, 'name': 'Linux', 'subStr': 'Linux' },
           { 'searchString': navigator.userAgent, 'name': 'iPhone', 'subStr': 'iPhone/iPod' }
	],
	defaultNotSupportedBrowsers: [{'os': 'Any', 'browser': 'MSIE', 'version': 8},{'os': 'Any', 'browser': 'MSIE', 'version': 7},{'os': 'Any', 'browser': 'MSIE', 'version': 6}],
	noticeText: {
    	   'professional': { "title": "Outdated Browser Detected", "notice": "Our website has detected that you are using an outdated browser. Using your current browser will prevent you from accessing features on our website. An upgrade is not required, but is strongly recommend to improve your browsing experience on our website.", "selectBrowser": "Use the links below to download a new browser or upgrade your existing browser.", "remindMeLater": "Remind me later", "neverRemindAgain": "No, don't remind me again" },
    	   'informal': { "title": "Whoaaa!", "notice": "It appears you're using an outdated browser which prevents access to some of the features on our website. While it's not required, you really should <b>upgrade or install a new browser</b>!", "selectBrowser": "Visit the official sites for popular browsers below:", "remindMeLater": "Not now, but maybe later", "neverRemindAgain": "No, don't remind me again" },
    	   'technical': { "title": "Old Browser Alert! <span class='bd-highlight'>DEFCON 5</span>", "notice": "Come on! If you are hitting our site, then you must at least be partially tech savvy. So, why the older browser? We're not asking you to brush off your old Fibonacci Heap and share it with the class. Just upgrade!\nI know, I know. You don't like to be told what to do. But, we're only asking you to upgrade so you can access all the latest, greatest features on our site. It's quick and easy. But, if you still want to skip it, that's cool. We will still welcome you &mdash; and your creepy old browser. :P", "selectBrowser": "Visit the official sites for popular browsers below:", "remindMeLater": "Remind me later", "neverRemindAgain": "No, don't remind me. I like my Commodore 64!" },
    	   'goofy': { "title": "Are You Serious?", "notice": "Are you really using <b>{browser_name}</b> as your browser?\nYou're surfing the web on a dinosaur (a dangerous one too &mdash; like a Tyrannosaurus or Pterodactyl or something scary like that). <b>Get with it and upgrade now!</b> If you do, we promise you will enjoy our site a whole lot more. :)", "selectBrowser": "Visit the official sites for popular browsers below:", "remindMeLater": "Maybe Later", "neverRemindAgain": "No, don't remind me again" },
    	   'mean': { "title": "Umm, Your Browser Sucks!", "notice": "Get a new one here.", "selectBrowser": "Official sites for popular browsers:", "remindMeLater": "Remind me later, a'hole", "neverRemindAgain": "F' off! My browser rocks!" }
	}
};

window.onload = function(){
	BrowserDetection.init();
};
/*!
 * Bootstrap v3.0.3 (http://getbootstrap.com)
 * Copyright 2013 Twitter, Inc.
 * Licensed under http://www.apache.org/licenses/LICENSE-2.0
 */

if("undefined"==typeof jQuery)throw new Error("Bootstrap requires jQuery");+function(a){"use strict";function b(){var a=document.createElement("bootstrap"),b={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var c in b)if(void 0!==a.style[c])return{end:b[c]}}a.fn.emulateTransitionEnd=function(b){var c=!1,d=this;a(this).one(a.support.transition.end,function(){c=!0});var e=function(){c||a(d).trigger(a.support.transition.end)};return setTimeout(e,b),this},a(function(){a.support.transition=b()})}(jQuery),+function(a){"use strict";var b='[data-dismiss="alert"]',c=function(c){a(c).on("click",b,this.close)};c.prototype.close=function(b){function c(){f.trigger("closed.bs.alert").remove()}var d=a(this),e=d.attr("data-target");e||(e=d.attr("href"),e=e&&e.replace(/.*(?=#[^\s]*$)/,""));var f=a(e);b&&b.preventDefault(),f.length||(f=d.hasClass("alert")?d:d.parent()),f.trigger(b=a.Event("close.bs.alert")),b.isDefaultPrevented()||(f.removeClass("in"),a.support.transition&&f.hasClass("fade")?f.one(a.support.transition.end,c).emulateTransitionEnd(150):c())};var d=a.fn.alert;a.fn.alert=function(b){return this.each(function(){var d=a(this),e=d.data("bs.alert");e||d.data("bs.alert",e=new c(this)),"string"==typeof b&&e[b].call(d)})},a.fn.alert.Constructor=c,a.fn.alert.noConflict=function(){return a.fn.alert=d,this},a(document).on("click.bs.alert.data-api",b,c.prototype.close)}(jQuery),+function(a){"use strict";var b=function(c,d){this.$element=a(c),this.options=a.extend({},b.DEFAULTS,d)};b.DEFAULTS={loadingText:"loading..."},b.prototype.setState=function(a){var b="disabled",c=this.$element,d=c.is("input")?"val":"html",e=c.data();a+="Text",e.resetText||c.data("resetText",c[d]()),c[d](e[a]||this.options[a]),setTimeout(function(){"loadingText"==a?c.addClass(b).attr(b,b):c.removeClass(b).removeAttr(b)},0)},b.prototype.toggle=function(){var a=this.$element.closest('[data-toggle="buttons"]'),b=!0;if(a.length){var c=this.$element.find("input");"radio"===c.prop("type")&&(c.prop("checked")&&this.$element.hasClass("active")?b=!1:a.find(".active").removeClass("active")),b&&c.prop("checked",!this.$element.hasClass("active")).trigger("change")}b&&this.$element.toggleClass("active")};var c=a.fn.button;a.fn.button=function(c){return this.each(function(){var d=a(this),e=d.data("bs.button"),f="object"==typeof c&&c;e||d.data("bs.button",e=new b(this,f)),"toggle"==c?e.toggle():c&&e.setState(c)})},a.fn.button.Constructor=b,a.fn.button.noConflict=function(){return a.fn.button=c,this},a(document).on("click.bs.button.data-api","[data-toggle^=button]",function(b){var c=a(b.target);c.hasClass("btn")||(c=c.closest(".btn")),c.button("toggle"),b.preventDefault()})}(jQuery),+function(a){"use strict";var b=function(b,c){this.$element=a(b),this.$indicators=this.$element.find(".carousel-indicators"),this.options=c,this.paused=this.sliding=this.interval=this.$active=this.$items=null,"hover"==this.options.pause&&this.$element.on("mouseenter",a.proxy(this.pause,this)).on("mouseleave",a.proxy(this.cycle,this))};b.DEFAULTS={interval:5e3,pause:"hover",wrap:!0},b.prototype.cycle=function(b){return b||(this.paused=!1),this.interval&&clearInterval(this.interval),this.options.interval&&!this.paused&&(this.interval=setInterval(a.proxy(this.next,this),this.options.interval)),this},b.prototype.getActiveIndex=function(){return this.$active=this.$element.find(".item.active"),this.$items=this.$active.parent().children(),this.$items.index(this.$active)},b.prototype.to=function(b){var c=this,d=this.getActiveIndex();return b>this.$items.length-1||0>b?void 0:this.sliding?this.$element.one("slid.bs.carousel",function(){c.to(b)}):d==b?this.pause().cycle():this.slide(b>d?"next":"prev",a(this.$items[b]))},b.prototype.pause=function(b){return b||(this.paused=!0),this.$element.find(".next, .prev").length&&a.support.transition.end&&(this.$element.trigger(a.support.transition.end),this.cycle(!0)),this.interval=clearInterval(this.interval),this},b.prototype.next=function(){return this.sliding?void 0:this.slide("next")},b.prototype.prev=function(){return this.sliding?void 0:this.slide("prev")},b.prototype.slide=function(b,c){var d=this.$element.find(".item.active"),e=c||d[b](),f=this.interval,g="next"==b?"left":"right",h="next"==b?"first":"last",i=this;if(!e.length){if(!this.options.wrap)return;e=this.$element.find(".item")[h]()}this.sliding=!0,f&&this.pause();var j=a.Event("slide.bs.carousel",{relatedTarget:e[0],direction:g});if(!e.hasClass("active")){if(this.$indicators.length&&(this.$indicators.find(".active").removeClass("active"),this.$element.one("slid.bs.carousel",function(){var b=a(i.$indicators.children()[i.getActiveIndex()]);b&&b.addClass("active")})),a.support.transition&&this.$element.hasClass("slide")){if(this.$element.trigger(j),j.isDefaultPrevented())return;e.addClass(b),e[0].offsetWidth,d.addClass(g),e.addClass(g),d.one(a.support.transition.end,function(){e.removeClass([b,g].join(" ")).addClass("active"),d.removeClass(["active",g].join(" ")),i.sliding=!1,setTimeout(function(){i.$element.trigger("slid.bs.carousel")},0)}).emulateTransitionEnd(600)}else{if(this.$element.trigger(j),j.isDefaultPrevented())return;d.removeClass("active"),e.addClass("active"),this.sliding=!1,this.$element.trigger("slid.bs.carousel")}return f&&this.cycle(),this}};var c=a.fn.carousel;a.fn.carousel=function(c){return this.each(function(){var d=a(this),e=d.data("bs.carousel"),f=a.extend({},b.DEFAULTS,d.data(),"object"==typeof c&&c),g="string"==typeof c?c:f.slide;e||d.data("bs.carousel",e=new b(this,f)),"number"==typeof c?e.to(c):g?e[g]():f.interval&&e.pause().cycle()})},a.fn.carousel.Constructor=b,a.fn.carousel.noConflict=function(){return a.fn.carousel=c,this},a(document).on("click.bs.carousel.data-api","[data-slide], [data-slide-to]",function(b){var c,d=a(this),e=a(d.attr("data-target")||(c=d.attr("href"))&&c.replace(/.*(?=#[^\s]+$)/,"")),f=a.extend({},e.data(),d.data()),g=d.attr("data-slide-to");g&&(f.interval=!1),e.carousel(f),(g=d.attr("data-slide-to"))&&e.data("bs.carousel").to(g),b.preventDefault()}),a(window).on("load",function(){a('[data-ride="carousel"]').each(function(){var b=a(this);b.carousel(b.data())})})}(jQuery),+function(a){"use strict";var b=function(c,d){this.$element=a(c),this.options=a.extend({},b.DEFAULTS,d),this.transitioning=null,this.options.parent&&(this.$parent=a(this.options.parent)),this.options.toggle&&this.toggle()};b.DEFAULTS={toggle:!0},b.prototype.dimension=function(){var a=this.$element.hasClass("width");return a?"width":"height"},b.prototype.show=function(){if(!this.transitioning&&!this.$element.hasClass("in")){var b=a.Event("show.bs.collapse");if(this.$element.trigger(b),!b.isDefaultPrevented()){var c=this.$parent&&this.$parent.find("> .panel > .in");if(c&&c.length){var d=c.data("bs.collapse");if(d&&d.transitioning)return;c.collapse("hide"),d||c.data("bs.collapse",null)}var e=this.dimension();this.$element.removeClass("collapse").addClass("collapsing")[e](0),this.transitioning=1;var f=function(){this.$element.removeClass("collapsing").addClass("in")[e]("auto"),this.transitioning=0,this.$element.trigger("shown.bs.collapse")};if(!a.support.transition)return f.call(this);var g=a.camelCase(["scroll",e].join("-"));this.$element.one(a.support.transition.end,a.proxy(f,this)).emulateTransitionEnd(350)[e](this.$element[0][g])}}},b.prototype.hide=function(){if(!this.transitioning&&this.$element.hasClass("in")){var b=a.Event("hide.bs.collapse");if(this.$element.trigger(b),!b.isDefaultPrevented()){var c=this.dimension();this.$element[c](this.$element[c]())[0].offsetHeight,this.$element.addClass("collapsing").removeClass("collapse").removeClass("in"),this.transitioning=1;var d=function(){this.transitioning=0,this.$element.trigger("hidden.bs.collapse").removeClass("collapsing").addClass("collapse")};return a.support.transition?(this.$element[c](0).one(a.support.transition.end,a.proxy(d,this)).emulateTransitionEnd(350),void 0):d.call(this)}}},b.prototype.toggle=function(){this[this.$element.hasClass("in")?"hide":"show"]()};var c=a.fn.collapse;a.fn.collapse=function(c){return this.each(function(){var d=a(this),e=d.data("bs.collapse"),f=a.extend({},b.DEFAULTS,d.data(),"object"==typeof c&&c);e||d.data("bs.collapse",e=new b(this,f)),"string"==typeof c&&e[c]()})},a.fn.collapse.Constructor=b,a.fn.collapse.noConflict=function(){return a.fn.collapse=c,this},a(document).on("click.bs.collapse.data-api","[data-toggle=collapse]",function(b){var c,d=a(this),e=d.attr("data-target")||b.preventDefault()||(c=d.attr("href"))&&c.replace(/.*(?=#[^\s]+$)/,""),f=a(e),g=f.data("bs.collapse"),h=g?"toggle":d.data(),i=d.attr("data-parent"),j=i&&a(i);g&&g.transitioning||(j&&j.find('[data-toggle=collapse][data-parent="'+i+'"]').not(d).addClass("collapsed"),d[f.hasClass("in")?"addClass":"removeClass"]("collapsed")),f.collapse(h)})}(jQuery),+function(a){"use strict";function b(){a(d).remove(),a(e).each(function(b){var d=c(a(this));d.hasClass("open")&&(d.trigger(b=a.Event("hide.bs.dropdown")),b.isDefaultPrevented()||d.removeClass("open").trigger("hidden.bs.dropdown"))})}function c(b){var c=b.attr("data-target");c||(c=b.attr("href"),c=c&&/#/.test(c)&&c.replace(/.*(?=#[^\s]*$)/,""));var d=c&&a(c);return d&&d.length?d:b.parent()}var d=".dropdown-backdrop",e="[data-toggle=dropdown]",f=function(b){a(b).on("click.bs.dropdown",this.toggle)};f.prototype.toggle=function(d){var e=a(this);if(!e.is(".disabled, :disabled")){var f=c(e),g=f.hasClass("open");if(b(),!g){if("ontouchstart"in document.documentElement&&!f.closest(".navbar-nav").length&&a('<div class="dropdown-backdrop"/>').insertAfter(a(this)).on("click",b),f.trigger(d=a.Event("show.bs.dropdown")),d.isDefaultPrevented())return;f.toggleClass("open").trigger("shown.bs.dropdown"),e.focus()}return!1}},f.prototype.keydown=function(b){if(/(38|40|27)/.test(b.keyCode)){var d=a(this);if(b.preventDefault(),b.stopPropagation(),!d.is(".disabled, :disabled")){var f=c(d),g=f.hasClass("open");if(!g||g&&27==b.keyCode)return 27==b.which&&f.find(e).focus(),d.click();var h=a("[role=menu] li:not(.divider):visible a",f);if(h.length){var i=h.index(h.filter(":focus"));38==b.keyCode&&i>0&&i--,40==b.keyCode&&i<h.length-1&&i++,~i||(i=0),h.eq(i).focus()}}}};var g=a.fn.dropdown;a.fn.dropdown=function(b){return this.each(function(){var c=a(this),d=c.data("bs.dropdown");d||c.data("bs.dropdown",d=new f(this)),"string"==typeof b&&d[b].call(c)})},a.fn.dropdown.Constructor=f,a.fn.dropdown.noConflict=function(){return a.fn.dropdown=g,this},a(document).on("click.bs.dropdown.data-api",b).on("click.bs.dropdown.data-api",".dropdown form",function(a){a.stopPropagation()}).on("click.bs.dropdown.data-api",e,f.prototype.toggle).on("keydown.bs.dropdown.data-api",e+", [role=menu]",f.prototype.keydown)}(jQuery),+function(a){"use strict";var b=function(b,c){this.options=c,this.$element=a(b),this.$backdrop=this.isShown=null,this.options.remote&&this.$element.load(this.options.remote)};b.DEFAULTS={backdrop:!0,keyboard:!0,show:!0},b.prototype.toggle=function(a){return this[this.isShown?"hide":"show"](a)},b.prototype.show=function(b){var c=this,d=a.Event("show.bs.modal",{relatedTarget:b});this.$element.trigger(d),this.isShown||d.isDefaultPrevented()||(this.isShown=!0,this.escape(),this.$element.on("click.dismiss.modal",'[data-dismiss="modal"]',a.proxy(this.hide,this)),this.backdrop(function(){var d=a.support.transition&&c.$element.hasClass("fade");c.$element.parent().length||c.$element.appendTo(document.body),c.$element.show(),d&&c.$element[0].offsetWidth,c.$element.addClass("in").attr("aria-hidden",!1),c.enforceFocus();var e=a.Event("shown.bs.modal",{relatedTarget:b});d?c.$element.find(".modal-dialog").one(a.support.transition.end,function(){c.$element.focus().trigger(e)}).emulateTransitionEnd(300):c.$element.focus().trigger(e)}))},b.prototype.hide=function(b){b&&b.preventDefault(),b=a.Event("hide.bs.modal"),this.$element.trigger(b),this.isShown&&!b.isDefaultPrevented()&&(this.isShown=!1,this.escape(),a(document).off("focusin.bs.modal"),this.$element.removeClass("in").attr("aria-hidden",!0).off("click.dismiss.modal"),a.support.transition&&this.$element.hasClass("fade")?this.$element.one(a.support.transition.end,a.proxy(this.hideModal,this)).emulateTransitionEnd(300):this.hideModal())},b.prototype.enforceFocus=function(){a(document).off("focusin.bs.modal").on("focusin.bs.modal",a.proxy(function(a){this.$element[0]===a.target||this.$element.has(a.target).length||this.$element.focus()},this))},b.prototype.escape=function(){this.isShown&&this.options.keyboard?this.$element.on("keyup.dismiss.bs.modal",a.proxy(function(a){27==a.which&&this.hide()},this)):this.isShown||this.$element.off("keyup.dismiss.bs.modal")},b.prototype.hideModal=function(){var a=this;this.$element.hide(),this.backdrop(function(){a.removeBackdrop(),a.$element.trigger("hidden.bs.modal")})},b.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove(),this.$backdrop=null},b.prototype.backdrop=function(b){var c=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var d=a.support.transition&&c;if(this.$backdrop=a('<div class="modal-backdrop '+c+'" />').appendTo(document.body),this.$element.on("click.dismiss.modal",a.proxy(function(a){a.target===a.currentTarget&&("static"==this.options.backdrop?this.$element[0].focus.call(this.$element[0]):this.hide.call(this))},this)),d&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("in"),!b)return;d?this.$backdrop.one(a.support.transition.end,b).emulateTransitionEnd(150):b()}else!this.isShown&&this.$backdrop?(this.$backdrop.removeClass("in"),a.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one(a.support.transition.end,b).emulateTransitionEnd(150):b()):b&&b()};var c=a.fn.modal;a.fn.modal=function(c,d){return this.each(function(){var e=a(this),f=e.data("bs.modal"),g=a.extend({},b.DEFAULTS,e.data(),"object"==typeof c&&c);f||e.data("bs.modal",f=new b(this,g)),"string"==typeof c?f[c](d):g.show&&f.show(d)})},a.fn.modal.Constructor=b,a.fn.modal.noConflict=function(){return a.fn.modal=c,this},a(document).on("click.bs.modal.data-api",'[data-toggle="modal"]',function(b){var c=a(this),d=c.attr("href"),e=a(c.attr("data-target")||d&&d.replace(/.*(?=#[^\s]+$)/,"")),f=e.data("modal")?"toggle":a.extend({remote:!/#/.test(d)&&d},e.data(),c.data());b.preventDefault(),e.modal(f,this).one("hide",function(){c.is(":visible")&&c.focus()})}),a(document).on("show.bs.modal",".modal",function(){a(document.body).addClass("modal-open")}).on("hidden.bs.modal",".modal",function(){a(document.body).removeClass("modal-open")})}(jQuery),+function(a){"use strict";var b=function(a,b){this.type=this.options=this.enabled=this.timeout=this.hoverState=this.$element=null,this.init("tooltip",a,b)};b.DEFAULTS={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1},b.prototype.init=function(b,c,d){this.enabled=!0,this.type=b,this.$element=a(c),this.options=this.getOptions(d);for(var e=this.options.trigger.split(" "),f=e.length;f--;){var g=e[f];if("click"==g)this.$element.on("click."+this.type,this.options.selector,a.proxy(this.toggle,this));else if("manual"!=g){var h="hover"==g?"mouseenter":"focus",i="hover"==g?"mouseleave":"blur";this.$element.on(h+"."+this.type,this.options.selector,a.proxy(this.enter,this)),this.$element.on(i+"."+this.type,this.options.selector,a.proxy(this.leave,this))}}this.options.selector?this._options=a.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},b.prototype.getDefaults=function(){return b.DEFAULTS},b.prototype.getOptions=function(b){return b=a.extend({},this.getDefaults(),this.$element.data(),b),b.delay&&"number"==typeof b.delay&&(b.delay={show:b.delay,hide:b.delay}),b},b.prototype.getDelegateOptions=function(){var b={},c=this.getDefaults();return this._options&&a.each(this._options,function(a,d){c[a]!=d&&(b[a]=d)}),b},b.prototype.enter=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs."+this.type);return clearTimeout(c.timeout),c.hoverState="in",c.options.delay&&c.options.delay.show?(c.timeout=setTimeout(function(){"in"==c.hoverState&&c.show()},c.options.delay.show),void 0):c.show()},b.prototype.leave=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs."+this.type);return clearTimeout(c.timeout),c.hoverState="out",c.options.delay&&c.options.delay.hide?(c.timeout=setTimeout(function(){"out"==c.hoverState&&c.hide()},c.options.delay.hide),void 0):c.hide()},b.prototype.show=function(){var b=a.Event("show.bs."+this.type);if(this.hasContent()&&this.enabled){if(this.$element.trigger(b),b.isDefaultPrevented())return;var c=this.tip();this.setContent(),this.options.animation&&c.addClass("fade");var d="function"==typeof this.options.placement?this.options.placement.call(this,c[0],this.$element[0]):this.options.placement,e=/\s?auto?\s?/i,f=e.test(d);f&&(d=d.replace(e,"")||"top"),c.detach().css({top:0,left:0,display:"block"}).addClass(d),this.options.container?c.appendTo(this.options.container):c.insertAfter(this.$element);var g=this.getPosition(),h=c[0].offsetWidth,i=c[0].offsetHeight;if(f){var j=this.$element.parent(),k=d,l=document.documentElement.scrollTop||document.body.scrollTop,m="body"==this.options.container?window.innerWidth:j.outerWidth(),n="body"==this.options.container?window.innerHeight:j.outerHeight(),o="body"==this.options.container?0:j.offset().left;d="bottom"==d&&g.top+g.height+i-l>n?"top":"top"==d&&g.top-l-i<0?"bottom":"right"==d&&g.right+h>m?"left":"left"==d&&g.left-h<o?"right":d,c.removeClass(k).addClass(d)}var p=this.getCalculatedOffset(d,g,h,i);this.applyPlacement(p,d),this.$element.trigger("shown.bs."+this.type)}},b.prototype.applyPlacement=function(a,b){var c,d=this.tip(),e=d[0].offsetWidth,f=d[0].offsetHeight,g=parseInt(d.css("margin-top"),10),h=parseInt(d.css("margin-left"),10);isNaN(g)&&(g=0),isNaN(h)&&(h=0),a.top=a.top+g,a.left=a.left+h,d.offset(a).addClass("in");var i=d[0].offsetWidth,j=d[0].offsetHeight;if("top"==b&&j!=f&&(c=!0,a.top=a.top+f-j),/bottom|top/.test(b)){var k=0;a.left<0&&(k=-2*a.left,a.left=0,d.offset(a),i=d[0].offsetWidth,j=d[0].offsetHeight),this.replaceArrow(k-e+i,i,"left")}else this.replaceArrow(j-f,j,"top");c&&d.offset(a)},b.prototype.replaceArrow=function(a,b,c){this.arrow().css(c,a?50*(1-a/b)+"%":"")},b.prototype.setContent=function(){var a=this.tip(),b=this.getTitle();a.find(".tooltip-inner")[this.options.html?"html":"text"](b),a.removeClass("fade in top bottom left right")},b.prototype.hide=function(){function b(){"in"!=c.hoverState&&d.detach()}var c=this,d=this.tip(),e=a.Event("hide.bs."+this.type);return this.$element.trigger(e),e.isDefaultPrevented()?void 0:(d.removeClass("in"),a.support.transition&&this.$tip.hasClass("fade")?d.one(a.support.transition.end,b).emulateTransitionEnd(150):b(),this.$element.trigger("hidden.bs."+this.type),this)},b.prototype.fixTitle=function(){var a=this.$element;(a.attr("title")||"string"!=typeof a.attr("data-original-title"))&&a.attr("data-original-title",a.attr("title")||"").attr("title","")},b.prototype.hasContent=function(){return this.getTitle()},b.prototype.getPosition=function(){var b=this.$element[0];return a.extend({},"function"==typeof b.getBoundingClientRect?b.getBoundingClientRect():{width:b.offsetWidth,height:b.offsetHeight},this.$element.offset())},b.prototype.getCalculatedOffset=function(a,b,c,d){return"bottom"==a?{top:b.top+b.height,left:b.left+b.width/2-c/2}:"top"==a?{top:b.top-d,left:b.left+b.width/2-c/2}:"left"==a?{top:b.top+b.height/2-d/2,left:b.left-c}:{top:b.top+b.height/2-d/2,left:b.left+b.width}},b.prototype.getTitle=function(){var a,b=this.$element,c=this.options;return a=b.attr("data-original-title")||("function"==typeof c.title?c.title.call(b[0]):c.title)},b.prototype.tip=function(){return this.$tip=this.$tip||a(this.options.template)},b.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")},b.prototype.validate=function(){this.$element[0].parentNode||(this.hide(),this.$element=null,this.options=null)},b.prototype.enable=function(){this.enabled=!0},b.prototype.disable=function(){this.enabled=!1},b.prototype.toggleEnabled=function(){this.enabled=!this.enabled},b.prototype.toggle=function(b){var c=b?a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs."+this.type):this;c.tip().hasClass("in")?c.leave(c):c.enter(c)},b.prototype.destroy=function(){this.hide().$element.off("."+this.type).removeData("bs."+this.type)};var c=a.fn.tooltip;a.fn.tooltip=function(c){return this.each(function(){var d=a(this),e=d.data("bs.tooltip"),f="object"==typeof c&&c;e||d.data("bs.tooltip",e=new b(this,f)),"string"==typeof c&&e[c]()})},a.fn.tooltip.Constructor=b,a.fn.tooltip.noConflict=function(){return a.fn.tooltip=c,this}}(jQuery),+function(a){"use strict";var b=function(a,b){this.init("popover",a,b)};if(!a.fn.tooltip)throw new Error("Popover requires tooltip.js");b.DEFAULTS=a.extend({},a.fn.tooltip.Constructor.DEFAULTS,{placement:"right",trigger:"click",content:"",template:'<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'}),b.prototype=a.extend({},a.fn.tooltip.Constructor.prototype),b.prototype.constructor=b,b.prototype.getDefaults=function(){return b.DEFAULTS},b.prototype.setContent=function(){var a=this.tip(),b=this.getTitle(),c=this.getContent();a.find(".popover-title")[this.options.html?"html":"text"](b),a.find(".popover-content")[this.options.html?"html":"text"](c),a.removeClass("fade top bottom left right in"),a.find(".popover-title").html()||a.find(".popover-title").hide()},b.prototype.hasContent=function(){return this.getTitle()||this.getContent()},b.prototype.getContent=function(){var a=this.$element,b=this.options;return a.attr("data-content")||("function"==typeof b.content?b.content.call(a[0]):b.content)},b.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".arrow")},b.prototype.tip=function(){return this.$tip||(this.$tip=a(this.options.template)),this.$tip};var c=a.fn.popover;a.fn.popover=function(c){return this.each(function(){var d=a(this),e=d.data("bs.popover"),f="object"==typeof c&&c;e||d.data("bs.popover",e=new b(this,f)),"string"==typeof c&&e[c]()})},a.fn.popover.Constructor=b,a.fn.popover.noConflict=function(){return a.fn.popover=c,this}}(jQuery),+function(a){"use strict";function b(c,d){var e,f=a.proxy(this.process,this);this.$element=a(c).is("body")?a(window):a(c),this.$body=a("body"),this.$scrollElement=this.$element.on("scroll.bs.scroll-spy.data-api",f),this.options=a.extend({},b.DEFAULTS,d),this.selector=(this.options.target||(e=a(c).attr("href"))&&e.replace(/.*(?=#[^\s]+$)/,"")||"")+" .nav li > a",this.offsets=a([]),this.targets=a([]),this.activeTarget=null,this.refresh(),this.process()}b.DEFAULTS={offset:10},b.prototype.refresh=function(){var b=this.$element[0]==window?"offset":"position";this.offsets=a([]),this.targets=a([]);var c=this;this.$body.find(this.selector).map(function(){var d=a(this),e=d.data("target")||d.attr("href"),f=/^#\w/.test(e)&&a(e);return f&&f.length&&[[f[b]().top+(!a.isWindow(c.$scrollElement.get(0))&&c.$scrollElement.scrollTop()),e]]||null}).sort(function(a,b){return a[0]-b[0]}).each(function(){c.offsets.push(this[0]),c.targets.push(this[1])})},b.prototype.process=function(){var a,b=this.$scrollElement.scrollTop()+this.options.offset,c=this.$scrollElement[0].scrollHeight||this.$body[0].scrollHeight,d=c-this.$scrollElement.height(),e=this.offsets,f=this.targets,g=this.activeTarget;if(b>=d)return g!=(a=f.last()[0])&&this.activate(a);for(a=e.length;a--;)g!=f[a]&&b>=e[a]&&(!e[a+1]||b<=e[a+1])&&this.activate(f[a])},b.prototype.activate=function(b){this.activeTarget=b,a(this.selector).parents(".active").removeClass("active");var c=this.selector+'[data-target="'+b+'"],'+this.selector+'[href="'+b+'"]',d=a(c).parents("li").addClass("active");d.parent(".dropdown-menu").length&&(d=d.closest("li.dropdown").addClass("active")),d.trigger("activate.bs.scrollspy")};var c=a.fn.scrollspy;a.fn.scrollspy=function(c){return this.each(function(){var d=a(this),e=d.data("bs.scrollspy"),f="object"==typeof c&&c;e||d.data("bs.scrollspy",e=new b(this,f)),"string"==typeof c&&e[c]()})},a.fn.scrollspy.Constructor=b,a.fn.scrollspy.noConflict=function(){return a.fn.scrollspy=c,this},a(window).on("load",function(){a('[data-spy="scroll"]').each(function(){var b=a(this);b.scrollspy(b.data())})})}(jQuery),+function(a){"use strict";var b=function(b){this.element=a(b)};b.prototype.show=function(){var b=this.element,c=b.closest("ul:not(.dropdown-menu)"),d=b.data("target");if(d||(d=b.attr("href"),d=d&&d.replace(/.*(?=#[^\s]*$)/,"")),!b.parent("li").hasClass("active")){var e=c.find(".active:last a")[0],f=a.Event("show.bs.tab",{relatedTarget:e});if(b.trigger(f),!f.isDefaultPrevented()){var g=a(d);this.activate(b.parent("li"),c),this.activate(g,g.parent(),function(){b.trigger({type:"shown.bs.tab",relatedTarget:e})})}}},b.prototype.activate=function(b,c,d){function e(){f.removeClass("active").find("> .dropdown-menu > .active").removeClass("active"),b.addClass("active"),g?(b[0].offsetWidth,b.addClass("in")):b.removeClass("fade"),b.parent(".dropdown-menu")&&b.closest("li.dropdown").addClass("active"),d&&d()}var f=c.find("> .active"),g=d&&a.support.transition&&f.hasClass("fade");g?f.one(a.support.transition.end,e).emulateTransitionEnd(150):e(),f.removeClass("in")};var c=a.fn.tab;a.fn.tab=function(c){return this.each(function(){var d=a(this),e=d.data("bs.tab");e||d.data("bs.tab",e=new b(this)),"string"==typeof c&&e[c]()})},a.fn.tab.Constructor=b,a.fn.tab.noConflict=function(){return a.fn.tab=c,this},a(document).on("click.bs.tab.data-api",'[data-toggle="tab"], [data-toggle="pill"]',function(b){b.preventDefault(),a(this).tab("show")})}(jQuery),+function(a){"use strict";var b=function(c,d){this.options=a.extend({},b.DEFAULTS,d),this.$window=a(window).on("scroll.bs.affix.data-api",a.proxy(this.checkPosition,this)).on("click.bs.affix.data-api",a.proxy(this.checkPositionWithEventLoop,this)),this.$element=a(c),this.affixed=this.unpin=null,this.checkPosition()};b.RESET="affix affix-top affix-bottom",b.DEFAULTS={offset:0},b.prototype.checkPositionWithEventLoop=function(){setTimeout(a.proxy(this.checkPosition,this),1)},b.prototype.checkPosition=function(){if(this.$element.is(":visible")){var c=a(document).height(),d=this.$window.scrollTop(),e=this.$element.offset(),f=this.options.offset,g=f.top,h=f.bottom;"object"!=typeof f&&(h=g=f),"function"==typeof g&&(g=f.top()),"function"==typeof h&&(h=f.bottom());var i=null!=this.unpin&&d+this.unpin<=e.top?!1:null!=h&&e.top+this.$element.height()>=c-h?"bottom":null!=g&&g>=d?"top":!1;this.affixed!==i&&(this.unpin&&this.$element.css("top",""),this.affixed=i,this.unpin="bottom"==i?e.top-d:null,this.$element.removeClass(b.RESET).addClass("affix"+(i?"-"+i:"")),"bottom"==i&&this.$element.offset({top:document.body.offsetHeight-h-this.$element.height()}))}};var c=a.fn.affix;a.fn.affix=function(c){return this.each(function(){var d=a(this),e=d.data("bs.affix"),f="object"==typeof c&&c;e||d.data("bs.affix",e=new b(this,f)),"string"==typeof c&&e[c]()})},a.fn.affix.Constructor=b,a.fn.affix.noConflict=function(){return a.fn.affix=c,this},a(window).on("load",function(){a('[data-spy="affix"]').each(function(){var b=a(this),c=b.data();c.offset=c.offset||{},c.offsetBottom&&(c.offset.bottom=c.offsetBottom),c.offsetTop&&(c.offset.top=c.offsetTop),b.affix(c)})})}(jQuery);
/**
 * bootbox.js 5.2.0
 *
 * http://bootboxjs.com/license.txt
 */
!function(e,t){'use strict';'function'==typeof define&&define.amd?define(['jquery'],t):'object'==typeof exports?module.exports=t(require('jquery')):e.bootbox=t(e.jQuery)}(this,function t(p,u){'use strict';var r,n,i,l;Object.keys||(Object.keys=(r=Object.prototype.hasOwnProperty,n=!{toString:null}.propertyIsEnumerable('toString'),l=(i=['toString','toLocaleString','valueOf','hasOwnProperty','isPrototypeOf','propertyIsEnumerable','constructor']).length,function(e){if('function'!=typeof e&&('object'!=typeof e||null===e))throw new TypeError('Object.keys called on non-object');var t,o,a=[];for(t in e)r.call(e,t)&&a.push(t);if(n)for(o=0;o<l;o++)r.call(e,i[o])&&a.push(i[o]);return a}));var d={};d.VERSION='5.0.0';var b={},f={dialog:"<div class=\"bootbox modal\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\"><div class=\"modal-dialog\"><div class=\"modal-content\"><div class=\"modal-body\"><div class=\"bootbox-body\"></div></div></div></div></div>",header:"<div class=\"modal-header\"><h5 class=\"modal-title\"></h5></div>",footer:'<div class="modal-footer"></div>',closeButton:'<button type="button" class="bootbox-close-button close" aria-hidden="true">&times;</button>',form:'<form class="bootbox-form"></form>',button:'<button type="button" class="btn"></button>',option:'<option></option>',promptMessage:'<div class="bootbox-prompt-message"></div>',inputs:{text:'<input class="bootbox-input bootbox-input-text form-control" autocomplete="off" type="text" />',textarea:'<textarea class="bootbox-input bootbox-input-textarea form-control"></textarea>',email:'<input class="bootbox-input bootbox-input-email form-control" autocomplete="off" type="email" />',select:'<select class="bootbox-input bootbox-input-select form-control"></select>',checkbox:'<div class="form-check checkbox"><label class="form-check-label"><input class="form-check-input bootbox-input bootbox-input-checkbox" type="checkbox" /></label></div>',radio:'<div class="form-check radio"><label class="form-check-label"><input class="form-check-input bootbox-input bootbox-input-radio" type="radio" name="bootbox-radio" /></label></div>',date:'<input class="bootbox-input bootbox-input-date form-control" autocomplete="off" type="date" />',time:'<input class="bootbox-input bootbox-input-time form-control" autocomplete="off" type="time" />',number:'<input class="bootbox-input bootbox-input-number form-control" autocomplete="off" type="number" />',password:'<input class="bootbox-input bootbox-input-password form-control" autocomplete="off" type="password" />',range:'<input class="bootbox-input bootbox-input-range form-control-range" autocomplete="off" type="range" />'}},m={locale:'en',backdrop:'static',animate:!0,className:null,closeButton:!0,show:!0,container:'body',value:'',inputType:'text',swapButtonOrder:!1,centerVertical:!1,multiple:!1,scrollable:!1};function c(e,t,o){return p.extend(!0,{},e,function(e,t){var o=e.length,a={};if(o<1||2<o)throw new Error('Invalid argument length');return 2===o||'string'==typeof e[0]?(a[t[0]]=e[0],a[t[1]]=e[1]):a=e[0],a}(t,o))}function h(e,t,o,a){var r;a&&a[0]&&(r=a[0].locale||m.locale,(a[0].swapButtonOrder||m.swapButtonOrder)&&(t=t.reverse()));var n,i,l,s={className:'bootbox-'+e,buttons:function(e,t){for(var o={},a=0,r=e.length;a<r;a++){var n=e[a],i=n.toLowerCase(),l=n.toUpperCase();o[i]={label:(s=l,c=t,void 0,p=b[c],p?p[s]:b.en[s])}}var s,c,p;return o}(t,r)};return n=c(s,a,o),l={},v(i=t,function(e,t){l[t]=!0}),v(n.buttons,function(e){if(l[e]===u)throw new Error('button key "'+e+'" is not allowed (options are '+i.join(' ')+')')}),n}function w(e){return Object.keys(e).length}function v(e,o){var a=0;p.each(e,function(e,t){o(e,t,a++)})}function g(e,t,o){e.stopPropagation(),e.preventDefault(),p.isFunction(o)&&!1===o.call(t,e)||t.modal('hide')}function y(e){return/([01][0-9]|2[0-3]):[0-5][0-9]?:[0-5][0-9]/.test(e)}function x(e){return/(\d{4})-(\d{2})-(\d{2})/.test(e)}return d.locales=function(e){return e?b[e]:b},d.addLocale=function(e,o){return p.each(['OK','CANCEL','CONFIRM'],function(e,t){if(!o[t])throw new Error('Please supply a translation for "'+t+'"')}),b[e]={OK:o.OK,CANCEL:o.CANCEL,CONFIRM:o.CONFIRM},d},d.removeLocale=function(e){if('en'===e)throw new Error('"en" is used as the default and fallback locale and cannot be removed.');return delete b[e],d},d.setLocale=function(e){return d.setDefaults('locale',e)},d.setDefaults=function(){var e={};return 2===arguments.length?e[arguments[0]]=arguments[1]:e=arguments[0],p.extend(m,e),d},d.hideAll=function(){return p('.bootbox').modal('hide'),d},d.init=function(e){return t(e||p)},d.dialog=function(e){if(p.fn.modal===u)throw new Error("\"$.fn.modal\" is not defined; please double check you have included the Bootstrap JavaScript library. See http://getbootstrap.com/javascript/ for more details.");if(e=function(r){var n,i;if('object'!=typeof r)throw new Error('Please supply an object of options');if(!r.message)throw new Error('"message" option must not be null or an empty string.');(r=p.extend({},m,r)).buttons||(r.buttons={});return n=r.buttons,i=w(n),v(n,function(e,t,o){if(p.isFunction(t)&&(t=n[e]={callback:t}),'object'!==p.type(t))throw new Error('button with key "'+e+'" must be an object');if(t.label||(t.label=e),!t.className){var a=!1;a=r.swapButtonOrder?0===o:o===i-1,t.className=i<=2&&a?'btn-primary':'btn-secondary btn-default'}}),r}(e),p.fn.modal.Constructor.VERSION){e.fullBootstrapVersion=p.fn.modal.Constructor.VERSION;var t=e.fullBootstrapVersion.indexOf('.');e.bootstrap=e.fullBootstrapVersion.substring(0,t)}else e.bootstrap='2',e.fullBootstrapVersion='2.3.2',console.warn('Bootbox will *mostly* work with Bootstrap 2, but we do not officially support it. Please upgrade, if possible.');var o=p(f.dialog),a=o.find('.modal-dialog'),r=o.find('.modal-body'),n=p(f.header),i=p(f.footer),l=e.buttons,s={onEscape:e.onEscape};if(r.find('.bootbox-body').html(e.message),0<w(e.buttons)&&(v(l,function(e,t){var o=p(f.button);switch(o.data('bb-handler',e),o.addClass(t.className),e){case'ok':case'confirm':o.addClass('bootbox-accept');break;case'cancel':o.addClass('bootbox-cancel')}o.html(t.label),i.append(o),s[e]=t.callback}),r.after(i)),!0===e.animate&&o.addClass('fade'),e.className&&o.addClass(e.className),e.size)switch(e.fullBootstrapVersion.substring(0,3)<'3.1'&&console.warn('"size" requires Bootstrap 3.1.0 or higher. You appear to be using '+e.fullBootstrapVersion+'. Please upgrade to use this option.'),e.size){case'small':case'sm':a.addClass('modal-sm');break;case'large':case'lg':a.addClass('modal-lg');break;case'xl':case'extra-large':e.fullBootstrapVersion.substring(0,3)<'4.2'&&console.warn('Using size "xl"/"extra-large" requires Bootstrap 4.2.0 or higher. You appear to be using '+e.fullBootstrapVersion+'. Please upgrade to use this option.'),a.addClass('modal-xl')}if(e.scrollable&&(e.fullBootstrapVersion.substring(0,3)<'4.3'&&console.warn('Using "scrollable" requires Bootstrap 4.3.0 or higher. You appear to be using '+e.fullBootstrapVersion+'. Please upgrade to use this option.'),a.addClass('modal-dialog-scrollable')),e.title&&(r.before(n),o.find('.modal-title').html(e.title)),e.closeButton){var c=p(f.closeButton);e.title?3<e.bootstrap?o.find('.modal-header').append(c):o.find('.modal-header').prepend(c):c.prependTo(r)}return e.centerVertical&&(e.fullBootstrapVersion<'4.0.0'&&console.warn('"centerVertical" requires Bootstrap 4.0.0-beta.3 or higher. You appear to be using '+e.fullBootstrapVersion+'. Please upgrade to use this option.'),a.addClass('modal-dialog-centered')),o.one('hide.bs.modal',function(e){e.target===this&&(o.off('escape.close.bb'),o.off('click'))}),o.one('hidden.bs.modal',function(e){e.target===this&&o.remove()}),o.one('shown.bs.modal',function(){o.find('.bootbox-accept:first').trigger('focus')}),'static'!==e.backdrop&&o.on('click.dismiss.bs.modal',function(e){o.children('.modal-backdrop').length&&(e.currentTarget=o.children('.modal-backdrop').get(0)),e.target===e.currentTarget&&o.trigger('escape.close.bb')}),o.on('escape.close.bb',function(e){s.onEscape&&g(e,o,s.onEscape)}),o.on('click','.modal-footer button:not(.disabled)',function(e){var t=p(this).data('bb-handler');t!==u&&g(e,o,s[t])}),o.on('click','.bootbox-close-button',function(e){g(e,o,s.onEscape)}),o.on('keyup',function(e){27===e.which&&o.trigger('escape.close.bb')}),p(e.container).append(o),o.modal({backdrop:!!e.backdrop&&'static',keyboard:!1,show:!1}),e.show&&o.modal('show'),o},d.alert=function(){var e;if((e=h('alert',['ok'],['message','callback'],arguments)).callback&&!p.isFunction(e.callback))throw new Error('alert requires the "callback" property to be a function when provided');return e.buttons.ok.callback=e.onEscape=function(){return!p.isFunction(e.callback)||e.callback.call(this)},d.dialog(e)},d.confirm=function(){var e;if(e=h('confirm',['cancel','confirm'],['message','callback'],arguments),!p.isFunction(e.callback))throw new Error('confirm requires a callback');return e.buttons.cancel.callback=e.onEscape=function(){return e.callback.call(this,!1)},e.buttons.confirm.callback=function(){return e.callback.call(this,!0)},d.dialog(e)},d.prompt=function(){var r,t,e,n,o,a;if(e=p(f.form),(r=h('prompt',['cancel','confirm'],['title','callback'],arguments)).value||(r.value=m.value),r.inputType||(r.inputType=m.inputType),o=r.show===u?m.show:r.show,r.show=!1,r.buttons.cancel.callback=r.onEscape=function(){return r.callback.call(this,null)},r.buttons.confirm.callback=function(){var e;if('checkbox'===r.inputType)e=n.find('input:checked').map(function(){return p(this).val()}).get();else if('radio'===r.inputType)e=n.find('input:checked').val();else{if(n[0].checkValidity&&!n[0].checkValidity())return!1;e='select'===r.inputType&&!0===r.multiple?n.find('option:selected').map(function(){return p(this).val()}).get():n.val()}return r.callback.call(this,e)},!r.title)throw new Error('prompt requires a title');if(!p.isFunction(r.callback))throw new Error('prompt requires a callback');if(!f.inputs[r.inputType])throw new Error('Invalid prompt type');switch(n=p(f.inputs[r.inputType]),r.inputType){case'text':case'textarea':case'email':case'password':n.val(r.value),r.placeholder&&n.attr('placeholder',r.placeholder),r.pattern&&n.attr('pattern',r.pattern),r.maxlength&&n.attr('maxlength',r.maxlength),r.required&&n.prop({required:!0}),r.rows&&!isNaN(parseInt(r.rows))&&'textarea'===r.inputType&&n.attr({rows:r.rows});break;case'date':case'time':case'number':case'range':if(n.val(r.value),r.placeholder&&n.attr('placeholder',r.placeholder),r.pattern&&n.attr('pattern',r.pattern),r.required&&n.prop({required:!0}),'date'!==r.inputType&&r.step){if(!('any'===r.step||!isNaN(r.step)&&0<parseInt(r.step)))throw new Error('"step" must be a valid positive number or the value "any". See https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step for more information.');n.attr('step',r.step)}(function(e,t,o){var a=!1,r=!0,n=!0;if('date'===e)t===u||(r=x(t))?o===u||(n=x(o))||console.warn('Browsers which natively support the "date" input type expect date values to be of the form "YYYY-MM-DD" (see ISO-8601 https://www.iso.org/iso-8601-date-and-time-format.html). Bootbox does not enforce this rule, but your max value may not be enforced by this browser.'):console.warn('Browsers which natively support the "date" input type expect date values to be of the form "YYYY-MM-DD" (see ISO-8601 https://www.iso.org/iso-8601-date-and-time-format.html). Bootbox does not enforce this rule, but your min value may not be enforced by this browser.');else if('time'===e){if(t!==u&&!(r=y(t)))throw new Error('"min" is not a valid time. See https://www.w3.org/TR/2012/WD-html-markup-20120315/datatypes.html#form.data.time for more information.');if(o!==u&&!(n=y(o)))throw new Error('"max" is not a valid time. See https://www.w3.org/TR/2012/WD-html-markup-20120315/datatypes.html#form.data.time for more information.')}else{if(t!==u&&isNaN(t))throw new Error('"min" must be a valid number. See https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-min for more information.');if(o!==u&&isNaN(o))throw new Error('"max" must be a valid number. See https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-max for more information.')}if(r&&n){if(o<=t)throw new Error('"max" must be greater than "min". See https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-max for more information.');a=!0}return a})(r.inputType,r.min,r.max)&&(r.min!==u&&n.attr('min',r.min),r.max!==u&&n.attr('max',r.max));break;case'select':var i={};if(a=r.inputOptions||[],!p.isArray(a))throw new Error('Please pass an array of input options');if(!a.length)throw new Error('prompt with "inputType" set to "select" requires at least one option');r.placeholder&&n.attr('placeholder',r.placeholder),r.required&&n.prop({required:!0}),r.multiple&&n.prop({multiple:!0}),v(a,function(e,t){var o=n;if(t.value===u||t.text===u)throw new Error('each option needs a "value" property and a "text" property');t.group&&(i[t.group]||(i[t.group]=p('<optgroup />').attr('label',t.group)),o=i[t.group]);var a=p(f.option);a.attr('value',t.value).text(t.text),o.append(a)}),v(i,function(e,t){n.append(t)}),n.val(r.value);break;case'checkbox':var l=p.isArray(r.value)?r.value:[r.value];if(!(a=r.inputOptions||[]).length)throw new Error('prompt with "inputType" set to "checkbox" requires at least one option');n=p('<div class="bootbox-checkbox-list"></div>'),v(a,function(e,o){if(o.value===u||o.text===u)throw new Error('each option needs a "value" property and a "text" property');var a=p(f.inputs[r.inputType]);a.find('input').attr('value',o.value),a.find('label').append('\n'+o.text),v(l,function(e,t){t===o.value&&a.find('input').prop('checked',!0)}),n.append(a)});break;case'radio':if(r.value!==u&&p.isArray(r.value))throw new Error('prompt with "inputType" set to "radio" requires a single, non-array value for "value"');if(!(a=r.inputOptions||[]).length)throw new Error('prompt with "inputType" set to "radio" requires at least one option');n=p('<div class="bootbox-radiobutton-list"></div>');var s=!0;v(a,function(e,t){if(t.value===u||t.text===u)throw new Error('each option needs a "value" property and a "text" property');var o=p(f.inputs[r.inputType]);o.find('input').attr('value',t.value),o.find('label').append('\n'+t.text),r.value!==u&&t.value===r.value&&(o.find('input').prop('checked',!0),s=!1),n.append(o)}),s&&n.find('input[type="radio"]').first().prop('checked',!0)}if(e.append(n),e.on('submit',function(e){e.preventDefault(),e.stopPropagation(),t.find('.bootbox-accept').trigger('click')}),''!==p.trim(r.message)){var c=p(f.promptMessage).html(r.message);e.prepend(c),r.message=e}else r.message=e;return(t=d.dialog(r)).off('shown.bs.modal'),t.on('shown.bs.modal',function(){n.focus()}),!0===o&&t.modal('show'),t},d.addLocale('en',{OK:'OK',CANCEL:'Cancel',CONFIRM:'OK'}),d});
$(function(){$("#skip-navigation, .skip-link").on("focus",function(){$(this).removeClass("sr-only")}).on("blur",function(){$(this).addClass("sr-only")}).on("click",function(){var id=$(this).attr("href");$(id).focus()});$("#main-content").attr("tabindex","-1");$("#utils").attr("role","navigation");$("#utils").attr("aria-label","Utility");$("#utils>span").each(function(){if($(this).text()=="|"||$(this).text()=="/"||$(this).html()==""){$(this).attr("aria-hidden",true);$(this).attr("tabindex","-1")}});$("#utils .fa").attr("aria-hidden",true);$("#nav").attr("aria-label","Primary");$("#nav-wrapper .navbar-static-top").removeAttr("role");$(".modal").attr("aria-modal","true");var both_sidebars=$(".sidebar").length>1;$(".sidebar").attr("role","region");$("#left-sidebar").attr("aria-label",both_sidebars?sidebar_primary_label:sidebar_label);$("#right-sidebar").attr("aria-label",both_sidebars?sidebar_secondary_label:sidebar_label);$(".widget-nav-menu").attr("role","navigation");$(".widget-nav-menu").attr("aria-label",secondary_label);if($(".widget-slider").length){$(".widget-slider .slider-next .slider-control").attr("aria-label",slider_next_label);$(".widget-slider .slider-prev .slider-control").attr("aria-label",slider_prev_label)}$(".sidebar>.widget").each(function(idx,obj){var $this=$(obj);var $first_header=$this.find(".widget-title").first();var has_header=$first_header.length>0;if(has_header){var _class=$this.attr("class");_class=_class.replace(/.*widget/i,"");var id="widget"+$.trim(_class.replace(/widget/i,""))+"-title";$first_header.attr("id",id);$(this).attr("aria-labelledby",id)}});var bookdetail_photo_count=$(".bookdetail-photo img").length;$(".bookdetail-photo img").each(function(idx,obj){var real_idx=idx+1;var $link=$(obj).parent();if(typeof $link.attr("href")==="undefined"){$link=$link.find("a").first();var title=$link.attr("title")}else{var title=$link.attr("title")}$(obj).attr("alt",sprintf(gallery_view_image_alt,real_idx,bookdetail_photo_count,title));$link.attr("aria-label",sprintf(gallery_view_image_aria,real_idx,bookdetail_photo_count,title))})});$(window).on("load",function(){$("#g-recaptcha-response").attr("aria-label",google_recaptcha_response);$("#a2apage_find").attr("aria-label",$("#a2apage_find").attr("title"));$("#a2a_copy_link_text").attr("aria-label",$("#a2a_copy_link_text").attr("title"));$("#a2apage_find").removeAttr("title");$("#a2a_copy_link_text").removeAttr("title");$(".a2a_s_yoolink").parent().remove();$("#a2apage_show_more_less .a2a_localize").text(show_all_label)});
/*! Magnific Popup - v1.1.1 - 2016-02-20
* http://dimsemenov.com/plugins/magnific-popup/
* Copyright (c) 2016 Dmitry Semenov;
*
* Updated 02/20/2019 - Added accesibility features to close button, mfp-wrap, and body elements when popup is open
* Updated 02/26/2019 - Fixed event bubbling on span inside of close button. _checkIfClose now checks if parent has class mfp-close.
*
* */
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?e(require("jquery")):e(window.jQuery||window.Zepto)}(function(e){var t,i,n,a,o,r,s=function(){},l=!!window.jQuery,c=e(window),d=function(e,i){t.ev.on("mfp"+e+".mfp",i)},p=function(t,i,n,a){var o=document.createElement("div");return o.className="mfp-"+t,n&&(o.innerHTML=n),a?i&&i.appendChild(o):(o=e(o),i&&o.appendTo(i)),o},u=function(i,n){t.ev.triggerHandler("mfp"+i,n),t.st.callbacks&&(i=i.charAt(0).toLowerCase()+i.slice(1),t.st.callbacks[i]&&t.st.callbacks[i].apply(t,e.isArray(n)?n:[n]))},f=function(i){return i===r&&t.currTemplate.closeBtn||(t.currTemplate.closeBtn=e(t.st.closeMarkup.replace(/%title%/g,t.st.tClose)),r=i),t.currTemplate.closeBtn},m=function(){e.magnificPopup.instance||((t=new s).init(),e.magnificPopup.instance=t)};s.prototype={constructor:s,init:function(){var i=navigator.appVersion;t.isLowIE=t.isIE8=document.all&&!document.addEventListener,t.isAndroid=/android/gi.test(i),t.isIOS=/iphone|ipad|ipod/gi.test(i),t.supportsTransition=function(){var e=document.createElement("p").style,t=["ms","O","Moz","Webkit"];if(void 0!==e.transition)return!0;for(;t.length;)if(t.pop()+"Transition"in e)return!0;return!1}(),t.probablyMobile=t.isAndroid||t.isIOS||/(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent),n=e(document),t.popupsCache={}},open:function(i){var a;if(!1===i.isObj){t.items=i.items.toArray(),t.index=0;var r,s=i.items;for(a=0;a<s.length;a++)if((r=s[a]).parsed&&(r=r.el[0]),r===i.el[0]){t.index=a;break}}else t.items=e.isArray(i.items)?i.items:[i.items],t.index=i.index||0;if(!t.isOpen){t.types=[],o="",i.mainEl&&i.mainEl.length?t.ev=i.mainEl.eq(0):t.ev=n,i.key?(t.popupsCache[i.key]||(t.popupsCache[i.key]={}),t.currTemplate=t.popupsCache[i.key]):t.currTemplate={},t.st=e.extend(!0,{},e.magnificPopup.defaults,i),t.fixedContentPos="auto"===t.st.fixedContentPos?!t.probablyMobile:t.st.fixedContentPos,t.st.modal&&(t.st.closeOnContentClick=!1,t.st.closeOnBgClick=!1,t.st.showCloseBtn=!1,t.st.enableEscapeKey=!1),t.bgOverlay||(t.bgOverlay=p("bg").on("click.mfp",function(){t.close()}),t.wrap=p("wrap").attr("tabindex",-1).attr("role","dialog").on("click.mfp",function(e){t._checkIfClose(e.target)&&t.close()}),t.container=p("container",t.wrap)),t.contentContainer=p("content"),t.st.preloader&&(t.preloader=p("preloader",t.container,t.st.tLoading));var l=e.magnificPopup.modules;for(a=0;a<l.length;a++){var m=l[a];m=m.charAt(0).toUpperCase()+m.slice(1),t["init"+m].call(t)}u("BeforeOpen"),t.st.showCloseBtn&&(t.st.closeBtnInside?(d("MarkupParse",function(e,t,i,n){i.close_replaceWith=f(n.type)}),o+=" mfp-close-btn-in"):t.wrap.append(f())),t.st.alignTop&&(o+=" mfp-align-top"),t.fixedContentPos?t.wrap.css({overflow:t.st.overflowY,overflowX:"hidden",overflowY:t.st.overflowY}):t.wrap.css({top:c.scrollTop(),position:"absolute"}),(!1===t.st.fixedBgPos||"auto"===t.st.fixedBgPos&&!t.fixedContentPos)&&t.bgOverlay.css({height:n.height(),position:"absolute"}),t.st.enableEscapeKey&&n.on("keyup.mfp",function(e){27===e.keyCode&&t.close()}),c.on("resize.mfp",function(){t.updateSize()}),t.st.closeOnContentClick||(o+=" mfp-auto-cursor"),o&&t.wrap.addClass(o);var g=t.wH=c.height(),h={};if(t.fixedContentPos&&t._hasScrollBar(g)){var v=t._getScrollbarSize();v&&(h.marginRight=v)}t.fixedContentPos&&(t.isIE7?e("body, html").css("overflow","hidden"):h.overflow="hidden");var C=t.st.mainClass;return t.isIE7&&(C+=" mfp-ie7"),C&&t._addClassToMFP(C),t.updateItemHTML(),u("BuildControls"),e("html").css(h),t._ariaHiddenElements=e("body").children(),e(t._ariaHiddenElements).each(function(){e(this).attr("data-aria-hidden",e(this).attr("aria-hidden")),e(this).attr("aria-hidden","true")}),t.bgOverlay.add(t.wrap).prependTo(t.st.prependTo||e(document.body)),t._lastFocusedEl=document.activeElement,setTimeout(function(){t.content?(t._addClassToMFP("mfp-ready"),t._setFocus()):t.bgOverlay.addClass("mfp-ready"),n.on("focusin.mfp",t._onFocusIn)},16),t.isOpen=!0,t.updateSize(g),u("Open"),i}t.updateItemHTML()},close:function(){t.isOpen&&(u("BeforeClose"),t.isOpen=!1,t.st.removalDelay&&!t.isLowIE&&t.supportsTransition?(t._addClassToMFP("mfp-removing"),setTimeout(function(){t._close()},t.st.removalDelay)):t._close())},_close:function(){u("Close");var i="mfp-removing mfp-ready ";if(t.bgOverlay.detach(),t.wrap.detach(),t.container.empty(),t.st.mainClass&&(i+=t.st.mainClass+" "),t._removeClassFromMFP(i),t.fixedContentPos){var a={marginRight:""};t.isIE7?e("body, html").css("overflow",""):a.overflow="",e("html").css(a)}n.off("keyup.mfp focusin.mfp"),t.ev.off(".mfp"),t.wrap.attr("class","mfp-wrap").removeAttr("style"),t.bgOverlay.attr("class","mfp-bg"),t.container.attr("class","mfp-container"),e(t._ariaHiddenElements).each(function(){void 0===e(this).attr("data-aria-hidden")?e(this).removeAttr("aria-hidden"):e(this).attr("aria-hidden",e(this).attr("data-aria-hidden")),e(this).removeAttr("data-aria-hidden")}),t._ariaHiddenElements=[],!t.st.showCloseBtn||t.st.closeBtnInside&&!0!==t.currTemplate[t.currItem.type]||t.currTemplate.closeBtn&&t.currTemplate.closeBtn.detach(),t.st.autoFocusLast&&t._lastFocusedEl&&e(t._lastFocusedEl).focus(),t.currItem=null,t.content=null,t.currTemplate=null,t.prevHeight=0,u("AfterClose")},updateSize:function(e){if(t.isIOS){var i=document.documentElement.clientWidth/window.innerWidth,n=window.innerHeight*i;t.wrap.css("height",n),t.wH=n}else t.wH=e||c.height();t.fixedContentPos||t.wrap.css("height",t.wH),u("Resize")},updateItemHTML:function(){var i=t.items[t.index];t.contentContainer.detach(),t.content&&t.content.detach(),i.parsed||(i=t.parseEl(t.index));var n=i.type;if(u("BeforeChange",[t.currItem?t.currItem.type:"",n]),t.currItem=i,!t.currTemplate[n]){var o=!!t.st[n]&&t.st[n].markup;u("FirstMarkupParse",o),t.currTemplate[n]=!o||e(o)}a&&a!==i.type&&t.container.removeClass("mfp-"+a+"-holder");var r=t["get"+n.charAt(0).toUpperCase()+n.slice(1)](i,t.currTemplate[n]);t.appendContent(r,n),i.preloaded=!0,u("Change",i),a=i.type,t.container.prepend(t.contentContainer),u("AfterChange")},appendContent:function(e,i){t.content=e,e?t.st.showCloseBtn&&t.st.closeBtnInside&&!0===t.currTemplate[i]?t.content.find(".mfp-close").length||t.content.append(f()):t.content=e:t.content="",u("BeforeAppend"),t.container.addClass("mfp-"+i+"-holder"),t.contentContainer.append(t.content)},parseEl:function(i){var n,a=t.items[i];if(a.tagName?a={el:e(a)}:(n=a.type,a={data:a,src:a.src}),a.el){for(var o=t.types,r=0;r<o.length;r++)if(a.el.hasClass("mfp-"+o[r])){n=o[r];break}a.src=a.el.attr("data-mfp-src"),a.src||(a.src=a.el.attr("href"))}return a.type=n||t.st.type||"inline",a.index=i,a.parsed=!0,t.items[i]=a,u("ElementParse",a),t.items[i]},addGroup:function(e,i){var n=function(n){n.mfpEl=this,t._openClick(n,e,i)};i||(i={});var a="click.magnificPopup";i.mainEl=e,i.items?(i.isObj=!0,e.off(a).on(a,n)):(i.isObj=!1,i.delegate?e.off(a).on(a,i.delegate,n):(i.items=e,e.off(a).on(a,n)))},_openClick:function(i,n,a){if((void 0!==a.midClick?a.midClick:e.magnificPopup.defaults.midClick)||!(2===i.which||i.ctrlKey||i.metaKey||i.altKey||i.shiftKey)){var o=void 0!==a.disableOn?a.disableOn:e.magnificPopup.defaults.disableOn;if(o)if(e.isFunction(o)){if(!o.call(t))return!0}else if(c.width()<o)return!0;i.type&&(i.preventDefault(),t.isOpen&&i.stopPropagation()),a.el=e(i.mfpEl),a.delegate&&(a.items=n.find(a.delegate)),t.open(a)}},updateStatus:function(e,n){if(t.preloader){i!==e&&t.container.removeClass("mfp-s-"+i),n||"loading"!==e||(n=t.st.tLoading);var a={status:e,text:n};u("UpdateStatus",a),e=a.status,n=a.text,t.preloader.html(n),t.preloader.find("a").on("click",function(e){e.stopImmediatePropagation()}),t.container.addClass("mfp-s-"+e),i=e}},_checkIfClose:function(i){if(!e(i).hasClass("mfp-prevent-close")){var n=t.st.closeOnContentClick,a=t.st.closeOnBgClick;if(n&&a)return!0;if(!t.content||e(i).hasClass("mfp-close")||e(i).parent().hasClass("mfp-close")||t.preloader&&i===t.preloader[0])return!0;if(i===t.content[0]||e.contains(t.content[0],i)){if(n)return!0}else if(a&&e.contains(document,i))return!0;return!1}},_addClassToMFP:function(e){t.bgOverlay.addClass(e),t.wrap.addClass(e)},_removeClassFromMFP:function(e){this.bgOverlay.removeClass(e),t.wrap.removeClass(e)},_hasScrollBar:function(e){return(t.isIE7?n.height():document.body.scrollHeight)>(e||c.height())},_setFocus:function(){(t.st.focus?t.content.find(t.st.focus).eq(0):t.wrap).focus()},_onFocusIn:function(i){if(i.target!==t.wrap[0]&&!e.contains(t.wrap[0],i.target))return t._setFocus(),!1},_parseMarkup:function(t,i,n){var a;n.data&&(i=e.extend(n.data,i)),u("MarkupParse",[t,i,n]),e.each(i,function(i,n){if(void 0===n||!1===n)return!0;if((a=i.split("_")).length>1){var o=t.find(".mfp-"+a[0]);if(o.length>0){var r=a[1];"replaceWith"===r?o[0]!==n[0]&&o.replaceWith(n):"img"===r?o.is("img")?o.attr("src",n):o.replaceWith(e("<img>").attr("src",n).attr("class",o.attr("class"))):o.attr(a[1],n)}}else t.find(".mfp-"+i).html(n)})},_getScrollbarSize:function(){if(void 0===t.scrollbarSize){var e=document.createElement("div");e.style.cssText="width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;",document.body.appendChild(e),t.scrollbarSize=e.offsetWidth-e.clientWidth,document.body.removeChild(e)}return t.scrollbarSize}},e.magnificPopup={instance:null,proto:s.prototype,modules:[],open:function(t,i){return m(),(t=t?e.extend(!0,{},t):{}).isObj=!0,t.index=i||0,this.instance.open(t)},close:function(){return e.magnificPopup.instance&&e.magnificPopup.instance.close()},registerModule:function(t,i){i.options&&(e.magnificPopup.defaults[t]=i.options),e.extend(this.proto,i.proto),this.modules.push(t)},defaults:{disableOn:0,key:null,midClick:!1,mainClass:"",preloader:!0,focus:"",closeOnContentClick:!1,closeOnBgClick:!0,closeBtnInside:!0,showCloseBtn:!0,enableEscapeKey:!0,modal:!1,alignTop:!1,removalDelay:0,prependTo:null,fixedContentPos:"auto",fixedBgPos:"auto",overflowY:"auto",closeMarkup:'<button title="%title%" aria-label="%title%" type="button" class="mfp-close"><span aria-hidden="true">&#215;</span></button>',tClose:"Close (Esc)",tLoading:"Loading...",autoFocusLast:!0}},e.fn.magnificPopup=function(i){m();var n=e(this);if("string"==typeof i)if("open"===i){var a,o=l?n.data("magnificPopup"):n[0].magnificPopup,r=parseInt(arguments[1],10)||0;o.items?a=o.items[r]:(a=n,o.delegate&&(a=a.find(o.delegate)),a=a.eq(r)),t._openClick({mfpEl:a},n,o)}else t.isOpen&&t[i].apply(t,Array.prototype.slice.call(arguments,1));else i=e.extend(!0,{},i),l?n.data("magnificPopup",i):n[0].magnificPopup=i,t.addGroup(n,i);return n};var g,h,v,C=function(){v&&(h.after(v.addClass(g)).detach(),v=null)};e.magnificPopup.registerModule("inline",{options:{hiddenClass:"hide",markup:"",tNotFound:"Content not found"},proto:{initInline:function(){t.types.push("inline"),d("Close.inline",function(){C()})},getInline:function(i,n){if(C(),i.src){var a=t.st.inline,o=e(i.src);if(o.length){var r=o[0].parentNode;r&&r.tagName&&(h||(g=a.hiddenClass,h=p(g),g="mfp-"+g),v=o.after(h).detach().removeClass(g)),t.updateStatus("ready")}else t.updateStatus("error",a.tNotFound),o=e("<div>");return i.inlineElement=o,o}return t.updateStatus("ready"),t._parseMarkup(n,{},i),n}}});var y,w=function(){y&&e(document.body).removeClass(y)},b=function(){w(),t.req&&t.req.abort()};e.magnificPopup.registerModule("ajax",{options:{settings:null,cursor:"mfp-ajax-cur",tError:'<a href="%url%">The content</a> could not be loaded.'},proto:{initAjax:function(){t.types.push("ajax"),y=t.st.ajax.cursor,d("Close.ajax",b),d("BeforeChange.ajax",b)},getAjax:function(i){y&&e(document.body).addClass(y),t.updateStatus("loading");var n=e.extend({url:i.src,success:function(n,a,o){var r={data:n,xhr:o};u("ParseAjax",r),t.appendContent(e(r.data),"ajax"),i.finished=!0,w(),t._setFocus(),setTimeout(function(){t.wrap.addClass("mfp-ready")},16),t.updateStatus("ready"),u("AjaxContentAdded")},error:function(){w(),i.finished=i.loadError=!0,t.updateStatus("error",t.st.ajax.tError.replace("%url%",i.src))}},t.st.ajax.settings);return t.req=e.ajax(n),""}}});var I;e.magnificPopup.registerModule("image",{options:{markup:'<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',cursor:"mfp-zoom-out-cur",titleSrc:"title",verticalFit:!0,tError:'<a href="%url%">The image</a> could not be loaded.'},proto:{initImage:function(){var i=t.st.image,n=".image";t.types.push("image"),d("Open"+n,function(){"image"===t.currItem.type&&i.cursor&&e(document.body).addClass(i.cursor)}),d("Close"+n,function(){i.cursor&&e(document.body).removeClass(i.cursor),c.off("resize.mfp")}),d("Resize"+n,t.resizeImage),t.isLowIE&&d("AfterChange",t.resizeImage)},resizeImage:function(){var e=t.currItem;if(e&&e.img&&t.st.image.verticalFit){var i=0;t.isLowIE&&(i=parseInt(e.img.css("padding-top"),10)+parseInt(e.img.css("padding-bottom"),10)),e.img.css("max-height",t.wH-i)}},_onImageHasSize:function(e){e.img&&(e.hasSize=!0,I&&clearInterval(I),e.isCheckingImgSize=!1,u("ImageHasSize",e),e.imgHidden&&(t.content&&t.content.removeClass("mfp-loading"),e.imgHidden=!1))},findImageSize:function(e){var i=0,n=e.img[0],a=function(o){I&&clearInterval(I),I=setInterval(function(){n.naturalWidth>0?t._onImageHasSize(e):(i>200&&clearInterval(I),3===++i?a(10):40===i?a(50):100===i&&a(500))},o)};a(1)},getImage:function(i,n){var a=0,o=function(){i&&(i.img[0].complete?(i.img.off(".mfploader"),i===t.currItem&&(t._onImageHasSize(i),t.updateStatus("ready")),i.hasSize=!0,i.loaded=!0,u("ImageLoadComplete")):++a<200?setTimeout(o,100):r())},r=function(){i&&(i.img.off(".mfploader"),i===t.currItem&&(t._onImageHasSize(i),t.updateStatus("error",s.tError.replace("%url%",i.src))),i.hasSize=!0,i.loaded=!0,i.loadError=!0)},s=t.st.image,l=n.find(".mfp-img");if(l.length){var c=document.createElement("img");c.className="mfp-img",i.el&&i.el.find("img").length&&(c.alt=i.el.find("img").attr("alt")),i.img=e(c).on("load.mfploader",o).on("error.mfploader",r),c.src=i.src,l.is("img")&&(i.img=i.img.clone()),(c=i.img[0]).naturalWidth>0?i.hasSize=!0:c.width||(i.hasSize=!1)}return t._parseMarkup(n,{title:function(i){if(i.data&&void 0!==i.data.title)return i.data.title;var n=t.st.image.titleSrc;if(n){if(e.isFunction(n))return n.call(t,i);if(i.el)return i.el.attr(n)||""}return""}(i),img_replaceWith:i.img},i),t.resizeImage(),i.hasSize?(I&&clearInterval(I),i.loadError?(n.addClass("mfp-loading"),t.updateStatus("error",s.tError.replace("%url%",i.src))):(n.removeClass("mfp-loading"),t.updateStatus("ready")),n):(t.updateStatus("loading"),i.loading=!0,i.hasSize||(i.imgHidden=!0,n.addClass("mfp-loading"),t.findImageSize(i)),n)}}});var x;e.magnificPopup.registerModule("zoom",{options:{enabled:!1,easing:"ease-in-out",duration:300,opener:function(e){return e.is("img")?e:e.find("img")}},proto:{initZoom:function(){var e,i=t.st.zoom,n=".zoom";if(i.enabled&&t.supportsTransition){var a,o,r=i.duration,s=function(e){var t=e.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"),n="all "+i.duration/1e3+"s "+i.easing,a={position:"fixed",zIndex:9999,left:0,top:0,"-webkit-backface-visibility":"hidden"},o="transition";return a["-webkit-"+o]=a["-moz-"+o]=a["-o-"+o]=a[o]=n,t.css(a),t},l=function(){t.content.css("visibility","visible")};d("BuildControls"+n,function(){if(t._allowZoom()){if(clearTimeout(a),t.content.css("visibility","hidden"),!(e=t._getItemToZoom()))return void l();(o=s(e)).css(t._getOffset()),t.wrap.append(o),a=setTimeout(function(){o.css(t._getOffset(!0)),a=setTimeout(function(){l(),setTimeout(function(){o.remove(),e=o=null,u("ZoomAnimationEnded")},16)},r)},16)}}),d("BeforeClose"+n,function(){if(t._allowZoom()){if(clearTimeout(a),t.st.removalDelay=r,!e){if(!(e=t._getItemToZoom()))return;o=s(e)}o.css(t._getOffset(!0)),t.wrap.append(o),t.content.css("visibility","hidden"),setTimeout(function(){o.css(t._getOffset())},16)}}),d("Close"+n,function(){t._allowZoom()&&(l(),o&&o.remove(),e=null)})}},_allowZoom:function(){return"image"===t.currItem.type},_getItemToZoom:function(){return!!t.currItem.hasSize&&t.currItem.img},_getOffset:function(i){var n,a=(n=i?t.currItem.img:t.st.zoom.opener(t.currItem.el||t.currItem)).offset(),o=parseInt(n.css("padding-top"),10),r=parseInt(n.css("padding-bottom"),10);a.top-=e(window).scrollTop()-o;var s={width:n.width(),height:(l?n.innerHeight():n[0].offsetHeight)-r-o};return void 0===x&&(x=void 0!==document.createElement("p").style.MozTransform),x?s["-moz-transform"]=s.transform="translate("+a.left+"px,"+a.top+"px)":(s.left=a.left,s.top=a.top),s}}});var k=function(e){if(t.currTemplate.iframe){var i=t.currTemplate.iframe.find("iframe");i.length&&(e||(i[0].src="//about:blank"),t.isIE8&&i.css("display",e?"block":"none"))}};e.magnificPopup.registerModule("iframe",{options:{markup:'<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe></div>',srcAction:"iframe_src",patterns:{youtube:{index:"youtube.com",id:"v=",src:"//www.youtube.com/embed/%id%?autoplay=1"},vimeo:{index:"vimeo.com/",id:"/",src:"//player.vimeo.com/video/%id%?autoplay=1"},gmaps:{index:"//maps.google.",src:"%id%&output=embed"}}},proto:{initIframe:function(){t.types.push("iframe"),d("BeforeChange",function(e,t,i){t!==i&&("iframe"===t?k():"iframe"===i&&k(!0))}),d("Close.iframe",function(){k()})},getIframe:function(i,n){var a=i.src,o=t.st.iframe;e.each(o.patterns,function(){if(a.indexOf(this.index)>-1)return this.id&&(a="string"==typeof this.id?a.substr(a.lastIndexOf(this.id)+this.id.length,a.length):this.id.call(this,a)),a=this.src.replace("%id%",a),!1});var r={};return o.srcAction&&(r[o.srcAction]=a),t._parseMarkup(n,r,i),t.updateStatus("ready"),n}}});var T=function(e){var i=t.items.length;return e>i-1?e-i:e<0?i+e:e},_=function(e,t,i){return e.replace(/%curr%/gi,t+1).replace(/%total%/gi,i)};e.magnificPopup.registerModule("gallery",{options:{enabled:!1,arrowMarkup:'<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',preload:[0,2],navigateByImgClick:!0,arrows:!0,tPrev:"Previous (Left arrow key)",tNext:"Next (Right arrow key)",tCounter:"%curr% of %total%"},proto:{initGallery:function(){var i=t.st.gallery,a=".mfp-gallery";if(t.direction=!0,!i||!i.enabled)return!1;o+=" mfp-gallery",d("Open"+a,function(){i.navigateByImgClick&&t.wrap.on("click"+a,".mfp-img",function(){if(t.items.length>1)return t.next(),!1}),n.on("keydown"+a,function(e){37===e.keyCode?t.prev():39===e.keyCode&&t.next()})}),d("UpdateStatus"+a,function(e,i){i.text&&(i.text=_(i.text,t.currItem.index,t.items.length))}),d("MarkupParse"+a,function(e,n,a,o){var r=t.items.length;a.counter=r>1?_(i.tCounter,o.index,r):""}),d("BuildControls"+a,function(){if(t.items.length>1&&i.arrows&&!t.arrowLeft){var n=i.arrowMarkup,a=t.arrowLeft=e(n.replace(/%title%/gi,i.tPrev).replace(/%dir%/gi,"left")).addClass("mfp-prevent-close"),o=t.arrowRight=e(n.replace(/%title%/gi,i.tNext).replace(/%dir%/gi,"right")).addClass("mfp-prevent-close");a.click(function(){t.prev()}),o.click(function(){t.next()}),t.container.append(a.add(o))}}),d("Change"+a,function(){t._preloadTimeout&&clearTimeout(t._preloadTimeout),t._preloadTimeout=setTimeout(function(){t.preloadNearbyImages(),t._preloadTimeout=null},16)}),d("Close"+a,function(){n.off(a),t.wrap.off("click"+a),t.arrowRight=t.arrowLeft=null})},next:function(){t.direction=!0,t.index=T(t.index+1),t.updateItemHTML()},prev:function(){t.direction=!1,t.index=T(t.index-1),t.updateItemHTML()},goTo:function(e){t.direction=e>=t.index,t.index=e,t.updateItemHTML()},preloadNearbyImages:function(){var e,i=t.st.gallery.preload,n=Math.min(i[0],t.items.length),a=Math.min(i[1],t.items.length);for(e=1;e<=(t.direction?a:n);e++)t._preloadItem(t.index+e);for(e=1;e<=(t.direction?n:a);e++)t._preloadItem(t.index-e)},_preloadItem:function(i){if(i=T(i),!t.items[i].preloaded){var n=t.items[i];n.parsed||(n=t.parseEl(i)),u("LazyLoad",n),"image"===n.type&&(n.img=e('<img class="mfp-img" />').on("load.mfploader",function(){n.hasSize=!0}).on("error.mfploader",function(){n.hasSize=!0,n.loadError=!0,u("LazyLoadError",n)}).attr("src",n.src)),n.preloaded=!0}}}});e.magnificPopup.registerModule("retina",{options:{replaceSrc:function(e){return e.src.replace(/\.\w+$/,function(e){return"@2x"+e})},ratio:1},proto:{initRetina:function(){if(window.devicePixelRatio>1){var e=t.st.retina,i=e.ratio;(i=isNaN(i)?i():i)>1&&(d("ImageHasSize.retina",function(e,t){t.img.css({"max-width":t.img[0].naturalWidth/i,width:"100%"})}),d("ElementParse.retina",function(t,n){n.src=e.replaceSrc(n,i)}))}}}}),m()});
(function(a){if(typeof define==="function"&&define.amd&&define.amd.jQuery){define(["jquery"],a)}else{a(jQuery)}}(function(e){var o="left",n="right",d="up",v="down",c="in",w="out",l="none",r="auto",k="swipe",s="pinch",x="tap",i="doubletap",b="longtap",A="horizontal",t="vertical",h="all",q=10,f="start",j="move",g="end",p="cancel",a="ontouchstart" in window,y="TouchSwipe";var m={fingers:1,threshold:75,cancelThreshold:null,pinchThreshold:20,maxTimeThreshold:null,fingerReleaseThreshold:250,longTapThreshold:500,doubleTapThreshold:200,swipe:null,swipeLeft:null,swipeRight:null,swipeUp:null,swipeDown:null,swipeStatus:null,pinchIn:null,pinchOut:null,pinchStatus:null,click:null,tap:null,doubleTap:null,longTap:null,triggerOnTouchEnd:true,triggerOnTouchLeave:false,allowPageScroll:"auto",fallbackToMouseEvents:true,excludedElements:"label, button, input, select, textarea, a, .noSwipe"};e.fn.swipe=function(D){var C=e(this),B=C.data(y);if(B&&typeof D==="string"){if(B[D]){return B[D].apply(this,Array.prototype.slice.call(arguments,1))}else{e.error("Method "+D+" does not exist on jQuery.swipe")}}else{if(!B&&(typeof D==="object"||!D)){return u.apply(this,arguments)}}return C};e.fn.swipe.defaults=m;e.fn.swipe.phases={PHASE_START:f,PHASE_MOVE:j,PHASE_END:g,PHASE_CANCEL:p};e.fn.swipe.directions={LEFT:o,RIGHT:n,UP:d,DOWN:v,IN:c,OUT:w};e.fn.swipe.pageScroll={NONE:l,HORIZONTAL:A,VERTICAL:t,AUTO:r};e.fn.swipe.fingers={ONE:1,TWO:2,THREE:3,ALL:h};function u(B){if(B&&(B.allowPageScroll===undefined&&(B.swipe!==undefined||B.swipeStatus!==undefined))){B.allowPageScroll=l}if(B.click!==undefined&&B.tap===undefined){B.tap=B.click}if(!B){B={}}B=e.extend({},e.fn.swipe.defaults,B);return this.each(function(){var D=e(this);var C=D.data(y);if(!C){C=new z(this,B);D.data(y,C)}})}function z(a0,aq){var av=(a||!aq.fallbackToMouseEvents),G=av?"touchstart":"mousedown",au=av?"touchmove":"mousemove",R=av?"touchend":"mouseup",P=av?null:"mouseleave",az="touchcancel";var ac=0,aL=null,Y=0,aX=0,aV=0,D=1,am=0,aF=0,J=null;var aN=e(a0);var W="start";var T=0;var aM=null;var Q=0,aY=0,a1=0,aa=0,K=0;var aS=null;try{aN.bind(G,aJ);aN.bind(az,a5)}catch(ag){e.error("events not supported "+G+","+az+" on jQuery.swipe")}this.enable=function(){aN.bind(G,aJ);aN.bind(az,a5);return aN};this.disable=function(){aG();return aN};this.destroy=function(){aG();aN.data(y,null);return aN};this.option=function(a8,a7){if(aq[a8]!==undefined){if(a7===undefined){return aq[a8]}else{aq[a8]=a7}}else{e.error("Option "+a8+" does not exist on jQuery.swipe.options")}return null};function aJ(a9){if(ax()){return}if(e(a9.target).closest(aq.excludedElements,aN).length>0){return}var ba=a9.originalEvent?a9.originalEvent:a9;var a8,a7=a?ba.touches[0]:ba;W=f;if(a){T=ba.touches.length}else{a9.preventDefault()}ac=0;aL=null;aF=null;Y=0;aX=0;aV=0;D=1;am=0;aM=af();J=X();O();if(!a||(T===aq.fingers||aq.fingers===h)||aT()){ae(0,a7);Q=ao();if(T==2){ae(1,ba.touches[1]);aX=aV=ap(aM[0].start,aM[1].start)}if(aq.swipeStatus||aq.pinchStatus){a8=L(ba,W)}}else{a8=false}if(a8===false){W=p;L(ba,W);return a8}else{ak(true)}return null}function aZ(ba){var bd=ba.originalEvent?ba.originalEvent:ba;if(W===g||W===p||ai()){return}var a9,a8=a?bd.touches[0]:bd;var bb=aD(a8);aY=ao();if(a){T=bd.touches.length}W=j;if(T==2){if(aX==0){ae(1,bd.touches[1]);aX=aV=ap(aM[0].start,aM[1].start)}else{aD(bd.touches[1]);aV=ap(aM[0].end,aM[1].end);aF=an(aM[0].end,aM[1].end)}D=a3(aX,aV);am=Math.abs(aX-aV)}if((T===aq.fingers||aq.fingers===h)||!a||aT()){aL=aH(bb.start,bb.end);ah(ba,aL);ac=aO(bb.start,bb.end);Y=aI();aE(aL,ac);if(aq.swipeStatus||aq.pinchStatus){a9=L(bd,W)}if(!aq.triggerOnTouchEnd||aq.triggerOnTouchLeave){var a7=true;if(aq.triggerOnTouchLeave){var bc=aU(this);a7=B(bb.end,bc)}if(!aq.triggerOnTouchEnd&&a7){W=ay(j)}else{if(aq.triggerOnTouchLeave&&!a7){W=ay(g)}}if(W==p||W==g){L(bd,W)}}}else{W=p;L(bd,W)}if(a9===false){W=p;L(bd,W)}}function I(a7){var a8=a7.originalEvent;if(a){if(a8.touches.length>0){C();return true}}if(ai()){T=aa}a7.preventDefault();aY=ao();Y=aI();if(a6()){W=p;L(a8,W)}else{if(aq.triggerOnTouchEnd||(aq.triggerOnTouchEnd==false&&W===j)){W=g;L(a8,W)}else{if(!aq.triggerOnTouchEnd&&a2()){W=g;aB(a8,W,x)}else{if(W===j){W=p;L(a8,W)}}}}ak(false);return null}function a5(){T=0;aY=0;Q=0;aX=0;aV=0;D=1;O();ak(false)}function H(a7){var a8=a7.originalEvent;if(aq.triggerOnTouchLeave){W=ay(g);L(a8,W)}}function aG(){aN.unbind(G,aJ);aN.unbind(az,a5);aN.unbind(au,aZ);aN.unbind(R,I);if(P){aN.unbind(P,H)}ak(false)}function ay(bb){var ba=bb;var a9=aw();var a8=aj();var a7=a6();if(!a9||a7){ba=p}else{if(a8&&bb==j&&(!aq.triggerOnTouchEnd||aq.triggerOnTouchLeave)){ba=g}else{if(!a8&&bb==g&&aq.triggerOnTouchLeave){ba=p}}}return ba}function L(a9,a7){var a8=undefined;if(F()||S()){a8=aB(a9,a7,k)}else{if((M()||aT())&&a8!==false){a8=aB(a9,a7,s)}}if(aC()&&a8!==false){a8=aB(a9,a7,i)}else{if(al()&&a8!==false){a8=aB(a9,a7,b)}else{if(ad()&&a8!==false){a8=aB(a9,a7,x)}}}if(a7===p){a5(a9)}if(a7===g){if(a){if(a9.touches.length==0){a5(a9)}}else{a5(a9)}}return a8}function aB(ba,a7,a9){var a8=undefined;if(a9==k){aN.trigger("swipeStatus",[a7,aL||null,ac||0,Y||0,T]);if(aq.swipeStatus){a8=aq.swipeStatus.call(aN,ba,a7,aL||null,ac||0,Y||0,T);if(a8===false){return false}}if(a7==g&&aR()){aN.trigger("swipe",[aL,ac,Y,T]);if(aq.swipe){a8=aq.swipe.call(aN,ba,aL,ac,Y,T);if(a8===false){return false}}switch(aL){case o:aN.trigger("swipeLeft",[aL,ac,Y,T]);if(aq.swipeLeft){a8=aq.swipeLeft.call(aN,ba,aL,ac,Y,T)}break;case n:aN.trigger("swipeRight",[aL,ac,Y,T]);if(aq.swipeRight){a8=aq.swipeRight.call(aN,ba,aL,ac,Y,T)}break;case d:aN.trigger("swipeUp",[aL,ac,Y,T]);if(aq.swipeUp){a8=aq.swipeUp.call(aN,ba,aL,ac,Y,T)}break;case v:aN.trigger("swipeDown",[aL,ac,Y,T]);if(aq.swipeDown){a8=aq.swipeDown.call(aN,ba,aL,ac,Y,T)}break}}}if(a9==s){aN.trigger("pinchStatus",[a7,aF||null,am||0,Y||0,T,D]);if(aq.pinchStatus){a8=aq.pinchStatus.call(aN,ba,a7,aF||null,am||0,Y||0,T,D);if(a8===false){return false}}if(a7==g&&a4()){switch(aF){case c:aN.trigger("pinchIn",[aF||null,am||0,Y||0,T,D]);if(aq.pinchIn){a8=aq.pinchIn.call(aN,ba,aF||null,am||0,Y||0,T,D)}break;case w:aN.trigger("pinchOut",[aF||null,am||0,Y||0,T,D]);if(aq.pinchOut){a8=aq.pinchOut.call(aN,ba,aF||null,am||0,Y||0,T,D)}break}}}if(a9==x){if(a7===p||a7===g){clearTimeout(aS);if(V()&&!E()){K=ao();aS=setTimeout(e.proxy(function(){K=null;aN.trigger("tap",[ba.target]);if(aq.tap){a8=aq.tap.call(aN,ba,ba.target)}},this),aq.doubleTapThreshold)}else{K=null;aN.trigger("tap",[ba.target]);if(aq.tap){a8=aq.tap.call(aN,ba,ba.target)}}}}else{if(a9==i){if(a7===p||a7===g){clearTimeout(aS);K=null;aN.trigger("doubletap",[ba.target]);if(aq.doubleTap){a8=aq.doubleTap.call(aN,ba,ba.target)}}}else{if(a9==b){if(a7===p||a7===g){clearTimeout(aS);K=null;aN.trigger("longtap",[ba.target]);if(aq.longTap){a8=aq.longTap.call(aN,ba,ba.target)}}}}}return a8}function aj(){var a7=true;if(aq.threshold!==null){a7=ac>=aq.threshold}return a7}function a6(){var a7=false;if(aq.cancelThreshold!==null&&aL!==null){a7=(aP(aL)-ac)>=aq.cancelThreshold}return a7}function ab(){if(aq.pinchThreshold!==null){return am>=aq.pinchThreshold}return true}function aw(){var a7;if(aq.maxTimeThreshold){if(Y>=aq.maxTimeThreshold){a7=false}else{a7=true}}else{a7=true}return a7}function ah(a7,a8){if(aq.allowPageScroll===l||aT()){a7.preventDefault()}else{var a9=aq.allowPageScroll===r;switch(a8){case o:if((aq.swipeLeft&&a9)||(!a9&&aq.allowPageScroll!=A)){a7.preventDefault()}break;case n:if((aq.swipeRight&&a9)||(!a9&&aq.allowPageScroll!=A)){a7.preventDefault()}break;case d:if((aq.swipeUp&&a9)||(!a9&&aq.allowPageScroll!=t)){a7.preventDefault()}break;case v:if((aq.swipeDown&&a9)||(!a9&&aq.allowPageScroll!=t)){a7.preventDefault()}break}}}function a4(){var a8=aK();var a7=U();var a9=ab();return a8&&a7&&a9}function aT(){return !!(aq.pinchStatus||aq.pinchIn||aq.pinchOut)}function M(){return !!(a4()&&aT())}function aR(){var ba=aw();var bc=aj();var a9=aK();var a7=U();var a8=a6();var bb=!a8&&a7&&a9&&bc&&ba;return bb}function S(){return !!(aq.swipe||aq.swipeStatus||aq.swipeLeft||aq.swipeRight||aq.swipeUp||aq.swipeDown)}function F(){return !!(aR()&&S())}function aK(){return((T===aq.fingers||aq.fingers===h)||!a)}function U(){return aM[0].end.x!==0}function a2(){return !!(aq.tap)}function V(){return !!(aq.doubleTap)}function aQ(){return !!(aq.longTap)}function N(){if(K==null){return false}var a7=ao();return(V()&&((a7-K)<=aq.doubleTapThreshold))}function E(){return N()}function at(){return((T===1||!a)&&(isNaN(ac)||ac===0))}function aW(){return((Y>aq.longTapThreshold)&&(ac<q))}function ad(){return !!(at()&&a2())}function aC(){return !!(N()&&V())}function al(){return !!(aW()&&aQ())}function C(){a1=ao();aa=event.touches.length+1}function O(){a1=0;aa=0}function ai(){var a7=false;if(a1){var a8=ao()-a1;if(a8<=aq.fingerReleaseThreshold){a7=true}}return a7}function ax(){return !!(aN.data(y+"_intouch")===true)}function ak(a7){if(a7===true){aN.bind(au,aZ);aN.bind(R,I);if(P){aN.bind(P,H)}}else{aN.unbind(au,aZ,false);aN.unbind(R,I,false);if(P){aN.unbind(P,H,false)}}aN.data(y+"_intouch",a7===true)}function ae(a8,a7){var a9=a7.identifier!==undefined?a7.identifier:0;aM[a8].identifier=a9;aM[a8].start.x=aM[a8].end.x=a7.pageX||a7.clientX;aM[a8].start.y=aM[a8].end.y=a7.pageY||a7.clientY;return aM[a8]}function aD(a7){var a9=a7.identifier!==undefined?a7.identifier:0;var a8=Z(a9);a8.end.x=a7.pageX||a7.clientX;a8.end.y=a7.pageY||a7.clientY;return a8}function Z(a8){for(var a7=0;a7<aM.length;a7++){if(aM[a7].identifier==a8){return aM[a7]}}}function af(){var a7=[];for(var a8=0;a8<=5;a8++){a7.push({start:{x:0,y:0},end:{x:0,y:0},identifier:0})}return a7}function aE(a7,a8){a8=Math.max(a8,aP(a7));J[a7].distance=a8}function aP(a7){if(J[a7]){return J[a7].distance}return undefined}function X(){var a7={};a7[o]=ar(o);a7[n]=ar(n);a7[d]=ar(d);a7[v]=ar(v);return a7}function ar(a7){return{direction:a7,distance:0}}function aI(){return aY-Q}function ap(ba,a9){var a8=Math.abs(ba.x-a9.x);var a7=Math.abs(ba.y-a9.y);return Math.round(Math.sqrt(a8*a8+a7*a7))}function a3(a7,a8){var a9=(a8/a7)*1;return a9.toFixed(2)}function an(){if(D<1){return w}else{return c}}function aO(a8,a7){return Math.round(Math.sqrt(Math.pow(a7.x-a8.x,2)+Math.pow(a7.y-a8.y,2)))}function aA(ba,a8){var a7=ba.x-a8.x;var bc=a8.y-ba.y;var a9=Math.atan2(bc,a7);var bb=Math.round(a9*180/Math.PI);if(bb<0){bb=360-Math.abs(bb)}return bb}function aH(a8,a7){var a9=aA(a8,a7);if((a9<=45)&&(a9>=0)){return o}else{if((a9<=360)&&(a9>=315)){return o}else{if((a9>=135)&&(a9<=225)){return n}else{if((a9>45)&&(a9<135)){return v}else{return d}}}}}function ao(){var a7=new Date();return a7.getTime()}function aU(a7){a7=e(a7);var a9=a7.offset();var a8={left:a9.left,right:a9.left+a7.outerWidth(),top:a9.top,bottom:a9.top+a7.outerHeight()};return a8}function B(a7,a8){return(a7.x>a8.left&&a7.x<a8.right&&a7.y>a8.top&&a7.y<a8.bottom)}}}));
/*
 * jQuery throttle / debounce - v1.1 - 3/7/2010
 * http://benalman.com/projects/jquery-throttle-debounce-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function(b,c){var $=b.jQuery||b.Cowboy||(b.Cowboy={}),a;$.throttle=a=function(e,f,j,i){var h,d=0;if(typeof f!=="boolean"){i=j;j=f;f=c}function g(){var o=this,m=+new Date()-d,n=arguments;function l(){d=+new Date();j.apply(o,n)}function k(){h=c}if(i&&!h){l()}h&&clearTimeout(h);if(i===c&&m>e){l()}else{if(f!==true){h=setTimeout(i?k:l,i===c?e-m:e)}}}if($.guid){g.guid=j.guid=j.guid||$.guid++}return g};$.debounce=function(d,e,f){return f===c?a(d,e,false):a(d,f,e!==false)}})(this);
/*	
 * jQuery mmenu v4.1.3
 * @requires jQuery 1.7.0 or later
 *
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 * www.frebsite.nl
 *
 * Dual licensed under the MIT and GPL licenses.
 * http://en.wikipedia.org/wiki/MIT_License
 * http://en.wikipedia.org/wiki/GNU_General_Public_License
 * Updated: 2013-12-?? Added bootstrap_compat boolean to strip conflicting bootstrap classes from cloned menu
 * Updated: 2014-01-13 Added bootstrap_classes option for adding class names to menu such as visible-xs
 * Updated: 2014-01-23 Added ability to change subtitle_text  (the back link at the top of the submenu)
 * Updated: 2014-01-31 Mika added 'open' class so links and arrow both open subnav
 * Updated: 2014-01-31 Mika added subnav locking using 'active' class, which can be added to an <li> during setup
 * Updated: 2014-02-20 Mat added 'subnavLockingEnable' boolean option to disable subnav locking where required.
 * Updated: 2019-02-06 Matt added acessibility attributes (ARIA) to certain elements
 * Updated: 2019-04-08 Matt added acessibility attributes (ARIA) to submenu indicators and cloned for tags for sidebar menus
 */


(function( $ ) {

	var _PLUGIN_	= 'mmenu',
		_VERSION_	= '4.1.3';


	//	Plugin already excists
	if ( $[ _PLUGIN_ ] )
	{
		return;
	}

	//	Global variables
	var glbl = {
		$wndw: null,
		$html: null,
		$body: null,
		$page: null,
		$blck: null,
		$original_menu: null,
		$allMenus: null,
		$scrollTopNode: null
	};

	var _c = {}, _e = {}, _d = {},
		_serialnr = 0;


	$[ _PLUGIN_ ] = function( $menu, opts, conf )
	{
		glbl.$allMenus = glbl.$allMenus.add( $menu );

		this.$menu = $menu;
		this.opts  = opts;
		this.conf  = conf;

		this.serialnr = _serialnr++;

		this._init();

		return this;
	};

	$[ _PLUGIN_ ].prototype = {

		open: function()
		{
			this._openSetup();
			this._openFinish();
			return 'open';
		},
		_openSetup: function()
		{
			//	Find scrolltop
			var _scrollTop = findScrollTop();

			//	Set opened
			this.$menu.addClass( _c.current );

			//	Close others
			glbl.$allMenus.not( this.$menu ).trigger( _e.close );

			//	Store style and position
			glbl.$page
				.data( _d.style, glbl.$page.attr( 'style' ) || '' )
				.data( _d.scrollTop, _scrollTop )
				.data( _d.offetLeft, glbl.$page.offset().left );

			//	Resize page to window width
			var _w = 0;
			glbl.$wndw
				.off( _e.resize )
				.on( _e.resize,
					function( e, force )
					{
						if ( force || glbl.$html.hasClass( _c.opened ) )
						{
							var nw = glbl.$wndw.width();
							if ( nw != _w )
							{
								_w = nw;
								glbl.$page.width( nw - glbl.$page.data( _d.offetLeft ) );
							}
						}
					}
				)
				.trigger( _e.resize, [ true ] );

			//	Prevent tabbing out of the menu
			if ( this.conf.preventTabbing )
			{
				glbl.$wndw
					.off( _e.keydown )
					.on( _e.keydown,
						function( e )
						{
							if ( e.keyCode == 9 )
							{
								e.preventDefault();
								return false;
							}
						}
					);
			}

			//	Add options
			if ( this.opts.modal )
			{
				glbl.$html.addClass( _c.modal );
			}
			if ( this.opts.moveBackground )
			{
				glbl.$html.addClass( _c.background );
			}
			if ( this.opts.position != 'left' )
			{
				glbl.$html.addClass( _c.mm( this.opts.position ) );
			}
			if ( this.opts.position == 'relative' )
			{
				glbl.$html.addClass( _c.relative );
			}
			if ( this.opts.zposition != 'back' )
			{
				glbl.$html.addClass( _c.mm( this.opts.zposition ) );
			}
			if ( this.opts.classes )
			{
				glbl.$html.addClass( this.opts.classes );
			}

			//	Open
			glbl.$html.addClass( _c.opened );
			this.$menu.addClass( _c.opened );

			//	Scroll page to scrolltop
			glbl.$page.scrollTop( _scrollTop );

			//	Scroll menu to top
			this.$menu.scrollTop( 0 );
		},
		_openFinish: function()
		{
			var that = this;

			//	Callback
			transitionend( glbl.$page,
				function()
				{
					that.$menu.trigger( _e.opened );
				}, this.conf.transitionDuration
			);

			//	Opening
			glbl.$html.addClass( _c.opening );
			this.$menu.trigger( _e.opening );

			//	Scroll window to top
			window.scrollTo( 0, 1 );

			// Reset Padding
			if ( that.opts.position == 'relative' )
			{
				$original_menu.parent().css("margin-bottom", '0px' );
				//	Open menu
				var id = this.$menu.attr( 'id' );
				if ( id && id.length )
				{
					if ( this.conf.clone )
					{
						id = _c.umm( id );
					}
					if ( this.conf.bootstrap_classes )
					{
						this.$menu.addClass(this.conf.bootstrap_classes);
					}

					$('a[href="#' + id + '"]')
						.off( _e.click )
						.on( _e.click,
							function( e )
							{
								e.preventDefault();
								that.$menu.trigger( _e.close );
							}
						);
				}
			}

			/**
			 * Accessibility Features
			 *
			 **/
				// Set aria-expanded to true
			var target_id = this.$menu.attr( 'id' ).replace('mm-', '');
			$('a[href="#' + target_id + '"]').attr('aria-expanded', true);

			//focus on the first navlink in the expanded menu
			this.$menu.find('ul:first>li:first-child>a').focus();
		},
		close: function()
		{
			var that = this;



			//	Callback
			transitionend( glbl.$page,
				function()
				{
					that.$menu
						.removeClass( _c.current )
						.removeClass( _c.opened );

					glbl.$html
						.removeClass( _c.opened )
						.removeClass( _c.modal )
						.removeClass( _c.background )
						.removeClass( _c.mm( that.opts.position ) )
						.removeClass( _c.mm( that.opts.zposition ) );

					if ( that.opts.classes )
					{
						glbl.$html.removeClass( that.opts.classes );
					}

					glbl.$wndw
						.off( _e.resize )
						.off( _e.keydown );

					//	Restore style and position
					glbl.$page.attr( 'style', glbl.$page.data( _d.style ) );

					if ( glbl.$scrollTopNode )
					{
						glbl.$scrollTopNode.scrollTop( glbl.$page.data( _d.scrollTop ) );
					}

					//	Closed
					that.$menu.trigger( _e.closed );
					if ( that.opts.position == 'relative' )
					{
						$original_menu.parent().css("margin-bottom", that.$menu.css( 'margin-bottom') );
						//	Open menu
						var id = that.$menu.attr( 'id' );
						if ( id && id.length ) {
							if ( that.conf.clone )
							{
								id = _c.umm( id );
							}

							$('a[href="#' + id + '"]')
								.off( _e.click )
								.on( _e.click,
									function( e )
									{
										e.preventDefault();
										that.$menu.trigger( _e.open );
									}
								);
						}
					}
				}, that.conf.transitionDuration
			);

			//	Closing

			glbl.$html.removeClass( _c.opening );
			this.$menu.trigger( _e.closing );

			/**
			 * Accessibility Features
			 *
			 **/
				// 	Set focus back to nav-toggle
			var target_id = this.$menu.attr( 'id' ).replace('mm-', '');
			var $target = $('a[href="#' + target_id + '"]');
			// Set aria-expanded to true
			$target.attr('aria-expanded', false);
			//focus on the first navlink in the expanded menu
			$target.focus();

			return 'close';
		},

		_init: function()
		{
			this.opts = extendOptions( this.opts, this.conf, this.$menu );
			this.direction = ( this.opts.slidingSubmenus ) ? 'horizontal' : 'vertical';

			//	INIT PAGE & MENU
			this._initPage( glbl.$page );
			this._initMenu();
			if ( this.opts.position != 'relative' ) { this._initBlocker(); }
			this._initPanles();
			this._initLinks();
			this._initOpenClose();
			this._bindCustomEvents();


			if ( $[ _PLUGIN_ ].addons )
			{
				for ( var a = 0; a < $[ _PLUGIN_ ].addons.length; a++ )
				{
					if ( typeof this[ '_addon_' + $[ _PLUGIN_ ].addons[ a ] ] == 'function' )
					{
						this[ '_addon_' + $[ _PLUGIN_ ].addons[ a ] ]();
					}
				}
			}
		},

		_bindCustomEvents: function()
		{
			var that = this;

			this.$menu
				.off( _e.open + ' ' + _e.close + ' ' + _e.setPage+ ' ' + _e.update )
				.on( _e.open + ' ' + _e.close + ' ' + _e.setPage+ ' ' + _e.update,
					function( e )
					{
						e.stopPropagation();
					}
				);

			//	Menu-events
			this.$menu
				.on( _e.open,
					function( e )
					{
						if ( $(this).hasClass( _c.current ) )
						{
							e.stopImmediatePropagation();
							return false;
						}
						return that.open();
					}
				)
				.on( _e.close,
					function( e )
					{
						if ( !$(this).hasClass( _c.current ) )
						{
							e.stopImmediatePropagation();
							return false;
						}
						return that.close();
					}
				)
				.on( _e.setPage,
					function( e, $p )
					{
						that._initPage( $p );
						that._initOpenClose();
					}
				);

			//	Panel-events
			var $panels = this.$menu.find( this.opts.isMenu && this.direction != 'horizontal' ? 'ul, ol' : '.' + _c.panel );
			$panels
				.off( _e.toggle + ' ' + _e.open + ' ' + _e.close )
				.on( _e.toggle + ' ' + _e.open + ' ' + _e.close,
					function( e )
					{
						e.stopPropagation();
					}
				);

			if ( this.direction == 'horizontal' )
			{
				$panels
					.on( _e.open,
						function( e )
						{
							return openSubmenuHorizontal( $(this), that.$menu );
						}
					);
			}
			else
			{
				$panels
					.on( _e.toggle,
						function( e )
						{
							var $t = $(this);
							return $t.triggerHandler( $t.parent().hasClass( _c.opened ) ? _e.close : _e.open );
						}
					)
					.on( _e.open,
						function( e )
						{
							$(this).parent().addClass( _c.opened );
							return 'open';
						}
					)
					.on( _e.close,
						function( e )
						{
							$(this).parent().removeClass( _c.opened );
							return 'close';
						}
					);
			}
		},

		_initBlocker: function()
		{
			var that = this;

			if ( !glbl.$blck )
			{
				glbl.$blck = $( '<div id="' + _c.blocker + '" />' ).appendTo( glbl.$body );
			}

			glbl.$blck
				.off( _e.touchstart )
				.on( _e.touchstart,
					function( e )
					{
						e.preventDefault();
						e.stopPropagation();
						glbl.$blck.trigger( _e.mousedown );
					}
				)
				.on( _e.mousedown,
					function( e )
					{
						e.preventDefault();
						if ( !glbl.$html.hasClass( _c.modal ) )
						{
							that.$menu.trigger( _e.close );
						}
					}
				);
		},
		_initPage: function( $p )
		{
			if ( !$p )
			{
				$p = $(this.conf.pageSelector, glbl.$body);
				if ( $p.length > 1 )
				{
					$[ _PLUGIN_ ].debug( 'Multiple nodes found for the page-node, all nodes are wrapped in one <' + this.conf.pageNodetype + '>.' );
					$p = $p.wrapAll( '<' + this.conf.pageNodetype + ' />' ).parent();
				}
			}

			$p.addClass( _c.page );
			glbl.$page = $p;
		},
		_initMenu: function()
		{
			var that = this;

			//	Clone if needed
			if ( this.conf.clone )
			{
				$original_menu = this.$menu;
				this.$menu = this.$menu.clone( true );
				this.$menu.add( this.$menu.find( '*' ) ).filter( '[id]' ).each(
					function()
					{
						$(this).attr( 'id', _c.mm( $(this).attr( 'id' ) ) );

						// updated by matt 4/8/19 for ADA compliance
						// allows the for attribute to be cloned to sidebar mobile menus
						$(this).attr( 'for', _c.mm( $(this).attr( 'for' ) ) );
					}
				);
			}

			// Strip conflicting bootstrap nav classes

			if ( this.conf.bootstrap_compat )
			{

				var bs_classes = 'nav navbar navbar-nav navbar-default navbar-static-top dropdown-menu collapse navbar-collapse navbar-form navbar-right hidden-sm hidden-md hidden-lg';
				this.$menu.removeClass(bs_classes);

				this.$menu.find("*").each(
					function()
					{
						$(this).removeClass(bs_classes);
						if( $(this).hasClass('caret') ) {
							$(this).remove();
						}

					}
				);
			}

			//	Strip whitespace
			this.$menu.contents().each(
				function() {
					if ( $(this)[ 0 ].nodeType == 3 )
					{
						$(this).remove();
					}
				}
			);

			//	Prepend to body
			if ( this.opts.position == 'relative' )
			{
				this.$menu.addClass( _c.menu );
				(this.opts.positionAfter) ?  $(this.opts.positionAfter).after(this.$menu) : original_object.after(this.$menu);

				// Set bottom padding of menu to match previous sibling padding/margin

				this.$menu.css("margin-bottom", $original_menu.parent().css( 'margin-bottom') );

			} else {
				this.$menu
					.prependTo( 'body' )
					.addClass( _c.menu );
			}

			//	Add direction class
			this.$menu.addClass( _c.mm( this.direction ) );

			//	Add options classes
			if ( this.opts.classes )
			{
				this.$menu.addClass( this.opts.classes );
			}
			if ( this.opts.isMenu )
			{
				this.$menu.addClass( _c.ismenu );
			}
			if ( this.opts.position != 'left' )
			{
				this.$menu.addClass( _c.mm( this.opts.position ) );
			}
			if ( this.opts.zposition != 'back' )
			{
				this.$menu.addClass( _c.mm( this.opts.zposition ) );
			}

			// set menu
			//this.$menu
		},
		_initPanles: function()
		{
			var that = this;


			//	Refactor List class
			this.__refactorClass( $('.' + this.conf.listClass, this.$menu), 'list' );

			//	Add List class
			if ( this.opts.isMenu )
			{
				$('ul, ol', this.$menu)
					.not( '.mm-nolist' )
					.addClass( _c.list );
			}

			var $lis = $('.' + _c.list + ' > li', this.$menu);

			//	Refactor Selected class
			this.__refactorClass( $lis.filter( '.' + this.conf.selectedClass ), 'selected' );

			//	Refactor Label class
			this.__refactorClass( $lis.filter( '.' + this.conf.labelClass ), 'label' );

			//	Refactor Spacer class
			this.__refactorClass( $lis.filter( '.' + this.conf.spacerClass ), 'spacer' );

			//	setSelected-event
			$lis
				.off( _e.setSelected )
				.on( _e.setSelected,
					function( e, selected )
					{
						e.stopPropagation();

						$lis.removeClass( _c.selected );
						if ( typeof selected != 'boolean' )
						{
							selected = true;
						}
						if ( selected )
						{
							$(this).addClass( _c.selected );
						}
					}
				);

			//	Refactor Panel class
			this.__refactorClass( $('.' + this.conf.panelClass, this.$menu), 'panel' );

			//	Add Panel class
			this.$menu
				.children()
				.filter( this.conf.panelNodetype )
				.add( this.$menu.find( '.' + _c.list ).children().children().filter( this.conf.panelNodetype ) )
				.addClass( _c.panel );

			var $panels = $('.' + _c.panel, this.$menu);

			//	Add an ID to all panels
			$panels
				.each(
					function( i )
					{
						var $t = $(this),
							id = $t.attr( 'id' ) || _c.mm( 'm' + that.serialnr + '-p' + i );

						$t.attr( 'id', id );
					}
				);

			//	Add open and close links to menu items  mm-panel
			$panels
				.find( '.' + _c.panel )
				.each(
					function( i )
					{
						var $t = $(this),
							$u = $t.is( 'ul, ol' ) ? $t : $t.find( 'ul ,ol' ).first(),
							$l = $t.parent(),
							$a = $l.find( '> a, > span' ),
							$p = $l.closest( '.' + _c.panel );

						$t.data( _d.parent, $l );

						if ( $l.parent().is( '.' + _c.list ) )
						{
							// change by mika 1/31/14
							// updated by matt 4/8/19 for ADA compliance
							$a.addClass(_c.open).attr( 'href', '#'+$t.attr( 'id' ) ).attr('aria-label', 'Submenu Open');
							var $btn = $( '<a class="' + _c.subopen + '" href="#' + $t.attr( 'id' ) + '" aria-label="Submenu Open" />' ).insertBefore( $a );

							if ( !$a.is( 'a' ) )
							{
								$btn.addClass( _c.fullsubopen );
							}

							if ( that.direction == 'horizontal' )
							{
								$u.prepend( '<li class="' + _c.subtitle + '"><a class="' + _c.subclose + '" href="#' + $p.attr( 'id' ) + '">' + (that.opts.subtitle_text != '' ? that.opts.subtitle_text : $a.text()) + '</a></li>' );

							}
						}
					}
				);

			//	Link anchors to panels
			var evt = this.direction == 'horizontal' ? _e.open : _e.toggle;
			$panels
				.each(
					function( i )
					{
						var $opening = $(this),
							id = $opening.attr( 'id' );

						$('a[href="#' + id + '"]', that.$menu)
							.off( _e.click )
							.on( _e.click,
								function( e )
								{
									e.preventDefault();
									$opening.trigger( evt );
								}
							);
					}
				);

			if ( this.direction == 'horizontal' )
			{
				//	Add opened-classes
				var $selected = $('.' + _c.list + ' > li.' + _c.selected, this.$menu);
				$selected
					.add( $selected.parents( 'li' ) )
					.parents( 'li' ).removeClass( _c.selected )
					.end().each(
					function()
					{
						var $t = $(this),
							$u = $t.find( '> .' + _c.panel );

						if ( $u.length )
						{
							$t.parents( '.' + _c.panel ).addClass( _c.subopened );
							$u.addClass( _c.opened );
						}
					}
				)
					.closest( '.' + _c.panel ).addClass( _c.opened )
					.parents( '.' + _c.panel ).addClass( _c.subopened );
			}
			else
			{
				//	Replace Selected-class with opened-class in parents from .Selected
				$('li.' + _c.selected, this.$menu)
					.addClass( _c.opened )
					.parents( '.' + _c.selected ).removeClass( _c.selected );
			}

			//	Set current opened
			var $current = $panels.filter( '.' + _c.opened );
			if ( !$current.length )
			{
				// test for active li element
				if(this.conf.subnavLockingEnable) {
					$current = $panels.find('li.active:last').first().parent();
				}
				if ( !$current.length )
				{
					$current = $panels.first();
				}
			}
			$current
				.addClass( _c.opened )
				.last()
				.addClass( _c.current );

			//	Rearrange markup
			if ( this.direction == 'horizontal' )
			{
				$panels.find( '.' + _c.panel ).appendTo( this.$menu );
			}
		},
		_initLinks: function()
		{
			var that = this;

			$('.' + _c.list + ' > li > a', this.$menu)
				.not( '.' + _c.open )
				.not( '.' + _c.subopen )
				.not( '.' + _c.subclose )
				.not( '[rel="external"]' )
				.not( '[target="_blank"]' )
				.off( _e.click )
				.on( _e.click,
					function( e )
					{
						var $t = $(this),
							href = $t.attr( 'href' );

						//	Set selected item
						if ( that.__valueOrFn( that.opts.onClick.setSelected, $t ) )
						{
							$t.parent().trigger( _e.setSelected );
						}

						//	Prevent default / don't follow link. Default: false
						var preventDefault = that.__valueOrFn( that.opts.onClick.preventDefault, $t, href.slice( 0, 1 ) == '#' );
						if ( preventDefault )
						{
							e.preventDefault();
						}

						//	Block UI. Default: false if preventDefault, true otherwise
						if ( that.__valueOrFn( that.opts.onClick.blockUI, $t, !preventDefault ) )
						{
							glbl.$html.addClass( _c.blocking );
						}

						//	Close menu. Default: true if preventDefault, false otherwise
						if ( that.__valueOrFn( that.opts.onClick.close, $t, preventDefault ) )
						{
							that.$menu.triggerHandler( _e.close );
						}
					}
				);
		},
		_initOpenClose: function()
		{
			var that = this;

			//	Open menu
			var id = this.$menu.attr( 'id' );
			if ( id && id.length )
			{
				if ( this.conf.clone )
				{
					id = _c.umm( id );
				}

				$('a[href="#' + id + '"]')
					.off( _e.click )
					.on( _e.click,
						function( e )
						{
							e.preventDefault();
							that.$menu.trigger( _e.open );
						}
					);
			}

			//	Close menu

			var id = glbl.$page.attr( 'id' );
			if ( id && id.length )
			{
				$('a[href="#' + id + '"]')
					.off( _e.click )
					.on( _e.click,
						function( e )
						{
							e.preventDefault();
							that.$menu.trigger( _e.close );
						}
					);
			}
		},

		__valueOrFn: function( o, $e, d )
		{
			if ( typeof o == 'function' )
			{
				return o.call( $e[ 0 ] );
			}
			if ( typeof o == 'undefined' && typeof d != 'undefined' )
			{
				return d;
			}
			return o;
		},

		__refactorClass: function( $e, c )
		{
			$e.removeClass( this.conf[ c + 'Class' ] ).addClass( _c[ c ] );
		}
	};


	$.fn[ _PLUGIN_ ] = function( opts, conf )
	{
		//	First time plugin is fired
		if ( !glbl.$wndw )
		{
			_initPlugin();
		}

		//	Extend options
		opts = extendOptions( opts, conf );
		conf = extendConfiguration( conf );

		return this.each(
			function()
			{
				var $menu = $(this);
				if ( $menu.data( _PLUGIN_ ) )
				{
					return;
				}
				$menu.data( _PLUGIN_, new $[ _PLUGIN_ ]( $menu, opts, conf ) );
			}
		);
	};

	$[ _PLUGIN_ ].version = _VERSION_;

	$[ _PLUGIN_ ].defaults = {
		position		: 'left',
		positionAfter   : '',
		subtitle_text: '',
		zposition		: 'back',
		moveBackground	: true,
		slidingSubmenus	: true,
		modal			: false,
		classes			: '',
		onClick			: {
//			close				: true,
//			blockUI				: null,
//			preventDefault		: null,
			setSelected			: true
		}
	};
	$[ _PLUGIN_ ].configuration = {
		preventTabbing		: true,
		panelClass			: 'Panel',
		listClass			: 'List',
		selectedClass		: 'Selected',
		labelClass			: 'Label',
		spacerClass			: 'Spacer',
		pageNodetype		: 'div',
		panelNodetype		: 'ul, ol, div',
		transitionDuration	: 400,
		subnavLockingEnable	: true
	};



	/*
		SUPPORT
	*/
	(function() {

		var wd = window.document,
			ua = window.navigator.userAgent;

		var _touch 				= 'ontouchstart' in wd,
			_overflowscrolling	= 'WebkitOverflowScrolling' in wd.documentElement.style,
			_transition			= (function() {
				var s = document.createElement( 'div' ).style;
				if ( 'webkitTransition' in s )
				{
					return 'webkitTransition';
				}
				return 'transition' in s;
			})(),
			_oldAndroidBrowser	= (function() {
				if ( ua.indexOf( 'Android' ) >= 0 )
				{
					return 2.4 > parseFloat( ua.slice( ua.indexOf( 'Android' ) +8 ) );
				}
				return false;
			})();

		$[ _PLUGIN_ ].support = {

			touch: _touch,
			transition: _transition,
			oldAndroidBrowser: _oldAndroidBrowser,

			overflowscrolling: (function() {
				if ( !_touch )
				{
					return true;
				}
				if ( _overflowscrolling )
				{
					return true;
				}
				if ( _oldAndroidBrowser )
				{
					return false;
				}
				return true;
			})()
		};
	})();


	/*
		BROWSER SPECIFIC FIXES
	*/
	$[ _PLUGIN_ ].useOverflowScrollingFallback = function( use )
	{
		if ( glbl.$html )
		{
			if ( typeof use == 'boolean' )
			{
				glbl.$html[ use ? 'addClass' : 'removeClass' ]( _c.nooverflowscrolling );
			}
			return glbl.$html.hasClass( _c.nooverflowscrolling );
		}
		else
		{
			_useOverflowScrollingFallback = use;
			return use;
		}
	};


	/*
		DEBUG
	*/
	$[ _PLUGIN_ ].debug = function( msg ) {};
	$[ _PLUGIN_ ].deprecated = function( depr, repl )
	{
		if ( typeof console != 'undefined' && typeof console.warn != 'undefined' )
		{
			console.warn( 'MMENU: ' + depr + ' is deprecated, use ' + repl + ' instead.' );
		}
	};


	//	Global vars
	var _useOverflowScrollingFallback = !$[ _PLUGIN_ ].support.overflowscrolling;


	function extendOptions( o, c, $m )
	{
		if ( typeof o != 'object' )
		{
			o = {};
		}

		if ( $m )
		{
			if ( typeof o.isMenu != 'boolean' )
			{
				var $c = $m.children();
				o.isMenu = ( $c.length == 1 && $c.is( c.panelNodetype ) );
			}
			return o;
		}


		//	Extend onClick
		if ( typeof o.onClick != 'object' )
		{
			o.onClick = {};
		}


		//	DEPRECATED
		if ( typeof o.onClick.setLocationHref != 'undefined' )
		{
			$[ _PLUGIN_ ].deprecated( 'onClick.setLocationHref option', '!onClick.preventDefault' );
			if ( typeof o.onClick.setLocationHref == 'boolean' )
			{
				o.onClick.preventDefault = !o.onClick.setLocationHref;
			}
		}
		//	/DEPRECATED


		//	Extend from defaults
		o = $.extend( true, {}, $[ _PLUGIN_ ].defaults, o );


		//	Degration
		if ( $[ _PLUGIN_ ].useOverflowScrollingFallback() )
		{
			switch( o.position )
			{
				case 'top':
				case 'relative':
				case 'right':
				case 'bottom':
					$[ _PLUGIN_ ].debug( 'position: "' + o.position + '" not supported when using the overflowScrolling-fallback.' );
					o.position = 'left';
					break;
			}
			switch( o.zposition )
			{
				case 'front':
				case 'next':
					$[ _PLUGIN_ ].debug( 'z-position: "' + o.zposition + '" not supported when using the overflowScrolling-fallback.' );
					o.zposition = 'back';
					break;
			}
		}

		return o;
	}
	function extendConfiguration( c )
	{
		if ( typeof c != 'object' )
		{
			c = {};
		}


		//	DEPRECATED
		if ( typeof c.panelNodeType != 'undefined' )
		{
			$[ _PLUGIN_ ].deprecated( 'panelNodeType configuration option', 'panelNodetype' );
			c.panelNodetype = c.panelNodeType;
		}
		//	/DEPRECATED


		c = $.extend( true, {}, $[ _PLUGIN_ ].configuration, c )

		//	Set pageSelector
		if ( typeof c.pageSelector != 'string' )
		{
			c.pageSelector = '> ' + c.pageNodetype;
		}

		return c;
	}

	function _initPlugin()
	{
		glbl.$wndw = $(window);
		glbl.$html = $('html');
		glbl.$body = $('body');

		glbl.$allMenus = $();


		//	Classnames, Datanames, Eventnames
		$.each( [ _c, _d, _e ],
			function( i, o )
			{
				o.add = function( c )
				{
					c = c.split( ' ' );
					for ( var d in c )
					{
						o[ c[ d ] ] = o.mm( c[ d ] );
					}
				};
			}
		);

		//	Classnames
		_c.mm = function( c ) { return 'mm-' + c; };
		_c.add( 'menu ismenu panel list subtitle selected label spacer current highest hidden page relative blocker modal background opened opening subopened subopen open fullsubopen subclose nooverflowscrolling' );
		_c.umm = function( c )
		{
			if ( c.slice( 0, 3 ) == 'mm-' )
			{
				c = c.slice( 3 );
			}
			return c;
		};

		//	Datanames
		_d.mm = function( d ) { return 'mm-' + d; };
		_d.add( 'parent style scrollTop offetLeft' );

		//	Eventnames
		_e.mm = function( e ) { return e + '.mm'; };
		_e.add( 'toggle open opening opened close closing closed update setPage setSelected transitionend touchstart touchend mousedown mouseup click keydown keyup resize' );


		$[ _PLUGIN_ ]._c = _c;
		$[ _PLUGIN_ ]._d = _d;
		$[ _PLUGIN_ ]._e = _e;

		$[ _PLUGIN_ ].glbl = glbl;

		$[ _PLUGIN_ ].useOverflowScrollingFallback( _useOverflowScrollingFallback );
	}

	function openSubmenuHorizontal( $opening, $m )
	{
		if ( $opening.hasClass( _c.current ) )
		{
			return false;
		}

		var $panels = $('.' + _c.panel, $m),
			$current = $panels.filter( '.' + _c.current );

		$panels
			.removeClass( _c.highest )
			.removeClass( _c.current )
			.not( $opening )
			.not( $current )
			.addClass( _c.hidden );

		if ( $opening.hasClass( _c.opened ) )
		{
			$current
				.addClass( _c.highest )
				.removeClass( _c.opened )
				.removeClass( _c.subopened );
		}
		else
		{
			$opening
				.addClass( _c.highest );

			$current
				.addClass( _c.subopened );
		}

		$opening
			.removeClass( _c.hidden )
			.removeClass( _c.subopened )
			.addClass( _c.current )
			.addClass( _c.opened );

		return 'open';
	}

	function findScrollTop()
	{
		if ( !glbl.$scrollTopNode )
		{
			if ( glbl.$html.scrollTop() != 0 )
			{
				glbl.$scrollTopNode = glbl.$html;
			}
			else if ( glbl.$body.scrollTop() != 0 )
			{
				glbl.$scrollTopNode = glbl.$body;
			}
		}
		return ( glbl.$scrollTopNode ) ? glbl.$scrollTopNode.scrollTop() : 0;
	}

	function transitionend( $e, fn, duration )
	{
		var s = $[ _PLUGIN_ ].support.transition;
		if ( s == 'webkitTransition' )
		{
			$e.one( 'webkitTransitionEnd', fn );
		}
		else if ( s )
		{
			$e.one( _e.transitionend, fn );
		}
		else
		{
			setTimeout( fn, duration );
		}
	}

})( jQuery );
!function(e){"use strict"
var s=function(){var s={bcClass:"sf-breadcrumb",menuClass:"sf-js-enabled",anchorClass:"sf-with-ul",menuArrowClass:"sf-arrows"},o=function(){var s=/iPhone|iPad|iPod/i.test(navigator.userAgent)
return s&&e(window).load(function(){e("body").children().on("click",e.noop)}),s}(),n=function(){var e=document.documentElement.style
return"behavior"in e&&"fill"in e&&/iemobile/i.test(navigator.userAgent)}(),t=function(e,o){var n=s.menuClass
o.cssArrows&&(n+=" "+s.menuArrowClass),e.toggleClass(n)},i=function(o,n){return o.find("li."+n.pathClass).slice(0,n.pathLevels).addClass(n.hoverClass+" "+s.bcClass).filter(function(){return e(this).children(n.popUpSelector).hide().show().length}).removeClass(n.pathClass)},r=function(e){e.children("a").toggleClass(s.anchorClass)},a=function(e){var s=e.css("ms-touch-action")
s="pan-y"===s?"auto":"pan-y",e.css("ms-touch-action",s)},l=function(s,t){var i="li:has("+t.popUpSelector+")"
e.fn.hoverIntent&&!t.disableHI?s.hoverIntent(u,p,i):s.on("mouseenter.superfish",i,u).on("mouseleave.superfish",i,p)
var r="MSPointerDown.superfish"
o||(r+=" touchend.superfish"),n&&(r+=" mousedown.superfish"),s.on("focusin.superfish","li",u).on("focusout.superfish","li",p).on(r,"a",t,h)},h=function(s){var o=e(this),n=o.siblings(s.data.popUpSelector)
n.length>0&&n.is(":hidden")&&(o.one("click.superfish",!1),"MSPointerDown"===s.type?o.trigger("focus"):e.proxy(u,o.parent("li"))())},u=function(){var s=e(this),o=d(s)
clearTimeout(o.sfTimer),s.siblings().superfish("hide").end().superfish("show")},p=function(){var s=e(this),n=d(s)
o?e.proxy(f,s,n)():(clearTimeout(n.sfTimer),n.sfTimer=setTimeout(e.proxy(f,s,n),n.delay))},f=function(s){s.retainPath=e.inArray(this[0],s.$path)>-1,this.superfish("hide"),this.parents("."+s.hoverClass).length||(s.onIdle.call(c(this)),s.$path.length&&e.proxy(u,s.$path)())},c=function(e){return e.closest("."+s.menuClass)},d=function(e){return c(e).data("sf-options")}
return{hide:function(s){if(this.length){var o=this,n=d(o)
if(!n)return this
var t=n.retainPath===!0?n.$path:"",i=o.find("li."+n.hoverClass).add(this).not(t).removeClass(n.hoverClass).children(n.popUpSelector),r=n.speedOut
s&&(i.show(),r=0),n.retainPath=!1,n.onBeforeHide.call(i),i.stop(!0,!0).animate(n.animationOut,r,function(){var s=e(this)
n.onHide.call(s)})}return this},show:function(){var e=d(this)
if(!e)return this
var s=this.addClass(e.hoverClass),o=s.children(e.popUpSelector)
return e.onBeforeShow.call(o),o.stop(!0,!0).animate(e.animation,e.speed,function(){e.onShow.call(o)}),this},destroy:function(){return this.each(function(){var o,n=e(this),i=n.data("sf-options")
return i?(o=n.find(i.popUpSelector).parent("li"),clearTimeout(i.sfTimer),t(n,i),r(o),a(n),n.off(".superfish").off(".hoverIntent"),o.children(i.popUpSelector).attr("style",function(e,s){return s.replace(/display[^;]+;?/g,"")}),i.$path.removeClass(i.hoverClass+" "+s.bcClass).addClass(i.pathClass),n.find("."+i.hoverClass).removeClass(i.hoverClass),i.onDestroy.call(n),n.removeData("sf-options"),void 0):!1})},init:function(o){return this.each(function(){var n=e(this)
if(n.data("sf-options"))return!1
var h=e.extend({},e.fn.superfish.defaults,o),u=n.find(h.popUpSelector).parent("li")
h.$path=i(n,h),n.data("sf-options",h),t(n,h),r(u),a(n),l(n,h),u.not("."+s.bcClass).superfish("hide",!0),h.onInit.call(this)})}}}()
e.fn.superfish=function(o){return s[o]?s[o].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof o&&o?e.error("Method "+o+" does not exist on jQuery.fn.superfish"):s.init.apply(this,arguments)},e.fn.superfish.defaults={popUpSelector:"ul,.sf-mega",hoverClass:"sfHover",pathClass:"overrideThisToUse",pathLevels:1,delay:800,animation:{opacity:"show"},animationOut:{opacity:"hide"},speed:"normal",speedOut:"fast",cssArrows:!0,disableHI:!1,onInit:e.noop,onBeforeShow:e.noop,onShow:e.noop,onBeforeHide:e.noop,onHide:e.noop,onIdle:e.noop,onDestroy:e.noop},e.fn.extend({hideSuperfishUl:s.hide,showSuperfishUl:s.show})}(jQuery);
/*!
 * jQuery Cookie Plugin v1.3.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function(factory){if(typeof define==='function'&&define.amd){define(['jquery'],factory)}else{factory(jQuery)}}(function($){var pluses=/\+/g;function encode(s){return config.raw?s:encodeURIComponent(s)}function decode(s){return config.raw?s:decodeURIComponent(s)}function stringifyCookieValue(value){return encode(config.json?JSON.stringify(value):String(value))}function parseCookieValue(s){if(s.indexOf('"')===0){s=s.slice(1,-1).replace(/\\"/g, '"').replace(/\\\\/g,'\\');}try{s=decodeURIComponent(s.replace(pluses,' '));return config.json?JSON.parse(s):s}catch(e){}}function read(s,converter){var value=config.raw?s:parseCookieValue(s);return $.isFunction(converter)?converter(value):value}var config=$.cookie=function(key,value,options){if(value!==undefined&&!$.isFunction(value)){options=$.extend({},config.defaults,options);if(typeof options.expires==='number'){var days=options.expires,t=options.expires=new Date();t.setTime(+t+days * 864e+5)}return(document.cookie=[encode(key),'=',stringifyCookieValue(value),options.expires?'; expires='+options.expires.toUTCString():'',options.path?'; path='+options.path:'',options.domain?'; domain='+options.domain:'',options.secure?'; secure':''].join(''))}var result=key?undefined:{};var cookies=document.cookie?document.cookie.split('; '):[];for(var i=0,l=cookies.length;i<l;i++){var parts=cookies[i].split('=');var name=decode(parts.shift());var cookie=parts.join('=');if(key&&key===name){result=read(cookie,value);break}if(!key&&(cookie=read(cookie))!==undefined){result[name]=cookie}}return result};config.defaults={};$.removeCookie=function(key,options){if($.cookie(key)===undefined){return false}$.cookie(key,'',$.extend({},options,{expires:-1}));return!$.cookie(key)}}));
/*! jQuery UI - v1.10.4 - 2014-01-27
* http://jqueryui.com
* Includes: jquery.ui.core.js, jquery.ui.widget.js, jquery.ui.mouse.js, jquery.ui.slider.js
* Copyright 2014 jQuery Foundation and other contributors; Licensed MIT */

(function(e,t){function i(t,i){var s,a,o,r=t.nodeName.toLowerCase();return"area"===r?(s=t.parentNode,a=s.name,t.href&&a&&"map"===s.nodeName.toLowerCase()?(o=e("img[usemap=#"+a+"]")[0],!!o&&n(o)):!1):(/input|select|textarea|button|object/.test(r)?!t.disabled:"a"===r?t.href||i:i)&&n(t)}function n(t){return e.expr.filters.visible(t)&&!e(t).parents().addBack().filter(function(){return"hidden"===e.css(this,"visibility")}).length}var s=0,a=/^ui-id-\d+$/;e.ui=e.ui||{},e.extend(e.ui,{version:"1.10.4",keyCode:{BACKSPACE:8,COMMA:188,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,LEFT:37,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SPACE:32,TAB:9,UP:38}}),e.fn.extend({focus:function(t){return function(i,n){return"number"==typeof i?this.each(function(){var t=this;setTimeout(function(){e(t).focus(),n&&n.call(t)},i)}):t.apply(this,arguments)}}(e.fn.focus),scrollParent:function(){var t;return t=e.ui.ie&&/(static|relative)/.test(this.css("position"))||/absolute/.test(this.css("position"))?this.parents().filter(function(){return/(relative|absolute|fixed)/.test(e.css(this,"position"))&&/(auto|scroll)/.test(e.css(this,"overflow")+e.css(this,"overflow-y")+e.css(this,"overflow-x"))}).eq(0):this.parents().filter(function(){return/(auto|scroll)/.test(e.css(this,"overflow")+e.css(this,"overflow-y")+e.css(this,"overflow-x"))}).eq(0),/fixed/.test(this.css("position"))||!t.length?e(document):t},zIndex:function(i){if(i!==t)return this.css("zIndex",i);if(this.length)for(var n,s,a=e(this[0]);a.length&&a[0]!==document;){if(n=a.css("position"),("absolute"===n||"relative"===n||"fixed"===n)&&(s=parseInt(a.css("zIndex"),10),!isNaN(s)&&0!==s))return s;a=a.parent()}return 0},uniqueId:function(){return this.each(function(){this.id||(this.id="ui-id-"+ ++s)})},removeUniqueId:function(){return this.each(function(){a.test(this.id)&&e(this).removeAttr("id")})}}),e.extend(e.expr[":"],{data:e.expr.createPseudo?e.expr.createPseudo(function(t){return function(i){return!!e.data(i,t)}}):function(t,i,n){return!!e.data(t,n[3])},focusable:function(t){return i(t,!isNaN(e.attr(t,"tabindex")))},tabbable:function(t){var n=e.attr(t,"tabindex"),s=isNaN(n);return(s||n>=0)&&i(t,!s)}}),e("<a>").outerWidth(1).jquery||e.each(["Width","Height"],function(i,n){function s(t,i,n,s){return e.each(a,function(){i-=parseFloat(e.css(t,"padding"+this))||0,n&&(i-=parseFloat(e.css(t,"border"+this+"Width"))||0),s&&(i-=parseFloat(e.css(t,"margin"+this))||0)}),i}var a="Width"===n?["Left","Right"]:["Top","Bottom"],o=n.toLowerCase(),r={innerWidth:e.fn.innerWidth,innerHeight:e.fn.innerHeight,outerWidth:e.fn.outerWidth,outerHeight:e.fn.outerHeight};e.fn["inner"+n]=function(i){return i===t?r["inner"+n].call(this):this.each(function(){e(this).css(o,s(this,i)+"px")})},e.fn["outer"+n]=function(t,i){return"number"!=typeof t?r["outer"+n].call(this,t):this.each(function(){e(this).css(o,s(this,t,!0,i)+"px")})}}),e.fn.addBack||(e.fn.addBack=function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}),e("<a>").data("a-b","a").removeData("a-b").data("a-b")&&(e.fn.removeData=function(t){return function(i){return arguments.length?t.call(this,e.camelCase(i)):t.call(this)}}(e.fn.removeData)),e.ui.ie=!!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()),e.support.selectstart="onselectstart"in document.createElement("div"),e.fn.extend({disableSelection:function(){return this.bind((e.support.selectstart?"selectstart":"mousedown")+".ui-disableSelection",function(e){e.preventDefault()})},enableSelection:function(){return this.unbind(".ui-disableSelection")}}),e.extend(e.ui,{plugin:{add:function(t,i,n){var s,a=e.ui[t].prototype;for(s in n)a.plugins[s]=a.plugins[s]||[],a.plugins[s].push([i,n[s]])},call:function(e,t,i){var n,s=e.plugins[t];if(s&&e.element[0].parentNode&&11!==e.element[0].parentNode.nodeType)for(n=0;s.length>n;n++)e.options[s[n][0]]&&s[n][1].apply(e.element,i)}},hasScroll:function(t,i){if("hidden"===e(t).css("overflow"))return!1;var n=i&&"left"===i?"scrollLeft":"scrollTop",s=!1;return t[n]>0?!0:(t[n]=1,s=t[n]>0,t[n]=0,s)}})})(jQuery);(function(t,e){var i=0,s=Array.prototype.slice,n=t.cleanData;t.cleanData=function(e){for(var i,s=0;null!=(i=e[s]);s++)try{t(i).triggerHandler("remove")}catch(o){}n(e)},t.widget=function(i,s,n){var o,a,r,h,l={},c=i.split(".")[0];i=i.split(".")[1],o=c+"-"+i,n||(n=s,s=t.Widget),t.expr[":"][o.toLowerCase()]=function(e){return!!t.data(e,o)},t[c]=t[c]||{},a=t[c][i],r=t[c][i]=function(t,i){return this._createWidget?(arguments.length&&this._createWidget(t,i),e):new r(t,i)},t.extend(r,a,{version:n.version,_proto:t.extend({},n),_childConstructors:[]}),h=new s,h.options=t.widget.extend({},h.options),t.each(n,function(i,n){return t.isFunction(n)?(l[i]=function(){var t=function(){return s.prototype[i].apply(this,arguments)},e=function(t){return s.prototype[i].apply(this,t)};return function(){var i,s=this._super,o=this._superApply;return this._super=t,this._superApply=e,i=n.apply(this,arguments),this._super=s,this._superApply=o,i}}(),e):(l[i]=n,e)}),r.prototype=t.widget.extend(h,{widgetEventPrefix:a?h.widgetEventPrefix||i:i},l,{constructor:r,namespace:c,widgetName:i,widgetFullName:o}),a?(t.each(a._childConstructors,function(e,i){var s=i.prototype;t.widget(s.namespace+"."+s.widgetName,r,i._proto)}),delete a._childConstructors):s._childConstructors.push(r),t.widget.bridge(i,r)},t.widget.extend=function(i){for(var n,o,a=s.call(arguments,1),r=0,h=a.length;h>r;r++)for(n in a[r])o=a[r][n],a[r].hasOwnProperty(n)&&o!==e&&(i[n]=t.isPlainObject(o)?t.isPlainObject(i[n])?t.widget.extend({},i[n],o):t.widget.extend({},o):o);return i},t.widget.bridge=function(i,n){var o=n.prototype.widgetFullName||i;t.fn[i]=function(a){var r="string"==typeof a,h=s.call(arguments,1),l=this;return a=!r&&h.length?t.widget.extend.apply(null,[a].concat(h)):a,r?this.each(function(){var s,n=t.data(this,o);return n?t.isFunction(n[a])&&"_"!==a.charAt(0)?(s=n[a].apply(n,h),s!==n&&s!==e?(l=s&&s.jquery?l.pushStack(s.get()):s,!1):e):t.error("no such method '"+a+"' for "+i+" widget instance"):t.error("cannot call methods on "+i+" prior to initialization; "+"attempted to call method '"+a+"'")}):this.each(function(){var e=t.data(this,o);e?e.option(a||{})._init():t.data(this,o,new n(a,this))}),l}},t.Widget=function(){},t.Widget._childConstructors=[],t.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",defaultElement:"<div>",options:{disabled:!1,create:null},_createWidget:function(e,s){s=t(s||this.defaultElement||this)[0],this.element=t(s),this.uuid=i++,this.eventNamespace="."+this.widgetName+this.uuid,this.options=t.widget.extend({},this.options,this._getCreateOptions(),e),this.bindings=t(),this.hoverable=t(),this.focusable=t(),s!==this&&(t.data(s,this.widgetFullName,this),this._on(!0,this.element,{remove:function(t){t.target===s&&this.destroy()}}),this.document=t(s.style?s.ownerDocument:s.document||s),this.window=t(this.document[0].defaultView||this.document[0].parentWindow)),this._create(),this._trigger("create",null,this._getCreateEventData()),this._init()},_getCreateOptions:t.noop,_getCreateEventData:t.noop,_create:t.noop,_init:t.noop,destroy:function(){this._destroy(),this.element.unbind(this.eventNamespace).removeData(this.widgetName).removeData(this.widgetFullName).removeData(t.camelCase(this.widgetFullName)),this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName+"-disabled "+"ui-state-disabled"),this.bindings.unbind(this.eventNamespace),this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus")},_destroy:t.noop,widget:function(){return this.element},option:function(i,s){var n,o,a,r=i;if(0===arguments.length)return t.widget.extend({},this.options);if("string"==typeof i)if(r={},n=i.split("."),i=n.shift(),n.length){for(o=r[i]=t.widget.extend({},this.options[i]),a=0;n.length-1>a;a++)o[n[a]]=o[n[a]]||{},o=o[n[a]];if(i=n.pop(),1===arguments.length)return o[i]===e?null:o[i];o[i]=s}else{if(1===arguments.length)return this.options[i]===e?null:this.options[i];r[i]=s}return this._setOptions(r),this},_setOptions:function(t){var e;for(e in t)this._setOption(e,t[e]);return this},_setOption:function(t,e){return this.options[t]=e,"disabled"===t&&(this.widget().toggleClass(this.widgetFullName+"-disabled ui-state-disabled",!!e).attr("aria-disabled",e),this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus")),this},enable:function(){return this._setOption("disabled",!1)},disable:function(){return this._setOption("disabled",!0)},_on:function(i,s,n){var o,a=this;"boolean"!=typeof i&&(n=s,s=i,i=!1),n?(s=o=t(s),this.bindings=this.bindings.add(s)):(n=s,s=this.element,o=this.widget()),t.each(n,function(n,r){function h(){return i||a.options.disabled!==!0&&!t(this).hasClass("ui-state-disabled")?("string"==typeof r?a[r]:r).apply(a,arguments):e}"string"!=typeof r&&(h.guid=r.guid=r.guid||h.guid||t.guid++);var l=n.match(/^(\w+)\s*(.*)$/),c=l[1]+a.eventNamespace,u=l[2];u?o.delegate(u,c,h):s.bind(c,h)})},_off:function(t,e){e=(e||"").split(" ").join(this.eventNamespace+" ")+this.eventNamespace,t.unbind(e).undelegate(e)},_delay:function(t,e){function i(){return("string"==typeof t?s[t]:t).apply(s,arguments)}var s=this;return setTimeout(i,e||0)},_hoverable:function(e){this.hoverable=this.hoverable.add(e),this._on(e,{mouseenter:function(e){t(e.currentTarget).addClass("ui-state-hover")},mouseleave:function(e){t(e.currentTarget).removeClass("ui-state-hover")}})},_focusable:function(e){this.focusable=this.focusable.add(e),this._on(e,{focusin:function(e){t(e.currentTarget).addClass("ui-state-focus")},focusout:function(e){t(e.currentTarget).removeClass("ui-state-focus")}})},_trigger:function(e,i,s){var n,o,a=this.options[e];if(s=s||{},i=t.Event(i),i.type=(e===this.widgetEventPrefix?e:this.widgetEventPrefix+e).toLowerCase(),i.target=this.element[0],o=i.originalEvent)for(n in o)n in i||(i[n]=o[n]);return this.element.trigger(i,s),!(t.isFunction(a)&&a.apply(this.element[0],[i].concat(s))===!1||i.isDefaultPrevented())}},t.each({show:"fadeIn",hide:"fadeOut"},function(e,i){t.Widget.prototype["_"+e]=function(s,n,o){"string"==typeof n&&(n={effect:n});var a,r=n?n===!0||"number"==typeof n?i:n.effect||i:e;n=n||{},"number"==typeof n&&(n={duration:n}),a=!t.isEmptyObject(n),n.complete=o,n.delay&&s.delay(n.delay),a&&t.effects&&t.effects.effect[r]?s[e](n):r!==e&&s[r]?s[r](n.duration,n.easing,o):s.queue(function(i){t(this)[e](),o&&o.call(s[0]),i()})}})})(jQuery);(function(t){var e=!1;t(document).mouseup(function(){e=!1}),t.widget("ui.mouse",{version:"1.10.4",options:{cancel:"input,textarea,button,select,option",distance:1,delay:0},_mouseInit:function(){var e=this;this.element.bind("mousedown."+this.widgetName,function(t){return e._mouseDown(t)}).bind("click."+this.widgetName,function(i){return!0===t.data(i.target,e.widgetName+".preventClickEvent")?(t.removeData(i.target,e.widgetName+".preventClickEvent"),i.stopImmediatePropagation(),!1):undefined}),this.started=!1},_mouseDestroy:function(){this.element.unbind("."+this.widgetName),this._mouseMoveDelegate&&t(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate)},_mouseDown:function(i){if(!e){this._mouseStarted&&this._mouseUp(i),this._mouseDownEvent=i;var s=this,n=1===i.which,a="string"==typeof this.options.cancel&&i.target.nodeName?t(i.target).closest(this.options.cancel).length:!1;return n&&!a&&this._mouseCapture(i)?(this.mouseDelayMet=!this.options.delay,this.mouseDelayMet||(this._mouseDelayTimer=setTimeout(function(){s.mouseDelayMet=!0},this.options.delay)),this._mouseDistanceMet(i)&&this._mouseDelayMet(i)&&(this._mouseStarted=this._mouseStart(i)!==!1,!this._mouseStarted)?(i.preventDefault(),!0):(!0===t.data(i.target,this.widgetName+".preventClickEvent")&&t.removeData(i.target,this.widgetName+".preventClickEvent"),this._mouseMoveDelegate=function(t){return s._mouseMove(t)},this._mouseUpDelegate=function(t){return s._mouseUp(t)},t(document).bind("mousemove."+this.widgetName,this._mouseMoveDelegate).bind("mouseup."+this.widgetName,this._mouseUpDelegate),i.preventDefault(),e=!0,!0)):!0}},_mouseMove:function(e){return t.ui.ie&&(!document.documentMode||9>document.documentMode)&&!e.button?this._mouseUp(e):this._mouseStarted?(this._mouseDrag(e),e.preventDefault()):(this._mouseDistanceMet(e)&&this._mouseDelayMet(e)&&(this._mouseStarted=this._mouseStart(this._mouseDownEvent,e)!==!1,this._mouseStarted?this._mouseDrag(e):this._mouseUp(e)),!this._mouseStarted)},_mouseUp:function(e){return t(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate),this._mouseStarted&&(this._mouseStarted=!1,e.target===this._mouseDownEvent.target&&t.data(e.target,this.widgetName+".preventClickEvent",!0),this._mouseStop(e)),!1},_mouseDistanceMet:function(t){return Math.max(Math.abs(this._mouseDownEvent.pageX-t.pageX),Math.abs(this._mouseDownEvent.pageY-t.pageY))>=this.options.distance},_mouseDelayMet:function(){return this.mouseDelayMet},_mouseStart:function(){},_mouseDrag:function(){},_mouseStop:function(){},_mouseCapture:function(){return!0}})})(jQuery);(function(t){var e=5;t.widget("ui.slider",t.ui.mouse,{version:"1.10.4",widgetEventPrefix:"slide",options:{animate:!1,distance:0,max:100,min:0,orientation:"horizontal",range:!1,step:1,value:0,values:null,change:null,slide:null,start:null,stop:null},_create:function(){this._keySliding=!1,this._mouseSliding=!1,this._animateOff=!0,this._handleIndex=null,this._detectOrientation(),this._mouseInit(),this.element.addClass("ui-slider ui-slider-"+this.orientation+" ui-widget"+" ui-widget-content"+" ui-corner-all"),this._refresh(),this._setOption("disabled",this.options.disabled),this._animateOff=!1},_refresh:function(){this._createRange(),this._createHandles(),this._setupEvents(),this._refreshValue()},_createHandles:function(){var e,i,s=this.options,n=this.element.find(".ui-slider-handle").addClass("ui-state-default ui-corner-all"),a="<a class='ui-slider-handle ui-state-default ui-corner-all' href='#'></a>",o=[];for(i=s.values&&s.values.length||1,n.length>i&&(n.slice(i).remove(),n=n.slice(0,i)),e=n.length;i>e;e++)o.push(a);this.handles=n.add(t(o.join("")).appendTo(this.element)),this.handle=this.handles.eq(0),this.handles.each(function(e){t(this).data("ui-slider-handle-index",e)})},_createRange:function(){var e=this.options,i="";e.range?(e.range===!0&&(e.values?e.values.length&&2!==e.values.length?e.values=[e.values[0],e.values[0]]:t.isArray(e.values)&&(e.values=e.values.slice(0)):e.values=[this._valueMin(),this._valueMin()]),this.range&&this.range.length?this.range.removeClass("ui-slider-range-min ui-slider-range-max").css({left:"",bottom:""}):(this.range=t("<div></div>").appendTo(this.element),i="ui-slider-range ui-widget-header ui-corner-all"),this.range.addClass(i+("min"===e.range||"max"===e.range?" ui-slider-range-"+e.range:""))):(this.range&&this.range.remove(),this.range=null)},_setupEvents:function(){var t=this.handles.add(this.range).filter("a");this._off(t),this._on(t,this._handleEvents),this._hoverable(t),this._focusable(t)},_destroy:function(){this.handles.remove(),this.range&&this.range.remove(),this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-widget ui-widget-content ui-corner-all"),this._mouseDestroy()},_mouseCapture:function(e){var i,s,n,a,o,r,l,h,u=this,c=this.options;return c.disabled?!1:(this.elementSize={width:this.element.outerWidth(),height:this.element.outerHeight()},this.elementOffset=this.element.offset(),i={x:e.pageX,y:e.pageY},s=this._normValueFromMouse(i),n=this._valueMax()-this._valueMin()+1,this.handles.each(function(e){var i=Math.abs(s-u.values(e));(n>i||n===i&&(e===u._lastChangedValue||u.values(e)===c.min))&&(n=i,a=t(this),o=e)}),r=this._start(e,o),r===!1?!1:(this._mouseSliding=!0,this._handleIndex=o,a.addClass("ui-state-active").focus(),l=a.offset(),h=!t(e.target).parents().addBack().is(".ui-slider-handle"),this._clickOffset=h?{left:0,top:0}:{left:e.pageX-l.left-a.width()/2,top:e.pageY-l.top-a.height()/2-(parseInt(a.css("borderTopWidth"),10)||0)-(parseInt(a.css("borderBottomWidth"),10)||0)+(parseInt(a.css("marginTop"),10)||0)},this.handles.hasClass("ui-state-hover")||this._slide(e,o,s),this._animateOff=!0,!0))},_mouseStart:function(){return!0},_mouseDrag:function(t){var e={x:t.pageX,y:t.pageY},i=this._normValueFromMouse(e);return this._slide(t,this._handleIndex,i),!1},_mouseStop:function(t){return this.handles.removeClass("ui-state-active"),this._mouseSliding=!1,this._stop(t,this._handleIndex),this._change(t,this._handleIndex),this._handleIndex=null,this._clickOffset=null,this._animateOff=!1,!1},_detectOrientation:function(){this.orientation="vertical"===this.options.orientation?"vertical":"horizontal"},_normValueFromMouse:function(t){var e,i,s,n,a;return"horizontal"===this.orientation?(e=this.elementSize.width,i=t.x-this.elementOffset.left-(this._clickOffset?this._clickOffset.left:0)):(e=this.elementSize.height,i=t.y-this.elementOffset.top-(this._clickOffset?this._clickOffset.top:0)),s=i/e,s>1&&(s=1),0>s&&(s=0),"vertical"===this.orientation&&(s=1-s),n=this._valueMax()-this._valueMin(),a=this._valueMin()+s*n,this._trimAlignValue(a)},_start:function(t,e){var i={handle:this.handles[e],value:this.value()};return this.options.values&&this.options.values.length&&(i.value=this.values(e),i.values=this.values()),this._trigger("start",t,i)},_slide:function(t,e,i){var s,n,a;this.options.values&&this.options.values.length?(s=this.values(e?0:1),2===this.options.values.length&&this.options.range===!0&&(0===e&&i>s||1===e&&s>i)&&(i=s),i!==this.values(e)&&(n=this.values(),n[e]=i,a=this._trigger("slide",t,{handle:this.handles[e],value:i,values:n}),s=this.values(e?0:1),a!==!1&&this.values(e,i))):i!==this.value()&&(a=this._trigger("slide",t,{handle:this.handles[e],value:i}),a!==!1&&this.value(i))},_stop:function(t,e){var i={handle:this.handles[e],value:this.value()};this.options.values&&this.options.values.length&&(i.value=this.values(e),i.values=this.values()),this._trigger("stop",t,i)},_change:function(t,e){if(!this._keySliding&&!this._mouseSliding){var i={handle:this.handles[e],value:this.value()};this.options.values&&this.options.values.length&&(i.value=this.values(e),i.values=this.values()),this._lastChangedValue=e,this._trigger("change",t,i)}},value:function(t){return arguments.length?(this.options.value=this._trimAlignValue(t),this._refreshValue(),this._change(null,0),undefined):this._value()},values:function(e,i){var s,n,a;if(arguments.length>1)return this.options.values[e]=this._trimAlignValue(i),this._refreshValue(),this._change(null,e),undefined;if(!arguments.length)return this._values();if(!t.isArray(arguments[0]))return this.options.values&&this.options.values.length?this._values(e):this.value();for(s=this.options.values,n=arguments[0],a=0;s.length>a;a+=1)s[a]=this._trimAlignValue(n[a]),this._change(null,a);this._refreshValue()},_setOption:function(e,i){var s,n=0;switch("range"===e&&this.options.range===!0&&("min"===i?(this.options.value=this._values(0),this.options.values=null):"max"===i&&(this.options.value=this._values(this.options.values.length-1),this.options.values=null)),t.isArray(this.options.values)&&(n=this.options.values.length),t.Widget.prototype._setOption.apply(this,arguments),e){case"orientation":this._detectOrientation(),this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-"+this.orientation),this._refreshValue();break;case"value":this._animateOff=!0,this._refreshValue(),this._change(null,0),this._animateOff=!1;break;case"values":for(this._animateOff=!0,this._refreshValue(),s=0;n>s;s+=1)this._change(null,s);this._animateOff=!1;break;case"min":case"max":this._animateOff=!0,this._refreshValue(),this._animateOff=!1;break;case"range":this._animateOff=!0,this._refresh(),this._animateOff=!1}},_value:function(){var t=this.options.value;return t=this._trimAlignValue(t)},_values:function(t){var e,i,s;if(arguments.length)return e=this.options.values[t],e=this._trimAlignValue(e);if(this.options.values&&this.options.values.length){for(i=this.options.values.slice(),s=0;i.length>s;s+=1)i[s]=this._trimAlignValue(i[s]);return i}return[]},_trimAlignValue:function(t){if(this._valueMin()>=t)return this._valueMin();if(t>=this._valueMax())return this._valueMax();var e=this.options.step>0?this.options.step:1,i=(t-this._valueMin())%e,s=t-i;return 2*Math.abs(i)>=e&&(s+=i>0?e:-e),parseFloat(s.toFixed(5))},_valueMin:function(){return this.options.min},_valueMax:function(){return this.options.max},_refreshValue:function(){var e,i,s,n,a,o=this.options.range,r=this.options,l=this,h=this._animateOff?!1:r.animate,u={};this.options.values&&this.options.values.length?this.handles.each(function(s){i=100*((l.values(s)-l._valueMin())/(l._valueMax()-l._valueMin())),u["horizontal"===l.orientation?"left":"bottom"]=i+"%",t(this).stop(1,1)[h?"animate":"css"](u,r.animate),l.options.range===!0&&("horizontal"===l.orientation?(0===s&&l.range.stop(1,1)[h?"animate":"css"]({left:i+"%"},r.animate),1===s&&l.range[h?"animate":"css"]({width:i-e+"%"},{queue:!1,duration:r.animate})):(0===s&&l.range.stop(1,1)[h?"animate":"css"]({bottom:i+"%"},r.animate),1===s&&l.range[h?"animate":"css"]({height:i-e+"%"},{queue:!1,duration:r.animate}))),e=i}):(s=this.value(),n=this._valueMin(),a=this._valueMax(),i=a!==n?100*((s-n)/(a-n)):0,u["horizontal"===this.orientation?"left":"bottom"]=i+"%",this.handle.stop(1,1)[h?"animate":"css"](u,r.animate),"min"===o&&"horizontal"===this.orientation&&this.range.stop(1,1)[h?"animate":"css"]({width:i+"%"},r.animate),"max"===o&&"horizontal"===this.orientation&&this.range[h?"animate":"css"]({width:100-i+"%"},{queue:!1,duration:r.animate}),"min"===o&&"vertical"===this.orientation&&this.range.stop(1,1)[h?"animate":"css"]({height:i+"%"},r.animate),"max"===o&&"vertical"===this.orientation&&this.range[h?"animate":"css"]({height:100-i+"%"},{queue:!1,duration:r.animate}))},_handleEvents:{keydown:function(i){var s,n,a,o,r=t(i.target).data("ui-slider-handle-index");switch(i.keyCode){case t.ui.keyCode.HOME:case t.ui.keyCode.END:case t.ui.keyCode.PAGE_UP:case t.ui.keyCode.PAGE_DOWN:case t.ui.keyCode.UP:case t.ui.keyCode.RIGHT:case t.ui.keyCode.DOWN:case t.ui.keyCode.LEFT:if(i.preventDefault(),!this._keySliding&&(this._keySliding=!0,t(i.target).addClass("ui-state-active"),s=this._start(i,r),s===!1))return}switch(o=this.options.step,n=a=this.options.values&&this.options.values.length?this.values(r):this.value(),i.keyCode){case t.ui.keyCode.HOME:a=this._valueMin();break;case t.ui.keyCode.END:a=this._valueMax();break;case t.ui.keyCode.PAGE_UP:a=this._trimAlignValue(n+(this._valueMax()-this._valueMin())/e);break;case t.ui.keyCode.PAGE_DOWN:a=this._trimAlignValue(n-(this._valueMax()-this._valueMin())/e);break;case t.ui.keyCode.UP:case t.ui.keyCode.RIGHT:if(n===this._valueMax())return;a=this._trimAlignValue(n+o);break;case t.ui.keyCode.DOWN:case t.ui.keyCode.LEFT:if(n===this._valueMin())return;a=this._trimAlignValue(n-o)}this._slide(i,r,a)},click:function(t){t.preventDefault()},keyup:function(e){var i=t(e.target).data("ui-slider-handle-index");this._keySliding&&(this._keySliding=!1,this._stop(e,i),this._change(e,i),t(e.target).removeClass("ui-state-active"))}}})})(jQuery);
/*	
 * jQuery mmenu header addon
 * @requires mmenu 4.0.0 or later
 *
 * mmenu.frebsite.nl
 *	
 * Copyright (c) Fred Heusschen
 * www.frebsite.nl
 *
 * Dual licensed under the MIT and GPL licenses.
 * http://en.wikipedia.org/wiki/MIT_License
 * http://en.wikipedia.org/wiki/GNU_General_Public_License
 */
!function(e){var t="mmenu",a="header";e[t].prototype["_addon_"+a]=function(){var n=this,r=this.opts[a],d=this.conf[a],s=e[t]._c,i=(e[t]._d,e[t]._e);s.add("header hasheader prev next title titletext"),i.add("updateheader");var o=e[t].glbl;if("boolean"==typeof r&&(r={add:r,update:r}),"object"!=typeof r&&(r={}),r=e.extend(!0,{},e[t].defaults[a],r),r.add){var h=r.content?r.content:'<a class="'+s.prev+'" href="#"></a><span class="'+s.title+'"></span><a class="'+s.next+'" href="#"></a>';e('<div class="'+s.header+'" />').prependTo(this.$menu).append(h)}var p=e("div."+s.header,this.$menu);if(p.length&&this.$menu.addClass(s.hasheader),r.update&&p.length){var l=p.find("."+s.title),u=p.find("."+s.prev),f=p.find("."+s.next),c="#"+o.$page.attr("id");u.add(f).on(i.click,function(t){t.preventDefault(),t.stopPropagation();var a=e(this).attr("href");"#"!==a&&(a==c?n.$menu.trigger(i.close):e(a,n.$menu).trigger(i.open))}),e("."+s.panel,this.$menu).each(function(){var t=e(this),a=e("."+d.panelHeaderClass,t).text(),n=e("."+d.panelPrevClass,t).attr("href"),o=e("."+d.panelNextClass,t).attr("href");a||(a=e("."+s.subclose,t).text()),a||(a=r.title),n||(n=e("."+s.subclose,t).attr("href")),t.off(i.updateheader).on(i.updateheader,function(e){e.stopPropagation(),l[a?"show":"hide"]().text(a),u[n?"show":"hide"]().attr("href",n),f[o?"show":"hide"]().attr("href",o)}),t.on(i.open,function(){e(this).trigger(i.updateheader)})}).filter("."+s.current).trigger(i.updateheader)}},e[t].defaults[a]={add:!1,content:!1,update:!1,title:"Menu"},e[t].configuration[a]={panelHeaderClass:"Header",panelNextClass:"Next",panelPrevClass:"Prev"},e[t].addons=e[t].addons||[],e[t].addons.push(a)}(jQuery);
/*
 cycle.js
 2016-05-01

 Public Domain.

 NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.

 This code should be minified before deployment.
 See http://javascript.crockford.com/jsmin.html

 USE YOUR OWN COPY. IT IS EXTREMELY UNWISE TO LOAD CODE FROM SERVERS YOU DO
 NOT CONTROL.
 */

/*jslint eval, for */

/*property
 $ref, decycle, forEach, indexOf, isArray, keys, length, push, retrocycle,
 stringify, test
 */

if (typeof JSON.decycle !== "function") {
    JSON.decycle = function decycle(object, replacer) {
        "use strict";

// Make a deep copy of an object or array, assuring that there is at most
// one instance of each object or array in the resulting structure. The
// duplicate references (which might be forming cycles) are replaced with
// an object of the form

//      {"$ref": PATH}

// where the PATH is a JSONPath string that locates the first occurance.

// So,

//      var a = [];
//      a[0] = a;
//      return JSON.stringify(JSON.decycle(a));

// produces the string '[{"$ref":"$"}]'.

// If a replacer function is provided, then it will be called for each value.
// A replacer function receives a value and returns a replacement value.

// JSONPath is used to locate the unique object. $ indicates the top level of
// the object or array. [NUMBER] or [STRING] indicates a child element or
// property.

        var objects = [];   // Keep a reference to each unique object or array
        var paths = [];     // Keep the path to each unique object or array

        return (function derez(value, path) {

// The derez function recurses through the object, producing the deep copy.

            var i;          // The loop counter
            var nu;         // The new object or array

// If a replacer function was provided, then call it to get a replacement value.

            if (replacer !== undefined) {
                value = replacer(value);
            }

// typeof null === "object", so go on if this value is really an object but not
// one of the weird builtin objects.

            if (
                    typeof value === "object" && value !== null &&
                    !(value instanceof Boolean) &&
                    !(value instanceof Date) &&
                    !(value instanceof Number) &&
                    !(value instanceof RegExp) &&
                    !(value instanceof String)
            ) {

// If the value is an object or array, look to see if we have already
// encountered it. If so, return a {"$ref":PATH} object. This is a hard
// linear search that will get slower as the number of unique objects grows.
// Someday, this should be replaced with an ES6 WeakMap.

                i = objects.indexOf(value);
                if (i >= 0) {
                    return {$ref: paths[i]};
                }

// Otherwise, accumulate the unique value and its path.

                objects.push(value);
                paths.push(path);

// If it is an array, replicate the array.

                if (Array.isArray(value)) {
                    nu = [];
                    value.forEach(function (element, i) {
                        nu[i] = derez(element, path + "[" + i + "]");
                    });
                } else {

// If it is an object, replicate the object.

                    nu = {};
                    Object.keys(value).forEach(function (name) {
                        nu[name] = derez(
                                value[name],
                                path + "[" + JSON.stringify(name) + "]"
                        );
                    });
                }
                return nu;
            }
            return value;
        }(object, "$"));
    };
}


if (typeof JSON.retrocycle !== "function") {
    JSON.retrocycle = function retrocycle($) {
        "use strict";

// Restore an object that was reduced by decycle. Members whose values are
// objects of the form
//      {$ref: PATH}
// are replaced with references to the value found by the PATH. This will
// restore cycles. The object will be mutated.

// The eval function is used to locate the values described by a PATH. The
// root object is kept in a $ variable. A regular expression is used to
// assure that the PATH is extremely well formed. The regexp contains nested
// * quantifiers. That has been known to have extremely bad performance
// problems on some browsers for very long strings. A PATH is expected to be
// reasonably short. A PATH is allowed to belong to a very restricted subset of
// Goessner's JSONPath.

// So,
//      var s = '[{"$ref":"$"}]';
//      return JSON.retrocycle(JSON.parse(s));
// produces an array containing a single element which is the array itself.

        var px = /^\$(?:\[(?:\d+|"(?:[^\\"\u0000-\u001f]|\\([\\"\/bfnrt]|u[0-9a-zA-Z]{4}))*")\])*$/;

        (function rez(value) {

// The rez function walks recursively through the object looking for $ref
// properties. When it finds one that has a value that is a path, then it
// replaces the $ref object with a reference to the value that is found by
// the path.

            if (value && typeof value === "object") {
                if (Array.isArray(value)) {
                    value.forEach(function (element, i) {
                        if (typeof element === "object" && element !== null) {
                            var path = element.$ref;
                            if (typeof path === "string" && px.test(path)) {
                                value[i] = eval(path);
                            } else {
                                rez(element);
                            }
                        }
                    });
                } else {
                    Object.keys(value).forEach(function (name) {
                        var item = value[name];
                        if (typeof item === "object" && item !== null) {
                            var path = item.$ref;
                            if (typeof path === "string" && px.test(path)) {
                                value[name] = eval(path);
                            } else {
                                rez(item);
                            }
                        }
                    });
                }
            }
        }($));
        return $;
    };
}
/*
jQuery Credit Card Validator 1.0

Copyright 2012-2015 Pawel Decowski
*/
(function(){var n,t=[].indexOf||function(n){for(var t=0,e=this.length;e>t;t++)if(t in this&&this[t]===n)return t
return-1}
n=jQuery,n.fn.validateCreditCard=function(e,r){var a,l,i,u,c,h,o,f,v,p,s,d,g
for(u=[{name:"amex",pattern:/^3[47]/,valid_length:[15]},{name:"diners_club_carte_blanche",pattern:/^30[0-5]/,valid_length:[14]},{name:"diners_club_international",pattern:/^36/,valid_length:[14]},{name:"jcb",pattern:/^35(2[89]|[3-8][0-9])/,valid_length:[16]},{name:"laser",pattern:/^(6304|670[69]|6771)/,valid_length:[16,17,18,19]},{name:"visa_electron",pattern:/^(4026|417500|4508|4844|491(3|7))/,valid_length:[16]},{name:"visa",pattern:/^4/,valid_length:[16]},{name:"mastercard",pattern:/^5[1-5]/,valid_length:[16]},{name:"maestro",pattern:/^(5018|5020|5038|6304|6759|676[1-3])/,valid_length:[12,13,14,15,16,17,18,19]},{name:"discover",pattern:/^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)/,valid_length:[16]}],a=!1,e&&("object"==typeof e?(r=e,a=!1,e=null):"function"==typeof e&&(a=!0)),null==r&&(r={}),null==r.accept&&(r.accept=function(){var n,t,e
for(e=[],n=0,t=u.length;t>n;n++)l=u[n],e.push(l.name)
return e}()),g=r.accept,s=0,d=g.length;d>s;s++)if(i=g[s],t.call(function(){var n,t,e
for(e=[],n=0,t=u.length;t>n;n++)l=u[n],e.push(l.name)
return e}(),i)<0)throw"Credit card type '"+i+"' is not supported"
return c=function(n){var e,a,c
for(c=function(){var n,e,a,i
for(i=[],n=0,e=u.length;e>n;n++)l=u[n],a=l.name,t.call(r.accept,a)>=0&&i.push(l)
return i}(),e=0,a=c.length;a>e;e++)if(i=c[e],n.match(i.pattern))return i
return null},o=function(n){var t,e,r,a,l,i
for(r=0,i=n.split("").reverse(),e=a=0,l=i.length;l>a;e=++a)t=i[e],t=+t,e%2?(t*=2,r+=10>t?t:t-9):r+=t
return r%10===0},h=function(n,e){var r
return r=n.length,t.call(e.valid_length,r)>=0},p=function(){return function(n){var t,e
return i=c(n),e=!1,t=!1,null!=i&&(e=o(n),t=h(n,i)),{card_type:i,valid:e&&t,luhn_valid:e,length_valid:t}}}(this),v=function(t){return function(){var e
return e=f(n(t).val()),p(e)}}(this),f=function(n){return n.replace(/[ -]/g,"")},a?(this.on("input.jccv",function(t){return function(){return n(t).off("keyup.jccv"),e.call(t,v())}}(this)),this.on("keyup.jccv",function(n){return function(){return e.call(n,v())}}(this)),e.call(this,v()),this):v()}}).call(this)
/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/
jQuery.easing["jswing"]=jQuery.easing["swing"];jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(a,b,c,d,e){return jQuery.easing[jQuery.easing.def](a,b,c,d,e)},easeInQuad:function(a,b,c,d,e){return d*(b/=e)*b+c},easeOutQuad:function(a,b,c,d,e){return-d*(b/=e)*(b-2)+c},easeInOutQuad:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b+c;return-d/2*(--b*(b-2)-1)+c},easeInCubic:function(a,b,c,d,e){return d*(b/=e)*b*b+c},easeOutCubic:function(a,b,c,d,e){return d*((b=b/e-1)*b*b+1)+c},easeInOutCubic:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b+c;return d/2*((b-=2)*b*b+2)+c},easeInQuart:function(a,b,c,d,e){return d*(b/=e)*b*b*b+c},easeOutQuart:function(a,b,c,d,e){return-d*((b=b/e-1)*b*b*b-1)+c},easeInOutQuart:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b*b+c;return-d/2*((b-=2)*b*b*b-2)+c},easeInQuint:function(a,b,c,d,e){return d*(b/=e)*b*b*b*b+c},easeOutQuint:function(a,b,c,d,e){return d*((b=b/e-1)*b*b*b*b+1)+c},easeInOutQuint:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b*b*b+c;return d/2*((b-=2)*b*b*b*b+2)+c},easeInSine:function(a,b,c,d,e){return-d*Math.cos(b/e*(Math.PI/2))+d+c},easeOutSine:function(a,b,c,d,e){return d*Math.sin(b/e*(Math.PI/2))+c},easeInOutSine:function(a,b,c,d,e){return-d/2*(Math.cos(Math.PI*b/e)-1)+c},easeInExpo:function(a,b,c,d,e){return b==0?c:d*Math.pow(2,10*(b/e-1))+c},easeOutExpo:function(a,b,c,d,e){return b==e?c+d:d*(-Math.pow(2,-10*b/e)+1)+c},easeInOutExpo:function(a,b,c,d,e){if(b==0)return c;if(b==e)return c+d;if((b/=e/2)<1)return d/2*Math.pow(2,10*(b-1))+c;return d/2*(-Math.pow(2,-10*--b)+2)+c},easeInCirc:function(a,b,c,d,e){return-d*(Math.sqrt(1-(b/=e)*b)-1)+c},easeOutCirc:function(a,b,c,d,e){return d*Math.sqrt(1-(b=b/e-1)*b)+c},easeInOutCirc:function(a,b,c,d,e){if((b/=e/2)<1)return-d/2*(Math.sqrt(1-b*b)-1)+c;return d/2*(Math.sqrt(1-(b-=2)*b)+1)+c},easeInElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e)==1)return c+d;if(!g)g=e*.3;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return-(h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g))+c},easeOutElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e)==1)return c+d;if(!g)g=e*.3;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return h*Math.pow(2,-10*b)*Math.sin((b*e-f)*2*Math.PI/g)+d+c},easeInOutElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e/2)==2)return c+d;if(!g)g=e*.3*1.5;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);if(b<1)return-.5*h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)+c;return h*Math.pow(2,-10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)*.5+d+c},easeInBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;return d*(b/=e)*b*((f+1)*b-f)+c},easeOutBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;return d*((b=b/e-1)*b*((f+1)*b+f)+1)+c},easeInOutBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;if((b/=e/2)<1)return d/2*b*b*(((f*=1.525)+1)*b-f)+c;return d/2*((b-=2)*b*(((f*=1.525)+1)*b+f)+2)+c},easeInBounce:function(a,b,c,d,e){return d-jQuery.easing.easeOutBounce(a,e-b,0,d,e)+c},easeOutBounce:function(a,b,c,d,e){if((b/=e)<1/2.75){return d*7.5625*b*b+c}else if(b<2/2.75){return d*(7.5625*(b-=1.5/2.75)*b+.75)+c}else if(b<2.5/2.75){return d*(7.5625*(b-=2.25/2.75)*b+.9375)+c}else{return d*(7.5625*(b-=2.625/2.75)*b+.984375)+c}},easeInOutBounce:function(a,b,c,d,e){if(b<e/2)return jQuery.easing.easeInBounce(a,b*2,0,d,e)*.5+c;return jQuery.easing.easeOutBounce(a,b*2-e,0,d,e)*.5+d*.5+c}});
/*!
 * jQuery Transit - CSS3 transitions and transformations
 * (c) 2011-2012 Rico Sta. Cruz <rico@ricostacruz.com>
 * MIT Licensed.
 *
 * http://ricostacruz.com/jquery.transit
 * http://github.com/rstacruz/jquery.transit
 */
;(function(k){k.transit={version:"0.9.9",propertyMap:{marginLeft:"margin",marginRight:"margin",marginBottom:"margin",marginTop:"margin",paddingLeft:"padding",paddingRight:"padding",paddingBottom:"padding",paddingTop:"padding"},enabled:true,useTransitionEnd:false};var d=document.createElement("div");var q={};function b(v){if(v in d.style){return v}var u=["Moz","Webkit","O","ms"];var r=v.charAt(0).toUpperCase()+v.substr(1);if(v in d.style){return v}for(var t=0;t<u.length;++t){var s=u[t]+r;if(s in d.style){return s}}}function e(){d.style[q.transform]="";d.style[q.transform]="rotateY(90deg)";return d.style[q.transform]!==""}var a=navigator.userAgent.toLowerCase().indexOf("chrome")>-1;q.transition=b("transition");q.transitionDelay=b("transitionDelay");q.transform=b("transform");q.transformOrigin=b("transformOrigin");q.transform3d=e();var i={transition:"transitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd",WebkitTransition:"webkitTransitionEnd",msTransition:"MSTransitionEnd"};var f=q.transitionEnd=i[q.transition]||null;for(var p in q){if(q.hasOwnProperty(p)&&typeof k.support[p]==="undefined"){k.support[p]=q[p]}}d=null;k.cssEase={_default:"ease","in":"ease-in",out:"ease-out","in-out":"ease-in-out",snap:"cubic-bezier(0,1,.5,1)",easeOutCubic:"cubic-bezier(.215,.61,.355,1)",easeInOutCubic:"cubic-bezier(.645,.045,.355,1)",easeInCirc:"cubic-bezier(.6,.04,.98,.335)",easeOutCirc:"cubic-bezier(.075,.82,.165,1)",easeInOutCirc:"cubic-bezier(.785,.135,.15,.86)",easeInExpo:"cubic-bezier(.95,.05,.795,.035)",easeOutExpo:"cubic-bezier(.19,1,.22,1)",easeInOutExpo:"cubic-bezier(1,0,0,1)",easeInQuad:"cubic-bezier(.55,.085,.68,.53)",easeOutQuad:"cubic-bezier(.25,.46,.45,.94)",easeInOutQuad:"cubic-bezier(.455,.03,.515,.955)",easeInQuart:"cubic-bezier(.895,.03,.685,.22)",easeOutQuart:"cubic-bezier(.165,.84,.44,1)",easeInOutQuart:"cubic-bezier(.77,0,.175,1)",easeInQuint:"cubic-bezier(.755,.05,.855,.06)",easeOutQuint:"cubic-bezier(.23,1,.32,1)",easeInOutQuint:"cubic-bezier(.86,0,.07,1)",easeInSine:"cubic-bezier(.47,0,.745,.715)",easeOutSine:"cubic-bezier(.39,.575,.565,1)",easeInOutSine:"cubic-bezier(.445,.05,.55,.95)",easeInBack:"cubic-bezier(.6,-.28,.735,.045)",easeOutBack:"cubic-bezier(.175, .885,.32,1.275)",easeInOutBack:"cubic-bezier(.68,-.55,.265,1.55)"};k.cssHooks["transit:transform"]={get:function(r){return k(r).data("transform")||new j()},set:function(s,r){var t=r;if(!(t instanceof j)){t=new j(t)}if(q.transform==="WebkitTransform"&&!a){s.style[q.transform]=t.toString(true)}else{s.style[q.transform]=t.toString()}k(s).data("transform",t)}};k.cssHooks.transform={set:k.cssHooks["transit:transform"].set};if(k.fn.jquery<"1.8"){k.cssHooks.transformOrigin={get:function(r){return r.style[q.transformOrigin]},set:function(r,s){r.style[q.transformOrigin]=s}};k.cssHooks.transition={get:function(r){return r.style[q.transition]},set:function(r,s){r.style[q.transition]=s}}}n("scale");n("translate");n("rotate");n("rotateX");n("rotateY");n("rotate3d");n("perspective");n("skewX");n("skewY");n("x",true);n("y",true);function j(r){if(typeof r==="string"){this.parse(r)}return this}j.prototype={setFromString:function(t,s){var r=(typeof s==="string")?s.split(","):(s.constructor===Array)?s:[s];r.unshift(t);j.prototype.set.apply(this,r)},set:function(s){var r=Array.prototype.slice.apply(arguments,[1]);if(this.setter[s]){this.setter[s].apply(this,r)}else{this[s]=r.join(",")}},get:function(r){if(this.getter[r]){return this.getter[r].apply(this)}else{return this[r]||0}},setter:{rotate:function(r){this.rotate=o(r,"deg")},rotateX:function(r){this.rotateX=o(r,"deg")},rotateY:function(r){this.rotateY=o(r,"deg")},scale:function(r,s){if(s===undefined){s=r}this.scale=r+","+s},skewX:function(r){this.skewX=o(r,"deg")},skewY:function(r){this.skewY=o(r,"deg")},perspective:function(r){this.perspective=o(r,"px")},x:function(r){this.set("translate",r,null)},y:function(r){this.set("translate",null,r)},translate:function(r,s){if(this._translateX===undefined){this._translateX=0}if(this._translateY===undefined){this._translateY=0}if(r!==null&&r!==undefined){this._translateX=o(r,"px")}if(s!==null&&s!==undefined){this._translateY=o(s,"px")}this.translate=this._translateX+","+this._translateY}},getter:{x:function(){return this._translateX||0},y:function(){return this._translateY||0},scale:function(){var r=(this.scale||"1,1").split(",");if(r[0]){r[0]=parseFloat(r[0])}if(r[1]){r[1]=parseFloat(r[1])}return(r[0]===r[1])?r[0]:r},rotate3d:function(){var t=(this.rotate3d||"0,0,0,0deg").split(",");for(var r=0;r<=3;++r){if(t[r]){t[r]=parseFloat(t[r])}}if(t[3]){t[3]=o(t[3],"deg")}return t}},parse:function(s){var r=this;s.replace(/([a-zA-Z0-9]+)\((.*?)\)/g,function(t,v,u){r.setFromString(v,u)})},toString:function(t){var s=[];for(var r in this){if(this.hasOwnProperty(r)){if((!q.transform3d)&&((r==="rotateX")||(r==="rotateY")||(r==="perspective")||(r==="transformOrigin"))){continue}if(r[0]!=="_"){if(t&&(r==="scale")){s.push(r+"3d("+this[r]+",1)")}else{if(t&&(r==="translate")){s.push(r+"3d("+this[r]+",0)")}else{s.push(r+"("+this[r]+")")}}}}}return s.join(" ")}};function m(s,r,t){if(r===true){s.queue(t)}else{if(r){s.queue(r,t)}else{t()}}}function h(s){var r=[];k.each(s,function(t){t=k.camelCase(t);t=k.transit.propertyMap[t]||k.cssProps[t]||t;t=c(t);if(k.inArray(t,r)===-1){r.push(t)}});return r}function g(s,v,x,r){var t=h(s);if(k.cssEase[x]){x=k.cssEase[x]}var w=""+l(v)+" "+x;if(parseInt(r,10)>0){w+=" "+l(r)}var u=[];k.each(t,function(z,y){u.push(y+" "+w)});return u.join(", ")}k.fn.transition=k.fn.transit=function(z,s,y,C){var D=this;var u=0;var w=true;if(typeof s==="function"){C=s;s=undefined}if(typeof y==="function"){C=y;y=undefined}if(typeof z.easing!=="undefined"){y=z.easing;delete z.easing}if(typeof z.duration!=="undefined"){s=z.duration;delete z.duration}if(typeof z.complete!=="undefined"){C=z.complete;delete z.complete}if(typeof z.queue!=="undefined"){w=z.queue;delete z.queue}if(typeof z.delay!=="undefined"){u=z.delay;delete z.delay}if(typeof s==="undefined"){s=k.fx.speeds._default}if(typeof y==="undefined"){y=k.cssEase._default}s=l(s);var E=g(z,s,y,u);var B=k.transit.enabled&&q.transition;var t=B?(parseInt(s,10)+parseInt(u,10)):0;if(t===0){var A=function(F){D.css(z);if(C){C.apply(D)}if(F){F()}};m(D,w,A);return D}var x={};var r=function(H){var G=false;var F=function(){if(G){D.unbind(f,F)}if(t>0){D.each(function(){this.style[q.transition]=(x[this]||null)})}if(typeof C==="function"){C.apply(D)}if(typeof H==="function"){H()}};if((t>0)&&(f)&&(k.transit.useTransitionEnd)){G=true;D.bind(f,F)}else{window.setTimeout(F,t)}D.each(function(){if(t>0){this.style[q.transition]=E}k(this).css(z)})};var v=function(F){this.offsetWidth;r(F)};m(D,w,v);return this};function n(s,r){if(!r){k.cssNumber[s]=true}k.transit.propertyMap[s]=q.transform;k.cssHooks[s]={get:function(v){var u=k(v).css("transit:transform");return u.get(s)},set:function(v,w){var u=k(v).css("transit:transform");u.setFromString(s,w);k(v).css({"transit:transform":u})}}}function c(r){return r.replace(/([A-Z])/g,function(s){return"-"+s.toLowerCase()})}function o(s,r){if((typeof s==="string")&&(!s.match(/^[\-0-9\.]+$/))){return s}else{return""+s+r}}function l(s){var r=s;if(k.fx.speeds[r]){r=k.fx.speeds[r]}return o(r,"ms")}k.transit.getTransitionValue=g})(jQuery);
var hasEasing="easeOutQuad"in jQuery.easing||jQuery.fn.transition?!0:!1,hasCSSTransitions=$.support.transition?!0:!1,hasTouch=jQuery.fn.swipe?!0:!1
hasCSSTransitions&&jQuery.fn.transition||($.fn.transition=$.fn.animate),function(t,n,e,i){var o="ontouchstart"in n||navigator.msMaxTouchPoints>0
	t.createPlugin=function(e,r){function s(){n.console&&console.log&&console.log("[createPlugin] "+Array.prototype.join.call(arguments," // "))}function a(t){return"object"!=typeof t||"Object"!==Object.prototype.toString.call(t).slice(8,-1)}if("string"!=typeof e||!e)return s("Error: you must specify a valid plugin name on the first parameter of the method.",e),i
		if(r==i||!r||a(r))return s("Error: you must specify a valid plugin implementation on the second parameter of the method.",r),i
		if("function"==typeof t.fn[e])return s("Error: there's already a jQuery plugin defined with this name.",e,t.fn[e].constructor),i
		"function"!=typeof Object.create&&(Object.create=function(t){function n(){}return n.prototype=t,new n})
		var u={isTouch:o,hasTouch:hasTouch,hasCSSTransitions:hasCSSTransitions,hasEasing:hasEasing,debug:!1,log_indent:"              - "},c={_name:"",_defaults:{},_init:function(n,e,i){this._name=n,this.element=e,this.$element=t(e),this.options=t.extend(u,this._defaults,i),this.viewHeight=this.$element.outerHeight(),this.viewWidth=this.$element.outerWidth(),"function"==typeof this.init&&this.init()},set:function(t,n){if(i!==this.options[t])this.options[t]=n
		else{if(i===this[t])return!1
			this[t]=n}return!0},get:function(t){return this.options[t]},_log:function(t){n.console&&console.log?console.log("["+this._name+"] - "+Array.prototype.join.call(arguments," // ")):alert("["+this._name+"] - "+Array.prototype.join.call(arguments," // "))}}
		t.fn[e]=function(n){var o,s=Array.prototype.slice.call(arguments,1)
			return this.each(function(){var i=this,a=(t(i),"modmb_"+e),u=t.data(i,a)
				if(!u){var h=t.extend({},c,r)
					u=t.data(i,a,Object.create(h)),u&&"function"==typeof u._init&&u._init.apply(u,[e,i,n])}if(u&&"string"==typeof n&&"_"!==n[0]&&"init"!==n){var f="destroy"==n?"_destroy":n
					"function"==typeof u[f]&&(o=u[f].apply(u,s)),"destroy"===n&&t.data(i,a,null)}}),o!==i?o:this}}}(window.jQuery,window,document)

/*
 * jquery.pkd.cart
 *
 * @fileOverview PKD Cart Javascript Object - Used for making requests to the PHP cart module
 *
 * @version 1.4.3
 *
 * Copyright (c) 2016 Bibliopolis (http://www.bibliopolis.com)
 *
 */
var pkd_cart={_defaults:{callbacks:{}},init:function(){},refresh:function(){if(this.options.debug){this._log("refresh()")}},update_values:function(){var t=this.$element.data("action")||"get_values";""!=$.trim(t)&&this.request([t,this.$element.data(),function(){if(""!=this.data){var t=this.instance;$.each(this.data.values,function(a,e){"total"==a&&$("#opamt").text(e.value),$("."+a).length>0&&(e.value>0?t.$element.addClass("active").find("."+a).fadeOut("fast",function(){$(this).html(e.html).fadeIn("fast")}):t.$element.removeClass("active").find("."+a).fadeOut("fast",function(){$(this).html(e.html).fadeIn("fast")}))})}}])},get_clean_data:function(t){if(delete t.modmb_pkdcart,void 0!==t.form_data&&t.form_data.length>0){$.each(t.form_data,function(a,e){"object"==typeof e.value&&(t.form_data[a].value="[object]")})}var a=JSON.stringify(JSON.decycle(t));return a=(a=(a=(a=a.replace(/^(\"|\')+/,"")).replace(/(\"|\')+$/,"")).replace(/\|/,"")).replace(/&/g,"%26")},request:function(t){var a=this,e=void 0===t[0]?"":t[0],n=this.get_clean_data(void 0===t[1]?"":t[1]),i=void 0===t[2]?$.noop():t[2],o=void 0===t[3]?$.noop():t[3],c="";$.ajax({type:"POST",url:"/manager/include/ajax_cart.php",data:"a_func="+e+"&a_params="+n,success:function(t){""!=$.trim(t)&&(c=jQuery.parseJSON(t))},error:function(t,a,n){window.console&&console.log&&console.log("[cart_request] ["+e+"] ["+a+"] "+(""!=n?"["+n+"] ":"")+general_ajax_error)},complete:function(){"get_values"!=e&&c.has_item_error?$.isFunction(o)&&o.call({data:c,instance:a}):$.isFunction(i)&&i.call({data:c,instance:a})}})},_destroy:function(){}};$.createPlugin("pkdcart",pkd_cart);
var modmb_wizard={fromSkip:false,_defaults:{completed:[],active:1,skipCompleted:true,resetFeedback:true,onBeforeNext:"",onNext:"",onChange:"",onBeforeFinish:"",onFinish:"",onAfterFinish:"",onSkip:""},init:function(){var instance=this;this.wizard_panels_length=0;this.$wizard_panels=[];$.each(this.$element.data(),function(idx,value){$.each(instance._defaults,function(key,obj){if(key==idx){if($.isArray(obj)){value=value+"";instance.options[key]=value.split(",");if(key=="completed"){$.each(instance.options[key],function(key2,value2){instance.options[key][key2]=parseInt(value2)})}}else{instance.options[key]=value}return false}})});this.$element.children(".panel").each(function(idx){var $ele=$(this);var index=idx+1;instance.wizard_panels_length++;instance.$wizard_panels[index]=$ele;$ele.addClass("wizard-todo");if(index==instance.options.active){instance.last_active=index;instance.active=index;instance.next_active=index+1;$ele.addClass("wizard-active").children(".panel-collapse").addClass("in")}if($.inArray(index,instance.options.completed)>-1){$ele.addClass("wizard-complete wizard-completed").removeClass("wizard-todo").children(".panel-collapse").addClass("in")}$ele.data("wizard_index",index);$ele.data("wizard_next",$ele.find(".wizard-next"));$ele.data("wizard_change",$ele.find(".wizard-change"));$ele.data("wizard_skip",$ele.find(".wizard-skip"));$ele.data("wizard_finish",$ele.find(".wizard-finish"));$ele.data("wizard_panel",$ele.find(".panel-collapse"));$ele.data("wizard_next_panel",$($ele.data("wizard_next").attr("href")));$ele.data("wizard_next_panel_parent",$ele.data("wizard_next_panel").parent());$ele.data("wizard_change_panel",$($ele.data("wizard_change").attr("href")));$ele.data("wizard_change_panel_parent",$ele.data("wizard_change_panel").parent());if(instance.options.resetFeedback){$ele.data("wizard_feedback",$ele.find(".panel-feedback"))}instance._init_events($ele)})},refresh:function(){this.last_active=this.active;this.active=this.$wizard_panels[this.next_active].data("wizard_index");this.next_active=this.active+1;this.submit_btn.button("reset")},change:function($ele){$active=this.$wizard_panels[this.active];var instance=this;var skip_scroll=true;if($active.hasClass("wizard-completed")&&$active.hasClass("wizard-todo")){$panel_completed=$active.find(".panel-update");$active.addClass("wizard-loading");var tpl_complete_data={tpl:$panel_completed.data("tpl-completed")};$panel_completed.ajax_template(tpl_complete_data,function(){$active.removeClass("wizard-loading").addClass("wizard-complete");$active.removeClass("wizard-active wizard-todo").children(".panel-collapse");instance.scroll_to_active()})}else{skip_scroll=false;$active.removeClass("wizard-active wizard-todo").children(".panel-collapse").removeClass("in").addClass("collapse")}if($active.children(".panel-collapse").hasClass("keep-in")){$active.children(".panel-collapse").removeClass("keep-in").addClass("in")}$ele.data("wizard_change_panel_parent").removeClass("wizard-complete").addClass("wizard-active wizard-todo");instance.next_active=$ele.data("wizard_change_panel_parent").data("wizard_index");instance.refresh();if(!skip_scroll){instance.scroll_to_active()}if(instance.options.onChange!=""&&$.isFunction(instance.options.onChange)){instance.options.onChange.call({element:$active,change_ele:$ele,instance:instance})}},next:function(t,panel){panel=panel||this.$wizard_panels[this.active];this.submit_btn=t;this.submit_btn.button("loading");var panel_skip=true;if(typeof this.$wizard_panels[this.active+1].find(".panel-collapse").data("skip")!=="undefined"){panel_skip=this.$wizard_panels[this.active+1].find(".panel-collapse").data("skip")=="true"?true:false}var skip=this.options.skipCompleted&&panel_skip?this._check_for_skip(panel):false;if(this.options.onNext!=""&&$.isFunction(this.options.onNext)){if(!skip){this.$wizard_panels[this.next_active].addClass("wizard-loading")}this.options.onNext.call({element:panel,instance:this,skippingPanels:skip})}else if(!skip){this.$wizard_panels[this.next_active].addClass("wizard-loading");this.$wizard_panels[this.active].removeClass("wizard-active wizard-todo wizard-disabled").addClass("wizard-complete wizard-completed");this.next_panel()}if(skip){this.$wizard_panels[this.active].removeClass("wizard-active wizard-todo wizard-disabled").addClass("wizard-complete wizard-completed");this.next_active=this.last_active;this.$wizard_panels[this.next_active].addClass("wizard-loading")}},next_panel:function(){if(this.$wizard_panels[this.next_active].children(".panel-collapse").hasClass("in")){this.$wizard_panels[this.next_active].children(".panel-collapse").removeClass("in").addClass("keep-in")}this.$wizard_panels[this.next_active].children(".panel-collapse").collapse("show")},reset_feedback:function(){$.each(this.$wizard_panels,function(key,val){if(typeof val!=="undefined"){val.data("wizard_feedback").html("")}})},_init_events:function($ele){var instance=this;$ele.data("wizard_skip").on("click",function(event){event.preventDefault();instance.fromSkip=true;instance.submit_btn=$(this);instance.submit_btn.button("loading");instance.$wizard_panels[instance.active].removeClass("wizard-active wizard-todo wizard-disabled").addClass("wizard-complete wizard-completed");if(instance.options.onSkip!=""&&$.isFunction(instance.options.onSkip)){instance.options.onSkip.call({element:instance.$wizard_panels[instance.active],instance:instance})}instance.next_panel();return false});$ele.data("wizard_finish").on("click",function(event){event.preventDefault();instance.submit_btn=$(this);instance.submit_btn.button("loading");if(instance.options.onBeforeFinish!=""&&$.isFunction(instance.options.onBeforeFinish)){instance.options.onBeforeFinish.call({element:instance.$wizard_panels[instance.active],instance:instance,callback_finish:instance.options.onFinish!=""&&$.isFunction(instance.options.onFinish)?instance.options.onFinish:"",callback_after_finish:instance.options.onAfterFinish!=""&&$.isFunction(instance.options.onAfterFinish)?instance.options.onAfterFinish:""})}});$ele.data("wizard_change").on("click",function(event){event.preventDefault();instance.submit_btn=$(this);if(instance.options.resetFeedback){instance.reset_feedback()}instance.submit_btn.button("loading");$panel_change=$ele.find(".panel-update");if($panel_change.data("tpl-change")!=="undefined"){var tpl_data={tpl:$panel_change.data("tpl-change")};$panel_change.ajax_template(tpl_data,function(){instance.change($ele)})}else{instance.change($ele)}return false});$ele.data("wizard_next").on("click",function(event){event.preventDefault();var $ele=instance.$wizard_panels[instance.active];var panel_id=$ele.find(".panel-collapse").attr("id");if(typeof instance.options.onBeforeNext[panel_id]!=="undefined"&&instance.options.onBeforeNext[panel_id]!=""&&$.isFunction(instance.options.onBeforeNext[panel_id])){instance.submit_btn=$(this);instance.submit_btn.button("loading");instance.options.onBeforeNext[panel_id].call({button:$(this),element:$ele,instance:instance})}else{instance.next($(this),$ele)}return false});$ele.on("show.bs.collapse",function(){});$ele.on("shown.bs.collapse",function(){instance.$wizard_panels[instance.next_active].removeClass("wizard-loading wizard-complete").addClass("wizard-active");instance.refresh();instance.scroll_to_active();if(instance.options.onNextAfter!=""&&$.isFunction(instance.options.onNextAfter)&&!instance.fromSkip){instance.options.onNextAfter.call({element:instance.$wizard_panels[instance.active],instance:instance})}instance.fromSkip=false})},afterFinish:function(_data){if(this.options.onAfterFinish!=""&&$.isFunction(this.options.onAfterFinish)){this.options.onAfterFinish.call({data:_data,instance:this,element:this.$wizard_panels[this.active]})}},scroll_to_active:function(){$("html,body").animate({scrollTop:this.$wizard_panels[this.active].offset().top+"px"},420,"swing")},_check_for_skip:function($panel){return this.last_active>$panel.data("wizard_next_panel_parent").data("wizard_index")},_destroy:function(){}};$.createPlugin("modmbwizard",modmb_wizard);
var state_required=true;var braintree_use_paypal=braintree_use_paypal||false;var cart;$(function(){cart=$("#utils-cart").pkdcart("update_values");var line_items=$(".cart-line-items").pkdcart();checkRadio($("#payment").data("payment_type"));checkUSFB();$("#payment").on("change",'input[name="usfb"]',checkUSFB);pkd_init_card_validator();$(document).on("keydown","#fld_cardNumber",function(){$(this).parent().addClass("active")});$(document).on("keyup","#fld_cardNumber",function(){if($(this).parent().hasClass("valid")){var $invalid_cc=$(".invalid-cc");if($invalid_cc.parent().children("p").length==2){$invalid_cc.parent().hide()}else{$invalid_cc.hide()}}});modals.edit_cart=$("#edit_cart_modal");if($("#capture_user_modal").length>0){modals.capture_user=$("#capture_user_modal");modals.capture_user.modal("show");modals.capture_user.on("hidden.bs.modal",function(e){checkForItemErrors()})}else{checkForItemErrors()}$(document).on("click",".edit-cart",function(event){event.preventDefault();var $btn=$(this);$btn.button("loading");modals.edit_cart.data("backdrop",true);modals.edit_cart.ajax_template({tpl:"tpl.cart.v2.modal.edit_cart"},function(){modals.edit_cart.modal("show");if($(".gift-wrap-message").length>0){$(".gift-wrap-message-textarea").countCharValidation(250,".remaining-characters")}$btn.button("reset")})});modals.add_to_cart=$("#add_to_cart_modal");$(document).on("click",".add-to-cart-ajax",function(event){event.preventDefault();var $_this=$(this);var $icon=$_this.find(".fa");var icon_classes=$icon.attr("class");$icon.removeAttr("class").addClass("fa fa-spinner fa-spin");var data={tpl:"tpl.cart.v2.modal.add_to_cart",sku:$_this.data("sku")};modals.add_to_cart.ajax_template(data,function(){modals.add_to_cart.modal("show");cart.pkdcart("request",["add",$_this.data(),function(){$icon.removeClass().addClass(icon_classes);var $modal_response=modals.add_to_cart.find(".modal-response");if(this.data.success){this.instance.update_values();$modal_response.html("");if(typeof ga!=="undefined"){ga("send","event","Cart","checkout-add-to-cart")}else if(typeof _gaq!=="undefined"){_gaq.push(["_trackEvent","Cart","checkout-add-to-cart"])}if(typeof _hsq!=="undefined"){_hsq.push(["trackEvent",{id:"checkout-add-to-cart"}])}if(typeof window[$_this.data("callback")]==="function"){window[$_this.data("callback")].call({data:this.data,instance:this.instance})}}else{$modal_response.html('<div class="alert alert-danger">'+this.data.msg+"</div>")}}])})});$(document).on("click",".update-item",function(event){var $_this=$(this);var $parent=$_this.parentsUntil(".cart-contents-row");var $_row=$parent.parent().parent().parent();$(this).append('<i class="fa fa-spinner fa-spin" />');line_items.pkdcart("request",["update_item",$(this).data(),function(){$_row.find(".fa-spinner").fadeOut();if(this.data.success){location.reload(true)}else{$_row.find(".cart-alert").before('<div class="cart-alert cart-alert-danger cart-alert-delay-remove fade in">'+general_cart_error+"</div>");$_row.find(".cart-alert-delay-remove").delay(4e3).fadeOut(function(){$(this).remove()})}}])});$(document).on("click",".remove-item-alert",function(event){$parent_row=$("#item_"+$(this).data("order_item_id"));if($parent_row.length>0){$parent_row.find('[class*="remove-item"]:visible').not(this).trigger("click")}});$(document).on("click",".remove-item",function(event){if(confirm(delete_confirm_msg)){var $_this=$(this);var $parent=$_this.parentsUntil(".cart-contents-row");var $_row=$parent.eq($parent.length-1).parent();$(this).append('<i class="fa fa-spinner fa-spin" />');line_items.pkdcart("request",["remove",$(this).data(),function(){if(this.data.success){if(parseInt(this.data.numitems)<1){location.reload(true);$_row.fadeOut()}else{this.instance.update_values();cart.pkdcart("update_values");$_row.fadeOut(function(){$_row.remove();if($_row.parent().find(".cart-alert").length==0){$(".cart-checkout").removeClass("disabled")}})}}else{$_row.find(".fa-spinner").fadeOut();$_row.find(".alert").fadeOut();$_row.prepend('<div class="alert alert-danger fade in">'+general_cart_error+"</div>");$_row.find(".alert").delay(4e3).fadeOut(function(){$(this).remove()})}}])}});$(document).on("click",".remove-item-edit",function(event){if(confirm(delete_confirm_msg)){var $_this=$(this);var $parent=$_this.parentsUntil(".cart-contents-row");var $_row=$parent.eq($parent.length-1).parent();$_this.append('<i class="fa fa-spinner fa-spin" />');cart.pkdcart("request",["remove",$(this).data(),function(){if(this.data.success){if(parseInt(this.data.numitems)<1){$_row.fadeOut();location.reload(true)}else{$(".cart-line-items").pkdcart("update_values");$_row.fadeOut(function(){$_row.remove();if($_row.parent().find(".cart-alert").length==0){$(".cart-checkout").removeClass("disabled")}})}}else{$_row.find(".fa-spinner").fadeOut();$_row.find(".alert").fadeOut();$_row.prepend('<div class="alert alert-danger fade in">'+general_cart_error+"</div>");$_row.find(".alert").delay(4e3).fadeOut(function(){$(this).remove()})}}])}});$(document).on("change",".cart-quantity",function(){var $parent=$(this).parent();var order_item_id=$(this).data("order_item_id");if($(this).val()==0){$parent.parent().find(".remove-item, .remove-item-edit").trigger("click")}else{$parent.append('<i class="fa fa-spinner fa-spin" />');var $icon=$parent.find(".fa");var data={order_item_id:order_item_id,quantity:$(this).val()};cart.pkdcart("request",["change_quantity",data,function(){$(".cart-line-items").pkdcart("update_values");var $needs_update_qty=$("#item_"+order_item_id).find(".needs-update-quantity");if($needs_update_qty.length>0){$needs_update_qty.parent().remove();$(".cart-checkout").removeClass("disabled")}$icon.removeClass("fa-spinner fa-spin").addClass("fa-check");setTimeout(function(){$icon.remove()},1e3)},function(){$.each(this.data.errors,function(outerkey,errors){$.each(errors,function(key,val){var $response=$(".cart-contents-row-"+key).find(".cart-content-disclaimer");$response.find(".cart-alert-danger").fadeOut();$response.prepend('<div class="cart-alert cart-alert-danger fade in">'+val+"</div>")})});$icon.remove();$(".cart-checkout").addClass("disabled")}])}});if($("#print_modal").length>0)modals.print=$("#print_modal");$(document).on("click",".print-link",function(event){event.preventDefault();modals.print.modal("show")});modals.edit_cart.on("hide.bs.modal",function(){$("#cart-summary").ajax_template($("#cart-summary").data(),function(){checkCartSummary();if($("#review-order").length>0){$("#review-order .cart-line-items").ajax_template($("#review-order .cart-line-items").data())}})});$(document).on("click",".cart-toggle",function(){var $_this=$(this);$("#cart-summary-items-inner").slideToggle(function(){if($(this).is(":visible")){$_this.addClass("active").html("<span></span>"+cart_hide_label);$("#cart-summary").data("cart-toggle","open");$("#cart-summary-items-inner").attr("aria-expanded",true)}else{$_this.removeClass("active").html("<span></span>"+cart_show_label);$("#cart-summary").data("cart-toggle","closed");$("#cart-summary-items-inner").attr("aria-expanded",false)}})});$(document).on("click","#apply-coupon",function(event){var _this=$(this);var parent=_this.parent().parent().parent();var coupon_val=$("#coupon_code").val();if(coupon_val!=""){_this.append('<i class="fa fa-spinner fa-spin" />');var icon=_this.find("i");line_items.pkdcart("request",["apply_coupon",{coupon_code:coupon_val},function(data){icon.remove();parent.find(".alert").remove();parent.prepend(this.data.msg);parent.find(".alert").delay(4e3).fadeOut(function(){$(this).remove()});if(this.data.success){$("#review-order .cart-line-items").ajax_template($("#review-order .cart-line-items").data(),function(){$("#cart-summary").ajax_template($("#cart-summary").data(),function(){checkCartSummary()})})}}])}return false});if($(".fld-state").length>0){state_required=$(".fld-state").hasClass("required")}checkCountry($(".fld-country"),false);$(document).on("change",".fld-country",function(){checkCountry(this,true)});$wizard=$("#wizard").modmbwizard({onChange:function(){var $change_ele=$(this.change_ele).find(".panel-collapse");if($change_ele.attr("id")=="payment"){checkUSFB();checkRadio($change_ele.data("payment_type"));if($("#token_value").length>0){if(typeof SqPaymentForm!=="undefined"){init_square_payment_form()}if(typeof braintree!=="undefined"){init_braintree_payment_form()}if(typeof Heartland!=="undefined"){init_heartland_payment_form()}}if($change_ele.data("payment_type")=="Bill to Institution"){$('input[name="PONumber"]').val($change_ele.data("po_num"))}}else{if(typeof SqPaymentForm!=="undefined"){sqPaymentForm.destroy()}}if($change_ele.attr("id")=="payment"||$change_ele.attr("id")=="shipping"){if($(".fld-state").length>0){state_required=$(".fld-state").hasClass("required")}checkCountry($(".fld-country"),false)}if($change_ele.attr("id")=="create-account"){if(typeof grecaptcha!=="undefined"){grecaptcha.reset()}}if(typeof cart_panel_update==="function"){cart_panel_update($change_ele.attr("id"),"change")}},onSkip:function(){this.element.find(".panel-update").ajax_template({tpl:"tpl.blank"},function(){})},onBeforeFinish:function(){var _this=this;var _instance=this.instance;var _element=this.element;var $feedback=_element.find(".panel-feedback");var $data_element=_element.find(".panel-collapse");$feedback.html("");var form_data=[];if($("#create-account").length>0){var create_account_data=$("#create-account").data();$.each(create_account_data,function(key,value){form_data.push({name:key,value:value})})}if($("#payment").length>0){var pay_data=$("#payment").data();$.each(pay_data,function(key,value){form_data.push({name:key,value:value})})}form_data.push({name:"comments",value:$(".cart-comments-field").val()});cart.pkdcart("request",["update_panel",{panel:this.element.children(".panel-collapse").attr("id"),form_data:form_data},function(){if(this.data.success){if(typeof ga!=="undefined")ga("send","pageview","/order"+($("#payment").data("payment_type")=="PayPal"||$("#payment").data("payment_type")=="Request PayPal"?"PP":"")+".php");else if(typeof _gaq!=="undefined")_gaq.push(["_trackPageview","/order"+($("#payment").data("payment_type")=="PayPal"||$("#payment").data("payment_type")=="Request PayPal"?"PP":"")+".php"]);if(typeof _hsq!=="undefined"){_hsq.push(["trackEvent",{id:"checkout-success"+($("#payment").data("payment_type")=="PayPal"||$("#payment").data("payment_type")=="Request PayPal"?"-paypal":"")}])}}if(typeof this.data.redirect!=="undefined"){try{window.location.replace(this.data.redirect)}catch(e){window.location=this.data.redirect}}else{if(_this.callback_finish!=""&&$.isFunction(_this.callback_finish)){_this.callback_finish.call({instance:_instance,data:this.data,element:_element,callback:_this.callback_after_finish!=""&&$.isFunction(_this.callback_after_finish)?_this.callback_after_finish:""})}}},function(){$(".wizard-loading").removeClass("wizard-loading");$(".wizard-active .wizard-actions .wizard-finish").button("reset");modals.edit_cart.ajax_template({tpl:"tpl.cart.v2.modal.edit_cart"},function(){modals.edit_cart.modal({backdrop:"static"});$(".cart-checkout").addClass("disabled");modals.edit_cart.modal("show");if($(".gift-wrap-message").length>0){$(".gift-wrap-message-textarea").countCharValidation(250,".remaining-characters")}})}])},onFinish:function(){if(this.callback!=""&&$.isFunction(this.callback)){this.callback.call({instance:this.instance,element:this.element,data:this.data})}},onAfterFinish:function(){var instance=this.instance;var $feedback=this.element.find(".panel-feedback");var $data_element=this.element.find(".panel-collapse");if(this.data.success){$("html,body").animate({scrollTop:"0px"});$("#checkout-page").parent().ajax_template({tpl:$data_element.hasClass("cim")&&$("#payment").data("payment_type")=="Credit Card"?"tpl.checkout.success.cim":"tpl.checkout.success"},function(){modals.print.ajax_template({tpl:"tpl.checkout.success.print"},function(){cart.pkdcart("request",["update_panel",{panel:"mark-order"}])})})}else{$feedback.html("").append('<div class="alert alert-danger">'+this.data.msg+"</div>");if("fields"in this.data){$(this.data.fields).addClass("error").parent().parent().addClass("has-error")}instance.submit_btn.button("reset")}},onNext:function(){var errors_html="";var errors=[];var instance=this.instance;$("#checkout-page").data("checkout_instance",this);var $feedback=this.element.find(".panel-feedback");var $data_element=this.element.find(".panel-collapse");var $panel_completed=this.element.find(".panel-update");var recaptcha_executed=false;$feedback.html("");if($data_element.attr("id")=="payment"){var payment_type=$("input:radio[name=paymentType]:checked").val();$data_element.data("payment_type",payment_type);$("#checkout-page").data("payment_type_label",$("input:radio[name=paymentType]:checked").parent().text());switch(payment_type){case"Credit Card":if(!$data_element.hasClass("cim")&&$("#token_value").length<1){var form_data=$(".checkout-billing .form-group *[name]").serializeArray();form_data.push({name:"payment_type",value:payment_type});var cc_error=false;this.element.find(".reqfld").each(function(idx,obj){$(obj).removeClass("error success info warning").parent().parent().removeClass("has-error");if($(obj).val()==""){if(errors.length==0&&$("#fld_exp_month").length>0){errors_html+="<p>"+credit_card_required_error+"</p>"}errors.push($(this));cc_error=true}});var year=parseInt($("#fld_exp_year").val());var month=parseInt($("#fld_exp_month").val());if(checkDateBeforeToday(new Date(year,month,0))){errors.push($("#fld_exp_year"));errors.push($("#fld_exp_month"));errors_html+='<p class="invalid-cc">'+credit_card_expired_error+"</p>";cc_error=true}if(!$('#payment input[name="usfb"]').is(":checked")){this.element.find(".form-control").removeClass("error success info warning").parent().parent().removeClass("has-error");this.element.find(".required:not(div)").each(function(idx,obj){if($(obj).val()==""){if(errors.length==0){errors_html+="<p>"+required_fields_error+"</p>"}errors.push($(this))}})}if(!is_valid){errors.push($cc_num);errors_html+='<p class="invalid-cc">'+valid_credit_card_error+"</p>"}else if(!cc_error){var ccnum=$cc_num.val();var ccnum_last4=ccnum.slice(-4);var cc_type=$(".card-number-validator").data("type");$data_element.data("cc_type",cc_type);$data_element.data("ccnum",ccnum);$data_element.data("cc_num_last4",ccnum_last4);$data_element.data("cc_exp_month",$("#fld_exp_month").val());$data_element.data("cc_exp_year",$("#fld_exp_year").val());if($("#cart-cvv").length>0){$data_element.data("cvv",$("#cart-cvv").val())}form_data.push({name:"cc_type",value:cc_type})}}else if($data_element.hasClass("cim")){$panel_completed.data("tpl-completed","tpl.checkout.payment.cim.completed");var form_data=$data_element.find(".active").children('input[type="hidden"]').serializeArray();form_data.push({name:"payment_type",value:payment_type});if($data_element.find(".active").data("payment_profile_id")==null){errors.push($("#checkout-payment-cc"));errors_html+='<p class="invalid-cc">'+credit_card_select_error+"</p>"}else{form_data.push({name:"payment_profile_id",value:$data_element.find(".active").data("payment_profile_id")})}}else if($("#token_value").length>0){if(!$('#payment input[name="usfb"]').is(":checked")){if(!$('#payment input[name="usfb"]').is(":checked")){this.element.find(".form-control").removeClass("error success info warning").parent().parent().removeClass("has-error");this.element.find(".required:not(div)").each(function(idx,obj){if($(obj).val()==""){if(errors.length==0){errors_html+="<p>"+required_fields_error+"</p>"}errors.push($(this))}})}}if($("#token_value").val()==""){errors_html+='<p class="invalid-cc">'+credit_card_required_error+"</p>";errors.push($("#checkout-payment-cc"))}if(pkd_gateway=="Square"&&use_3dsecure&&$data_element.data("cc_verification")==null){errors_html+='<p class="invalid-cc">'+general_cart_error+"</p>";errors.push($("#checkout-payment-cc"))}var form_data=$(".checkout-billing .form-group *[name]").serializeArray();form_data.push({name:"payment_type",value:payment_type});form_data.push({name:"token_value",value:$("#token_value").val()});if($data_element.data("cc_verification")!=null){form_data.push({name:"verification_token",value:$data_element.data("cc_verification")})}}break;case"Bill to Institution":var form_data=$(".checkout-billing .form-group *[name]").serializeArray();form_data.push({name:"payment_type",value:payment_type});var $po=$('input[name="PONumber"]');if($po.hasClass("po_is_req")){if($po.val()==""){errors.push($po);errors_html+="<p>"+purchase_order_num_error+"</p>"}}else{if(!$('#payment input[name="usfb"]').is(":checked")){this.element.find(".form-control").removeClass("error success info warning").parent().parent().removeClass("has-error");if(!$('#payment input[name="usfb"]').is(":checked")){this.element.find(".required:not(div)").each(function(idx,obj){if($(obj).val()==""){if(errors.length==0){errors_html+="<p>"+required_fields_error+"</p>"}errors.push($(this))}})}}}$data_element.data("po_num",$po.val());if($data_element.hasClass("cim")){form_data.push({name:"payment_profile_id",value:0})}if($data_element.hasClass("cim")){$panel_completed.data("tpl-completed","tpl.checkout.payment.completed")}break;case"PayPal":if(braintree_use_paypal){var form_data=[];if($("#token_value").length>0&&$("#token_value").val()!=""){var form_data=[{name:"token_value",value:$("#token_value").val()}]}else{errors_html+="<p>"+paypal_general_error+"</p>";errors.push($("#token_value"))}}else{var form_data=$(".checkout-billing .form-group *[name]").serializeArray();if(!$('#payment input[name="usfb"]').is(":checked")){this.element.find(".form-control").removeClass("error success info warning").parent().parent().removeClass("has-error");this.element.find(".required:not(div)").each(function(idx,obj){if($(obj).val()==""){if(errors.length==0){errors_html+="<p>"+required_fields_error+"</p>"}errors.push($(this))}})}}form_data.push({name:"payment_type",value:payment_type});break;default:var form_data=$(".checkout-billing .form-group *[name]").serializeArray();form_data.push({name:"payment_type",value:payment_type});if($data_element.hasClass("cim")){form_data.push({name:"payment_profile_id",value:0})}if(!$('#payment input[name="usfb"]').is(":checked")){this.element.find(".form-control").removeClass("error success info warning").parent().parent().removeClass("has-error");this.element.find(".required:not(div)").each(function(idx,obj){if($(obj).val()==""){if(errors.length==0){errors_html+="<p>"+required_fields_error+"</p>"}errors.push($(this))}})}if($data_element.hasClass("cim")){$panel_completed.data("tpl-completed","tpl.checkout.payment.completed")}break}if($("#billing_save_changes").length>0&&($("#billing_save_changes").attr("type")=="hidden"||$("#billing_save_changes").is(":checked"))){form_data.push({name:"save_changes",value:$("#billing_save_changes").val()})}form_data.push({name:"java_enabled",value:window.navigator.javaEnabled()});form_data.push({name:"color_depth",value:screen.colorDepth});form_data.push({name:"screen_height",value:screen.height});form_data.push({name:"screen_width",value:screen.width});form_data.push({name:"timezone_offset",value:(new Date).getTimezoneOffset()});form_data.push({name:"browser_lang",value:window.navigator.language})}else{this.element.find(".form-control").removeClass("error success info warning").parent().parent().removeClass("has-error");this.element.find(".required:not(div)").each(function(idx,obj){if($(obj).val()==""){errors.push($(this));errors_html="<p>"+required_fields_error+"</p>"}});if($data_element.attr("id")=="shipping"&&$data_element.hasClass("cim")){var form_data=$data_element.find(".active").children("form").serializeArray();form_data.push({name:"shipping_profile_id",value:$data_element.find(".active").data("shipping_profile_id")})}else if($data_element.attr("id")=="create-account"){if($("#create-username").val()==""&&$("#create-password").val()==""){instance.$wizard_panels[instance.next_active].removeClass("wizard-loading");instance.submit_btn.button("reset");instance.$wizard_panels[instance.active].data("wizard_skip").trigger("click");return 0}else{if(errors.length==0){grecaptcha.execute();recaptcha_executed=true}}}}if(!recaptcha_executed){$feedback.html("");if(errors.length>0){$.each(errors,function(idx,obj){$(this).addClass("error").parent().parent().addClass("has-error")});$feedback.append('<div class="alert alert-danger">'+errors_html+"</div>");instance.$wizard_panels[instance.next_active].removeClass("wizard-loading");instance.submit_btn.button("reset");if($data_element.attr("id")=="create-account"){if(typeof grecaptcha!=="undefined"){grecaptcha.reset()}}return false}var element=this.element;var skippingPanels=this.skippingPanels;var data={panel:element.children(".panel-collapse").attr("id"),form_data:typeof form_data!=="undefined"?form_data:$data_element.find("form").serializeArray()};cart.pkdcart("request",["update_panel",data,function(){var this_data=this.data;var cart_instance=this.instance;if(typeof this.data.redirect!=="undefined"){try{window.location.replace(this.data.redirect)}catch(e){window.location=this.data.redirect}}if(this.data.success){if(typeof ga!=="undefined")ga("send","event","Cart","checkout-"+data.panel);else if(typeof _gaq!=="undefined")_gaq.push(["_trackEvent","Cart","checkout-"+data.panel]);if(typeof _hsq!=="undefined"){_hsq.push(["trackEvent",{id:"checkout-"+data.panel}])}if($panel_completed.data("tpl-completed")!=="undefined"){var tpl_complete_data={tpl:$panel_completed.data("tpl-completed")};$panel_completed.ajax_template(tpl_complete_data,function(){instance.$wizard_panels[instance.active].removeClass("wizard-active wizard-todo").addClass("wizard-complete wizard-completed");if($panel_completed.data("tpl-completed")=="tpl.checkout.payment.completed"||$panel_completed.data("tpl-completed")=="tpl.checkout.payment.ship_exempt.completed"){$("#payment_type").text($("#checkout-page").data("payment_type_label")!=""?$("#checkout-page").data("payment_type_label"):$data_element.data("payment_type"));switch($data_element.data("payment_type")){case"Credit Card":if($("#payment_type_info").length>0){$("#payment_type_info").html('<p><span class="capitalize">'+$data_element.data("cc_type")+"</span> "+cart_cc_ending_in_text+" "+$data_element.data("cc_num_last4")+(typeof $data_element.data("cc_exp_month")!=="undefined"||typeof $data_element.data("cc_exp_year")!=="undefined"?"<br/>"+expires_text+" "+(typeof $data_element.data("cc_exp_month")!=="undefined"?$data_element.data("cc_exp_month")+"/":"")+(typeof $data_element.data("cc_exp_year")!=="undefined"?$data_element.data("cc_exp_year"):""):"")+"</p>")}break;case"Bill to Institution":if($("#payment_type_info").length>0){$("#payment_type_info").html("<p>"+po_number_text+": "+$data_element.data("po_num")+"</p>")}break}}else if($panel_completed.data("tpl-completed")=="tpl.checkout.create_account.completed"){if($("#account-info").length>0){var pass=$data_element.data("create-password");var str_pass=pass.replace(/./g,"*");$("#account-info").html("<p><em>"+$data_element.data("create-username")+"</em><br/>"+str_pass+"</p>")}}cart_instance.update_values();$("#cart-summary").ajax_template($("#cart-summary").data(),function(){checkCartSummary();if(typeof this_data.panels!=="undefined"&&this_data.panels!=""){var panels=[];$.each(this_data.panels,function(id,actions){if(actions.reload){var $update=$("#"+id).find(".panel-update");panels.push({panel:$update,tpl:$update.data("tpl-reload")})}});if(panels.length>0){if(skippingPanels){if(panels.length>1){panels[1].panel.ajax_template(panels[1],function(){if(panels.length>2){panels[2].panel.ajax_template(panels[2],function(){instance.next_panel()})}else{instance.next_panel()}})}else{instance.next_panel()}}else{panels[0].panel.ajax_template(panels[0],function(){if(panels.length>1){panels[1].panel.ajax_template(panels[1],function(){if(panels.length>2){panels[2].panel.ajax_template(panels[2],function(){instance.next_panel()})}else{instance.next_panel()}})}else{instance.next_panel()}})}}}else{instance.next_panel()}})})}}else{$feedback.html("").append('<div class="alert alert-danger">'+this.data.msg+"</div>");$(this.data.fields).addClass("error").parent().parent().addClass("has-error");instance.$wizard_panels[instance.next_active].removeClass("wizard-loading");instance.submit_btn.button("reset")}},function(){$(".wizard-loading").removeClass("wizard-loading");$(".wizard-active .wizard-actions .wizard-next").button("reset");modals.edit_cart.ajax_template({tpl:"tpl.cart.v2.modal.edit_cart"},function(){modals.edit_cart.modal({backdrop:"static"});$(".cart-checkout").addClass("disabled");modals.edit_cart.modal("show");if($(".gift-wrap-message").length>0){$(".gift-wrap-message-textarea").countCharValidation(250,".remaining-characters")}})}])}},onNextAfter:function(){var $data_element=this.element.find(".panel-collapse");if($data_element.attr("id")=="payment"){checkUSFB();checkRadio();pkd_init_card_validator();if($(".fld-state").length>0){state_required=$(".fld-state").hasClass("required")}checkCountry($(".fld-country"),false);if($("#token_value").length>0){if(typeof SqPaymentForm!=="undefined"){init_square_payment_form()}if(typeof braintree!=="undefined"){init_braintree_payment_form()}if(typeof Heartland!=="undefined"){init_heartland_payment_form()}}}else{if(typeof SqPaymentForm!=="undefined"){sqPaymentForm.destroy()}}if(typeof cart_panel_update==="function"){cart_panel_update($data_element.attr("id"),"next")}}});$("body").tooltip({selector:".cart-line-item a, .tooltip-link",container:"body"});$("#checkout-page").on("click",".use-shipping-address",function(){$(".address-card .well").removeClass("active");$(this).addClass("active").parent().parent().removeClass("error")});$("#checkout-page").on("click",".use-payment-profile",function(){$(".payment-card .well").removeClass("active");$(this).addClass("active").parent().parent().removeClass("error")});modals.login=$("#login_modal");$("#main-content").on("click",".ajax-login",function(event){event.preventDefault();modals.login.ajax_template({tpl:"tpl.acct.modal.login"},function(){modals.login.modal("show")})});$(document).on("submit","#checkout-new-customer-form",function(){var $form=$(this);var errors_html="";var errors=[];$form.find(".alert").remove();$form.find(".required").each(function(idx,obj){$(obj).removeClass("error success info warning").parent().parent().removeClass("has-error");if($(obj).val()==""){errors.push($(this));errors_html="<p>"+required_fields_error+"</p>"}});if(errors.length>0){$.each(errors,function(idx,obj){$(this).addClass("error").parent().parent().addClass("has-error")});$form.prepend('<div class="alert alert-danger">'+errors_html+"</div>");return false}});$(document).on("submit","#checkout-returning-customer-form, #capture-customer-form",function(){var $form=$(this);var errors_html="";var errors=[];var $submit_btn=$form.find(".form-actions").children(".btn").button("loading");var form_data=$form.serializeArray();$form.find(".alert").remove();$form.find(".required").each(function(idx,obj){$(obj).removeClass("error success info warning").parent().parent().removeClass("has-error");if($(obj).val()==""){errors.push($(this));errors_html="<p>"+required_fields_error+"</p>"}});if(errors.length>0){$.each(errors,function(idx,obj){$(this).addClass("error").parent().parent().addClass("has-error")});$form.prepend('<div class="alert alert-danger">'+errors_html+"</div>");$submit_btn.button("reset");return false}$.ajax({type:"POST",url:pkd_loc.httppath+"manager/include/ajax_call.php",data:"a_func=pkd_login_user&a_params="+JSON.stringify(form_data),success:function(raw_data){var data=jQuery.parseJSON(raw_data);if(data.success){window.location.href=pkd_loc.httppath+(!pkd_is_payment_page?"checkout.php":"payment.php")}else{$form.prepend(data.msg);$submit_btn.button("reset")}}});return false})});var $wizard;function checkRadio(val){var checkObj=$("input:radio[name=paymentType]:checked");if(typeof val!=="undefined"){$('input:radio[value="'+val+'"]').prop("checked",true)}var val=val||checkObj.val();var pay_type=val;var checkObjParent=checkObj.closest(".panel-collapse");if(val=="Credit Card"){pkd_init_card_validator();if(checkObjParent.hasClass("cim")){$(".cim-hide").fadeOut();$(".checkout-payment-left").addClass("cim-full-width")}$("#checkout-payment-cc").fadeIn("fast")}else{if(checkObjParent.hasClass("cim")){$(".cim-hide").fadeIn();$(".checkout-payment-left").removeClass("cim-full-width")}$("#checkout-payment-cc").fadeOut("fast")}if(val=="Bill to Institution"){$("#checkout-payment-po").fadeIn("fast")}else{$("#checkout-payment-po").fadeOut("fast")}if(braintree_use_paypal){if(val=="PayPal"){$("#checkout-payment-paypal").removeClass("hide");$(".checkout-payment-right").fadeOut("fast");$("#payment .wizard-actions").addClass("hide")}else{$("#checkout-payment-paypal").addClass("hide");$(".checkout-payment-right").fadeIn("fast");$("#payment .wizard-actions").removeClass("hide")}}if(pkd_is_payment_page){if(val=="PayPal"){$("#checkout-payment-paypal").removeClass("hide")}else{$("#checkout-payment-paypal").addClass("hide")}}if(val=="Check/Money Order"||val=="PayPal"||val=="Request PayPal"||val=="Bank Transfer"){$(".checkout-payment-left").fadeOut("fast")}else{$(".checkout-payment-left").fadeIn("fast")}}function checkUSFB(){if($('#payment input[name="usfb"]').is(":checked")){$(".form-group-hide").fadeOut()}else{$(".form-group-hide").fadeIn()}}function checkCountry(obj,changeState){var $this=$(obj);var val=$this.val();if(val==""||val=="??")return;var $form=$this.closest("form");var $province=$form.find(".fld-province");var $state=$form.find(".fld-state");if(val=="US"){$province.val("n/a");$province.attr("disabled","disabled");if(state_required){$province.removeClass("required");$province.parent().parent().find(".req_fld").html("");$state.addClass("required");$state.parent().parent().find(".req_fld").html("*")}$state.removeAttr("disabled");if(changeState){$state.prop("selectedIndex",0)}}else{if(changeState){$province.val("")}$province.removeAttr("disabled");if(state_required){$province.parent().parent().find(".req_fld").html("");$state.removeClass("required");$state.parent().parent().find(".req_fld").html("")}$state.prop("selectedIndex",1);$state.attr("disabled","disabled")}}function checkCartSummary(){var summary_toggle=$("#cart-summary").data("cart-toggle")||"closed";if(summary_toggle=="open"){$(".cart-toggle").addClass("active");$("#cart-summary-items-inner").slideDown()}}function checkForItemErrors(){if($("#checkout-page").hasClass("checkout-has-error")){modals.edit_cart.ajax_template({tpl:"tpl.cart.v2.modal.edit_cart"},function(){modals.edit_cart.modal({backdrop:"static"});$(".cart-checkout").addClass("disabled");modals.edit_cart.modal("show");if($(".gift-wrap-message").length>0){$(".gift-wrap-message-textarea").countCharValidation(250,".remaining-characters")}})}}function checkDateBeforeToday(date){return new Date(date.toDateString())<new Date((new Date).toDateString())}var cartAccountSubmit=function(token){var checkout_instance=$("#checkout-page").data("checkout_instance");var $feedback=checkout_instance.element.find(".panel-feedback");var $data_element=checkout_instance.element.find(".panel-collapse");var $panel_completed=checkout_instance.element.find(".panel-update");var element=checkout_instance.element;$data_element.data("create-username",$("#create-username").val());$data_element.data("create-password",$("#create-password").val());$data_element.data("confirm-create-password",$("#confirm-create-password").val());var instance=checkout_instance.instance;var skippingPanels=checkout_instance.skippingPanels;var data={panel:element.children(".panel-collapse").attr("id"),form_data:$data_element.find("form").serializeArray()};data.form_data.push({name:"g-recaptcha-response",value:token});cart.pkdcart("request",["update_panel",data,function(){var this_data=this.data;var cart_instance=this.instance;if(typeof this.data.redirect!=="undefined"){try{window.location.replace(this.data.redirect)}catch(e){window.location=this.data.redirect}}if(this.data.success){if(typeof ga!=="undefined")ga("send","event","Cart","checkout-"+data.panel);else if(typeof _gaq!=="undefined")_gaq.push(["_trackEvent","Cart","checkout-"+data.panel]);if(typeof _hsq!=="undefined"){_hsq.push(["trackEvent",{id:"checkout-"+data.panel}])}if($panel_completed.data("tpl-completed")!=="undefined"){var tpl_complete_data={tpl:$panel_completed.data("tpl-completed")};$panel_completed.ajax_template(tpl_complete_data,function(){instance.$wizard_panels[instance.active].removeClass("wizard-active wizard-todo").addClass("wizard-complete wizard-completed");if(typeof this_data.panels!=="undefined"&&this_data.panels!=""){var panels=[];$.each(this_data.panels,function(id,actions){if(actions.reload){var $update=$("#"+id).find(".panel-update");panels.push({panel:$update,tpl:$update.data("tpl-reload")})}});if(panels.length>0){if(skippingPanels){if(panels.length>1){panels[1].panel.ajax_template(panels[1],function(){if(panels.length>2){panels[2].panel.ajax_template(panels[2],function(){instance.next_panel()})}else{instance.next_panel()}})}else{instance.next_panel()}}else{panels[0].panel.ajax_template(panels[0],function(){if(panels.length>1){panels[1].panel.ajax_template(panels[1],function(){if(panels.length>2){panels[2].panel.ajax_template(panels[2],function(){instance.next_panel()})}else{instance.next_panel()}})}else{instance.next_panel()}})}}}else{instance.next_panel()}})}}else{$feedback.html("").append('<div class="alert alert-danger">'+this.data.msg+"</div>");$(this.data.fields).addClass("error").parent().parent().addClass("has-error");instance.$wizard_panels[instance.next_active].removeClass("wizard-loading");if(typeof grecaptcha!=="undefined"){grecaptcha.reset()}instance.submit_btn.button("reset")}},function(){$(".wizard-loading").removeClass("wizard-loading");$(".wizard-active .wizard-actions .wizard-next").button("reset");modals.edit_cart.ajax_template({tpl:"tpl.cart.v2.modal.edit_cart"},function(){modals.edit_cart.modal({backdrop:"static"});$(".cart-checkout").addClass("disabled");modals.edit_cart.modal("show");if($(".gift-wrap-message").length>0){$(".gift-wrap-message-textarea").countCharValidation(250,".remaining-characters")}})}])};
$.fn.extend({pkd_payment_form:function(feedback,callback,callback_validation){var form=this;var pkdDataForm={required:$(form).find(".required"),feedback:typeof feedback!="undefined"&&feedback!=""?$(feedback):$(form).find('[class*="feedback"]'),ajaxFunc:typeof $(form).data("pkdform-func")!="undefined"?$(form).data("pkdform-func"):"",data:"",submit_button:$("#payment-submit"),action:typeof $(form).data("action")!="undefined"?$(form).data("action"):""};var callback=typeof callback!=="undefined"&&$.isFunction(callback)?callback:null;var callback_validation=typeof callback_validation!=="undefined"&&$.isFunction(callback_validation)?callback_validation:null;var form_pass=$.isEmptyObject(pkdDataForm.required)?false:true;var errors=[];var errors_html="";pkdDataForm.submit_button.on("click",function(e){e.preventDefault();pkdDataForm.feedback.html("");errors_html="";pkdDataForm.submit_button.button("loading");$.each(pkdDataForm.required,function(){$(this).removeClass("error success info warning").parent().parent().removeClass("has-error");if($(this).val()==""){errors_html="<p>"+required_fields_error+"</p>";errors.push($(this));form_pass=false}});$(form).data("form_pass",form_pass);$(form).data("form_errors",errors);$(form).data("form_errors_html",errors_html);if(callback_validation!=null&&form_pass){callback_validation.call({data_form:pkdDataForm,the_form:form})}else{$(form).submit()}return false});$(form).submit(function(e){var $to_scroll=$("html,body");var form_pass_final=$(this).data("form_pass_final")||false;if(!form_pass_final){if($(this).data("form_pass")){if(pkdDataForm.action!=""){$(this).attr("action",pkdDataForm.action)}if(pkdDataForm.ajaxFunc!=""){e.preventDefault();pkdDataForm.data=$(form).serializeArray();pkdDataForm.data.push({name:"java_enabled",value:window.navigator.javaEnabled()});pkdDataForm.data.push({name:"color_depth",value:screen.colorDepth});pkdDataForm.data.push({name:"screen_height",value:screen.height});pkdDataForm.data.push({name:"screen_width",value:screen.width});pkdDataForm.data.push({name:"timezone_offset",value:(new Date).getTimezoneOffset()});pkdDataForm.data.push({name:"browser_lang",value:window.navigator.language});var data=JSON.stringify(pkdDataForm.data);$.ajax({type:"POST",url:"manager/include/ajax_cart.php",data:"a_func="+pkdDataForm.ajaxFunc+"&a_params="+encodeURIComponent(data),success:function(data){var rspns=$.parseJSON(data);if(rspns.success){if(typeof ga!=="undefined")ga("send","pageview","/payment.php");else if(typeof _gaq!=="undefined")_gaq.push(["_trackPageview","/payment.php"]);if(typeof _hsq!=="undefined"){_hsq.push(["trackEvent",{id:"payment-success"}])}if(typeof rspns.redirect!=="undefined"){try{window.location.replace(rspns.redirect)}catch(e){window.location=rspns.redirect}}else{if(callback!==null){var form_callback_pass=callback.call({form:pkdDataForm,data:rspns})}else{pkdDataForm.submit_button.button("reset")}if(callback===null||(typeof form_callback_pass!=="undefined"?form_callback_pass.hasOwnProperty("submit_form")?form_callback_pass.submit_form:false:true)){$("html,body").animate({scrollTop:"0px"});$("#main-content").parent().ajax_template({tpl:"tpl.payment.success"},function(){modals.print.ajax_template({tpl:"tpl.payment.success.print"},function(){$.ajax({type:"POST",url:"manager/include/ajax_cart.php",data:"a_func=mark_payment&a_params="})})})}}}else{pkdDataForm.feedback.html('<div class="alert alert-danger">'+rspns.msg+"</div>").fadeIn().removeClass("hidden");$to_scroll.animate({scrollTop:pkdDataForm.feedback.position().top},"slow");pkdDataForm.submit_button.button("reset")}},error:function(){pkdDataForm.feedback.html('<div class="alert alert-danger">'+general_ajax_error+"</div>").fadeIn().removeClass("hidden");$to_scroll.animate({scrollTop:pkdDataForm.feedback.position().top},"slow");pkdDataForm.submit_button.button("reset")}})}}else{pkdDataForm.submit_button.button("reset");var errors_html=$(this).data("form_errors_html");var fin_errors=$(this).data("form_errors");if(fin_errors.length>0||errors_html!=""){if(fin_errors.length>0){$.each(fin_errors,function(idx,obj){$(this).addClass("error").parent().parent().addClass("has-error")})}if(errors_html!=""){pkdDataForm.feedback.html('<div class="alert alert-danger">'+errors_html+"</div>");$to_scroll.animate({scrollTop:pkdDataForm.feedback.position().top},"slow")}}form_pass=true}}else{$("html,body").animate({scrollTop:"0px"});$("#main-content").parent().ajax_template({tpl:"tpl.payment.success"},function(){modals.print.ajax_template({tpl:"tpl.payment.success.print"},function(){$.ajax({type:"POST",url:"manager/include/ajax_cart.php",data:"a_func=mark_payment&a_params="})})})}return false})}});function pkd_payment_validation(){var the_form=this.the_form;var data_form=this.data_form;var gateway_capture=typeof pkd_payment_gateway_capture!=="undefined"&&$.isFunction(pkd_payment_gateway_capture);var payment_errors_html="";var payment_errors=[];var amount=$("#opamt").val();if(isNaN(amount)){payment_errors.push($("#opamt"));payment_errors_html="<p>Please enter a valid amount.</p>"}if(!gateway_capture){if($("input:radio[name=paymentType]:checked").val()=="Credit Card"){var cc_error=false;$("#checkout-billing-wrapper").find(".reqfld").each(function(idx,obj){$(obj).removeClass("error success info warning").parent().parent().removeClass("has-error");if($(obj).val()==""){payment_errors.push($(this));cc_error=true}});if(cc_error){payment_errors_html+="<p>"+credit_card_required_error+"</p>"}if(!is_valid){payment_errors.push($cc_num);payment_errors_html+='<p class="invalid-cc">'+valid_credit_card_error+"</p>"}}}if(payment_errors.length>0){the_form.data("form_pass",false);the_form.data("form_errors",payment_errors);the_form.data("form_errors_html",payment_errors_html);the_form.submit();return false}amount=parseFloat(amount);amount=amount.truncateToDecimals(2);$("#opamt").val(amount);bootbox.confirm({size:"sm",onEscape:true,buttons:{confirm:{label:"Confirm",className:"btn-success"},cancel:{label:"Cancel",className:"btn-danger"}},message:"<h4>"+sprintf(payment_form_confirm,currencyFormatter.format(amount))+"</h4>",callback:function(result){if(result){if(gateway_capture){pkd_payment_gateway_capture(the_form)}else{the_form.submit()}}else{data_form.submit_button.button("reset")}}})}$(function(){if($("#payment-form").length>0){var pkd_payment_callback=null;if(typeof pkd_payment_before_finish!=="undefined"){pkd_payment_callback=pkd_payment_before_finish}$("#payment-form").pkd_payment_form($("#payment-page > .payment-response"),pkd_payment_callback,pkd_payment_validation)}});
$.fn.extend({
	expandCategory: function() {
  
		this.each(function() {
			$(this).click(function(e){
				$a = $(this);
				$expand = $a.children("span.expand");
				$li = $(this).closest("li");
				$ul = $li.children("ul");
				if($ul.length > 0) {
					e.preventDefault();
					$ul.slideToggle(800, 'swing', function(){
						var reltag = $expand.text();
						$expand.text($expand.attr("rel"));
						$expand.attr("rel", reltag);
					});					
				}
			});
		});
  
  	},
	pkdDataSignup: function (feedback) {
		this.each(function() {	
			if($(this).length > 0) {		
				$(this).submit(function (e) {
				    	e.preventDefault();
				    
					//set some form html objects
					$form = $(this);						//this form
					$fields = $(this).find(".form-control");			//this form's fields
					$submit_btn = $form.find('button[type="submit"]');	//this form's submit button
					
					//feedback for signup
					$feedback = (typeof feedback != "undefined" ? $(feedback) : $("#widget_cc_email_box_message1"));
					
					//todo: add list selector functionality
					var selected_list = 1;
				    	
				        //reset error and feedback states
				    	$feedback.html("").hide();
			                $(".has-error").removeClass("has-error");
			                var errors = {};
			                
			                //reset form parameters
			                var emailparams = "";
			                
			                //set button into loading state
					$submit_btn.button('loading');
					
					$fields.each(function(idx, val){
					      	$fld = $(val);
					      	var fld_isRequired = $fld.hasClass('required');
					      	var fld_val = $fld.val();
					     	
					      	if( $fld.attr("type") == "checkbox" ) {
					      		if($fld.is(":checked")) {
					      			//todo: add checkbox list handler
					      		}
					      	} else {	            			            	
					              	if( fld_isRequired && $.trim(fld_val) == "" ) {
					              		if($.isEmptyObject(errors))
					              			errors = new Array();
					              			
					              		$fld.parent().addClass("has-error");
					              		errors.push($fld);
					              	}
					        }
					});
				    
				    
				    	//If email address is empty don't bother calling ajax 
				    	//instead print out a not so nice message to enter an email address		    	
					if($.isEmptyObject(errors)) {
				    		emailparams = encodeURIComponent( $form.serialize() );
				    		$.ajax({
				    			type: "POST",
				    			url: "/manager/include/ajax_call.php",
				    			data: "a_func=widget_subscribe&a_params=" + emailparams +'|'+selected_list+'|'+$form.data('widget-id'),
				    			success: function (data) {
				    				//Returns an array(success=>true/false, msg)
				    				var response = jQuery.parseJSON(data);
				    				
				    				$(".has-error").removeClass("has-error");
				    				
				    				//If the email was successfully added then hide form and print out nice message
				    				//Otherwise, print out a not so nice message telling them they signed up wrong
				    				if(response.success) {			
				    					$form.fadeOut(function () {
				    						$feedback.html('<div class="alert alert-success">'+response.msg+'</div>').fadeIn("fast");
				    					});
				    				} else {
						    			if( $.trim(response.fields) != "" ) {
				      						var new_errors = [];
				      						$.each(response.fields, function(idx,val) {
				      							$fld = $("#"+val);
				      							new_errors.push($fld);
				      							$fld.parent().addClass("has-error");
				      						});		      						
				                  				new_errors[0].focus();
									}
				    					$feedback.html('<div class="alert alert-danger">'+response.msg+'</div>').fadeIn("fast");
				    				}
				    			},
				    			complete: function() {
								$submit_btn.button('reset');
					    			$('html, body').animate({
			      						scrollTop: $feedback.offset().top - 20
			      					}, 600);
				    			}
				    		});
				    	} else {
				    		$feedback.html('<div class="alert alert-danger">Please fill in required fields.</div>').fadeIn("fast", function(){
				    			$submit_btn.button('reset');
			            			errors[0].focus();
				    			$('html, body').animate({
		      						scrollTop: $feedback.offset().top - 20
		      					}, 600);
				    		});
				    		return false;
				    	}
			    	});
			}
		});
	}
});var use_3dsecure = false;var square_app_id = 'WvLiauluHPNpAV_WOaAHwg';var square_location_id = '4P0RW3X3KATFC';var sqPaymentForm;function init_square_payment_form(){if(typeof SqPaymentForm!=="undefined"){sqPaymentForm=new SqPaymentForm({applicationId:square_app_id,locationId:square_location_id,autoBuild:false,inputClass:"sq-input",cardNumber:{elementId:"card-number",placeholder:"Card Number"},cvv:{elementId:"cvv",placeholder:"CVV"},expirationDate:{elementId:"expiration-date",placeholder:"MM/YY"},postalCode:{elementId:"postal-code",placeholder:"Postal Code"},inputStyles:[{fontSize:"14px",padding:"5px 10px"},{mediaMaxWidth:"400px",fontSize:"16px"}],callbacks:{cardNonceResponseReceived:function(errors,nonce,cardData){var $data_element=!pkd_is_payment_page?$("#payment"):$("#payment-form");var $btn=!pkd_is_payment_page?$data_element.find(".wizard-actions").find(".btn"):$("#payment-submit");var errorDiv=$("#token_errors");if(errors){var errors_html="";errors.forEach(function(error){errors_html+="<p>"+error.message+"</p>"});if(pkd_is_payment_page){$data_element.data("form_pass",false);$data_element.data("form_errors",[$(".checkout-form-cc")]);$data_element.data("form_errors_html",errors_html);$data_element.submit()}else{errorDiv.html(errors_html);$("html,body").animate({scrollTop:errorDiv.offset().top-15+"px"});$btn.button("reset")}}else{$("#token_value").val(nonce);if(pkd_is_payment_page){$("#cc_type").val(cardData.card_brand);$("#printcc").val(cardData.last_4)}else{errorDiv.html("");$data_element.data("cc_num",nonce);$data_element.data("cc_type",cardData.card_brand);$data_element.data("cc_num_last4",cardData.last_4);$data_element.data("cc_exp_month",cardData.exp_month);$data_element.data("cc_exp_year",cardData.exp_year)}if(use_3dsecure){if($("input#opamt").length>0){var amount=parseFloat($("#opamt").val())}else{var amount=parseFloat($("#opamt").text())}amount=amount.truncateToDecimals(2);amount=amount.toString();var addrLines=[$("#fld_billAddress1").val()];var SqVerificationDetails={billingContact:{familyName:$("#fld_billLastName").val(),givenName:$("#fld_billFirstName").val(),email:$("#customerEmail").val(),country:$("#fld_billCountry option:selected").data("code"),region:$("#fld_billProvince").is(":disabled")?$("#fld_billState").val():$("#fld_billProvince").val(),city:$("#fld_billCity").val(),addressLines:addrLines,postalCode:$("#fld_billPostalCode").val(),phone:$("#fld_billHomePhone").val()},amount:amount,currencyCode:currency,intent:"CHARGE"};try{sqPaymentForm.verifyBuyer(nonce,SqVerificationDetails,function(err,result){if(typeof err==="undefined"||err==null){var verification_token=result.token;if(pkd_is_payment_page){$("#cc_verification").val(verification_token);setTimeout(function(){$data_element.data("form_pass",true);$data_element.submit()},750)}else{$data_element.data("cc_verification",verification_token);$wizard.modmbwizard("next",$btn)}}else{if(pkd_is_payment_page){$data_element.data("form_pass",false);$data_element.data("form_errors",[$(".checkout-form-cc")]);$data_element.data("form_errors_html",err);$data_element.submit()}else{errorDiv.html('<div class="alert alert-danger">'+err+"</div>");$("html,body").animate({scrollTop:errorDiv.offset().top-15+"px"});$btn.button("reset")}}})}catch(err){console.log(err);if(pkd_is_payment_page){$data_element.data("form_pass",false);$data_element.data("form_errors",[]);$data_element.data("form_errors_html",square_verify_error);$data_element.submit()}else{errorDiv.html('<div class="alert alert-danger">'+square_verify_error+"</div>");$("html,body").animate({scrollTop:errorDiv.offset().top-15+"px"});$btn.button("reset")}}}else{if(!pkd_is_payment_page){$wizard.modmbwizard("next",$btn)}else{setTimeout(function(){$data_element.data("form_pass",true);$data_element.submit()},750)}}}},unsupportedBrowserDetected:function(){}}});sqPaymentForm.build()}}function square_payment_form_submit(){var payment_type=$("input:radio[name=paymentType]:checked").val();if(payment_type=="Credit Card"){sqPaymentForm.requestCardNonce()}else{$wizard.modmbwizard("next",$("#payment").find(".wizard-actions").find(".btn"))}}function pkd_payment_gateway_capture(the_form){var payment_type=$("input:radio[name=paymentType]:checked").val();if(payment_type=="Credit Card"){if(typeof sqPaymentForm!=="undefined"){$("#token_value").val("");sqPaymentForm.requestCardNonce()}else{the_form.data("form_pass",false);the_form.data("form_errors",[]);the_form.data("form_errors_html","<p>"+general_ajax_error+"</p>");the_form.submit()}}return false}$(function(){$wizard.modmbwizard("set","onBeforeNext",{payment:function(){square_payment_form_submit()}})});$(window).load(function(){if($("#payment-form").length>0||$(".checkout-steps-row").length>0){init_square_payment_form()}});
//extended jquery functions always in use globally
$.fn.extend({
	/**********************************************************
	 * Placeholder replacement for older browsers
	 * TODO: Remove this when we drop support for IE8
	 * @return void
	 **********************************************************/
	placeholder: function() {
		$(this).find('input[type="text"], input[type="password"], textarea').each(function(ev){
			var placeholder = $(this).attr("placeholder");
			var is_textarea = $(this).is("textarea");
			if(typeof placeholder !== "undefined" && placeholder != "" && ($(this).val() == "") || (is_textarea && $(this).text() == "")) {

				$t = $(this);
				$t.addClass('hasPlaceholder');
				$t.attr("value", placeholder);

				$t.bind("focus", function(){
					if( this.value == placeholder )
						this.value = "";
				});

				$t.bind("blur", function(){
					if( this.value == "" )
						this.value = placeholder;
				});

				$(this).parents("form:first").submit(function(){

					var _t = $(this).find('.hasPlaceholder');
					if( _t.val() == placeholder )
						_t.val("");
				});
			}
		});
	},
	/**********************************************************
	 * Clear form of all data
	 * TODO: Remove need for global.js clearForm()
	 * @return void
	 **********************************************************/
	clearForm: function(){
		this.each(function() {
			$(this).click(function() {

				var form = this;
				while (form.nodeName != "FORM" && form.parentNode) {
					form = form.parentNode;
				}

				clearForm(form);
				with(form){
					if(typeof recordsLength !== 'undefined')
						recordsLength.selectedIndex = 1;

					if(typeof kwconj !== 'undefined')
						kwconj[0].checked = true;
				}

				$(form).parent().find('.alert').remove();
			});
		});
	},
	/**********************************************************
	 * Allow alerts to show/hide depending on user cookie
	 * @param string key	The key to use for the cookie
	 * @return void
	 **********************************************************/
	alert_cookie: function(key) {
		this.each(function() {
			//if key is not passed we'll just use default global message
			key = key || 'global_message';

			//setup cookie if this is first time for user
			if(typeof $.cookie(key) == "undefined")
				$.cookie(key,'open',{path:'/'});

			//when we close the alert, set the cookie to closed for other interactions
			$alert = $(this).find('.alert');
			$alert.bind('closed.bs.alert', function () {
				$.cookie(key, 'closed',{path:'/'});
			});
		});
	},
	/**********************************************************
	 * Gets the natural width and height for an image
	 * @return object	Has two keys width and height
	 **********************************************************/
	real_size: function() {
		var $img = $(this);
		if ($img.prop('naturalWidth') == undefined) {
			var $tmpImg = $('<img/>').attr('src', $img.attr('src'));
			$img.prop('naturalWidth', $tmpImg[0].width);
			$img.prop('naturalHeight', $tmpImg[0].height);
		}
		return { width: $img.prop('naturalWidth'), height: $img.prop('naturalHeight') };
	},
	ajax_template: function(data, callback) {
		var $_this = $(this);
		if(data !== null) {
			//only get parameters allowed by our application
			var params = JSON.stringify(data, allowed_tpl_ajax_data);
			$.ajax({
				type: "POST",
				url: "/manager/include/ajax_call.php",
				data: "a_func=pkd_get_template&a_params="+data.tpl+"|"+params,
				success: function (response) {
					$_this.html(response);
				},
				error: function(jqXHR, textStatus, error) {
					if ( window.console && console.log )
						console.log('[ajax_template] [' + textStatus + '] '+(error != '' ? '['+error+'] ' : '') + general_ajax_error);
					$_this.html('<div class="alert alert-danger">'+general_ajax_error+'</div>');
				},
				complete: function() {
					//setup callback for finished request
					if ( $.isFunction(callback) ) {
						callback.call({
							data:data
						});
					}
				}
			});
		} else {
			//setup callback for finished request
			if ( $.isFunction(callback) ) {
				callback.call();
			}
		}
	},
	//Counts down the number of remaining characters left
	countCharValidation: function (num, selector) {
		this.each(function () {
			var
				chars = "",
				charCount = 0;

			$(selector).text(num);
			chars = $(this).val();
			var $remaining_chars = $(this).parent().find(selector);
			$(this).keyup(function () {
				chars = $(this).val();
				$.fn.countChar(chars, num, $remaining_chars);
			});
			$.fn.countChar(chars, num, $remaining_chars);
		});
	},
	countChar: function (str, num, selector) {
		str = $.trim(str);
		var charCount = (str == "" ? 0 : str.length);
		var remaing = num - parseInt(charCount);
		$(selector).text(remaing);
	}
	});


/**
 * Intl.NumberFormat as currencyFormatter
 * Creates a proper price out of the entered amount.
 *
 */
const currencyFormatter = new Intl.NumberFormat(pkd_loc.currency_loc, {
	style: 'currency',
	currency: pkd_loc.currency,
	minimumFractionDigits: 2
});

/**
 * Number.prototype.truncateToDecimals(dec)
 * Truncates a number to only two decimal places without rounding
 * Should do validation before using this function
 *
 * @param integer dec: length of decimal
 *
 */
Number.prototype.truncateToDecimals = function(dec) {
	var dec = dec || 2;
	var regex = /\./g;
	var num_str = this.toString();

	if(num_str.match(regex)) {
		// we have a number with a decimal
		const calcDec = Math.pow(10, dec);
		return Math.trunc(this * calcDec) / calcDec;
	} else {
		return this.toFixed(dec);
	}
};


$(function(){
	if (!("placeholder" in document.createElement("input")))
		$("form").placeholder();

	if($('#global-message').length > 0)
		$('#global-message').alert_cookie('global_message');

	if($('#pkd_locale').length > 0) {
		$('#pkd_locale').selectpicker({style: 'btn-link'});
		$('#pkd_locale').on('change', function (e) {
			var url = $(this).find("option:selected").data('target') || '';
			if(url != '') {
				try {
					window.location.replace(url);
				} catch (e) {
					window.location = url;
				}
			}
		});
	}

	//setup lightbox in gallery mode
	magnific_popup_config.type = 'image';
	magnific_popup_config.zoom = {
		enabled: true,
		opener: function(openerElement) {
			//if the opener element is an image than use that to zoom
			//else if there is no image inside the openerElement than find the image up a couple parents(used for detail page)
			return (openerElement.is('img') ? openerElement : (openerElement.find('img').length > 0 ? openerElement.find('img') : openerElement.parent().parent().find('img')));
		}
	};
	magnific_popup_config.gallery = {
		enabled:true
	};
	magnific_popup_config.image.markup = '<div class="mfp-figure">'+
		'<div class="mfp-close"></div>'+
		'<div class="mfp-enlarge"><a href="javascript:;" class="mfp-enlarge-link"></a></div>'+
		'<div class="mfp-img"></div>'+
		'<div class="mfp-bottom-bar">'+
		'<div class="mfp-title"></div>'+
		'<div class="mfp-counter"></div>'+
		'</div>'+
		'</div>';
	magnific_popup_config.image.titleSrc = custom_title_src;
	magnific_popup_config.callbacks = {
		open: function() {
			$self = this;
			if($self.wrap.find('.mfp-figure').css('position') != 'static') {
				boxed_enabled = true;
			}
			$img = $self.wrap.find('.mfp-img');
			$self.wrap.on('click', '.mfp-enlarge-link', function() {
				$self.wrap.toggleClass('mfp-enlarged');
				if($self.wrap.hasClass('mfp-enlarged')) {
					last_max_height = $img.css('max-height');
					$img.css('max-height', 'none');
				} else {
					$img.css('max-height', last_max_height);
				}
			});
		},
		beforeClose: function() {
			if(enlarge_enabled) {
				this.wrap.off('click', '.mfp-enlarge-link');
				this.wrap.removeClass('mfp-enlarged');
			}
		},
		change: function() {
			if(boxed_enabled) {
				$.magnificPopup.instance.resizeImage.call(this);
			}
		},
		imageLoadComplete: function() {
			$self = this;
			if(boxed_enabled) {
				$.magnificPopup.instance.resizeImage.call(this);
			}
			if($self.wrap.find('.mfp-enlarge').is(':visible')) {
				enlarge_enabled = true;
			}
			if(enlarge_enabled) {
				$self.wrap.removeClass('mfp-enlarged');
				$img = $self.wrap.find('.mfp-img');
				var img_size = $img.real_size();
				if(img_size.width <= $img.width() ) {
					$self.wrap.find(".mfp-enlarge").hide();
				} else {
					$self.wrap.find(".mfp-enlarge").show();
				}
			}
		}
	};
	$('.lightbox').magnificPopup(magnific_popup_config);
	$.magnificPopup.instance.resizeImage = function() {
		var mfp = $.magnificPopup.instance;
		var item = mfp.currItem;
		if(!item || !item.img) return;

		if(mfp.st.image.verticalFit) {
			var decr = 0;
			// fix box-sizing in ie7/8
			if(mfp.isLowIE) {
				decr = parseInt(item.img.css('padding-top'), 10) + parseInt(item.img.css('padding-bottom'),10);
			}
			var maxHeight = (mfp.wH-decr);
			if(boxed_enabled) {
				var bottomBarHeight = ( $('.mfp-title').outerHeight(true) + ($('.mfp-bottom-bar').outerHeight(true) - $('.mfp-bottom-bar').height() ) );
				var mfpFigure = $('.mfp-figure');
				maxHeight = maxHeight - ( ( (mfpFigure.outerHeight(true) - mfpFigure.height() )  + bottomBarHeight ) );
			}
			item.img.css('max-height', maxHeight);

		}

	};

	//setup sharing tooltips
	$('.share-icon').tooltip();
	$('.st_sharethis span').tooltip();

	//toggle login layer
	$(".topic-notification-login").on('click', function(){
		$_tn_login_ele = $('.topic-notification-list-login-hide');
		$_tn_login_ele.parent().toggleClass('open');
		$_tn_login_ele.slideToggle();
	});

	//setup popovers for shipping links on order detail
	if($(".ship-link-popover").length > 0 ) {
		$(".ship-link-popover").each(function(){
			var popover_html = $(this).next().html();
			var options = {
				html:true,
				content:popover_html,
				placement:'auto'
			};
			$(this).popover(options);
		});
	}

	
	$('a[rel="external"]').on('click', function(e) {
		e.preventDefault();
		window.open( $(this).attr('href') );
	});

	if( $(".clearForm").length > 0 ) {
		$(".clearForm").clearForm();
	}

	// see if referrer is a page within site
	if($("#backHistory").length > 0) {
		if(document.referrer.indexOf(window.location.hostname) != -1)
			$('#backHistory').show();
		else
			$('#backHistory').hide();
	}

	//setup global namespace for modals
	if($('#coupon_rules_modal').length > 0) modals.coupon = $('#coupon_rules_modal');
	if($('#cvv_modal').length > 0) modals.cvv = $('#cvv_modal');
	if($('#print_modal').length > 0) modals.print = $('#print_modal');
	if($('#order_history_modal').length > 0) {
		modals.orderhistory = $('#order_history_modal');
		modals.orderhistory.on('hide.bs.modal', function () {
			$(this).removeData('bs.modal');
		});
	}

	if($('#lang_modal').length > 0) {
		modals.lang = $('#lang_modal');
		$('#pkd_locale_preferred').selectpicker();
		$('.pkd-locale-preferred-dropdown .btn').append('<span class="fa fa-spinner fa-spin" />');
		$("#pkd_locale_preferred").on('change', function(e){
			var $icon = $(".pkd-locale-preferred-dropdown").find('.fa');
			var val = $(this).val();
			var $response = $('#modal-response-lang');
			$icon.css('display', 'inline-block');
			$(this).prop('disabled', true);
			//only get parameters allowed by our application
			$.ajax({
				type: "POST",
				url: "/manager/include/ajax_call.php",
				data: "a_func="+ajax_registered_calls.setpreferredlocale+"&a_params="+val+"|",
				success: function (response) {
					$response.html(response);
				},
				error: function(jqXHR, textStatus, error) {
					if ( window.console && console.log )
						console.log('[set_preferred_locale] [' + textStatus + '] '+(error != '' ? '['+error+'] ' : '') + general_ajax_error);
					$response.html('<div class="alert alert-sm alert-danger">'+general_ajax_error+'</div>');
				},
				complete: function(){
					$("#pkd_locale_preferred").prop('disabled', false);
					$icon.css('display', 'none');
				}
			});
		});
	}

	/* Setup Topic Notification */
	if($('#lyr-topic').length > 0) {
		modals.topics = $('#lyr-topic');
		modals.topics.on('hide.bs.modal', function (e) {
			window.location.hash = "";
		});
	}

	$('.topic-notification-link').on('click', function(e){
		$opener = $(this);
		append_tn_modal();
		e.preventDefault();
		if( $opener.hasClass("mobile") && $opener.data('mobile') != '' ) {
			window.location = $opener.data('mobile');
		} else if( ! $.isEmptyObject(modals.topics) ) {

			modals.topics.modal('show');

			//set hash to topics
			if(!window.location.hash) {
				window.location.hash = "topics";
			}

			if($(".widget_el_email_wrap_loggedin").length > 0) {
				var url = $(".widget_el_email_wrap_loggedin a").attr("href");
				if(url.indexOf("topics") == "-1")
					$(".widget_el_email_wrap_loggedin a").attr("href", url+"#topics");
			}
		}
	});

	//if we have a hash tag
	if(window.location.hash)
	{
		switch(window.location.hash)
		{
			//when hash is topics then open topic modal
			case "#topics":
				append_tn_modal();
				if( ! $.isEmptyObject(modals.topics) )
				{
					modals.topics.modal('show');
				}
				break;
		}
	}

	$('.want-link-remove').on('click', function(e){
		e.preventDefault();
		if(confirm(wish_remove_confirm)){
			window.location=$(this).attr('href');
		}
	});


	$(document).on('click', '.mock-anchor', function(e) {
		var url = $(this).data('url') || '';
		var target = $(this).data('url-target') || '';
		if($.trim(url) != '') {
			if($.trim(target) == '') {
				window.location = url;
			} else {
				window.open(url,target);
			}
		}
	});

	if($('.flex-form').length > 0) {

		$(".flex-form-link").on('click', function(e){
			e.preventDefault();

			var target = $(this).attr('href');

			//show flex form layer
			$(target).addClass('display-flex');

			//flex layer no longer hidden to user
			$(target).attr('aria-hidden', 'false');

			//keyup event for closing the popup once opened
			$(document).keyup(function(e) {
				// esc to close flex form
				if (e.keyCode === 27) $(target).trigger('click');
			});

			//wait a short very brief moment to show the form
			setTimeout(function(){
				//make form active and visible
				$(target).find('.flex-form-inner').addClass('active');

				//focus on first element
				var $form = $(target).find('form');
				$form.find("input[type!='hidden'],select,textarea").first().focus();
			}, 50);
		});

		$('.flex-form').on('click', function(e) {
			//hide the form and form layer
			$(this).removeClass('display-flex').attr('aria-hidden', 'true');
			$(this).find(".flex-form-inner").removeClass('active');

			//unbind esc keyup event
			$(document).off('keyup');
		});

		$(".flex-form form").each(function(idx,obj){
			$(this).addClass('is-flex');
			$(this).on('click', function(e){
				//allow you to click inside the form without closing the layer
				e.stopPropagation();
			});
		});
	}
});