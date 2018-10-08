<?php include_once("include.php"); ?>
<?php
/*
TODO
- 인쇄시 페이징 관련 안내
- 타인의 일지 공유 키를 가지고 들어온 사용자의 일지 인쇄?
- 댓글 없을때 처리
- 장부 입출력에 따른 금액 색상 변경
- 확인하지 않은 알림 항목은 색상 변경
- 알림 목록 진입시 알림뱃지 제거 처리
- 첫페이지 데이터의 로드 결과가 스크롤 높이보다 작을 경우의 처리
*/
if(processGoogleBot()) {
    die();
}

?>
<!doctype html>
<html class="no-js" lang="ko">
<head>

<title><?=$pageTitle?></title>

<meta charset="utf-8" />

<meta name="desription" content="<?=$description?>">
<meta name="author" content="<?=COMPANY_NAME?>">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial=scale=1, maximum-scale=1.5, user-scalable=yes">

<meta property="og:type" content="website">
<meta property="og:title" content="<?=$pageTitle?>">
<meta property="og:description" content="<?=$description?>">
<meta property="og:image" content="<?=$imageUrl?>">
<meta property="og:url" content="<?=$pageUrl?>">

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<meta name="google-signin-client_id" content="50956169315-k3kqcvs4hmdg89tbqmbfspqfhkk5cc8h.apps.googleusercontent.com">
<meta name="naver-site-verification" content="4c383834a9dc223cedad58c5b4c4678538f30aed">
<meta name="naver-site-verification" content="4c383834a9dc223cedad58c5b4c4678538f30aed">
<meta name="theme-color" content="#ffffff">

<link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/favicon-16x16.png">

<link rel="stylesheet" href="css/app.css?20170614_1" id="link_app_css">
<link rel="stylesheet" href="js/vendor/jquery-ui/jquery-ui.min.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="photoswipe/photoswipe.css">
<link rel="stylesheet" href="photoswipe/default-skin.css">

</head>
<body class="">

<?php include_once("analyticstracking.php"); ?>

<div id="box_nav_bar">
    <div id="div_top_banner">
        <span class="icon_home" style="margin-right: 5px">
            <img src="images/logo.png" alt="스마트 영농일지 가입하러 가기" width="11">
        </span>
        <a href="./">파밍노트 - 스마트 영농일지 가입하러 가기</a>
    </div>
    <div id="div_navigation_bar">
        <a href="/" title="홈으로" id="btn_home" class="clear">
            <span class="icon_home">
                <img src="images/logo.png" alt="홈으로" width="11">
            </span><!--
            --><span class="txt_home">홈으로</span>
        </a>
        <ul id="ul_left_navigation_item" class="ul_navigation_item">
            <li item_template="item">
                <i></i>
                <span id="span_title"></span>
                <img id="img_icon">
                <span id="span_badge" class="hidden"></span>
            </li>
        </ul>
        <h1 id="h_title"></h1>
        <ul id="ul_center_navigation_item" class="ul_navigation_item">
            <li item_template="item">
                <i></i>
                <span id="span_title"></span>
                <img id="img_icon">
                <span id="span_badge" class="hidden"></span>
            </li>
        </ul>
        <ul id="ul_right_navigation_item" class="ul_navigation_item">
            <li item_template="item">
                <i></i>
                <span id="span_title"></span>
                <img id="img_icon">
                <span id="span_badge" class="hidden"></span>
            </li>
        </ul>
    </div>
</div>
<div id="div_stage"></div>
<div id="div_left_menu_wide" class="div_left_menu_container">
    <!-- include left_menu.html -->
</div>
<div id="box_loading" class="hidden">
    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
    <p id="p_loading_message" class="hidden"><!-- <b>잠시만 기다려주세요.</b> --></p>    
</div>
<script src="js/vendor/require.js"></script>
<script>

