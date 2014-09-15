var IIDXSD = IIDXSD || {};

(function(){


	var CHECK_WIDTH = 650;
	var MODE_NORMAL = "modeNormal";
	var MODE_NARROW = "modeNarrow";
	var MODE_CHANGE = "modeChange";

	var _currentMode = MODE_NORMAL;
	var _eventTarget;

	var _checkBox = [];
	var _colList = {};
	var _folderList = [];
	
	init = function(){
		$.cookie.defaults.path = "/";
		initWindowSize();
		initButtons();
		initMasonry();
		setSearchForm();
		if($('body.data').get(0)){
			setVersionForm();
			setModal();
			setDataFolder();
			setDetailSetting();
		}
		if(window.addEventListener){
			$("body.data").on('AutoPagerize_DOMNodeInserted', init);
		}
	},

	/**
	 * ウィンドウサイズ初期化
	 */
	initWindowSize = function(){
		_eventTarget = document.createElement("object");

		var checkSize = function(){
			var w = $(window).width();
			if(w > screen.width){
				w = screen.width;
			}
			if(w < CHECK_WIDTH && _currentMode == MODE_NORMAL){
				onChangeMode(MODE_NARROW);
			}
			else if(w >= CHECK_WIDTH && _currentMode == MODE_NARROW){
				onChangeMode(MODE_NORMAL);
			}
		};
		$(window).resize(checkSize);
		checkSize();
	},

	/**
	 * モード変更
	 */
	onChangeMode = function(mode){
		_currentMode = mode;
		if($("#vdetail").css("top") != "0px"){
			$("#vdetail").css("top", (-$("#vdetail").height() + 20) + "px").css("right", (-$("#vdetail").width() + 100) + "px");
		}
	},

	/**
	 * ボタン初期化
	 */
	initButtons = function(){

		//TOPのLINK
		var showMenuLink = function(){
			$(this).stop().animate({opacity:1});
		};
		var hideMenuLink = function(){
			$(this).stop().animate({opacity:0});
		};


		$("#quickmenu ul.sublink").mouseover(showMenuLink);
		$("#quickmenu ul.sublink").mouseout(hideMenuLink);

		$("#quickmenu ul.sublink").css("visibility", "visible").css("opacity", 0);


		if(Modernizr.touch){
			$("#quickmenu ul.sublink").click(function(e){
				if($(this).css("opacity") == 0){
					$("#quickmenu ul.sublink").each(hideMenuLink);
					$(this).stop().animate({opacity:1});
					e.stopPropagation();
					e.preventDefault();
				}
			});
		}


		//TOPに戻る
		$("a#backToTop").click(function(){
			$("html,body").animate({scrollTop:0}, 600);
		});

		//詳細設定表示
		$("#vdetail").click(function(){
			if($("#vdetail").css("top") != "0px"){
				$("#vdetail").animate({right:0}, 200).animate({top:0}, 500);
			}
		});
		$("#controlSetting").click(function(){
			$("#vdetail").animate({top:-$("#vdetail").height() + 20}, 200).animate({right:-$("#vdetail").width() + 100}, 500);
		});
		$("#vdetail").css("top", -$("#vdetail").height() + 20).css("right", -$("#vdetail").width() + 100).css("display", "block");

	},

	/**
	 * TOPのパネルを準備
	 */
	initMasonry = function(){
		$("#versionAC").masonry({
			itemSelector:".container",
			isAnimated:true,
			isFitWidth:true
		});
		$("#versionCS").masonry({
			itemSelector:".container",
			isAnimated:true,
			isFitWidth:true
		});
		$("#versionALL").masonry({
			itemSelector:".container",
			isAnimated:true,
			isFitWidth:true
		});
		$("#menuInfo").masonry({
			itemSelector:".container",
			isAnimated:true,
			isFitWidth:true
		});
	},

	/**
	 * モーダルウィンドウ初期化
	 */
	setModal = function(){
		adjustCenter("#modal .container");
		var opts = {
			lines: 13,
			length: 7,
			width: 4,
			radius: 10,
			corners: 1,
			rotate: 0,
			color: '#000',
			speed: 1,
			trail: 60,
			shadow: false,
			hwaccel: false,
			className: 'spinner',
			zIndex: 2e9,
			top: 'auto',
			left: 'auto'
		};
		var spinner = new Spinner(opts);
		
		//load
		$("a.modal").click(function(){
			displayModal(true);//先にウィンドウだけ表示
			$("#modal .container").html('');
			spinner.spin($("#modal .container").get(0));
			$.ajax({
				url: $(this).attr("href"),
				success: function(str){
					$("#modal .container").html(str);
					//モーダルクローズ
					$("#modal .container a.close").click(function() {
						displayModal(false);
						return false;
					});
				},
				error: function(obj, errStr){
					//
				},
				complete: function(){
					//
				}
			});
			return false;
		});

		//表示
		function displayModal(isShow){
			if(isShow){
				$("#modal").fadeIn(500);
			}
			else{
				$("#modal").fadeOut(250);
			}
		};
		
		//センター揃え
		function adjustCenter(target){
			var margin_top = ($(window).height() - $(target).height()) / 2;
			var margin_left = ($(window).width() - $(target).width()) / 2;
			if(_currentMode == MODE_NARROW){
				$(target).css({top:margin_top+"px", left:0+"px"});
			}
			else{
				$(target).css({top:margin_top+"px", left:margin_left+"px"});
			}
		};

		//リサイズ時
		$(window).resize(function(){
			adjustCenter("#modal .container");
		});

		//背景クリック
		$("#modal .background").click(function(){
			displayModal(false);
		});

		//モーダルクローズ
		/*$("#modal .container a.close").click(function() {
			displayModal(false);
			return false;
		});*/
	},

	/**
	 * バージョン選択Form
	 */
	setVersionForm = function(){
		var url = location.href.substr(8);
		var urlSplit = url.split("/");
		var startIndex = (url.search("sift-swift") != -1) ? 2 : 5;

		if(urlSplit.length > (startIndex + 4) || urlSplit.length < (startIndex + 1)){
			return;
		}
		var ACCS = urlSplit[startIndex];
		var version = urlSplit[startIndex+1];
		var versionType = urlSplit[startIndex+2];
		if(ACCS == "all"){
			$("#selectedVer").val("all");
		}
		else{
			$("#selectedVer").val(ACCS + version);
			if(versionType){
				$("#versionType").val(versionType);
			}
		}
	},

	/**
	 * 検索フォーム
	 */
	setSearchForm = function(){
		$("#searchWord").keypress(function(e){
			if((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)){
				execSearch();
				return false;
			}
			else{
				return true;
			}
		});
	},

	/**
	 * フォルダ開閉設定
	 */
	setDataFolder = function(){
		$("table#datatable h3").click(function(){
			var tbody = $(this).parent().parent("tr").parent("tbody").next("tbody");
			$(tbody).toggle();
		});
	},

	/**
	 * 詳細設定のチェックボックスの動作
	 */
	 setDetailSetting = function(){
	 	var isFirstShow = true;
	 	
	 	if($.cookie("isSetting") == "0.1"){
	 		isFirstShow = false;
	 	}
	 	$.cookie("isSetting", "0.1");
	 	$("#datatable .data_title tr td").each(function(){
	 		_folderList.push($(this));
	 	});
	 	$("#vdetail input:checkbox").each(function(){
	 		_checkBox.push($(this));
	 		$(this).click(function(){
	 			var colName = $(this).val();
	 			if($(this).attr("checked") == "checked"){
	 				$("table#datatable tr td." + colName + ",table#datatable tr th." + colName).css("display", "");
	 				$.cookie("check_" + colName, 1);
	 			}
	 			else{
	 				$("table#datatable tr td." + colName + ",table#datatable tr th." + colName).css("display", "none");
	 				$.cookie("check_" + colName, 0);
	 			}
	 			
	 		});
	 		var colName = $(this).val();

	 		var col = $("table#datatable tr td." + colName + ",table#datatable tr th." + colName);
	 		_colList[colName] = col;
	 		if($.cookie("check_" + colName) == 0){
	 			col.css("display", "none");
	 			isFirstShow = false;
	 		}
	 		else{
	 			$(this).attr("checked", "checked");
	 		}
	 	});

	 	setColspan();

	 	if(isFirstShow && _currentMode == MODE_NARROW){
	 		selectViewMode.smt();
	 	}
	 },


	
	showData = function(){
		var ver = $("#selectedVer").val();
		var type = $("#versionType").val();
		var verType = ver.substr(0, 2).toLowerCase();
		var verNum = ver.substr(2);
		if(verType != "al"){
			location.href = $("base").attr("href") + "view/" + verType + "/" + verNum + "/" + type;
		}
		else{
			location.href = $("base").attr("href") + "view/all";
		}
	},
	
	searchData = function(){
		execSearch();
	},

	execSearch = function(){
		var searchWord = encodeURIComponent($("#searchWord").val());
		if(searchWord == ""){alert("検索文字を入力してください。");return;}
		if(searchWord == "%2f" || searchWord == "%2F"){searchWord = "%252f";}
		if(searchWord == "!"){searchWord = "%21";}
		if(searchWord.indexOf("*") >= 0 || searchWord.indexOf("(") >= 0 || searchWord.indexOf(")") >= 0){alert("検索できない文字が含まれています。");return;}
		location.href = $("base").attr("href") + "view/search/" + searchWord;
	},

	/**
	 * 表示モード選択
	 */
	selectViewMode = {
		recommend : function(){
			var showList = ["bpm", null, null, "difn7", "difh7", "difa7", "difd7", "difn14", "difh14", "difa14", "difd14", "notesn7", "notesh7", "notesa7", "notesd7",  "notesn14", "notesh14", "notesa14", "notesd14"];
			execTableColControl(showList);
		},
		all : function(){
			var showList = ["bpm", "artist", "genre", "difn7", "difh7", "difa7", "difd7", "difn14", "difh14", "difa14", "difd14", "notesn7", "notesh7", "notesa7", "notesd7",  "notesn14", "notesh14", "notesa14", "notesd14"];
			execTableColControl(showList);
		},
		none : function(){
			var showList = [null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null];
			execTableColControl(showList);
		},
		sp : function(){
			var showList = ["bpm", "artist", "genre", "difn7", "difh7", "difa7", "difd7", null, null, null, null, "notesn7", "notesh7", "notesa7", "notesd7", null, null, null, null];
			execTableColControl(showList);
		},
		dp : function(){
			var showList = ["bpm", "artist", "genre", null, null, null, null, "difn14", "difh14", "difa14", "difd14", null, null, null, null, "notesn14", "notesh14", "notesa14", "notesd14"];
			execTableColControl(showList);
		},
		smt : function(){
			var showList = ["bpm", null, null, "difn7", "difh7", "difa7", "difd7", "difn14", "difh14", "difa14", "difd14", null, null, null, null, null, null, null ,null];
			execTableColControl(showList);
		}

	},

	/**
	 * 列の表示非表示
	 */
	execTableColControl = function(colNameList){
		var len = _checkBox.length;
		var i = 0;
		for(i = 0; i < len; i++){
			var check = _checkBox[i];
			var colName = check.val();
			var col = _colList[colName];
			if(colNameList[i] != null){
				col.css("display", "");
				check.attr("checked", "checked");
				$.cookie("check_" + colName, 1);
			}
			else{
				col.css("display", "none");
				check.removeAttr("checked");
				$.cookie("check_" + colName, 0);
			}
		}
		
		setColspan();
	}

	setColspan = function(){
		var colHeadList = $("#datatable thead tr th");
		var colNum = colHeadList.size();
		colHeadList.each(function(){
			if($(this).css("display") == "none"){
				colNum--;
			}
		});

		var folderNum = _folderList.length;
		for(i = 0; i < folderNum; i++){
			var tdObj = _folderList[i];
			tdObj.attr("colspan", colNum);
		}
	};
	
	//public
	IIDXSD.showData = showData;
	IIDXSD.searchData = searchData;
	IIDXSD.selectViewMode = selectViewMode;
	IIDXSD.init = init;
}());