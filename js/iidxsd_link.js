var IIDXSD_LINK = IIDXSD_LINK || {};
(function(){
	var BASE_URL = 'http://iidxsd.sift-swift.net/';
	var AC_MIN_VER = 0;
	var AC_MAX_VER = 21;
	var CS_MIN_VER = 3;
	var CS_MAX_VER = 16;
	var VER_LIST = ['1st', 'substream', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', 'RED', 'HAPPYSKY', 'DistorteD', 'GOLD', 'TROOPERS', 'EMPRESS', 'SIRIUS', 'RA', 'Lincle', 'tricoro','SPADA'];
	write = function(flt,mgn){
		var styles = 'background:#E0E9EE;color:#FFFFFF;width:225px;border:1px solid #333333;padding:5px 0px;text-align:center;font-size:80%;';

		if(flt != 0){
			styles += 'float:left;';
		}
		if(mgn != 0){
			styles += 'margin:'+ mgn +'px;';
		}

		var HTML = '<div id="IIDXSD_LINK_CONTAINER" style="'+styles+'"><a href="'+BASE_URL+'" target="_blank"><img src="'+BASE_URL+'images/iidxsd_banner.png" width="200" height="40" alt="IIDXSmartData" border="0" /></a>';
		HTML += getSelectVersion();
		HTML += getSelectType();
		HTML += '<a style="border:0px;background:#333333;padding:5px 4px;color:#FFFFFF;text-decoration:none;" href="javascript:IIDXSD_LINK.show()">表示</a>';
		HTML += '</div>';
		document.write(HTML);
	},
	getSelectVersion = function(){
		var i = 0;
		var html = '<select id="IIDXSD_LINK_VERSION" name="IIDXSD_LINK_VERSION">';
		html += '<optgroup label="AC" class="optac">';
		var isSelected = '';
		for(i = AC_MIN_VER; i <= AC_MAX_VER; i++){
			if(i == AC_MAX_VER){
				isSelected = 'selected="selected"';
			}
			html += '<option value="ac' + i + '" '+isSelected+'>' + VER_LIST[i] + '</option>';
		}
		html += '</optgroup><optgroup label="CS" class="optcs">';
		for(i = CS_MIN_VER; i <= CS_MAX_VER; i++){
			html += '<option value="cs' + i + '">' + VER_LIST[i] + '</option>';
		}
		html += '</optgroup>';
		html += '</select>';
		return html;
	},
	getSelectType = function(){
		var html = '<select id="IIDXSD_LINK_TYPE" name="IIDXSD_LINK_TYPE">'
		+'	<option value="new" selected>新曲</option>'
		+'	<option value="all">全曲</option>'
		+'	<option value="delete">削除曲</option>'
		+'	<option value="revival">復活曲</option>'
		+'</select>';
		return html;
	},
	show = function(){
		var fVer = document.getElementById('IIDXSD_LINK_VERSION');
		var fType = document.getElementById('IIDXSD_LINK_TYPE');
		var ver = fVer.value;
		var type = fType.value;

		var accs = ver.substr(0, 2);
		var vernum = ver.substr(2);

		var url = BASE_URL + 'view/' + accs + '/' + vernum + '/' + type;
		location.href = url;


	};
	
	IIDXSD_LINK.show = show;
	write(1, 5);
})();