var requireArgs = "v=20170611_3";
require.config({
    packages: [{
        name: 'moment',
        location: 'js/vendor',
        main: 'moment',
    }],
});
require.config({
    waitSeconds : 60,
    urlArgs: requireArgs,
    paths: {
        "jquery": ["js/vendor/jquery"],
        "jquery-ui": ["js/vendor/jquery-ui/jquery-ui.min"],
        "clipboard": ["js/vendor/clipboard.min"],
        "dateformatter": ["js/vendor/DateFormatter"],
        "photoswipe": ["photoswipe/photoswipe.min"],
        "photoswipe-ui": ["photoswipe/photoswipe-ui-default.min"],
        "exif": ["js/vendor/exif"],
        "farmingnote_resources": ["js/farmingnote.resources"],
        "farmingnote_core": ["js/farmingnote.core"],
        "farmingnote_s": ["js/farmingnote.s"],
        "farmingnote_types": ["js/farmingnote.types"],
        "farmingnote_spinnertypes": ["js/farmingnote.spinnerTypes"],
        "farmingnote_function": ["js/farmingnote.function"],
        "navigation_controller": ["js/view_controller/navigation_controller"],
        "view_controller": ["js/view_controller/view_controller"],
    },
    shim: {
        "jquery-ui": {
            deps: ["jquery"],
        },
        "farmingnote_core": {
            deps: ["jquery", "jquery-ui", "farmingnote_resources"],
        },
        "farmingnote_s": {
            deps: ["farmingnote_core"],
        },
        "farmingnote_spinnertypes": {
            deps: ["farmingnote_resources"],
        },
        "farmingnote_types": {
            deps: ["dateformatter", "farmingnote_resources", "farmingnote_core", "farmingnote_s"],
        },
        "farmingnote_function": {
            deps: ["farmingnote_types"],
        },
    },
});
function initFarmingnote() {
    // Farmingnote.processBetaMessage();
    
	showProgress();

    Farmingnote.ulLeftMenu = null;
    
    // left menu 로드
    $.get("left_menu.html", function(html) {
        var ulLeftMenu = $(html);
        if(ulLeftMenu) {
            var divLeftMenuWide = $("#div_left_menu_wide");
            divLeftMenuWide.addClass("hidden");
            divLeftMenuWide.append(ulLeftMenu);
            ulLeftMenu.find("li").click(function() {
                var menuId = $(this).attr("menu_id");
                Farmingnote.actionLeftMenu(menuId);
            });
            Farmingnote.divLeftMenuWide = divLeftMenuWide;
        }
    });
    
    moment.locale('ko');
    // 아무곳이나 클릭시 모든 popover 제거
    $(document).bind("click", function(event) {
        removeAllPopovers();
    });
    // 활성화시마다 세션 체크
    $(window).focus(function() {
        Farmingnote.checkSession(function(isSuccess) {
            if(!isSuccess) {
                if(!Farmingnote.userId) return;

                require(["js/view_controller/login_view_controller"], function() {
                    // 자동로그인 시도
                    var loginViewController = LoginViewController.forAutoLogin();
                    loginViewController.autoLogin(function(result) {
                        if(result) {
                            // do nothing
                        } else {
                            // 자동 로그인 실패시
                            loginViewController.showExpiredAlert();
                            
                            loginViewController = new LoginViewController();
                            loginViewController.onPrepare = function() {
                                mainNavigationController.replaceRootViewController(loginViewController);    
                            };
                        }
                    });
                });
            }
        });
    });
    // date picker 기본 설정
    $.datepicker.setDefaults({
        dateFormat: Farmingnote.s.dateFormatForDatePicker,
        prevText: '이전 달',
        nextText: '다음 달',
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        showMonthAfterYear: true,
        yearSuffix: '년',
    });
    // photoswipe element 추가
    $.get("photoswipe.html", function(data) {
        var element = $(data);
        $(document.body).append(element);
        initPhotoSwipeFromDOM(".div_images");
    });
    
    window.mainNavigationController = new NavigationController();
    
    Farmingnote.checkSession(function(isSuccess) {
        if(isSuccess) {
            require(["js/view_controller/main_view_controller"], function() {
                var mainViewController = new MainViewController();
                mainViewController.onPrepare = function() {
                    mainNavigationController.replaceRootViewController(mainViewController);
                    mainNavigationController.processPathIdentifier();
                };
            });
        } else {
            require(["js/view_controller/login_view_controller"], function() {
                var loginViewController = LoginViewController.forAutoLogin();
                loginViewController.autoLogin(function(result) {
                    if(result) {
                        if(!mainNavigationController.viewControllers.length) {
                            require(["js/view_controller/main_view_controller"], function() {
                                var mainViewController = new MainViewController();
                                mainViewController.onPrepare = function() {
                                    mainNavigationController.replaceRootViewController(mainViewController);
                                    mainNavigationController.processPathIdentifier();
                                };
                            });
                        }
                    } else {
                        loginViewController = new LoginViewController();
                        loginViewController.onPrepare = function() {
                            mainNavigationController.replaceRootViewController(loginViewController);
                            dismissProgress();

                            mainNavigationController.processPathIdentifier();
                        };
                    }
                });
            }); 
        }
    });

    Farmingnote.processCallback();
}

require(
    [
        "jquery",
        "jquery-ui",
        "dateformatter",
        "moment",
        "clipboard",
        "photoswipe",
        "photoswipe-ui",
        "exif",
        "farmingnote_resources",
        "farmingnote_core",
        "farmingnote_s",
        "farmingnote_types",
        "farmingnote_spinnertypes",
        "farmingnote_function",
        "navigation_controller",
        "view_controller",
    ], function() {
        window.moment = arguments[3];
        window.Clipboard = arguments[4];
        window.PhotoSwipe = arguments[5];
        window.PhotoSwipeUI_Default = arguments[6];
        initFarmingnote();
    }, function(err) {
        alert(r.strings.network_error_message);
    }
);
</script>
</body>
</html>