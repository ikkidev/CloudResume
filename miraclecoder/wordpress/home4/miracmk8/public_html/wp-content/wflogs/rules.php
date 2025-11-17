<?php
if (!defined('WFWAF_VERSION')) {
	exit('Access denied');
}
/*
	This file is generated automatically. Any changes made will be lost.
*/

$this->failScores['sqli'] = 100;
$this->failScores['xss'] = 100;
$this->failScores['rce'] = 100;

$this->variables['sqliRegex'] = new wfWAFRuleVariable($this, 'sqliRegex', '/(?:[^\\w<]|\\/\\*\\![0-9]*|^)(?:
@@HOSTNAME|
ALTER|ANALYZE|ASENSITIVE|
BEFORE|BENCHMARK|BETWEEN|BIGINT|BINARY|BLOB|
CALL|CASE|CHANGE|CHAR|CHARACTER|CHAR_LENGTH|COLLATE|COLUMN|CONCAT|CONDITION|CONSTRAINT|CONTINUE|CONVERT|CREATE|CROSS|CURRENT_DATE|CURRENT_TIME|CURRENT_TIMESTAMP|CURRENT_USER|CURSOR|
DATABASE|DATABASES|DAY_HOUR|DAY_MICROSECOND|DAY_MINUTE|DAY_SECOND|DECIMAL|DECLARE|DEFAULT|DELAYED|DELETE|DESCRIBE|DETERMINISTIC|DISTINCT|DISTINCTROW|DOUBLE|DROP|DUAL|DUMPFILE|
EACH|ELSE|ELSEIF|ELT|ENCLOSED|ESCAPED|EXISTS|EXIT|EXPLAIN|EXTRACTVALUE|
FETCH|FLOAT|FLOAT4|FLOAT8|FORCE|FOREIGN|FROM|FULLTEXT|
GRANT|GROUP|HAVING|HEX|HIGH_PRIORITY|HOUR_MICROSECOND|HOUR_MINUTE|HOUR_SECOND|
IFNULL|IGNORE|INDEX|INFILE|INNER|INOUT|INSENSITIVE|INSERT|INTERVAL|ISNULL|ITERATE|
JOIN|KILL|LEADING|LEAVE|LIMIT|LINEAR|LINES|LOAD|LOAD_FILE|LOCALTIME|LOCALTIMESTAMP|LOCK|LONG|LONGBLOB|LONGTEXT|LOOP|LOW_PRIORITY|
MASTER_SSL_VERIFY_SERVER_CERT|MATCH|MAXVALUE|MEDIUMBLOB|MEDIUMINT|MEDIUMTEXT|MID|MIDDLEINT|MINUTE_MICROSECOND|MINUTE_SECOND|MODIFIES|
NATURAL|NO_WRITE_TO_BINLOG|NULL|NUMERIC|OPTION|ORD|ORDER|OUTER|OUTFILE|
PRECISION|PRIMARY|PRIVILEGES|PROCEDURE|PROCESSLIST|PURGE|
RANGE|READ_WRITE|REGEXP|RELEASE|REPEAT|REQUIRE|RESIGNAL|RESTRICT|RETURN|REVOKE|RLIKE|ROLLBACK|
SCHEMA|SCHEMAS|SECOND_MICROSECOND|SELECT|SENSITIVE|SEPARATOR|SHOW|SIGNAL|SLEEP|SMALLINT|SPATIAL|SPECIFIC|SQLEXCEPTION|SQLSTATE|SQLWARNING|SQL_BIG_RESULT|SQL_CALC_FOUND_ROWS|SQL_SMALL_RESULT|STARTING|STRAIGHT_JOIN|SUBSTR|
TABLE|TERMINATED|TINYBLOB|TINYINT|TINYTEXT|TRAILING|TRANSACTION|TRIGGER|
UNDO|UNHEX|UNION|UNLOCK|UNSIGNED|UPDATE|UPDATEXML|USAGE|USING|UTC_DATE|UTC_TIME|UTC_TIMESTAMP|
VALUES|VARBINARY|VARCHAR|VARCHARACTER|VARYING|WHEN|WHERE|WHILE|WRITE|YEAR_MONTH|ZEROFILL)(?=[^\\w]|$)/ix');
$this->variables['xssRegex'] = new wfWAFRuleVariable($this, 'xssRegex', '/(?:
#tags
(?:\\<|\\+ADw\\-|\\xC2\\xBC)(script|iframe|svg|object|embed|applet|link|style|meta|base|\\/\\/|\\?xml\\-stylesheet)(?:[^\\w]|\\xC2\\xBE)|
#protocols
(?:^|[^\\w])(?:(?:(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:6a|4a)|0*(?:106|74));?|j)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:61|41)|0*(?:97|65));?|a)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:76|56)|0*(?:118|86));?|v)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:61|41)|0*(?:97|65));?|a)|(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:76|56)|0*(?:118|86));?|v)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:62|42)|0*(?:98|66));?|b)|(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:65|45)|0*(?:101|69));?|e)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:63|43)|0*(?:99|67));?|c)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:6d|4d)|0*(?:109|77));?|m)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:61|41)|0*(?:97|65));?|a)|(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:6c|4c)|0*(?:108|76));?|l)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:69|49)|0*(?:105|73));?|i)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:76|56)|0*(?:118|86));?|v)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:65|45)|0*(?:101|69));?|e))(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:73|53)|0*(?:115|83));?|s)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:63|43)|0*(?:99|67));?|c)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:72|52)|0*(?:114|82));?|r)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:69|49)|0*(?:105|73));?|i)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:70|50)|0*(?:112|80));?|p)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:74|54)|0*(?:116|84));?|t)|(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:6d|4d)|0*(?:109|77));?|m)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:68|48)|0*(?:104|72));?|h)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:74|54)|0*(?:116|84));?|t)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:6d|4d)|0*(?:109|77));?|m)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:6c|4c)|0*(?:108|76));?|l)|(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:6d|4d)|0*(?:109|77));?|m)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:6f|4f)|0*(?:111|79));?|o)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:63|43)|0*(?:99|67));?|c)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:68|48)|0*(?:104|72));?|h)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:61|41)|0*(?:97|65));?|a)|(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:64|44)|0*(?:100|68));?|d)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:61|41)|0*(?:97|65));?|a)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:74|54)|0*(?:116|84));?|t)(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*(?:61|41)|0*(?:97|65));?|a)(?!(?:&\\#(?:x0*3a|0*58);?|&colon;?|\\:)(?:&\\#(?:x0*(?:69|49)|0*(?:105|73));?|i)(?:&\\#(?:x0*(?:6d|4d)|0*(?:109|77));?|m)(?:&\\#(?:x0*(?:61|41)|0*(?:97|65));?|a)(?:&\\#(?:x0*(?:67|47)|0*(?:103|71));?|g)(?:&\\#(?:x0*(?:65|45)|0*(?:101|69));?|e)(?:&\\#(?:x0*2f|0*47);?|\\/)(?:(?:&\\#(?:x0*(?:70|50)|0*(?:112|80));?|p)(?:&\\#(?:x0*(?:6e|4e)|0*(?:110|78));?|n)(?:&\\#(?:x0*(?:67|47)|0*(?:103|71));?|g)|(?:&\\#(?:x0*(?:62|42)|0*(?:98|66));?|b)(?:&\\#(?:x0*(?:6d|4d)|0*(?:109|77));?|m)(?:&\\#(?:x0*(?:70|50)|0*(?:112|80));?|p)|(?:&\\#(?:x0*(?:67|47)|0*(?:103|71));?|g)(?:&\\#(?:x0*(?:69|49)|0*(?:105|73));?|i)(?:&\\#(?:x0*(?:66|46)|0*(?:102|70));?|f)|(?:&\\#(?:x0*(?:70|50)|0*(?:112|80));?|p)?(?:&\\#(?:x0*(?:6a|4a)|0*(?:106|74));?|j)(?:&\\#(?:x0*(?:70|50)|0*(?:112|80));?|p)(?:&\\#(?:x0*(?:65|45)|0*(?:101|69));?|e)(?:&\\#(?:x0*(?:67|47)|0*(?:103|71));?|g)|(?:&\\#(?:x0*(?:74|54)|0*(?:116|84));?|t)(?:&\\#(?:x0*(?:69|49)|0*(?:105|73));?|i)(?:&\\#(?:x0*(?:66|46)|0*(?:102|70));?|f)(?:&\\#(?:x0*(?:66|46)|0*(?:102|70));?|f)|(?:&\\#(?:x0*(?:73|53)|0*(?:115|83));?|s)(?:&\\#(?:x0*(?:76|56)|0*(?:118|86));?|v)(?:&\\#(?:x0*(?:67|47)|0*(?:103|71));?|g)(?:&\\#(?:x0*2b|0*43);?|\\+)(?:&\\#(?:x0*(?:78|58)|0*(?:120|88));?|x)(?:&\\#(?:x0*(?:6d|4d)|0*(?:109|77));?|m)(?:&\\#(?:x0*(?:6c|4c)|0*(?:108|76));?|l))(?:(?:&\\#(?:x0*3b|0*59);?|;)(?:&\\#(?:x0*(?:63|43)|0*(?:99|67));?|c)(?:&\\#(?:x0*(?:68|48)|0*(?:104|72));?|h)(?:&\\#(?:x0*(?:61|41)|0*(?:97|65));?|a)(?:&\\#(?:x0*(?:72|52)|0*(?:114|82));?|r)(?:&\\#(?:x0*(?:73|53)|0*(?:115|83));?|s)(?:&\\#(?:x0*(?:65|45)|0*(?:101|69));?|e)(?:&\\#(?:x0*(?:74|54)|0*(?:116|84));?|t)(?:&\\#(?:x0*3d|0*61);?|=)[\\-a-z0-9]+)?(?:(?:&\\#(?:x0*3b|0*59);?|;)(?:&\\#(?:x0*(?:62|42)|0*(?:98|66));?|b)(?:&\\#(?:x0*(?:61|41)|0*(?:97|65));?|a)(?:&\\#(?:x0*(?:73|53)|0*(?:115|83));?|s)(?:&\\#(?:x0*(?:65|45)|0*(?:101|69));?|e)(?:&\\#(?:x0*36|0*54);?|6)(?:&\\#(?:x0*34|0*52);?|4))?(?:&\\#(?:x0*2c|0*44);?|,)))(?:\\s|(?:&\\#(?:x0*(?:9|a|d)|0*(?:9|10|13));?|&Tab;?|&NewLine;?))*(?:&\\#(?:x0*3a|0*58);?|&colon|\\:)|
#css expression
(?:^|[^\\w])(?:(?:\\\\0*65|\\\\0*45|e)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*78|\\\\0*58|x)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*70|\\\\0*50|p)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*72|\\\\0*52|r)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*65|\\\\0*45|e)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*73|\\\\0*53|s)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*73|\\\\0*53|s)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*69|\\\\0*49|i)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6f|\\\\0*4f|o)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6e|\\\\0*4e|n))[^\\w]*?(?:\\\\0*28|\\()|
#css properties
(?:^|[^\\w])(?:(?:(?:\\\\0*62|\\\\0*42|b)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*65|\\\\0*45|e)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*68|\\\\0*48|h)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*61|\\\\0*41|a)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*76|\\\\0*56|v)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*69|\\\\0*49|i)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6f|\\\\0*4f|o)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*72|\\\\0*52|r)(?:\\/\\*.*?\\*\\/)*)|(?:(?:\\\\0*2d|\\\\0*2d|-)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6d|\\\\0*4d|m)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6f|\\\\0*4f|o)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*7a|\\\\0*5a|z)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*2d|\\\\0*2d|-)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*62|\\\\0*42|b)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*69|\\\\0*49|i)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6e|\\\\0*4e|n)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*64|\\\\0*44|d)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*69|\\\\0*49|i)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6e|\\\\0*4e|n)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*67|\\\\0*47|g)(?:\\/\\*.*?\\*\\/)*))[^\\w]*(?:\\\\0*3a|\\\\0*3a|:)[^\\w]*(?:\\\\0*75|\\\\0*55|u)(?:\\\\0*72|\\\\0*52|r)(?:\\\\0*6c|\\\\0*4c|l)|
#properties
(?:^|[^\\w])(?:on(?:abort|activate|active|addsourcebuffer|addstream|addtrack|afterprint|afterscriptexecute|afterupdate|alerting|animationcancel|animationend|animationiteration|animationstart|antennaavailablechange|appinstalled|audioend|audioprocess|audiostart|autocomplete|autocompleteerror|auxclick|beforeactivate|beforecopy|beforecut|beforedeactivate|beforeeditfocus|beforeinput|beforeinstallprompt|beforepaste|beforeprint|beforescriptexecute|beforeunload|beforeupdate|beforexrselect|begin|beginevent|blocked|blur|bounce|boundary|broadcast|busy|cached|callschanged|cancel|canplay|canplaythrough|cardstatechange|cellchange|cfstatechange|change|chargingchange|chargingtimechange|checkboxstatechange|checking|click|close|command|commandupdate|compassneedscalibration|complete|compositionend|compositionstart|compositionupdate|connect|connected|connecting|connectioninfoupdate|contactchange|contextmenu|controllerchange|controlselect|copy|cuechange|currentchannelchanged|currentsourcechanged|cut|data|dataavailable|datachange|datachannel|dataerror|datasetchanged|datasetcomplete|dblclick|deactivate|delivered|deliveryerror|deliverysuccess|devicechange|devicelight|devicemotion|deviceorientation|deviceproximity|dialing|disabled|dischargingtimechange|disconnected|disconnecting|domattrmodified|domcharacterdatamodified|domcontentloaded|dommenuitemactive|dommenuiteminactive|dommousescroll|domnodeinserted|domnodeinsertedintodocument|domnoderemoved|domnoderemovedfromdocument|domsubtreemodified|downloading|drag|dragdrop|dragend|dragenter|dragexit|dragleave|dragover|dragstart|drain|drop|durationchange|eitbroadcasted|emptied|enabled|encrypted|end|ended|endevent|enter|enterpictureinpicture|error|errorupdate|exit|failed|fetch|filterchange|finish|focus|focusin|focusout|formchange|formdata|forminput|frequencychange|fullscreenchange|fullscreenerror|gamepadconnected|gamepaddisconnected|gesturechange|gestureend|gesturestart|gotpointercapture|hashchange|headphoneschange|held|help|holding|icccardlockerror|iccinfochange|icecandidate|iceconnectionstatechange|icegatheringstatechange|identityresult|idpassertionerror|idpvalidationerror|inactive|incoming|input|install|invalid|isolationchange|keydown|keypress|keystatuschange|keyup|languagechange|layoutcomplete|leavepictureinpicture|levelchange|load|loaded|loadeddata|loadedmetadata|loadend|loading|loadingdone|loadingerror|loadstart|localized|losecapture|lostpointercapture|mark|mediacomplete|mediaerror|message|messageerror|midimessage|mousedown|mouseenter|mouseleave|mousemove|mouseout|mouseover|mouseup|mousewheel|move|moveend|movestart|mozaudioavailable|mozbrowseractivitydone|mozbrowserasyncscroll|mozbrowseraudioplaybackchange|mozbrowsercaretstatechanged|mozbrowserclose|mozbrowsercontextmenu|mozbrowserdocumentfirstpaint|mozbrowsererror|mozbrowserfindchange|mozbrowserfirstpaint|mozbrowsericonchange|mozbrowserloadend|mozbrowserloadstart|mozbrowserlocationchange|mozbrowsermanifestchange|mozbrowsermetachange|mozbrowseropensearch|mozbrowseropentab|mozbrowseropenwindow|mozbrowserresize|mozbrowserscroll|mozbrowserscrollareachanged|mozbrowserscrollviewchange|mozbrowsersecuritychange|mozbrowserselectionstatechanged|mozbrowsershowmodalprompt|mozbrowsertitlechange|mozbrowserusernameandpasswordrequired|mozbrowservisibilitychange|mozfullscreenchange|mozfullscreenerror|mozgamepadbuttondown|mozgamepadbuttonup|mozinterruptbegin|mozinterruptend|mozmousepixelscroll|mozorientation|mozpointerlockchange|mozpointerlockerror|mozscrolledareachanged|moztimechange|mscontentzoom|msgesturechange|msgesturedoubletap|msgestureend|msgesturehold|msgesturerestart|msgesturestart|msgesturetap|msgotpointercapture|msinertiastart|mslostpointercapture|msmanipulationstatechanged|msneedkey|mspointercancel|mspointerdown|mspointerenter|mspointerhover|mspointerleave|mspointermove|mspointerout|mspointerover|mspointerup|mute|negotiationneeded|nodecreate|nomatch|notificationclick|noupdate|obsolete|offline|online|open|orientationchange|outofsync|overconstrained|overflow|page|pagehide|pageshow|paste|pause|peeridentity|peerinfoupdat|play|playing|pointercancel|pointerdown|pointerenter|pointerleave|pointerlockchange|pointerlockerror|pointermove|pointerrawupdate|pointerout|pointerover|pointerup|popstate|popuphidden|popuphiding|popupshowing|popupshown|progress|propertychange|push|pushsubscriptionchange|radiostatechange|ratechange|readystatechange|received|rejectionhandled|removesourcebuffer|removestream|removetrack|repeat|repeatevent|reset|resize|resizeend|resizestart|resourcetimingbufferfull|result|resume|resuming|retrieving|reverse|rowdelete|rowenter|rowexit|rowinserted|rowsdelete|rowsinserted|scanningstatechanged|scroll|search|seek|seeked|seeking|select|selectionchange|selectstart|sending|sent|sessionavailable|sessionconnect|settingchange|shippingaddresschange|shippingoptionchange|show|signalingstatechange|slotchange|smartcard|sort|soundend|soundstart|sourceclose|sourceended|sourceopen|speakerforcedchange|speechend|speechstart|stalled|start|started|statechange|statuschange|stkcommand|stksessionend|stop|storage|submit|success|suspend|svgabort|svgerror|svgload|svgresize|svgscroll|svgunload|svgzoom|synchrestored|timeerror|timeout|timer|timeupdate|toggle|tonechange|touchcancel|touchend|touchenter|touchleave|touchmove|touchstart|trackchange|transitioncancel|transitionend|transitionrun|transitionstart|underflow|unhandledrejection|unload|unmute|update|updateend|updatefound|updateready|updatestart|upgradeneeded|urlflip|userproximity|ussdreceived|valuechange|versionchange|visibilitychange|voicechange|voiceschanged|volumechange|vrdisplayactivate|vrdisplayblur|vrdisplayconnect|vrdisplayconnected|vrdisplaydeactivate|vrdisplaydisconnect|vrdisplaydisconnected|vrdisplayfocus|vrdisplaypresentchange|waiting|waitingforkey|webglcontextcreationerror|webglcontextlost|webglcontextrestored|webkitanimationend|webkitanimationiteration|webkitanimationstart|webkitfullscreenchange|webkitfullscreenerror|webkitmouseforcechanged|webkitmouseforcedown|webkitmouseforceup|webkitmouseforcewillbegin|webkittransitionend|webkitwillrevealbottom|wheel|writeend|zoom)|formaction|data\\-bind|ev:event)[^\\w]
)/ix');

$this->blacklistedParams['request.queryString[action]'][] = '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i';
$this->blacklistedParams['request.queryString[img]'][] = '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i';
$this->blacklistedParams['request.body[action]'][] = '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i';
$this->blacklistedParams['request.body[img]'][] = '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i';
$this->blacklistedParams['request.body[nsextt]'][] = '/.*/';
$this->blacklistedParams['request.fileNames[Filedata]'][] = '/\\/uploadify\\.php$/i';
$this->blacklistedParams['request.fileNames[yiw_contact]'][] = '/.*/';
$this->blacklistedParams['request.fileNames[filename]'][] = '/\\/license\\.php$/i';
$this->blacklistedParams['request.fileNames[update_file]'][] = '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php$/i';
$this->blacklistedParams['request.fileNames[Filedata]'][] = '/tiny_mce[\\/]+plugins[\\/]+tinybrowser[\\/]+upload_file\\.php$/i';
$this->blacklistedParams['request.fileNames[upload]'][] = '/elfinder[\\/]+php[\\/]+connector\\.minimal\\.php$/i';

$this->whitelistedParams['request.body[excerpt]'][] = '/.*/';
$this->whitelistedParams['request.body[comment]'][] = array (
  'url' => '/wp-comments-post\\.php$/i',
  'rules' => 
  array (
    0 => '3',
    1 => '12',
    2 => '146',
  ),
);
$this->whitelistedParams['request.body[content]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[data]'][] = array(
'url' => '/\\/wp-admin\\/admin-ajax\\.php$/i',
'rules' => array (
  0 => '9',
),
'conditional' => new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'notMatch', '/^(?:nopriv_)?wpgdprc_process_action$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '/^(?:nopriv_)?wpgdprc_process_action$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notEquals', 'elementor_js_log', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notEquals', 'elementor_js_log', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))))))
);
$this->whitelistedParams['request.body[params][files]'][] = array (
  'url' => '/\\/wp\\-load\\.php$/i',
  'rules' => 
  array (
    0 => '9',
  ),
);
$this->whitelistedParams['request.queryString[s]'][] = '/\\/wp-admin\\/(?:network\\/)?(?:plugin(?:s|-install)|edit)\\.php$/i';
$this->whitelistedParams['request.body[whitelistedPath]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[whitelistedParam]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[oldWhitelistedPath]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[oldWhitelistedParam]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[newWhitelistedPath]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[newWhitelistedParam]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[bannedURLs]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[scan_include_extra]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[newcontent]'][] = '/\\/wp-admin\\/(?:network\\/)?(?:(?:plugin|theme)-editor|admin-ajax)\\.php$/i';
$this->whitelistedParams['request.body[widget-text]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[widget-custom_html]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.queryString[_wp_http_referer]'][] = '/.{0,1}/';
$this->whitelistedParams['request.body[_wp_http_referer]'][] = array (
  'url' => '/.*/',
  'rules' => 
  array (
    0 => '13',
  ),
);
$this->whitelistedParams['request.queryString[plugin]'][] = '/\\/wp-admin\\/(?:network\\/)?plugins\\.php$/i';
$this->whitelistedParams['request.queryString[action]'][] = '/\\/wp-admin\\/(?:network\\/)?plugins\\.php$/i';
$this->whitelistedParams['request.queryString[checked]'][] = '/\\/wp-admin\\/(?:network\\/)?plugins\\.php$/i';
$this->whitelistedParams['request.body[action]'][] = '/\\/wp-admin\\/(?:network\\/)?plugins\\.php$/i';
$this->whitelistedParams['request.body[checked]'][] = '/\\/wp-admin\\/(?:network\\/)?plugins\\.php$/i';
$this->whitelistedParams['request.body[submit]'][] = '/\\/wp-admin\\/(?:network\\/)?plugins\\.php$/i';
$this->whitelistedParams['request.body[blogname]'][] = '/\\/wp-admin\\/options\\.php$/i';
$this->whitelistedParams['request.body[blogdescription]'][] = '/\\/wp-admin\\/options\\.php$/i';
$this->whitelistedParams['request.body[siteurl]'][] = '/\\/wp-admin\\/options\\.php$/i';
$this->whitelistedParams['request.body[home]'][] = '/\\/wp-admin\\/options\\.php$/i';
$this->whitelistedParams['request.body[admin_email]'][] = '/\\/wp-admin\\/options\\.php$/i';
$this->whitelistedParams['request.body[moderation_keys]'][] = '/\\/wp-admin\\/options\\.php$/i';
$this->whitelistedParams['request.body[blacklist_keys]'][] = '/\\/wp-admin\\/options\\.php$/i';
$this->whitelistedParams['request.body[permalink_structure]'][] = '/\\/wp-admin\\/options\\.php$/i';
$this->whitelistedParams['request.body[category_base]'][] = '/\\/wp-admin\\/options\\.php$/i';
$this->whitelistedParams['request.body[tag_base]'][] = '/\\/wp-admin\\/options\\.php$/i';
$this->whitelistedParams['request.queryString[s]'][] = '/\\/wp-admin\\/edit-comments\\.php$/i';
$this->whitelistedParams['request.body[log]'][] = '/\\/wp-login\\.php$/i';
$this->whitelistedParams['request.body[pwd]'][] = '/\\/wp-login\\.php$/i';
$this->whitelistedParams['request.body[redirect_to]'][] = '/\\/wp-login\\.php$/i';
$this->whitelistedParams['request.queryString[s]'][] = '/\\/wp-admin\\/network\\/(?:user|site)s\\.php$/i';
$this->whitelistedParams['request.body[blog]'][] = '/\\/wp-admin\\/network\\/site-new\\.php$/i';
$this->whitelistedParams['request.body[deletedWhitelistedPath]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[deletedWhitelistedParam]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[itsec_global][log_location]'][] = '/\\/wp-admin\\/options\\.php$/i';
$this->whitelistedParams['request.body[itsec_backup][location]'][] = '/\\/wp-admin\\/options\\.php$/i';
$this->whitelistedParams['request.body[dir]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[sql_query]'][] = '/(?:lint|import)\\.php$/i';
$this->whitelistedParams['request.body[divi_integration_body]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[divi_integration_head]'][] = '/\\/wp-admin\\/admin-ajax\\.php$/i';
$this->whitelistedParams['request.body[modules]'][] = array(
'url' => '/\\/wp-admin\\/admin-ajax\\.php$/i',
'rules' => array (
  0 => '3',
  1 => '9',
),
'conditional' => new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'et_fb_get_shortcode_from_fb_object', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'et_fb_ajax_save', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIs', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'currentUserIs', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))))))
);
$this->whitelistedParams['request.body[fl_builder_data][settings][html]'][] = array(
'url' => '/.*/',
'rules' => array (
  0 => '9',
),
'conditional' => new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIs', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'currentUserIs', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))))
);
$this->whitelistedParams['request.body[partials]'][] = array(
'url' => '/.*/',
'rules' => array (
  0 => '9',
),
'conditional' => new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIs', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))))
);
$this->whitelistedParams['request.body[code]'][] = array(
'url' => '/\\/wp\\-load\\.php$/i',
'rules' => array (
  0 => '3',
),
'conditional' => new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'ZXhlYw', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#^define\\(\\s*\'DONOTCACHEDB\',\\s*true\\s*\\);\\s*if\\s*\\(\\s*function_exists\\(\\s*\'vp_ai_ping_get\'\\s*\\)\\s*\\)\\s*return\\s*vp_ai_ping_get\\(\\);\\s*else\\s*return\\s*\\$this->ai_ping_get\\(\\);$#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'code'), array (
)))))))
);
$this->whitelistedParams['request.body[files]'][] = array (
  'url' => '/\\/vp\\-restore\\-helper\\-[a-zA-Z0-9]+\\.php$/i',
  'rules' => 
  array (
    0 => '3',
    1 => '9',
  ),
);
$this->whitelistedParams['request.body[actions]'][] = array(
'url' => '/\\/wp-admin\\/admin-ajax\\.php$/i',
'rules' => array (
  0 => '3',
  1 => '9',
),
'conditional' => new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'elementor_ajax', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIs', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'currentUserIs', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))))))
);
$this->whitelistedParams['request.body[options][modules][ga_code]'][] = array (
  'url' => '#wp\\-admin/+options\\-general.php$#i',
  'rules' => 
  array (
    0 => '9',
  ),
);
$this->whitelistedParams['request.fileNames'][] = array (
  'url' => '#importbuddy\\.php$#i',
  'rules' => 
  array (
    0 => '76',
  ),
);

$this->rules[208] = wfWAFRule::create($this, 208, NULL, 'xss', '100', 'Strong Testimonials < 2.40.1 - Stored Cross Site Scripting (XSS)', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/post\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'custom'), array (
))))));
$this->rules[67] = wfWAFRule::create($this, 67, NULL, 'lfi', '100', 'Directory Traversal - wp-config.php', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/(^|\\/|\\\\)(\\.\\.?(\\\\|\\/)+)+wp\\-config\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[119] = wfWAFRule::create($this, 119, NULL, 'rce', '100', 'Duplicator Installer wp-config.php Overwrite', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/installer(-backup)?\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', '3', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action_ajax'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[18] = wfWAFRule::create($this, 18, NULL, 'priv-esc', '100', 'User Roles Manager Privilege Escalation <= 4.24', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'notEquals', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'ure_other_roles'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/(network/)?(profile|user-new)\\.php#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[272] = wfWAFRule::create($this, 272, NULL, 'auth-bypass', '100', 'WAF-RULE-272', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '122e966f883480ce46bc8e69ee9c6034'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[66] = wfWAFRule::create($this, 66, NULL, 'dos', '100', 'WordPress Core <= 4.5.3 - DoS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'update-plugin', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/(^|\\/|\\\\|%2f|%5c)\\.\\.(\\\\|\\/|%2f|%5c)/i', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
))))));
$this->rules[286] = wfWAFRule::create($this, 286, NULL, 'auth-bypass', '100', 'Arbitrary Plugin deactivation in multiple themes including Activello <=1.4.0', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'deactivate_plugin', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'activate_plugin', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'plugin'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[117] = wfWAFRule::create($this, 117, NULL, 'privesc', '100', 'WordPress Core: Arbitrary File Deletion', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/(network/)?post\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'editattachment', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/\\/|\\\\/', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'thumb'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'thumb'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[292] = wfWAFRule::create($this, 292, NULL, 'priv-esc', '100', 'WAF-RULE-292', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/profile\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '2a4a96d0fefcf401d6f5cf1e0ff75e3a'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '2a4a96d0fefcf401d6f5cf1e0ff75e3a'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[298] = wfWAFRule::create($this, 298, NULL, 'xss', '100', 'WAF-RULE-298', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/post\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '58031e3297dea8f8016062d61d8fa8d0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '9831ec25a33ef9ed1be178a5e35c5d3d'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '9831ec25a33ef9ed1be178a5e35c5d3d'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'dece9f96647ce6f656ea2d6cf963379e'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'dece9f96647ce6f656ea2d6cf963379e'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[301] = wfWAFRule::create($this, 301, NULL, 'xss', '100', 'Themify Portfolio Post < 1.1.5 - Stored Cross Site Scripting (XSS)', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp-admin/(network/)?post\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'project_date'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'project_client'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'project_services'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'project_launch'), array (
))))));
$this->rules[316] = wfWAFRule::create($this, 316, NULL, 'sde', '100', 'Elementor Contact Form DB < 1.6 Unauthenticated Sensitive Data Export', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp-admin/#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'download_csv'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'download_csv'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'download_csv'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'download_csv'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'form_name'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'form_name'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'form_id'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'form_id'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[330] = wfWAFRule::create($this, 330, NULL, 'auth-bypass', '100', 'WAF-RULE-330', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '457d0846266cff5c1d4a6becdc41e90a', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '5d7064fc34dcedc726d727af07de58ad', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIs', 'subscriber', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[345] = wfWAFRule::create($this, 345, NULL, 'xss', '100', 'Related Posts for WordPress < 2.0.4 - Reflected Cross-Site Scripting (XSS)', 0, 'blockXSS', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'lang'), array (
))))));
$this->rules[350] = wfWAFRule::create($this, 350, NULL, 'xss', '100', 'Mapplic <= 6.1 and Mapplic Lite <= 1.0 - SSRF to Stored Cross-Site Scripting (XSS)', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'mapplic-mapdata'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#\\.svg$#i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'new-map'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#"map":"[^"]+\\.svg"#i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'mapplic-mapdata'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[263] = wfWAFRule::create($this, 263, NULL, 'xss', '100', 'All in One SEO Pack <= 3.6.1 Contributor+ Stored XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/post\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'aiosp_title'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'aiosp_title'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'aiosp_description'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'aiosp_description'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[289] = wfWAFRule::create($this, 289, NULL, 'priv-esc', '100', 'TI WooCommerce Wishlist < 1.21.12 - Authenticated WP Options Change', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'tinvwl_import_settings', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[268] = wfWAFRule::create($this, 268, NULL, 'xss', '100', 'WAF-RULE-268', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/post\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '58031e3297dea8f8016062d61d8fa8d0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/vc_raw_html|vc_raw_js|custom_onclick_code/', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '9a0364b9e99bb480dd25e1f0284c8555'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '9a0364b9e99bb480dd25e1f0284c8555'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[374] = wfWAFRule::create($this, 374, NULL, 'xss', '100', 'Profile and User-New XSS Logonly Rule', 0, 'log', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/(network/)?(profile|user-new)\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
))))));
$this->rules[1] = wfWAFRule::create($this, 1, NULL, 'whitelist', '100', 'Whitelisted URL', 1, 'allow', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/(network/)?(post|profile|user-new|settings)\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'wordfence_loadLiveTraffic', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'wordfence_ticker', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIs', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'install-plugin', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'update-plugin', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'delete-plugin', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'search-plugins', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'search-install-plugins', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'activate-plugin', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'update-theme', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'delete-theme', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'install-theme', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'customize_save', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
))))))))));
$this->rules[2] = wfWAFRule::create($this, 2, NULL, 'lfi', '100', 'Slider Revolution: Local File Inclusion', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'revslider_show_image', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_revslider_show_image', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/\\.php$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'img'), array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'revslider_show_image', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_revslider_show_image', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/\\.php$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'img'), array (
))))))));
$this->rules[60] = wfWAFRule::create($this, 60, NULL, 'file_upload', '100', 'Slider Revolution: Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'revslider_ajax_action', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_revslider_ajax_action', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'update_plugin', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'client_action'), array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'revslider_ajax_action', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_revslider_ajax_action', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'update_plugin', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'client_action'), array (
)))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[15] = wfWAFRule::create($this, 15, NULL, 'xss', '100', 'dzs-videogallery 8.80 XSS HTML injection in inline JavaScript', 0, 'blockXSS', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/dzs\\-videogallery[\\/]+admin[\\/]+(?:playlist|tag)seditor[\\/]+popup\\.php/', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'contains', '\'', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'initer'), array (
))))));
$this->rules[16] = wfWAFRule::create($this, 16, NULL, 'sqli', '100', 'Simple Ads Manager <= 2.9.4.116 - SQL Injection', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/simple-ads-manager[\\/]+sam-ajax-loader\\.php/', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'sqliRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'wc'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
))))));
$this->rules[17] = wfWAFRule::create($this, 17, NULL, 'rfi', '100', 'Gwolle Guestbook <= 1.5.3 - Remote File Inclusion', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/gwolle\\-gb[\\/]+frontend[\\/]+captcha[\\/]+ajaxresponse\\.php/', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/.*/', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'abspath'), array (
))))));
$this->rules[21] = wfWAFRule::create($this, 21, NULL, 'file_upload', '100', 'Ninja Forms <= 2.9.42 - Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'nf_async_upload', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[29] = wfWAFRule::create($this, 29, NULL, 'xss', '100', 'WPMain Stored XSS <= 3.1.2', 1, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'mainwp-setup', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'page'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'page'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[31] = wfWAFRule::create($this, 31, NULL, 'file_upload', '100', 'EWWW Image Optimizer <= 2.8.0 - Remote Command Execution', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'ewww_image_optimizer_delay'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'notMatch', '/^\\d+$/', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'ewww_image_optimizer_delay'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'ewww_image_optimizer_optipng_level'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '/^\\d+$/', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'ewww_image_optimizer_optipng_level'), array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'ewww_image_optimizer_pngout_level'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '/^\\d+$/', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'ewww_image_optimizer_pngout_level'), array (
))))))));
$this->rules[33] = wfWAFRule::create($this, 33, NULL, 'sqli', '100', 'Kento Post View Counter SQLi <= 2.8', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '46f5a89acb206a7f58db187e45fa2a4d', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '/^(?:country|city)$/ix', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '5fc75f82e79d75efb9716109034a3209'), array (
))))))));
$this->rules[36] = wfWAFRule::create($this, 36, NULL, 'file_upload', '100', 'WP Mobile Detector <= 3.5 - Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-mobile\\-detector[/]+resize\\.php#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#/wp\\-mobile\\-detector[/]+timthumb\\.php#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'src'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '/\\.(?:png|gif|jpg|jpeg|jif|jfif|svg)$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'src'), array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'src'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '/\\.(?:png|gif|jpg|jpeg|jif|jfif|svg)$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'src'), array (
))))))));
$this->rules[37] = wfWAFRule::create($this, 37, NULL, 'sqli', '100', 'Double Opt-In for Download <= 2.0.9 - SQL Injection', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'id'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '/^[0-9]+$/', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'id'), array (
)))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'populate_download_edit_form', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))));
$this->rules[38] = wfWAFRule::create($this, 38, NULL, 'sde', '100', 'WP Maintenance Mode <= 2.0.3 - Sensitive Data Exposure', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '9082302c5211de15622f1cfab357f521', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))));
$this->rules[39] = wfWAFRule::create($this, 39, NULL, 'sde', '100', 'WP Maintenance Mode <= 2.0.3 - Auth Bypass', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'wpmm_reset_settings', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))));
$this->rules[40] = wfWAFRule::create($this, 40, NULL, 'rce', '100', 'WP Maintenance Mode <= 2.0.3 - Remote Code Execution', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#wp\\-admin/+options\\-general.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'wp-maintenance-mode', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'page'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'page'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/["\\$]/', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'options', 'modules', 'ga_code'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'options', 'modules', 'ga_code'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', '1', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'options', 'modules', 'ga_status'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'options', 'modules', 'ga_status'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'modules', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'tab'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'tab'), array (
))))));
$this->rules[41] = wfWAFRule::create($this, 41, NULL, 'auth-bypass', '100', 'Robo Gallery <= 2.0.14 - Auth Bypass', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'rbs_gallery', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'author', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'contributor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[44] = wfWAFRule::create($this, 44, NULL, 'auth-bypass', '100', 'SEO by SQUIRRLY <= 6.1.0 - Auth Bypass', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'c12e6c914ed9a7bbeca851684096ac94', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'eadf52d0c96eb78634b8d939a66fb96f', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'affcac9194a01c0146937eac49f5bd9f', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))))));
$this->rules[45] = wfWAFRule::create($this, 45, NULL, 'auth-bypass', '100', 'DELUCKS SEO <= 1.3.9 - Unauthorized Options Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'dpc_save_settings'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'dpc_save_settings'), array (
)))))));
$this->rules[48] = wfWAFRule::create($this, 48, NULL, 'xss', '100', 'All in One SEO Pack 2.3.6.1 - Persistent XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/Abonti|aggregator|AhrefsBot|asterias|BDCbot|BLEXBot|BuiltBotTough|Bullseye|BunnySlippers|ca\\-crawler|CCBot|Cegbfeieh|CheeseBot|CherryPicker|CopyRightCheck|cosmos|Crescent|discobot|DittoSpyder|DotBot|Download Ninja|EasouSpider|EmailCollector|EmailSiphon|EmailWolf|EroCrawler|Exabot|ExtractorPro|Fasterfox|FeedBooster|Foobot|Genieo|grub\\-client|Harvest|hloader|httplib|HTTrack|humanlinks|ieautodiscovery|InfoNaviRobot|IstellaBot|Java\\/1\\.|JennyBot|k2spider|Kenjin Spider|Keyword Density\\/0\\.9|larbin|LexiBot|libWeb|libwww|LinkextractorPro|linko|LinkScan\\/8\\.1a Unix|LinkWalker|LNSpiderguy|lwp\\-trivial|magpie|Mata Hari|MaxPointCrawler|MegaIndex|Microsoft URL Control|MIIxpc|Mippin|Missigua Locator|Mister PiX|MJ12bot|moget|MSIECrawler|NetAnts|NICErsPRO|Niki\\-Bot|NPBot|Nutch|Offline Explorer|Openfind|panscient\\.com|PHP\\/5\\.\\{|ProPowerBot\\/2\\.14|ProWebWalker|Python\\-urllib|QueryN Metasearch|RepoMonkey|RMA|SemrushBot|SeznamBot|SISTRIX|sitecheck\\.Internetseer\\.com|SiteSnagger|SnapPreviewBot|Sogou|SpankBot|spanner|spbot|Spinn3r|suzuran|Szukacz\\/1\\.4|Teleport|Telesoft|The Intraformant|TheNomad|TightTwatBot|Titan|toCrawl\\/UrlDispatcher|True_Robot|turingos|TurnitinBot|UbiCrawler|UnisterBot|URLy Warning|VCI|WBSearchBot|Web Downloader\\/6\\.9|Web Image Collector|WebAuto|WebBandit|WebCopier|WebEnhancer|WebmasterWorldForumBot|WebReaper|WebSauger|Website Quester|Webster Pro|WebStripper|WebZip|Wotbox|wsr\\-agent|WWW\\-Collector\\-E|Xenu|Zao|Zeus|ZyBORG|coccoc|Incutio|lmspider|memoryBot|SemrushBot|serf|Unknown|uptime files/i', array(wfWAFRuleComparisonSubject::create($this, array('request.headers', 'User-Agent'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.headers', 'User-Agent'), array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/semalt\\.com|kambasoft\\.com|savetubevideo\\.com|buttons\\-for\\-website\\.com|sharebutton\\.net|soundfrost\\.org|srecorder\\.com|softomix\\.com|softomix\\.net|myprintscreen\\.com|joinandplay\\.me|fbfreegifts\\.com|openmediasoft\\.com|zazagames\\.org|extener\\.org|openfrost\\.com|openfrost\\.net|googlsucks\\.com|best\\-seo\\-offer\\.com|buttons\\-for\\-your\\-website\\.com|www\\.Get\\-Free\\-Traffic\\-Now\\.com|best\\-seo\\-solution\\.com|buy\\-cheap\\-online\\.info|site3\\.free\\-share\\-buttons\\.com|webmaster\\-traffic\\.co/i', array(wfWAFRuleComparisonSubject::create($this, array('request.headers', 'Referer'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.headers', 'Referer'), array (
)))))));
$this->rules[49] = wfWAFRule::create($this, 49, NULL, 'xss', '100', 'All in One SEO Pack <= 2.3.7 - Unauthenticated Stored XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/sitemap_.*?<.*?(:?_\\d+)?\\.xml(:?\\.gz)?/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
))))));
$this->rules[50] = wfWAFRule::create($this, 50, NULL, 'auth-bypass', '100', 'Fluid Responsive Slideshow <= 2.2.26 - Unauthorized Content Modification', 1, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'frs_save', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[52] = wfWAFRule::create($this, 52, NULL, 'file_upload', '100', 'File Manager <= 3.0.0 - Arbitrary File Upload/Download', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'dfff0a7fa1a55c8c1a4966c19f6da452'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'dfff0a7fa1a55c8c1a4966c19f6da452'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '266e0d3d29830abfe7d4ed98b47966f7', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))));
$this->rules[53] = wfWAFRule::create($this, 53, NULL, 'file_upload', '100', 'Levo Slideshow <= 2.3 - Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^(?:lvo_admin_head|lvo_add_new_album|lvo_delete_album|reset_albums|save_lvo_settings|lvo_single_image_upload|lvo_resize_image_and_add|lvo_delete_image|lvo_get_albums_table|lvo_get_albums_images_table|lvo_get_album|lvo_get_album_images|lvo_delete_cache|lvo_reorder_image|lvo_reorder_album|lvo_bulk_delete_albums|lvo_bulk_disable_albums|lvo_bulk_enable_albums|delete_image|lvo_bulk_delete_images|lvo_bulk_disable_images|lvo_bulk_enable_images|lvo_disable_album|lvo_enable_album|lvo_disable_image|lvo_enable_image)$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'task'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'task'), array (
))))));
$this->rules[55] = wfWAFRule::create($this, 55, NULL, 'auth-bypass', '100', 'Form Lightbox <= 2.1 - Unauthenticated Options Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/form\\-lightbox/ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[57] = wfWAFRule::create($this, 57, NULL, 'priv-esc', '100', 'Ultimate Product Catalogue <= 3.8.1 - Privilege Escalation', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '8c2e1c2817e3de18e2140498bdd4f7fa', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'e12a2417ffbd0ae4010210b596a3f230', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'df33bf68ad0288e1547139e02c1e096b', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'c000b32f92bbd81b6cbbddd101073e54', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'cc61a84091dcc8b9bd6ae35cf48d71ab', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'c80c9038bbb5910385decc276e42061e', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'b81e270701125a0024db04bebdbcfc2a', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '2e563359c1b268da0041c5bf822857a1', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '4ba84dbaaafd4e7d98f55e9f093fe65a', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '1deb089a44f2962f92c678a451e61142', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '6ffa8f3e70a6279866e4b2c16fe18729', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'aa1c4fd7fb193a2cd1b0cc9150131b31', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '91e590bfc230eb3971ef1bb6b97ef974', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'd0e980fd7bc681b3c3085b1ac31024d6', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '069dde6f8ea27c8618cc8f6c6703a7c7', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '819900411c0d5c99c116bbce137ee04b', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '097d5401a3ae688b669f29351b9667de', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '81f1bbc03176c4525b8801b0058b309a', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'a8072b3a87b49ffea18548f35c6abd8c', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '364409901cb1fce968104dce4bf7e4fe', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '246c8343383408c8644f31b1f42617ce', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '66d87c0a0e2c02192c322c61d9d6990a', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '67bfe619d00425b51276ae083ae271a5', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '4aaddae320d8aaa8241ffd22693dd546', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '141f5901534f2b3092be526cac250bb6', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '2b7efaffcb87e027a011c33125585db7', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '979e32726f541a1e568557e9eb6554aa', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'c252a9eb30d304ba6079376ef5231aad', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '75b0967858cf244d4e2654e69b33d2f1', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '9cfad494bbf947c2ce316fe96eac396d', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'a4a148b325f286e07d9f24e3654e2672', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '3863850b63dc41d4e6e8cee097644d18', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '8fb62eed357b03c7be735352ab247bbe', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'a0380a8020e3a09257a6c67a1fe14627', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'b0f145120ec76e700969f63c5af3e8f4', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '52f6fc037a9e97f93309b1115882c080', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'f2a2c32747d2d49ddf682158eb9a510e', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '5caa7c3d6bba5a36798619b0ac4747bb', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'a0793408acebd97af0414d46b6705a65', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'f605a16b247f81f2eb2fdc097e1e1a19', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'ea7348459bf68bf881facb0e5d18ccd7', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'c747677e1903fdfffd4108f3347cf5ab', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '05c0ea3ee2df67b6bc2f3921c3fe2180', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'd986eb29534241e46402c30e678af902', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))))));
$this->rules[58] = wfWAFRule::create($this, 58, NULL, 'file_upload', '100', '360 Product Rotation <= 1.2.1 - Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#includes\\/+plugin\\-media\\-upload\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'author', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'contributor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[59] = wfWAFRule::create($this, 59, NULL, 'xss', '100', 'Generic XSS Injection in IP Forwarding Headers', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.headers', 'Client-IP'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.headers', 'X-Forwarded-For'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.headers', 'X-Forwarded'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.headers', 'X-Cluster-Client-IP'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.headers', 'Forwarded-For'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.headers', 'Forwarded'), array (
))))));
$this->rules[64] = wfWAFRule::create($this, 64, NULL, 'rce', '100', 'TimThumb <= 2.8.13 - Remote Code Execution', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/(?:timthumb\\.php|img\\.php)/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/[^A-Za-z0-9\\-\\.\\_:\\/\\?\\&\\+\\;\\=]/', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'src'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'webshot'), array (
))))));
$this->rules[63] = wfWAFRule::create($this, 63, NULL, 'rfd', '100', 'TimThumb <= 1.33 - Remote File Download', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/(?:timthumb\\.php|img\\.php)/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '_^[^\\?]+?\\.(?:jpg|jpeg|gif|png)(?:\\?[a-z0-9\\-\\_\\.\\~%\\!\\$&\'\\(\\)\\*\\+,;\\=\\:@\\/\\?]*)?$_iu', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'src'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'src'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthLessThan', '1', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'webshot'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'webshot'), array (
)))))));
$this->rules[65] = wfWAFRule::create($this, 65, NULL, 'file_upload', '100', 'MailPoet <= 2.6.7 - Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^(?:wysija_)+campaigns/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'page'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'page'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'themes', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'themeupload', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))))));
$this->rules[69] = wfWAFRule::create($this, 69, NULL, 'file_upload', '100', 'N-Media Post Front-end Form <= 1.0 - Unauthenticated Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^(?:nopriv_)?nm_postfront_save_settings$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'author', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^(?:nopriv_)?nm_postfront_(?:load_post_form|save_post|upload_file)$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#/plupload[^/]*/+examples/+upload\\.php#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
))))))));
$this->rules[70] = wfWAFRule::create($this, 70, NULL, 'file_upload', '100', 'CYSTEME Finder <= 1.3 - Multiple Unauthenticated Vulnerabilities', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/cysteme\\-finder[^/]*/+php/+connector\\.php#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[71] = wfWAFRule::create($this, 71, NULL, 'file_upload', '100', 'Estatik <= 2.2.5 - Unauthenticated Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^(?:nopriv_)?es_prop_media_images$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))));
$this->rules[68] = wfWAFRule::create($this, 68, NULL, 'file_upload', '100', 'Malicious File Upload (Patterns)', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'author', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'filePatternsMatch', '', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
))))));
$this->rules[76] = wfWAFRule::create($this, 76, NULL, 'file_upload', '100', 'Malicious File Upload (PHP)', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'author', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'fileHasPHP', '', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
))))));
$this->rules[78] = wfWAFRule::create($this, 78, NULL, 'file_upload', '100', 'BePro Listings <= 2.2.0020 - Unauthenticated Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'notMatch', '/\\.(jpe?g|png|mpeg|mov|flv|pdf|docx?|txt|csv|avi|mp3|wma|wav)($|\\.)/i', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'save_bepro_listing'), array (
))))));
$this->rules[81] = wfWAFRule::create($this, 81, NULL, 'xss', '100', 'FancyBox for WordPress <= 3.0.2 - Persistent XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'fancybox-for-wordpress', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'page'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'mfbfw'), array (
))))));
$this->rules[83] = wfWAFRule::create($this, 83, NULL, 'file_download', '100', 'Delete All Comments <= 2.0.0 - Unauthenticated Remote File Download', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/delete\\-all\\-comments/delete\\-all\\-comments\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'restorefromfileNAME'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'restorefromfileURL'), array (
)))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[87] = wfWAFRule::create($this, 87, NULL, 'sqli', '100', 'NextGEN Gallery <= 2.1.77 - SQL Injection', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/nggallery/+tags/+.*?%25#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#/nggallery/+tags/+(?:[^\\$]*\\$|.*?%24)#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
))))));
$this->rules[88] = wfWAFRule::create($this, 88, NULL, 'file_upload', '100', 'Showbiz Pro 1.7.1 - Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'showbiz_ajax_action', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'update_plugin', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'client_action'), array (
))))));
$this->rules[89] = wfWAFRule::create($this, 89, NULL, 'file_upload', '100', 'Tevolution <= 2.3.6 - Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#monetize[\\/]+templatic\\-custom_fields[\\/]+single\\-upload\\.php#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
))))));
$this->rules[91] = wfWAFRule::create($this, 91, NULL, 'auth-bypass', '100', 'Newspaper Premium Theme <= 6.7.1 - Privilege Escalation', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^(?:nopriv_)?td_ajax_update_panel$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))));
$this->rules[93] = wfWAFRule::create($this, 93, NULL, 'lfi', '100', 'Autoptimize <= 2.1.0 - Unauthenticated Local File Inclusion', 1, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#%%(?:COMMENTS|INJECTLATER)%%#', array(wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.body', array (
))))));
$this->rules[94] = wfWAFRule::create($this, 94, NULL, 'file_upload', '100', 'jQuery HTML5 File Upload <= 3.0 - Unauthenticated Options Update and Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/Save\\sSetting/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'savesetting'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/(p(h(p|tml)[0-9]?|l|y)|(j|a)sp|aspx|sh|shtml|html?|cgi|htaccess|ini|exe)/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'accepted_file_types'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/(p(h(p|tml)[0-9]?|l|y)|(j|a)sp|aspx|sh|shtml|html?|cgi|htaccess|ini|exe)/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'inline_file_types'), array (
)))))));
$this->rules[95] = wfWAFRule::create($this, 95, NULL, 'obji', '100', 'Google Forms <= 0.86 - Unauthenticated Object Injection', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/(^|;|{|})O:+?\\+*[0-9]+:(?!"stdClass")/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'wpgform-action'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/(^|;|{|})O:+?\\+*[0-9]+:(?!"stdClass")/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'wpgform-options'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
))))));
$this->rules[99] = wfWAFRule::create($this, 99, NULL, 'privesc', '100', 'WP Support Plus Responsive Ticket System <= 7.1.3 - Privilege Escalation', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notEquals', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'email'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'loginGuestFacebook', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
))))));
$this->rules[105] = wfWAFRule::create($this, 105, NULL, 'sqli', '100', 'Ultimate Form Builder Lite <= 1.3.6 - SQLi -> RCE via Obji', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'da6c71b8bb99069bd8e2fde83d95cf0d', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '144e471fa0e0005b146b3f10ed5f8192', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/(?:^|&)(?:f|%66)(?:o|%6f)(?:r|%72)(?:m|%6d)(?:_|%5f)(?:d|%64)(?:a|%61)(?:t|%74)(?:a|%61)(?:\\[|%5b)(.+?)(?:\\]|%5d)(?:\\[|%5b)(?:n|%6e)(?:a|%61)(?:m|%6d)(?:e|%65)(?:\\]|%5d)=(?:f|%66)(?:o|%6f)(?:r|%72)(?:m|%6d)(?:_|%5f)(?:i|%69)(?:d|%64)&.*?(?:f|%66)(?:o|%6f)(?:r|%72)(?:m|%6d)(?:_|%5f)(?:d|%64)(?:a|%61)(?:t|%74)(?:a|%61)(?:\\[|%5b)\\1(?:\\]|%5d)(?:\\[|%5b)(?:v|%76)(?:a|%61)(?:l|%6c)(?:u|%75)(?:e|%65)(?:\\]|%5d)=\\d*[^\\d&]+/i', array(wfWAFRuleComparisonSubject::create($this, 'request.rawBody', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/(?:^|&)(?:f|%66)(?:o|%6f)(?:r|%72)(?:m|%6d)(?:_|%5f)(?:d|%64)(?:a|%61)(?:t|%74)(?:a|%61)(?:\\[|%5b)(.+?)(?:\\]|%5d)(?:\\[|%5b)(?:v|%76)(?:a|%61)(?:l|%6c)(?:u|%75)(?:e|%65)(?:\\]|%5d)=\\d*[^\\d&]+[^&]*&.*?(?:f|%66)(?:o|%6f)(?:r|%72)(?:m|%6d)(?:_|%5f)(?:d|%64)(?:a|%61)(?:t|%74)(?:a|%61)(?:\\[|%5b)\\1(?:\\]|%5d)(?:\\[|%5b)(?:n|%6e)(?:a|%61)(?:m|%6d)(?:e|%65)(?:\\]|%5d)=(?:f|%66)(?:o|%6f)(?:r|%72)(?:m|%6d)(?:_|%5f)(?:i|%69)(?:d|%64)(?:$|&)/i', array(wfWAFRuleComparisonSubject::create($this, 'request.rawBody', array (
)))))));
$this->rules[106] = wfWAFRule::create($this, 106, NULL, 'auth-bypass', '100', 'UserPro - User Profiles with Social Login <= 4.9.17 - Authentication Bypass', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'true', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'up_auto_log'), array (
))))));
$this->rules[107] = wfWAFRule::create($this, 107, NULL, 'auth-bypass', '100', 'Formidable Forms <= 2.05.03 - Multiple Vulnerabilities', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'before_html'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'before_html'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'after_html'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'after_html'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^(?:nopriv_)?frm_forms_preview$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))));
$this->rules[108] = wfWAFRule::create($this, 108, NULL, 'spam', '100', 'XRumer/XEvil Spam', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'author', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'contributor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'matchCount', '/This\\s+message\\s+is\\s+posted\\s+here\\s+using\\s+XRumer/i', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
))))));
$this->rules[112] = wfWAFRule::create($this, 112, NULL, 'auth-bypass', '100', 'Super Socializer <= 7.10.6 - Authentication Bypass', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'the_champ_user_auth', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notEquals', '', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'security'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'security'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notEquals', '', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'profileData', 'email'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'profileData', 'email'), array (
))))));
$this->rules[121] = wfWAFRule::create($this, 121, NULL, 'auth-bypass', '100', 'Accelerated Mobile Pages <= 0.9.97.19 - Missing Authentication Checks', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin\\/admin\\-ajax\\.php$/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/^ampforwp_(save_installer|get_licence_activate_update|deactivate_license|enable_modules_upgread)$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/^(amppb_(color_picker|textEditor|export_layout_data|save_layout_data)|ampforwp_get_image)$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'author', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'contributor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))))));
$this->rules[137] = wfWAFRule::create($this, 137, NULL, 'auth-bypass', '100', 'Related Posts <= 5.12.90 - Missing Authentication', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/^yuzo_related_post/', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'name_options'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'save_options'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'save_options'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'reset_options'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'reset_options'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[138] = wfWAFRule::create($this, 138, NULL, 'privesc', '100', 'Yellow Pencil Visual Theme Customizer <= 7.1.9 Arbitrary Options Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'yp_remote_get'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'yp_remote_get'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[139] = wfWAFRule::create($this, 139, NULL, 'auth-bypass', '100', 'ARI-Adminer <= 1.1.14: Missing Auth Check', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/(a|%61|%41)(r|%72|%52)(i|%69|%49)(\\-|%2d)(a|%61|%41)(d|%64|%44)(m|%6d|%4D)(i|%69|%49)(n|%6e|%4E)(e|%65|%45)(r|%72|%52)/#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[140] = wfWAFRule::create($this, 140, NULL, 'rce', '100', 'WAF-RULE-140', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '49477a8d7cf7b0a69df4aece24f5453f'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '0c8e91156c85449ebda7234a2e357cc1'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'cd4fdb546b7b0674fce6c4b0bc27b7f4'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'f4bd3746a1b566e14ba0adb68f06f4b9'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '49477a8d7cf7b0a69df4aece24f5453f'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '0c8e91156c85449ebda7234a2e357cc1'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'cd4fdb546b7b0674fce6c4b0bc27b7f4'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'f4bd3746a1b566e14ba0adb68f06f4b9'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[142] = wfWAFRule::create($this, 142, NULL, 'auth-bypass', '100', 'WooCommerce User Email Verification <= 3.3.0 - Unauthenticated Arbitrary Options Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'wuev_form_type'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[144] = wfWAFRule::create($this, 144, NULL, 'auth-bypass', '100', 'WooCommerce Checkout Manager <= 4.2.6 - Unauthenticated Media Deletion', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'update_attachment_wccm', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'wooccm_front_enduploadsave', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'remove'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'remove'), array (
)))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[145] = wfWAFRule::create($this, 145, NULL, 'xss', '100', 'Blog Designer <= 1.8.10 - Unauthenticated Stored Cross-Site Scripting', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'custom_css'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'custom_css'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'blog_page_display'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'identical', 'save', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'identical', 'true', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'updated'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'updated'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[147] = wfWAFRule::create($this, 147, NULL, 'xss', '100', 'WP Live Chat Support <= 8.0.28 - Unauthenticated Stored Cross-Site Scripting', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'wplc_save_settings'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'wplc_save_settings'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'wplc_custom_css'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'wplc_custom_css'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'wplc_custom_js'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'wplc_custom_js'), array (
)))))));
$this->rules[148] = wfWAFRule::create($this, 148, NULL, 'auth-bypass', '100', 'WPGraphQL <= 0.2.3 - Multiple Vulnerable Actions', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/application\\/json/', array(wfWAFRuleComparisonSubject::create($this, array('request.headers', 'Content-Type'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/\\/graphql/', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^\\s*\\{\\s*"query"/', array(wfWAFRuleComparisonSubject::create($this, 'request.rawBody', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\{\\s*(plugins|themes|mediaItems|users|comments|posts|pages)/', array(wfWAFRuleComparisonSubject::create($this, 'request.rawBody', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/mutation\\s*\\{\\s*registerUser.*?roles:/s', array(wfWAFRuleComparisonSubject::create($this, 'request.rawBody', array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[149] = wfWAFRule::create($this, 149, NULL, 'privesc', '100', 'WAF-RULE-149', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '7e978ce09187339eb687db73fa4af779', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^(administrator|editor|shop_manager|author)$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'b40a9b4758686946978ccfc290f5cd4b'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[153] = wfWAFRule::create($this, 153, NULL, 'rce', '100', 'WAF-RULE-153', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', 'cb26e9d5bd6311e7d4a759ac8c38d9d0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '5ebeb6065f64f2346dbb00ab789cf001'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'c13367945d5d4c91047b3b50234aa7ab'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[154] = wfWAFRule::create($this, 154, NULL, 'privesc', '100', 'Hybrid Composer <= 1.4.5 - Unauthenticated Options Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'hc_ajax_save_option', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[151] = wfWAFRule::create($this, 151, NULL, 'privesc', '100', 'File Manager <= 4.8 - Privilege Escalation, SQL Injection, File Deletion', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#^mk_file_manager_(backup_remove|single_backup_remove|single_backup_logs|single_backup_restore)$#i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))));
$this->rules[152] = wfWAFRule::create($this, 152, NULL, 'backdoor', '100', 'WAF-RULE-152', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/^[0-9a-f]{32}$/', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '5f4dcc3b5aa765d61d8327deb882cf99'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '5f4dcc3b5aa765d61d8327deb882cf99'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '808ad1ac54d3a5e6ab09ed69c2a6605d', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '84a1c9137ae2863590475c6c385b92d7', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '0ebbe8a2b6999ec31f44118f5396e3f3', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '770209dbd19d2cd3da20a08cb138036e', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'd480834a6c46e6e0778d0c863a010667', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '9a2f516318cdf6712d01150110b590b8', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))))));
$this->rules[155] = wfWAFRule::create($this, 155, NULL, 'auth-bypass', '100', 'NicDark Plugins (nd-booking, nd-shortcodes) Unauthenticated Arbitrary Options Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#^(?:nopriv_)?nd_[^_]+_import_settings_php_function#i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[156] = wfWAFRule::create($this, 156, NULL, 'file_upload', '100', 'Woody Ad Snippets <= 2.2.4 - Unauthenticated File Upload/Stored XSS/RCE', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.files', 'wbcr_inp_import_files'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[157] = wfWAFRule::create($this, 157, NULL, 'auth-bypass', '100', 'Login or Logout Menu Item <= 1.1.1 - Unauthenticated Settings Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'lolmi_settings_submit'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'lolmi_settings_submit'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[158] = wfWAFRule::create($this, 158, NULL, 'xss', '100', 'WP Mega Menu <= 1.3.0 Unauthenticated Settings Update/XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'wpmm_theme_type'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'export_wpmm_theme', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[159] = wfWAFRule::create($this, 159, NULL, 'file_upload', '100', 'Simple 301 Redirects Addon – Bulk Uploader <= 1.2.5 - Multiple vulnerabilities', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'submit_bulk_301'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'submit_bulk_301'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'bulk301clearlist', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'bulk301export', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[160] = wfWAFRule::create($this, 160, NULL, 'file_upload', '100', 'Ultimate FAQ <= 1.8.24 File Upload Vulnerability', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'EWD_UFAQ_ImportFaqsFromSpreadsheet', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'Action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'Action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[161] = wfWAFRule::create($this, 161, NULL, 'auth-bypass', '100', 'WP Private Content Plus <= 1.31 Unauthenticated Options Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'wppcp_tab'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[103] = wfWAFRule::create($this, 103, NULL, 'obji', '100', 'PHP Object Injection', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'matchCount', '/(^|;|{|})O:+?\\+*[0-9]+:"WP_Theme"/i', array(wfWAFRuleComparisonSubject::create($this, 'request.headers', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.cookies', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
))))));
$this->rules[3] = wfWAFRule::create($this, 3, NULL, 'sqli', '40', 'SQL Injection', 1, 'failSQLi', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'matchCount', new wfWAFRuleVariable($this, 'sqliRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[9] = wfWAFRule::create($this, 9, NULL, 'xss', '100', 'XSS: Cross Site Scripting', 1, 'failXSS', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'matchCount', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
))))));
$this->rules[11] = wfWAFRule::create($this, 11, NULL, 'file_upload', '100', 'Malicious File Upload', 1, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\.(p(h(pt?|t(ml?)?|ar)[0-9]?|l|y)|(j|a)sp|aspx|sh|shtml|html?|cgi|htaccess|user\\.ini)($|\\.)/i', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[12] = wfWAFRule::create($this, 12, NULL, 'lfi', '100', 'Directory Traversal', 1, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/(^|\\/|\\\\)\\.\\.(\\\\|\\/)/', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[13] = wfWAFRule::create($this, 13, NULL, 'lfi', '100', 'LFI: Local File Inclusion', 1, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/^\\/(?:\\.\\/)*(?:var|home|usr|mnt|media|etc|tmp|dev|proc)\\//i', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[14] = wfWAFRule::create($this, 14, NULL, 'xxe', '100', 'XXE: External Entity Expansion', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/<\\!(?:DOCTYPE|ENTITY)\\s+(?:%\\s*)?\\w+\\s+SYSTEM/i', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
))))));
$this->rules[146] = wfWAFRule::create($this, 146, NULL, 'rce', '100', 'PHAR Deserialization Attack', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/phar:\\/\\//i', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[120] = wfWAFRule::create($this, 120, NULL, 'privesc', '100', 'WP GDPR Compliance <= 1.4.2 - Update Any Option / Call Any Action', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/^(?:nopriv_)?wpgdprc_process_action$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '/^\\{[\'"]type[\'"]:[\'"]access_request[\'"],\\s?[\'"]email[\'"]:[\'"][^\'"]+[\'"],\\s?[\'"]consent[\'"]:(true|false)\\}$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'data'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[122] = wfWAFRule::create($this, 122, NULL, 'privesc', '100', 'Kiwi Social Share <= 2.0.10 - Unauthenticated Update Any Option', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^(?:nopriv_)?kiwi_social_share_set_option$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[123] = wfWAFRule::create($this, 123, NULL, 'sde', '100', 'Kiwi Social Share <= 2.0.10 - Unauthenticated Read Any Option', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^(?:nopriv_)?kiwi_social_share_get_option$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'author', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))))));
$this->rules[124] = wfWAFRule::create($this, 124, NULL, 'privesc', '100', 'Toolset Types <= 2.3.3 - Update Arbitrary Usermeta', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/^(?:wp_capabilities|wp_user_level|session_tokens|source_domain|primary_blog)$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'wp_screen_options', 'option'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[125] = wfWAFRule::create($this, 125, NULL, 'auth-bypass', '100', 'Orbit Fox by ThemeIsle <= 2.6.3 - Improper REST Capabilities Checks', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/wp-json[\\/]+templates-directory[\\/]+import_elementor/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/templates-directory[\\/]+import_elementor/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'rest_route'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'rest_route'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[128] = wfWAFRule::create($this, 128, NULL, 'xss', '100', 'WordPress <= 5.0 - Contributor+ Potential Stored XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIs', 'contributor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'currentUserIs', 'author', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#/wp\\-comments\\-post\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#<form\\W#i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'comment'), array (
))))));
$this->rules[129] = wfWAFRule::create($this, 129, NULL, 'privesc', '100', 'Total Donations (all known versions) - Multiple Unauthenticated AJAX Actions', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/the-ajax-caller\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^(nopriv_)?miglaA?_/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^(?:nopriv_)?miglaA?_(?:add_(?:amount|options|offline_backend)|change_donation|constantcontact_test|delete_(?:postmeta|mform)|export_report|form_bgcolor|get(?:OffDonation|me(?:_array)?|_(?:option|postmeta))|mailchimp_(?:getlists|test)|new_(?:mform|mCampaignCreator)|purgeCache|remove_(?:donation|options)|report|reset_(?:c?form|theme)|retrieve_cc_lists|save_(?:option|campaign(?:_creator)?)|stripe_(?:add(?:Basic)?|get|delete)Plan|syncPlan|test_(?:email|hEmail|offline_email|constant_contact)|update(?:Undesignated|_(?:me|barinfo|c?form|me(?:tadata)?|arr|us|recurring_plans|report|postmeta)))$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))));
$this->rules[130] = wfWAFRule::create($this, 130, NULL, 'bypass', '100', 'UserPro < 4.9.21 - User Registration Privilege Escalation', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^userpro_process_form$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/(?:^|&)((?:f|%66)(?:o|%6f)(?:r|%72)(?:m|%6d)(?:_|%5f))?(?:r|%72)(?:o|%6f)(?:l|%6c)(?:e|%65)(?:(?:-|%2d).+)?=(?:(?:a|%61)(?:d|%64)(?:m|%6d)(?:i|%69)(?:n|%6e)(?:i|%69)(?:s|%73)(?:t|%74)(?:r|%72)(?:a|%61)(?:t|%74)(?:o|%6f)(?:r|%72)|(?:e|%65)(?:d|%64)(?:i|%69)(?:t|%74)(?:o|%6f)(?:r|%72)|(?:s|%73)(?:h|%68)(?:o|%6f)(?:p|%70)(?:_|%5f)(?:m|%6d)(?:a|%61)(?:n|%6e)(?:a|%61)(?:g|%67)(?:e|%65)(?:r|%72))/i', array(wfWAFRuleComparisonSubject::create($this, 'request.rawBody', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[131] = wfWAFRule::create($this, 131, NULL, 'privesc', '100', 'Simple Social Buttons < 2.0.22 - Arbitrary Options Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^ssb_import$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[132] = wfWAFRule::create($this, 132, NULL, 'privesc', '100', 'Freemius SDK < 2.2.2 - Authenticated Arbitrary Options Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^fs_set_db_option$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[133] = wfWAFRule::create($this, 133, NULL, 'lfi', '100', 'WP Fastest Cache <= 0.8.9.0  - Unauthenticated Arbitrary Directory Deletion', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^postratings$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/(^|\\/|\\\\)\\.\\.(\\\\|\\/)/', array(wfWAFRuleComparisonSubject::create($this, array('request.headers', 'Referer'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[134] = wfWAFRule::create($this, 134, NULL, 'auth-bypass', '100', 'Siteground Optimizer <= 5.0.12 - Improper REST capabilities checks', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/wp-json[\\/]+siteground-optimizer[\\/]+v1/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/siteground-optimizer[\\/]+v1/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'rest_route'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'rest_route'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[135] = wfWAFRule::create($this, 135, NULL, 'privesc', '100', 'Easy WP SMTP <= 1.3.9 - Unauthenticated AJAX actions', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'swpsmtp_import_settings'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^swpsmtp_(clear_log|self_destruct)$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[136] = wfWAFRule::create($this, 136, NULL, 'xss', '100', 'Social Warfare <= 3.5.2 - Unauthenticated Stored Cross-Site Scripting', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'swp_url'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[162] = wfWAFRule::create($this, 162, NULL, 'xss', '100', 'Bold Page Builder <= 2.3.1 - Unauthenticated Options Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^(?:nopriv_)?bt_bb_save_custom_css$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))))));
$this->rules[163] = wfWAFRule::create($this, 163, NULL, 'auth-bypass', '100', 'WAF-RULE-163', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '0ae56c7098340a973151232bd90ef954'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notEquals', 'forms', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '0ae56c7098340a973151232bd90ef954'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '/^[0-9a-fA-F]{32}$/', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '3c6e0b8a9c15224a8228b9a98ca1531d'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[164] = wfWAFRule::create($this, 164, NULL, 'auth-bypass', '100', 'WAF-RULE-164', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'ce7c9ce58308d0e1a422a3aa75e0a4f6'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/wp\\-config\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'ce7c9ce58308d0e1a422a3aa75e0a4f6'), array (
))))));
$this->rules[165] = wfWAFRule::create($this, 165, NULL, 'auth-bypass', '100', 'LifterLMS <= 3.34.5 - Unauthenticated options update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.files', 'llms_import'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[166] = wfWAFRule::create($this, 166, NULL, 'redirect', '100', 'Qode Instagram Widget <= 2.0.1 - Unauthenticated Open Redirect', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/qode-instagram-widget\\/lib\\/instagram-redirect\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
))))));
$this->rules[167] = wfWAFRule::create($this, 167, NULL, 'auth-bypass', '100', 'Motors – Car Dealer & Classified Ads <= 1.4.0 - Multiple Options Update vulnerabilities', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.files', 'import_settings'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'export_settings'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'export_settings'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/^stm_ajax_(file_)?automanager/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/stm_listings_.+option/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'stm_xml_do_import_automanager'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'stm_xml_do_import_automanager'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[168] = wfWAFRule::create($this, 168, NULL, 'auth-bypass', '100', 'Advanced AJAX Product Filters <= 1.3.5', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'br-aapf-setup', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'page'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[170] = wfWAFRule::create($this, 170, NULL, 'auth-bypass', '100', 'WAF-RULE-170', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '535563d2fe010e377d91a08df7bd24b0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '3ac340832f29c11538fbe2d6f75e8bcc'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'd723bd8fe120d9b9501b59dbdbbd862e', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '3ac340832f29c11538fbe2d6f75e8bcc'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '820b1f6a2f4f02db8d34dda2c54c495c', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '3ac340832f29c11538fbe2d6f75e8bcc'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[171] = wfWAFRule::create($this, 171, NULL, 'file_upload', '100', 'Theme Editor <= 2.1 - Authenticated Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/^webphoto_upload$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[172] = wfWAFRule::create($this, 172, NULL, 'xss', '100', 'WAF-RULE-172', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '0e96cee26df98f4f879c7cb49c39f3c0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[174] = wfWAFRule::create($this, 174, NULL, 'auth-bypass', '100', 'WAF-RULE-174', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'f5bc9502d581b68aff5f7d9a63575099'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notEquals', 'products', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'f5bc9502d581b68aff5f7d9a63575099'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '/^[0-9a-fA-F]{32}$/', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '3c6e0b8a9c15224a8228b9a98ca1531d'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[176] = wfWAFRule::create($this, 176, NULL, 'xss', '100', 'WP Shopify <= 2.0.4 Stored XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-json/wpshopify/v1/settings#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[177] = wfWAFRule::create($this, 177, NULL, 'file-download', '100', 'WAF-RULE-177', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '9acb44549b41563697bb490144ec6258'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '9acb44549b41563697bb490144ec6258'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'e98d2f001da5678b39482efbdf5770dc'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'e98d2f001da5678b39482efbdf5770dc'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[179] = wfWAFRule::create($this, 179, NULL, 'bypass', '100', 'WAF-RULE-179', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '24be2a598b291f85f33806d203026c37', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[181] = wfWAFRule::create($this, 181, NULL, 'sqli', '40', 'Email Subscribers & Newsletters <= 4.2.1 - Blind SQL Injection in INSERT statement', 0, 'fail', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'open', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'es'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'es'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'viewstatus', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'es'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'es'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'matchCount', new wfWAFRuleVariable($this, 'sqliRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'hash'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'hash'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
))))));
$this->rules[182] = wfWAFRule::create($this, 182, NULL, 'auth-bypass', '100', 'WAF-RULE-182', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '4fd3f28c184ae3daa6f186652fb99e5b', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[184] = wfWAFRule::create($this, 184, NULL, 'redirect', '100', 'Qode Twitter Feed <= 2.0.1 - Unauthenticated Open Redirect', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/qode-twitter-feed\\/lib\\/twitter-redirect\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
))))));
$this->rules[185] = wfWAFRule::create($this, 185, NULL, 'auth-bypass', '100', 'Give WP <= 2.5.9 - Unauthenticated Options Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'stripe_publishable_key'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'stripe_publishable_key'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[186] = wfWAFRule::create($this, 186, NULL, 'priv-esc', '100', 'Mesmerize <= 1.6.89 and Materialis <=1.0.172 - Authenticated Options Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'companion_disable_popup_wpnonce'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[187] = wfWAFRule::create($this, 187, NULL, 'xss', '100', 'Sassy Social Share <= 3.3.3 - Cross-Site Scripting (XSS)', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'heateor_sss_sharing_count', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'contains', '%3C', array(wfWAFRuleComparisonSubject::create($this, 'request.uri', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'contains', '<', array(wfWAFRuleComparisonSubject::create($this, 'request.uri', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'contains', '%3c', array(wfWAFRuleComparisonSubject::create($this, 'request.uri', array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'contains', 'urls', array(wfWAFRuleComparisonSubject::create($this, 'request.uri', array (
))))));
$this->rules[189] = wfWAFRule::create($this, 189, NULL, 'bypass', '100', 'WAF-RULE-189', 1, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'c0d5e9be7a9135b027a06ce179aeede4', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '437057731cac3362ec1a53a1500bb359', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'f394ca45e157fd9d6c7a33290849f9c9', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'd239fe945281de1a7c8f3c86f2d0c8aa', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[190] = wfWAFRule::create($this, 190, NULL, 'auth-bypass', '100', 'WAF-RULE-190', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '581c6097632dc2d623c338be20ddbe3b', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'a10311459433adf322f2590a4987c423', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'e3df100a642110ce84a2ea9df50348de'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'e3df100a642110ce84a2ea9df50348de'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '075ae3d2fc31640504f814f60e5ef713', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'e3df100a642110ce84a2ea9df50348de'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'e3df100a642110ce84a2ea9df50348de'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[191] = wfWAFRule::create($this, 191, NULL, 'bypass', '100', 'WAF-RULE-191', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '92c7747b426e808c0511abb196b21839', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '88f5ffe34fd510b6a2860adf55338150', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[192] = wfWAFRule::create($this, 192, NULL, 'priv-esc', '100', 'WAF-RULE-192', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '03f5edc926cf643e92ff071ac918fd40'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '03f5edc926cf643e92ff071ac918fd40'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '31b122f6bb962bef60add910b99ca403'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '31b122f6bb962bef60add910b99ca403'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '03f5edc926cf643e92ff071ac918fd40'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '03f5edc926cf643e92ff071ac918fd40'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '31b122f6bb962bef60add910b99ca403'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '31b122f6bb962bef60add910b99ca403'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[194] = wfWAFRule::create($this, 194, NULL, 'xss', '100', 'WAF-RULE-194', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '62ddcd9e16a731bd6e8ee06f745e069f', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'f9379e4b99a86ffdb917dad4669d5556', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '0ef47b1ffa7f9e4c22da26dbbbfb36ac', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[196] = wfWAFRule::create($this, 196, NULL, 'auth-bypass', '100', 'Data Tables Generator by Supsystic <= 1.9.86 Unprotected AJAX to Stored XSS and Setting Modification', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'supsystic-tables', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[198] = wfWAFRule::create($this, 198, NULL, 'rce', '100', 'Code Snippets <= 2.13.3 CSRF to RCE & XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'code-snippets', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'import'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'import'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'import-snippets', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'page'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'page'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.files', 'code_snippets_import_files'), array (
))))));
$this->rules[199] = wfWAFRule::create($this, 199, NULL, 'xss', '100', 'Elementor Page Builder < 2.8.5 - Authenticated Reflected XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'elementor-system-info', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'page'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/(?:%22|")/', array(wfWAFRuleComparisonSubject::create($this, 'request.uri', array (
))))));
$this->rules[200] = wfWAFRule::create($this, 200, NULL, 'auth-bypass', '100', 'WAF-RULE-200', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '6d68066077b27eacc644ff626fdd1a50'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '6d68066077b27eacc644ff626fdd1a50'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '457da60d8318d940be14c14017c39555'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'ff6d4bd7110ba645676d3416f6df4670'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'ab3b704c5fd8d3f6982a67d38a3f5b22'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '14c4b06b824ec593239362517f538b29'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '4ccaa172acbdcbf38fa0ea0ef0229c49'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '/^[0-9a-f]+_\\d+$/', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '4ccaa172acbdcbf38fa0ea0ef0229c49'), array (
))))));
$this->rules[201] = wfWAFRule::create($this, 201, NULL, 'auth-bypass', '100', 'WAF-RULE-201', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '14b076be3e70e504ff97a2c06f8ea171', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[203] = wfWAFRule::create($this, 203, NULL, 'auth-bypass', '100', 'Brizy < 1.0.114 Unauthenticated Options Update', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#\\/brizy\\/admin\\/site\\-settings\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[204] = wfWAFRule::create($this, 204, NULL, 'auth-bypass', '100', 'WAF-RULE-204', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'a6339676151ddbacf872e49bdf3e8ad2'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '/^(subscriber|customer|contributor)$/i', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'a6339676151ddbacf872e49bdf3e8ad2'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[205] = wfWAFRule::create($this, 205, NULL, 'auth-bypass', '100', 'wpCentral < 1.4.8 - Privilege Escalation', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'my_wpc_fetch_authkey', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[206] = wfWAFRule::create($this, 206, NULL, 'privesc', '100', 'wpCentral <= 1.5.0 - Privilege Escalation', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#^my_wpc_#', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'auth_key'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'auth_key'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notEquals', '192.200.108.100', array(wfWAFRuleComparisonSubject::create($this, 'request.ip', array (
))))));
$this->rules[207] = wfWAFRule::create($this, 207, NULL, 'auth-bypass', '100', 'ThemeGrill Demo Importer < 1.6.2 - Auth Bypass & Database Wipe', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'do_reset_wordpress'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'do_reset_wordpress'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[209] = wfWAFRule::create($this, 209, NULL, 'rce', '100', 'TRX Addons >= 1.6.50 - Remote Code Execution', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/wp-json[\\/]+trx_addons[\\/]+V2[\\/]+get[\\/]+sc_layout/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/trx_addons[\\/]+V2[\\/]+get[\\/]+sc_layout/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'rest_route'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'rest_route'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[210] = wfWAFRule::create($this, 210, NULL, 'privesc', '100', 'WAF-RULE-210', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '7a59fd4f5cce79652ef292f2b51f0dfd', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '51f1d7ff83b618ce886613d7095fade9', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '8370c3b13e0c94cdbaffa53a06d5c2d2', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'bd1afdae2293b5fd939f1bb9ca5251c4', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '6a8cd74fec6faf83ff69e46f7e939e48', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '525b599e9a0ab7e9f79696cf1cef1ef2', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '2570a32b634e95219aa02d322443bcad'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '2570a32b634e95219aa02d322443bcad'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '9800a67eea78038c92469487f15b93d3', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '2570a32b634e95219aa02d322443bcad'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '2570a32b634e95219aa02d322443bcad'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[211] = wfWAFRule::create($this, 211, NULL, 'auth-bypass', '100', 'WooCommerce Smart Coupons <= 4.6.0 - Unauthenticated Coupon Creation', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'smart_coupon_amount'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '15', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'page'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '20', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[212] = wfWAFRule::create($this, 212, NULL, 'auth-bypass', '100', 'Ultimate Membership Pro < 8.6.1 Unprotected AJAX Actions', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'ihc_ajax_admin_popup', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_get_font_awesome_popup', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_delete_user_via_ajax', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_ajax_admin_popup_the_forms', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_ajax_template_popup_preview', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_login_form_preview', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_locker_preview_ajax', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_register_preview_ajax', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_approve_new_user', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_approve_user_email', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_reorder_levels', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_preview_select_level', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_update_aweber', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_get_cc_list', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_return_csv_link', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_delete_coupon_ajax', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_notification_templates_ajax', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_delete_currency_code_ajax', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_preview_user_listing', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_delete_user_level_relationship', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_make_user_affiliate', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_check_mail_server', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_do_generate_individual_pages', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_preview_invoice_via_ajax', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_run_custom_process', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_do_delete_woo_ihc_relation', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_make_export_file', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_admin_send_email_popup', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_admin_do_send_email', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_admin_do_send_email', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_generate_direct_link', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_generate_direct_link_by_uid', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_direct_login_delete_item', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_save_reason_for_cancel_delete_level', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_close_admin_notice', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_update_list_notification_constants', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_admin_delete_level', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_admin_delete_order', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_admin_delete_locker', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_admin_delete_register_field', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'ihc_admin_delete_payment_transaction', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[213] = wfWAFRule::create($this, 213, NULL, 'bypass', '100', 'WAF-RULE-213', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'cdef14b9d09ae96af85e1345499b81d9', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '3a0f3ac31d68bada2e8a8c9195674125'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'c122109fa0979d460e250797adc964d2', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '3a0f3ac31d68bada2e8a8c9195674125'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[214] = wfWAFRule::create($this, 214, NULL, 'priv-esc', '100', 'WAF-RULE-214', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '5276ec6fa680f7e0a5dc5df500147561', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '8c7dd922ad47494fc02c388e12c00eac'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '8c7dd922ad47494fc02c388e12c00eac'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[215] = wfWAFRule::create($this, 215, NULL, 'auth-bypass', '100', 'WAF-RULE-215', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'f6e0ac7f3ad6c0fb2af125d3fc05678a', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '4e0167456791cf383081c83620efaeb9', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'f86521bbe6ad44c84d9be1546effd40f', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[216] = wfWAFRule::create($this, 216, NULL, 'auth-bypass', '100', 'Async JavaScript <= 2.19.07.14 - Authenticated Plugin Settings Change', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'aj_steps', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[217] = wfWAFRule::create($this, 217, NULL, 'auth-bypass', '100', 'WAF-RULE-217', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#\\/wp\\-admin\\/#', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '71860c77c6745379b0d44304d66b6a13'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '71860c77c6745379b0d44304d66b6a13'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '2764ca9d34e90313978d044f27ae433b'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '2764ca9d34e90313978d044f27ae433b'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '0580ed080b59ed437493cf1e52dda68c'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'e45ba3636e0df08325541792789202bb'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'e5480a783241b4cecbcd936ac5e43992'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '9af4e42e451dba5bb07bdbfbdb595e2d'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '48b59b9339e61474fa55291db00d9f30'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '3bcfbab968c9464c467c4bab392bb403'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '6ed0bc32fcc52ef2bd055514942b7204'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '4c857bc8b830464afa704f10d70a24f9'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '6a232bac5fde7686b61fe29be2cbcae7'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'fdef75d0c3285f4aa5598ae5b2b6c220'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'a9f642be9c8f5ccd5ab42f157ae18c5f'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '2d6e7737f9bc93ea908b339f44365983'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '0580ed080b59ed437493cf1e52dda68c'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'e45ba3636e0df08325541792789202bb'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'e5480a783241b4cecbcd936ac5e43992'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '9af4e42e451dba5bb07bdbfbdb595e2d'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '48b59b9339e61474fa55291db00d9f30'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '3bcfbab968c9464c467c4bab392bb403'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '6ed0bc32fcc52ef2bd055514942b7204'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '4c857bc8b830464afa704f10d70a24f9'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '6a232bac5fde7686b61fe29be2cbcae7'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'fdef75d0c3285f4aa5598ae5b2b6c220'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'a9f642be9c8f5ccd5ab42f157ae18c5f'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '2d6e7737f9bc93ea908b339f44365983'), array (
)))))));
$this->rules[218] = wfWAFRule::create($this, 218, NULL, 'xss', '100', 'Easy Forms for Mailchimp <= 6.6.2 Settings update/Stored XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'migrate_prevoious_forms', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'migrate_old_plugin_settings', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[220] = wfWAFRule::create($this, 220, NULL, 'auth-bypass', '100', 'WAF-RULE-220', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'c881e4373a35aa645a60c9c4f6056d17', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'e4320523e9c1db973220080cd0146a70', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#^idx_#', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notEquals', 'idx_get_saves', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notEquals', 'idx_check_login', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[222] = wfWAFRule::create($this, 222, NULL, 'auth-bypass', '100', 'WAF-RULE-222', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'e8a43789cce4eaee66f5af68e832f210', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '46d1f7a3b426837651e70f1e4525e2fc', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'e2747695b1d30daca617a8c5708d9fec', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '01da931955bedd5def99acca8931c0af', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '7851f092d332ecc0aa45a145383873ac', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '26c150f7c6b4e92cb94ff82bd2dace8b', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '3b699ea99a59eacb2157e3784d24cd4e', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '8fddb32f887bf3d76f694df551ca98ae', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'a0cd543807039401fde4e3e326215385', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'eaccb4631bf2ad334f5d2847ee59e5de', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'fa47c486e5b049fe22ad7d62931da666', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'e2a32cf249503cbd2063fb971f767b1d', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'e981587409a4cabc1fcb7e8da5020ba9', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '659adcbd54c2d533ecccf5ec8cc20ba2', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'f05fb5311d8e8bc2c74e9aea99ea06f8', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '17d19b0403f386b201665d49d2cf229a', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[223] = wfWAFRule::create($this, 223, NULL, 'bypass', '100', 'WAF-RULE-223', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', 'a979024825634ef52fd7fca2404206e4', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[224] = wfWAFRule::create($this, 224, NULL, 'xss', '100', 'WAF-RULE-224', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-(?:ajax|post)\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#^c(?:ore)?37_lp_#', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#^wplx_campaign#', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '32003126ef405405da2c71a534a8acab', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '28f81077c8efbd32c9d5871762b937aa', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[225] = wfWAFRule::create($this, 225, NULL, 'xss', '100', 'WAF-RULE-225', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'd067cd4e3ec93478796872d23a8f53df', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'a5a47552eca02003e86edf3c24d40a06', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-post\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'b08d94100a708384dd382c719038cae0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '19d7c53678e3f0dce91ef07ceb2f7b50', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '3ac02641cf41c7283e5aef8a851e0ae8', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))))));
$this->rules[226] = wfWAFRule::create($this, 226, NULL, 'auth-bypass', '100', 'WAF-RULE-226', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '199f203c0e63313383b7ee7b26a61e3e'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '199f203c0e63313383b7ee7b26a61e3e'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '53f932d9758ddedf3eb8bfa6138ada87'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '53f932d9758ddedf3eb8bfa6138ada87'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '178b343ef715f054a745748cae356282'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '178b343ef715f054a745748cae356282'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '2654b721f3e5a5d8afaa001565297405'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '2654b721f3e5a5d8afaa001565297405'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '138323282685d8fb70094a6650e888ea'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '138323282685d8fb70094a6650e888ea'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '3fa7c98035c506117d6f0d99d2ed67d0'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '3fa7c98035c506117d6f0d99d2ed67d0'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '61b92fa8340efd655d4e925b6cc24b59'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '61b92fa8340efd655d4e925b6cc24b59'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '7ea2dc58336ce62017ff55732a05bc18'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '7ea2dc58336ce62017ff55732a05bc18'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '3e7d4583f80795791a2a137abf97773a'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '3e7d4583f80795791a2a137abf97773a'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '967851ebf030bdda557665889885f826'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '967851ebf030bdda557665889885f826'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[227] = wfWAFRule::create($this, 227, NULL, 'lfi', '100', 'WP Fastest Cache < 0.9.0.3 Authenticated arbitrary path deletion', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'wpfc_delete_current_page_cache', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/\\.|%2E/', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'path'), array (
))))));
$this->rules[228] = wfWAFRule::create($this, 228, NULL, 'xss', '100', 'WAF-RULE-228', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', 'ed002717bf0cefedee418b9a8ca8d479', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '22b4e8e8c10143c2e09142d80d1a0feb'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '22b4e8e8c10143c2e09142d80d1a0feb'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[229] = wfWAFRule::create($this, 229, NULL, 'xss', '100', 'WAF-RULE-229', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '19b4e4e37e5de8dedd85d9806259fa6a', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[230] = wfWAFRule::create($this, 230, NULL, 'file_upload', '100', 'WAF-RULE-230', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '631fec6e3ee38d20e28e7d5cf3b4ed00', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '25d30d18757946cd573365404a8c1e94', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '4bc43e9c3a0111abd4121e584ebba642', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '18a7b867ccfa63c551da0121e43052f5', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '8067a1785e8b1ef2e970caf0baf113d9', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'ffabb3289f4b856f083eb3761f2a6f9a', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[231] = wfWAFRule::create($this, 231, NULL, 'rce', '100', 'WordPress File Upload <= 4.12.2 Remote Code Execution via Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'wfu_ajax_action_ask_server', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#2e2e(2f|5c)#i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'filenames'), array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'wfu_ajax_action', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#2e2e(2f|5c)#i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'uploadedfile_1_name'), array (
))))))));
$this->rules[232] = wfWAFRule::create($this, 232, NULL, 'priv-esc', '100', 'WAF-RULE-232', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '250ad173e7a8168398bddc1ecf80d280', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'c9cb993e03a52db8df96c3e817313a25', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '8c3498c2618a55d519928e0637a32445', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '33bb0f49c93db8a21cb99f10894a6800', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'd45b1e731f897c7df289d61bf8c3a234', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[233] = wfWAFRule::create($this, 233, NULL, 'priv-esc', '100', 'WAF-RULE-233', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/wp-json[\\/]+rankmath[\\/]+v1[\\/]+update/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/rankmath[\\/]+v1[\\/]+update/i', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'c98d48a702d2fb75df0353af9c222655'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'c98d48a702d2fb75df0353af9c222655'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[234] = wfWAFRule::create($this, 234, NULL, 'auth-bypass', '100', 'Elementor < 2.9.6 Safe Mode', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'elementor_ajax', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#enable_safe_mode#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'actions'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'actions'), array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'safe', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'elementor-mode'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'elementor-mode'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[235] = wfWAFRule::create($this, 235, NULL, 'rce', '100', 'LifterLMS < 3.37.15 RCE', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'export_admin_table', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'get_admin_table_data', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[236] = wfWAFRule::create($this, 236, NULL, 'file_upload', '100', 'WAF-RULE-236', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '39f16e71cfb391feaac94e248f4f7a84', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '8c014a158180a4b7060e4244d5ae6ce7', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '0e8378b0231dea08d24eb1e66ac5b8b3', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '9fa6d3fc6a144281642523205b0d77d2', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '12d0ca86b95c3aa84421b71439451ffd', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[237] = wfWAFRule::create($this, 237, NULL, 'auth-bypass', '100', 'WAF-RULE-237', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#(?:nopriv_)?onetone_#', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[238] = wfWAFRule::create($this, 238, NULL, 'auth-bypass', '100', 'Klarna Checkout for WooCommerce < 2.0.10 - Authenticated Arbitrary Plugin Deactivation, Activation and Installation', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'change_klarna_addon_status', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[239] = wfWAFRule::create($this, 239, NULL, 'auth-bypass', '100', 'WAF-RULE-239', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', 'fcccd9665d4b5bcea3ec9eede835bd34', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '25bddb0194983607301b302af6cbf361'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '25bddb0194983607301b302af6cbf361'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[240] = wfWAFRule::create($this, 240, NULL, 'bypass', '100', 'WAF-RULE-240', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'f2a9d8b7f0752d3af52450aa45f7a392', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '8677d261724b430a85c66edb939b74c7'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '8677d261724b430a85c66edb939b74c7'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[241] = wfWAFRule::create($this, 241, NULL, 'rce', '100', 'Media Library Assistant < 2.82 Authenticated RCE', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#\\[mla_gallery#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'shortcode'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'shortcode'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[243] = wfWAFRule::create($this, 243, NULL, 'xss', '100', 'WAF-RULE-243', 1, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '3b5641370ffc4bcc4b71532bd7081fc3', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '478201af544817ea25ab9ea4b6431b38', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'd667c99f8451f93bd64032e6dcf8f872', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '0d602af3aea8e1f09d118129ab018153', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '8e7b9eb111bfa4e70a82f3a9e985b3db', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'f3ca31296a9ae5359152b46db19a907c', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '00b102e9c588cbcdfd97195ab3178f0b', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '236525ade212fdc7bdf2aee727835f5b', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '7ef4b6c133d6b1b29076698d94e509a1', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '59dddcecaf2d3a9419909f56dca2c953', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '10976ad767dd08d20ec72399ea9ab3a9', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'f3fa5cc704ff52652b369cbd079e3c4f', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'author', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'contributor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[244] = wfWAFRule::create($this, 244, NULL, 'file_upload', '100', 'Elementor Pro => 2.9.3 Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'elementor_ajax', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.files', 'zip_upload'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[247] = wfWAFRule::create($this, 247, NULL, 'xss', '100', 'WP Product Review < 3.7.6 Unauthenticated Stored XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/wp-json[\\/]+wp-product-review[\\/]+update-review/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/wp-product-review[\\/]+update-review/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'rest_route'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'rest_route'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[248] = wfWAFRule::create($this, 248, NULL, 'bypass', '100', 'Photo Gallery by 10Web < 1.5.55 Unauthenticated SQL Injection', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#bwg_frontend_data#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#[\\r\\n\\t<>]#', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#[\\r\\n\\t<>]|\\%[\\da-f]{2}#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'bwg_search_0'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'bwg_search_0'), array (
))))));
$this->rules[249] = wfWAFRule::create($this, 249, NULL, 'priv-esc', '100', 'bbPress < 2.6.5 - Privilege Escalation', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-login\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'bbp-forums-role'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'bbp-forums-role'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'register', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[250] = wfWAFRule::create($this, 250, NULL, 'priv-esc', '100', 'bbPress 2.6-2.6.5 Authenticated Privilege Escalation', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'bbpress', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'option_page'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'option_page'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', '_bbp_allow_super_mods'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', '_bbp_allow_super_mods'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[251] = wfWAFRule::create($this, 251, NULL, 'spam', '100', 'WAF-RULE-251', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '6f60a340ce20ce36206616205b3a0d51', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'contains', '%3C', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '2e042b5db1ab08351fdc15ce17a3361a'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '2e042b5db1ab08351fdc15ce17a3361a'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'contains', '<', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '2e042b5db1ab08351fdc15ce17a3361a'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '2e042b5db1ab08351fdc15ce17a3361a'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'contains', '%3c', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '2e042b5db1ab08351fdc15ce17a3361a'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '2e042b5db1ab08351fdc15ce17a3361a'), array (
)))))));
$this->rules[252] = wfWAFRule::create($this, 252, NULL, 'file_upload', '100', 'WAF-RULE-252', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '75328881430f08813e8680e146523a90', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '30e1548b67c0b8645fc6f2ddab7e7b99', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/\\.(p(h(p|tml)[0-9]?|l|y)|(j|a)sp|aspx|sh|shtml|html?|cgi|htaccess|user\\.ini)($|\\.)/i', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
))))));
$this->rules[253] = wfWAFRule::create($this, 253, NULL, 'file_download', '100', 'WAF-RULE-253', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-post\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'a0d247a9e6058678af9cb1b75155097a', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '162f690b37f07850951c7a3cef949b6d', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '6c16d9a9fe7d620c0fbe3611ffb57f22', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[254] = wfWAFRule::create($this, 254, NULL, 'bypass', '100', 'WAF-RULE-254', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '6d747ff214d197629f5db75a3b13b34b', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[255] = wfWAFRule::create($this, 255, NULL, 'auth-bypass', '100', 'Brizy Page Builder < 1.0.126  Improper Access Controls on AJAX Calls', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/brizy(\\-|_)(?!(timestamp|submit_form|heartbeat))/', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'contributor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'author', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[257] = wfWAFRule::create($this, 257, NULL, 'xss', '100', 'WAF-RULE-257', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'f5f5677bb464b7a9d27c9bc4601b02f7'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '6b2f27ea23cbc62ad6e0d211a57e64e2'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[258] = wfWAFRule::create($this, 258, NULL, 'auth-bypass', '100', 'KingComposer < 2.9.4 Multiple vulnerabilities', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#kc_(?:update_option|installed_extensions)#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#(?:nopriv_)?kc_(?:push_section|install_online_preset)#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'author', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'contributor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))))));
$this->rules[260] = wfWAFRule::create($this, 260, NULL, 'file_upload', '100', 'WAF-RULE-260', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '7ea49baf7ff31da636a3f5477a940eee', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'e1e50512d4b53d7c29d8c1f42973de40', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[261] = wfWAFRule::create($this, 261, NULL, 'backdoor', '100', 'WAF-RULE-261', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', 'b8514b20da5ed83c563cb93c63b9b630', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'c41359df90b8d4c0a69449661ee4fa41'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'c41359df90b8d4c0a69449661ee4fa41'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'fb216d9e8791e63c8d12bdc420956839'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'fb216d9e8791e63c8d12bdc420956839'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[264] = wfWAFRule::create($this, 264, NULL, 'xss', '100', 'Newsletter <= 6.7.9 Reflected XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'tnpc_render', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'encoded_options'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#(\\\\u003c|\\\\u003e)#i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'encoded_options'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
)))))));
$this->rules[265] = wfWAFRule::create($this, 265, NULL, 'xss', '100', 'WAF-RULE-265', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#(?:nopriv_)?install_online_preset#', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))));
$this->rules[266] = wfWAFRule::create($this, 266, NULL, 'xss', '100', 'WPBakery Page Builder <= 6.2.0 Contributor+ Stored XSS FE Save Post', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'vc_save', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'content'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'content'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/vc_raw_html|vc_raw_js|custom_onclick_code/', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'content'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'content'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[267] = wfWAFRule::create($this, 267, NULL, 'obji', '100', 'WAF-RULE-267', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', 'ec16c8b7348b86dd53b25acb753aee01', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/(^|;|{|})O:+?\\+*[0-9]+:/i', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '93da65a9fd0004d9477aeac024e08e15', 'e8624ec2ca452c9113851208d88c50c6'), array (
))))));
$this->rules[269] = wfWAFRule::create($this, 269, NULL, 'xss', '100', 'WAF-RULE-269', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/wp-json[\\/]+wp[\\/]+V2[\\/]+posts/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/wp[\\/]+V2[\\/]+posts/i', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'c98d48a702d2fb75df0353af9c222655'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'c98d48a702d2fb75df0353af9c222655'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/vc_raw_html|vc_raw_js|custom_onclick_code/', array(wfWAFRuleComparisonSubject::create($this, 'request.rawBody', array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '9a0364b9e99bb480dd25e1f0284c8555'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[270] = wfWAFRule::create($this, 270, NULL, 'xss', '100', 'WAF-RULE-270', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '1a45e1a86c5a26edea803aec8a5413e4', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/vc_raw_html|vc_raw_js|custom_onclick_code/', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '66f6181bcb4cff4cd38fbc804a036db6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '66f6181bcb4cff4cd38fbc804a036db6'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[271] = wfWAFRule::create($this, 271, NULL, 'file_upload', '100', 'Quiz and Survey Master <= 7.0.0 Arbitrary File Deletion and Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/^\\/(?:\\.\\/)*(?:var|home|usr|mnt|media|etc|tmp|dev|proc)\\/|(^|\\/|\\\\)\\.\\.(\\\\|\\/)/i', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'qsm_remove_file_fd_question', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\.(p(h(p|tml)[0-9]?|l|y)|(j|a)sp|aspx|sh|shtml|html?|cgi|htaccess|user\\.ini)($|\\.)/i', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'qsm_upload_image_fd_question', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))))));
$this->rules[273] = wfWAFRule::create($this, 273, NULL, 'bypass', '100', 'WAF-RULE-273', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '0954ba238528b4dfd6d3429503d78665', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '6e52833ee0bc5c75d39bb250476ca9ca', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '7137865cd4a6ea47dd8aba4ad72c383c', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '71ed7394b4352d1d35c3e1223351255c', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '378aac690ffb45a76da4bacd96fed995', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '6c8f4fd4ef35605e93f805c10f3b89e0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '1155d2b5fe99c1e031982f64bdfeaa4c', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '3ef5aa60dba1ecec10733bd77e49c470', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '8d5c37269a2973c88bb403a1170c6ff2', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'f66a000a92856bfcf0ad455aa7742d03'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'f66a000a92856bfcf0ad455aa7742d03'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[274] = wfWAFRule::create($this, 274, NULL, 'xss', '100', 'Discount Rules for WooCommerce < 2.1.0 - Multiple Vulnerabilities', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#^(?:nopriv_)?wdr_ajax#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notEquals', 'get_price_html', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'method'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notEquals', 'get_variable_product_bulk_table', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'method'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[275] = wfWAFRule::create($this, 275, NULL, 'xss', '100', 'WAF-RULE-275', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '13542b3f1363243bd8095e4f908688de'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '63d2f77e69fe5c0db4c5482f2fad9ccf', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '62d20fa9cfe074dad920afd110efccc0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'd3cda9d3d12261c02ffa6b2f57390bfb', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'e138c37b679fa33e448ecefb27f173fd', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '715dfc7783556b778f0b38de2c8b4454', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '4124c5d015192d02e28f5020b0222d14', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'f03b82fc11720207260f93b90de6314d', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '588d21810a086ab1ae436ef98380d099', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '9dc3768087aedbe8d59e3dfed7f1a7eb', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '71ba5e4ceb3391c8a62b7c8975800241', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'ee5f012b2527601cdd395f27d9fcc73f', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[276] = wfWAFRule::create($this, 276, NULL, 'bypass', '100', 'Kali Forms <= 2.1.1 Unprotected AJAX', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'kaliforms_update_option_ajax', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'kaliforms_clear_log', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'kaliforms_form_delete_uploaded_file', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_kaliforms_form_delete_uploaded_file', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[278] = wfWAFRule::create($this, 278, NULL, 'rce', '100', 'File Manager < 6.9 Remote Code Execution', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/php/connector.minimal.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
))))));
$this->rules[279] = wfWAFRule::create($this, 279, NULL, 'auth-bypass', '100', 'NextScripts: Social Networks Auto-Poster < 4.3.18 Authorization Bypass', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'nxs_snap_aj', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[280] = wfWAFRule::create($this, 280, NULL, 'file_upload', '100', 'WAF-RULE-280', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', 'da1d108ca6aac6deb848c7ca0818f31c', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '9b0685819a0777155dcb7c198057f927', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '9d452b7a6a9c1c57203bfdccb77fc427'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '9d452b7a6a9c1c57203bfdccb77fc427'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'e1fc81bf796f48d8c10fc07a84e44cf5', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '9d452b7a6a9c1c57203bfdccb77fc427'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '9d452b7a6a9c1c57203bfdccb77fc427'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '18d075abab560662e6aa3d0859c14d3d', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '9d452b7a6a9c1c57203bfdccb77fc427'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '9d452b7a6a9c1c57203bfdccb77fc427'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'ff8d67ac27d672f7a96d9152001c2297', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '9d452b7a6a9c1c57203bfdccb77fc427'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '9d452b7a6a9c1c57203bfdccb77fc427'), array (
)))))));
$this->rules[281] = wfWAFRule::create($this, 281, NULL, 'email forgery', '100', 'Email Subscribers & Newsletters <= 4.5.5  Unauthenticated email forgery/spoofing', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'submitted', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'ig_es_broadcast_submitted'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'ig_es_broadcast_submitted'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[282] = wfWAFRule::create($this, 282, NULL, 'xss', '100', 'WAF-RULE-282', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#import_xml_layouts#', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#import_xml_layouts#', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '48f074fa17f82e80816475181040e35f'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '48f074fa17f82e80816475181040e35f'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[283] = wfWAFRule::create($this, 283, NULL, 'auth-bypass', '100', 'Forminator < 1.13.5 - Unauthenticated Sensitive Data Export', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'forminator_export_entries', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[284] = wfWAFRule::create($this, 284, NULL, 'priv-esc', '100', 'WAF-RULE-284', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '5f773e5ea05f23d7303d5a1d5c6f8661', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[285] = wfWAFRule::create($this, 285, NULL, 'rce', '100', 'Function Injection in Multiple Themes using Epsilon Framework <= 1.2.1', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#(?:nopriv_)?epsilon_framework_ajax_action#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#(?:nopriv_)?welcome_screen_ajax_callback#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#(?:nopriv_)?epsilon_dashboard_ajax_callback#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[287] = wfWAFRule::create($this, 287, NULL, 'priv-esc', '100', 'WAF-RULE-287', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'e826224e09c2a5df4c90549352c9ff94', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '45019560182f3877b2aa6fc8ac2d8d0c', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'eb40dcddb7a8d4672042a9e14fd40a44', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '2e5ebf55687485a8d64e16e3ff53d53a', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'fb6d8320636d01e26a16a2199efe6e0b', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'b54e88b69d74885223543ae92144e21b', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '15c46de6aa6f9237a00e2980deef7724', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '354daeefa848545b02aef49378f20b56', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '21dd23b8080a763208dca02c1fabb978', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'af560e5faf4d2ecc43dc0459c7cfbfee', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '2512b52bc3ef97d904cf3833ec5df1b9', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '651b96f03b84a5b46b82eafd03b4d332', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'aeb03a36d77410548b56d1dd45844839', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'b6b1f977803e22eb47637696a4da8d5d', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '01ea59cd4766e499ac7b23dab206fbb8', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'de48ff1a67e4ae52bfa3bacb27fce56f', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '9b17bdd50804cf963baa278d68654eee', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '23227c864d0a99c094f9ee26dc8e54f7', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '6bd84afa2e734be8ecb205270902d945', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '0acb073717e3ed889de43b8c73e9ae4b', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '7bdb82147acbdc9aeff988474eee568d', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'bfe29a1c264a51685de1bd252b34b446', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '2512b52bc3ef97d904cf3833ec5df1b9', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '651b96f03b84a5b46b82eafd03b4d332', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '3a204e13330f526b4c467421a974798f', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '51a833fb5092bac21d188abe30d53cdb', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'd79a9b56ed9a1e48e51bbf14936868ec', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'c9796f82093299b8b7797f961aeb1778', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'fe93a3d0d0eb235d026a84a1b3bbf604', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '4ecfe0cde1b9809e3a9ecec72e896d89', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'ec10c0a0c140cb4af29f1f597f1e762b', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'a20bcc50a5a09289357c8feb8470d81d', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'ea731b99bb87e15439c4638893d217a7', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '57b03a34566ed02d2dc633bab751c362', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '3eb278b530b1346a677bb43e30c20422', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '89f12131bcbecd4b1e6274657397dd55', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '26af102324011c8ea03a1fa92b2d187f', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'aa9a1d6593b1781ea7661c9f4c199176', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '1be383b63ea02fb6569c57e4e0599273', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'a3543a2303084133b3093f070238fbc7', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[288] = wfWAFRule::create($this, 288, NULL, 'obji', '100', 'WAF-RULE-288', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/(^|;|{|})O:+?\\+*[0-9]+:/i', array(wfWAFRuleComparisonSubject::create($this, array('request.cookies', 'usces_cookie'), array (
))))));
$this->rules[290] = wfWAFRule::create($this, 290, NULL, 'xss', '100', 'Simple Download Monitor < 3.8.9 - Unauthenticated Cross-Site Scripting', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', new wfWAFRuleVariable($this, 'xssRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.headers', 'User-Agent'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', '1', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'smd_process_download'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'smd_process_download'), array (
))))));
$this->rules[291] = wfWAFRule::create($this, 291, NULL, 'priv-esc', '100', 'WAF-RULE-291', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'd6cba1ad2c0b131207c9dc2584126130'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'd6cba1ad2c0b131207c9dc2584126130'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '29a7e96467b69a9f5a93332e29e9b0de'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '#^um_#i', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '29a7e96467b69a9f5a93332e29e9b0de'), array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'ceeec9bdf80786f517d11254b72ed896'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'ceeec9bdf80786f517d11254b72ed896'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'cb8fceba5cdbccb268a9c5a570be3ad1'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[294] = wfWAFRule::create($this, 294, NULL, 'obji', '100', 'WordPress < 5.5.2 - Object Injection POP Chain', 1, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'matchCount', '/(^|;|{|})C:+?\\+*[0-9]+:"\\\\?Requests_Utility_FilteredIterator"/i', array(wfWAFRuleComparisonSubject::create($this, 'request.headers', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.cookies', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
))))));
$this->rules[295] = wfWAFRule::create($this, 295, NULL, 'xss', '100', 'WordPress < 5.5.2 - Reflected XSS in global variables', 1, 'blockXSS', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#(?:%2f|/)(?:%77|%57|w)(?:%70|%50|p)(?:%2d|-)(?:%61|%41|a)(?:%64|%44|d)(?:%6d|%4d|m)(?:%69|%49|i)(?:%6e|%4e|n)(?:%2f|/).*(?:%2f|/)(?:%77|%57|w)(?:%70|%50|p)(?:%2d|-)(?:%61|%41|a)(?:%64|%44|d)(?:%6d|%4d|m)(?:%69|%49|i)(?:%6e|%4e|n)(?:%2f|/).*%27#i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
))))));
$this->rules[297] = wfWAFRule::create($this, 297, NULL, 'priv-esc', '100', 'WAF-RULE-297', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'contains', 'content_form_registration', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'ebb67a4271abe715344471b0f16321f6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'ebb67a4271abe715344471b0f16321f6'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'contains', 'user_role', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'ebb67a4271abe715344471b0f16321f6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'ebb67a4271abe715344471b0f16321f6'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '1e17970e966da0ba165c221c1150c9a2', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[299] = wfWAFRule::create($this, 299, NULL, 'priv-esc', '100', 'WAF-RULE-299', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '0960acb7f838d0b8b13e3e8c305fdff4', '2e5d8aa3dfa8ef34ca5131d20f9dad51', '93f6bfde0afd2111572d0596662a75a3'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[303] = wfWAFRule::create($this, 303, NULL, 'file_upload', '100', 'ListingPro <= 2.5.13 Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'lp_cc_addons_actions', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_lp_cc_addons_actions', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[304] = wfWAFRule::create($this, 304, NULL, 'rce', '100', 'WAF-RULE-304', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'ce26c48f439fe595a172cefa06d81455'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#<\\?#', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'ce26c48f439fe595a172cefa06d81455'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthLessThan', '1', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'cb584e44c43ed6bd0bc2d9c7e242837d'), array (
))))));
$this->rules[305] = wfWAFRule::create($this, 305, NULL, 'lfi', '100', 'WAF-RULE-305', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/^\\/(?:\\.\\/)*(?:var|home|usr|mnt|media|etc|tmp|dev|proc)\\//i', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '0cf8e11fc535c88c86d86d122fd0c50c', '66f6181bcb4cff4cd38fbc804a036db6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '92bafe2ae4e62fe175e66582ec07c11b', '66f6181bcb4cff4cd38fbc804a036db6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'd454458206a3c8961bfbb10ca4c3d513', '66f6181bcb4cff4cd38fbc804a036db6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '04d24f487e985efc1c9436958d0f1b5e', '66f6181bcb4cff4cd38fbc804a036db6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'e8b2da685ab6f111dc6f21732e9a15b4', '66f6181bcb4cff4cd38fbc804a036db6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '0cf8e11fc535c88c86d86d122fd0c50c', '4975ea593c17e065904aeb4351014ea7'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '92bafe2ae4e62fe175e66582ec07c11b', '4975ea593c17e065904aeb4351014ea7'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'd454458206a3c8961bfbb10ca4c3d513', '4975ea593c17e065904aeb4351014ea7'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '04d24f487e985efc1c9436958d0f1b5e', '4975ea593c17e065904aeb4351014ea7'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'e8b2da685ab6f111dc6f21732e9a15b4', '4975ea593c17e065904aeb4351014ea7'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'd63a25305c8d31f61afefa985193fd66', '4975ea593c17e065904aeb4351014ea7'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/(^|\\/|\\\\)\\.\\.(\\\\|\\/)/', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '0cf8e11fc535c88c86d86d122fd0c50c', '66f6181bcb4cff4cd38fbc804a036db6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '92bafe2ae4e62fe175e66582ec07c11b', '66f6181bcb4cff4cd38fbc804a036db6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'd454458206a3c8961bfbb10ca4c3d513', '66f6181bcb4cff4cd38fbc804a036db6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '04d24f487e985efc1c9436958d0f1b5e', '66f6181bcb4cff4cd38fbc804a036db6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'e8b2da685ab6f111dc6f21732e9a15b4', '66f6181bcb4cff4cd38fbc804a036db6'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthLessThan', '1', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'cb584e44c43ed6bd0bc2d9c7e242837d'), array (
))))));
$this->rules[306] = wfWAFRule::create($this, 306, NULL, 'file_upload', '100', 'WAF-RULE-306', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '533a059f159d8fda0941a99e05a05a90'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '533a059f159d8fda0941a99e05a05a90'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '533a059f159d8fda0941a99e05a05a90'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '533a059f159d8fda0941a99e05a05a90'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthLessThan', '1', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'cb584e44c43ed6bd0bc2d9c7e242837d'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'filePatternsMatch', '', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'fileHasPHP', '', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
)))))));
$this->rules[307] = wfWAFRule::create($this, 307, NULL, 'brute-force', '100', 'Known malicious User-Agents', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', array(wfWAFRuleComparisonSubject::create($this, array('request.headers', 'User-Agent'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#mozlila#i', array(wfWAFRuleComparisonSubject::create($this, array('request.headers', 'User-Agent'), array (
))))));
$this->rules[308] = wfWAFRule::create($this, 308, NULL, 'priv-esc', '100', 'WAF-RULE-308', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '085d5a3d7d5cffa61fd83c8a69da76a0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'f9ee2a22a7282340af0275c8a584077e', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '8cc23aa3ce798b7e49545303a334c261', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '47b30e222881044451af6dd27b3ea9ef', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[309] = wfWAFRule::create($this, 309, NULL, 'csrf', '100', 'Redux Framework <=4.1.23 CSRF to Options Change', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#ajax_save$#', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '1', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'opt_name'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'opt_name'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthLessThan', '1', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'nonce'), array (
))))));
$this->rules[310] = wfWAFRule::create($this, 310, NULL, 'file_upload', '100', 'WAF-RULE-310', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-post\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '65035fb600bb19da09f590757801223f', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[311] = wfWAFRule::create($this, 311, NULL, 'file_upload', '100', 'WAF-RULE-311', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', 'd8dba20c91603fae480abe06f9e90a1a', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '71860c77c6745379b0d44304d66b6a13'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '71860c77c6745379b0d44304d66b6a13'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'bdace1ea5c5fb64733b477f34cc22746'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'bdace1ea5c5fb64733b477f34cc22746'), array (
)))))));
$this->rules[312] = wfWAFRule::create($this, 312, NULL, 'xss', '100', 'WAF-RULE-312', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', 'd8dba20c91603fae480abe06f9e90a1a', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '71860c77c6745379b0d44304d66b6a13'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '71860c77c6745379b0d44304d66b6a13'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '3dc3e919c70e8e1f7d6f640b691d63db'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '3dc3e919c70e8e1f7d6f640b691d63db'), array (
)))))));
$this->rules[313] = wfWAFRule::create($this, 313, NULL, 'obji', '100', 'WAF-RULE-313', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#wp\\-admin/+admin\\-post.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'wp_async_send_server_events', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_wp_async_send_server_events', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/(^|;|{|})O:+?\\+*[0-9]+:(?!"(?:stdClass)")/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'event_data'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'event_data'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
))))));
$this->rules[314] = wfWAFRule::create($this, 314, NULL, 'file_upload', '100', 'WAF-RULE-314', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'cbadc87b7d6f58613f48906a393af520', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '05631c4dbb0b40012ed735863499aba1', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#(?:\\.php|\\.\\.\\/|\\.jsp|\\.vbs|\\.exe|\\.bat|\\.php5|\\.pht|\\.phtml|\\.shtml|\\.asa|\\.cer|\\.asax|\\.swf|\\.xap|;|\\.asp|\\.aspx|\\*|<|>|::)#i', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '95b99b69f08b85818249373eed659f98'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'e459df65a554aa009f5cfb2ac374ac47'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'a338db8e486d050d9a7bf3c6cbccd25a'), array (
))))));
$this->rules[315] = wfWAFRule::create($this, 315, NULL, 'xss', '100', 'Site Offline < 1.4.4 CSRF -> XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'sahu_site_offline_wp', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'page'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'countdown_date'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'notMatch', '#^\\d{4}\\/\\d{2}\\/\\d{2}$#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'countdown_date'), array (
))))));
$this->rules[317] = wfWAFRule::create($this, 317, NULL, 'xss', '100', 'FV Flowplayer Video Player < 7.4.38.727 - Authenticated Stored Cross-Site Scripting (XSS)', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'fv_player_db_save', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'editor', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[318] = wfWAFRule::create($this, 318, NULL, 'redirect', '100', 'WAF-RULE-318', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '9d32a72b659c138e3c7069d2a39c1557', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'f17ca2c829680ada2fec9fc87bc5f606'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'f17ca2c829680ada2fec9fc87bc5f606'), array (
))))));
$this->rules[319] = wfWAFRule::create($this, 319, NULL, 'auth-bypass', '100', 'WAF-RULE-319', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', 'd8ffa20bb3b381b73bd10336a96eea39', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[320] = wfWAFRule::create($this, 320, NULL, 'auth-bypass', '100', 'WAF-RULE-320', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', 'fbb77137e6dd82fc7fec407ae736e33c', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[321] = wfWAFRule::create($this, 321, NULL, 'xss', '100', 'Rule 321', 0, 'blockXSS', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'versionGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('wordpress.plugins', 'autoptimize'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#j[\\savscript]+<\\!--[\\s\\S]*?-->[\\savscript]*:|"[a-z\\s\\:\\-]+<\\!--[\\s\\S]*?-->[a-z\\s\\:\\-]+=\\s*"|<<\\!--|<[^dp\\s][^>]*<\\!--#ix', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
))))));
$this->rules[322] = wfWAFRule::create($this, 322, NULL, 'csrf', '100', 'WAF-RULE-322', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'versionEquals', '3.0.0', array(wfWAFRuleComparisonSubject::create($this, array('wordpress.plugins', 'official-facebook-pixel'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', 'b1ec9fc99e803d8fd1899dbab02608ac', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))));
$this->rules[323] = wfWAFRule::create($this, 323, NULL, 'auth-bypass', '100', 'Popup Builder < 3.73 Authorization Bypass', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'add_condition_group_row', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'add_condition_rule_row', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'change_condition_rule_row', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'change_popup_status', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'sgpb_subscribers_delete', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'sgpb_add_subscribers', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'sgpb_import_subscribers', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'sgpb_save_imported_subscribers', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'sgpb_send_newsletter', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'sgpb_change_review_popup_show_period', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'sgpb_dont_show_review_popup', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'sgpb_reset_popup_opening_count', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[324] = wfWAFRule::create($this, 324, NULL, 'file_upload', '100', 'WAF-RULE-324', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '40e53a87247e59aafe839ffb1da6cc50', array(wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[325] = wfWAFRule::create($this, 325, NULL, 'auth-bypass', '100', 'Ultimate GDPR & CCPA Compliance Toolkit for WordPress < 2.5 Unauthenticated Settings Import/Export', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'ct-ultimate-gdpr-export'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'ct-ultimate-gdpr-export-services'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'ct-ultimate-gdpr-import'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'ct-ultimate-gdpr-import-services'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[329] = wfWAFRule::create($this, 329, NULL, 'bypass', '100', 'WAF-RULE-329', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '1eb96e79b5d5552379f826c38f6fd317', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '69e5edb869cc5be030fac06d99ccbd53', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '5e5a7619f9eb2b8fd174332ed71d0a79', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '5c98f6c059fce4ee77ed116683300be0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '819e68efb77156b53d150d243d2eb685', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'd587d441d62d440daa01b629f9c5f870', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'c9cff4f3118cdcf628b781b9ba0caa05', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[327] = wfWAFRule::create($this, 327, NULL, 'file_upload', '100', 'WAF-RULE-327', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '1211afcc3f1106115df1d5adeb4e14b3', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[328] = wfWAFRule::create($this, 328, NULL, 'bypass', '100', 'WAF-RULE-328', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '680ffad6bd39ac5a2f62f6ae188d1338', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '5c2c2bec96f035697861364d7080c03e', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[331] = wfWAFRule::create($this, 331, NULL, 'information-disclosure', '100', 'WAF-RULE-331', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/wp-json[\\/]+mpp[\\/]+v2[\\/]+get_users/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/mpp[\\/]+v2[\\/]+get_users/i', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'c98d48a702d2fb75df0353af9c222655'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'c98d48a702d2fb75df0353af9c222655'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[332] = wfWAFRule::create($this, 332, NULL, 'csrf', '100', 'Better Search < 2.5.3 CSRF to Settings Import and XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'import_settings', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'bsearch_action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthLessThan', '1', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'bsearch_import_settings_nonce'), array (
))))));
$this->rules[333] = wfWAFRule::create($this, 333, NULL, 'file_upload', '100', 'QuadMenu < 2.0.7 - Unauthenticated RCE via compiler_save', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'quadmenu_compiler_save', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_quadmenu_compiler_save', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[334] = wfWAFRule::create($this, 334, NULL, 'file_upload', '100', 'QuadMenu < 2.0.7 - Unauthenticated RCE via compiler_save 2', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'quadmenu_compiler_save', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_quadmenu_compiler_save', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/\\.(p(h(pt?|t(ml?)?|ar)[0-9]?|l|y)|(j|a)sp|aspx|sh|shtml|html?|cgi|htaccess|user\\.ini)($|\\.)/i', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'output', 'imports'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'output', 'imports'), array (
))))));
$this->rules[335] = wfWAFRule::create($this, 335, NULL, 'xss', '100', 'WAF-RULE-335', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '1e17970e966da0ba165c221c1150c9a2', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#(?:["_]tag|header_size|title_size)":"(?!(?:div|header|footer|main|article|section|aside|nav|span|p|a|none|h1|h2|h3|h4|h5|h6|null|large|custom|ul|)")#i', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'ebb67a4271abe715344471b0f16321f6'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[336] = wfWAFRule::create($this, 336, NULL, 'sqli', '100', 'Tutor LMS <= 1.8.3 SQLi Bypass', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'tutor_place_rating', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'tutor_mark_answer_as_correct', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'tutor_quiz_builder_get_question_form', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'tutor_quiz_builder_get_answers_by_question', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#[\\r\\n\\t<>]#', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
))))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'tutor_answering_quiz_question', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'tutor_action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'tutor_action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#[\\r\\n\\t<>]#', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.queryString', array (
)))))));
$this->rules[337] = wfWAFRule::create($this, 337, NULL, 'csrf', '100', 'Better Search < 2.5.3 CSRF to Stored XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'import_settings', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'bsearch_action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'bsearch_action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthLessThan', '1', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'bsearch_import_settings_nonce'), array (
))))));
$this->rules[338] = wfWAFRule::create($this, 338, NULL, 'sqli', '40', 'SQL Injection in User-Agent String', 0, 'failSQLi', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'matchCount', new wfWAFRuleVariable($this, 'sqliRegex', NULL), array(wfWAFRuleComparisonSubject::create($this, array('request.headers', 'User-Agent'), array (
))))));
$this->rules[339] = wfWAFRule::create($this, 339, NULL, 'sqli', '100', 'WAF-RULE-339', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#%[0-9a-f]{2}#i', array(wfWAFRuleComparisonSubject::create($this, array('request.headers', 'fb831f965a1e3f3ee3af2b3c2de8be12'), array (
))))));
$this->rules[340] = wfWAFRule::create($this, 340, NULL, 'priv-esc', '100', 'WAF-RULE-340', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '28830deb1689b577c9b94f9b2b8f6b8b', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[341] = wfWAFRule::create($this, 341, NULL, 'information-disclosure', '100', 'WAF-RULE-341', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/wp-json[\\/]+store-locator-plus[\\/]+v2[\\/]+options[\\/]+all/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/store-locator-plus[\\/]+v2[\\/]+options[\\/]+all/i', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'c98d48a702d2fb75df0353af9c222655'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'c98d48a702d2fb75df0353af9c222655'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/wp-json[\\/]+store-locator-plus[\\/]+v2[\\/]+options[\\/]+import/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/store-locator-plus[\\/]+v2[\\/]+options[\\/]+import/i', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'c98d48a702d2fb75df0353af9c222655'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'c98d48a702d2fb75df0353af9c222655'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[342] = wfWAFRule::create($this, 342, NULL, 'priv-esc', '100', 'The Plus Addons for Elementor PRO <= 4.1.5 Privilege Escalation', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'theplus_ajax_register', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'administrator', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'tp_user_reg_role'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'tp_user_reg_role'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'contributor', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'tp_user_reg_role'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'tp_user_reg_role'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'editor', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'tp_user_reg_role'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'tp_user_reg_role'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'author', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'tp_user_reg_role'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'tp_user_reg_role'), array (
)))))));
$this->rules[343] = wfWAFRule::create($this, 343, NULL, 'auth-bypass', '100', 'The Plus Addons for Elementor PRO <= 4.1.5 Authentication Bypass', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'theplus_ajax_login', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'theplus_google_ajax_register', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'email'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'email'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'email'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'email'), array (
)))))));
$this->rules[344] = wfWAFRule::create($this, 344, NULL, 'obji', '100', 'PHP Object Injection in Cookies', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/(^|;|{|})(?:O|C):\\d+:"(?!stdClass")[^"]+":/', array(wfWAFRuleComparisonSubject::create($this, 'request.cookies', array (
))))));
$this->rules[346] = wfWAFRule::create($this, 346, NULL, 'auth-bypass', '100', 'Flo Forms < 1.0.36 - Authenticated Options Import to Stored XSS', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'flo_import_forms_options', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[347] = wfWAFRule::create($this, 347, NULL, 'priv-esc', '100', 'BuddyPress <  7.2.1 - REST API Privilege Escalation', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/wp-json[\\/]+buddypress[\\/]+v1[\\/]+members[\\/]+(?:me|\\d+)/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/buddypress[\\/]+v1[\\/]+members[\\/]+(?:me|\\d+)/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'rest_route'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'rest_route'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'versionLessThan', '7.2.1', array(wfWAFRuleComparisonSubject::create($this, array('wordpress.plugins', 'buddypress'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.rawBody', array (
))))));
$this->rules[348] = wfWAFRule::create($this, 348, NULL, 'file_upload', '100', 'Thrive Legacy Themes < 2.0.0 Option Injection', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/wp-json[\\/]+td[\\/]+v1[\\/]+optin[\\/]+subscription/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/td[\\/]+v1[\\/]+optin[\\/]+subscription/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'rest_route'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'rest_route'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'identical', '', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'api_key'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'api_key'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#api_key":""#i', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.rawBody', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#(?:hook_url|hookUrl)":"\\{#i', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.rawBody', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#[\\{\\}]#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'hook_url'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'hookUrl'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'hook_url'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'hookUrl'), array (
)))))));
$this->rules[349] = wfWAFRule::create($this, 349, NULL, 'rfd', '100', 'Thrive Legacy Themes < 2.0.0 RFD to RCE', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/wp-json[\\/]+thrive[\\/]+kraken/i', array(wfWAFRuleComparisonSubject::create($this, 'request.path', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/thrive[\\/]+kraken/i', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'rest_route'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'rest_route'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#^(?![0-9a-f]{32}).#', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'id'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'id'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '#"id":"(?![0-9a-f]{32})#i', array(wfWAFRuleComparisonSubject::create($this, 'request.body', array (
)),
wfWAFRuleComparisonSubject::create($this, 'request.rawBody', array (
)))))));
$this->rules[351] = wfWAFRule::create($this, 351, NULL, 'auth-bypass', '100', 'WAF-RULE-351', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '8964dcc97d07f5ed1c56ccd2d9aca31f', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '8e8b20d64aefc4f269744d3a64488431', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '03cf419d467aba1e7dad2008d55fec96', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[352] = wfWAFRule::create($this, 352, NULL, 'file_upload', '100', 'Business Hours Pro <= 5.5.0  Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'iva_bh_ajax_action', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_iva_bh_ajax_action', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'iva_bh_import_ajax_action', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_iva_bh_import_ajax_action', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[353] = wfWAFRule::create($this, 353, NULL, 'lfi', '100', 'WAF-RULE-353', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#"(?:loop_)?svg_image":\\{[^\\}]*"url":"[^"]+\\.(?!svg)\\w+"#', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'ebb67a4271abe715344471b0f16321f6'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '1e17970e966da0ba165c221c1150c9a2', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))));
$this->rules[354] = wfWAFRule::create($this, 354, NULL, 'privesc', '100', 'WAF-RULE-354', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'contains', 'tp_login_register', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'ebb67a4271abe715344471b0f16321f6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'ebb67a4271abe715344471b0f16321f6'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'contains', 'tp_register', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'ebb67a4271abe715344471b0f16321f6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'ebb67a4271abe715344471b0f16321f6'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'contains', 'user_role', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'ebb67a4271abe715344471b0f16321f6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'ebb67a4271abe715344471b0f16321f6'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '1e17970e966da0ba165c221c1150c9a2', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[356] = wfWAFRule::create($this, 356, NULL, 'redirect', '100', 'WAF-RULE-356', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', 'f2962b668e5e7538f759cb020fb5e6aa', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '71860c77c6745379b0d44304d66b6a13'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '71860c77c6745379b0d44304d66b6a13'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'b2507468f95156358fa490fd543ad2f0'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'b2507468f95156358fa490fd543ad2f0'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'lengthGreaterThan', '0', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '93473a7344419b15c4219cc2b6c64c6f'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '93473a7344419b15c4219cc2b6c64c6f'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[357] = wfWAFRule::create($this, 357, NULL, 'file_upload', '100', 'WAF-RULE-357', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', '273872df3460c4c83d753747ae8a4a87', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '6c342951bdc4d5543e47ec5ffcc45371', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '13e8543a15c797284974cccef36cc67b', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '5403ea8819cef48f344d0f2cf2919356', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'e9a5146a78c87333295b14f48d2e719e', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'f4730089247e76687c5871f0272d9f37', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[359] = wfWAFRule::create($this, 359, NULL, 'priv-esc', '100', 'WAF-RULE-359', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'contains', 'eael-login-register', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'ebb67a4271abe715344471b0f16321f6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'ebb67a4271abe715344471b0f16321f6'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '#register_user_role":"[^"]#', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', 'ebb67a4271abe715344471b0f16321f6'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', 'ebb67a4271abe715344471b0f16321f6'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '1e17970e966da0ba165c221c1150c9a2', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[360] = wfWAFRule::create($this, 360, NULL, 'bypass', '100', 'WAF-RULE-360', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'md5Equals', 'fb931ef2578903b639c003dd9a771c00', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', '0b0409c078affd2ff64de3f1e09d760e', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'e645a9193e9bc657ef8c9d7d6ee35e64', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'md5Equals', 'bc6ac849571b0c35e101a1ce97dda512', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '418c5509e2171d55b0aee5c2ea4442b5'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '418c5509e2171d55b0aee5c2ea4442b5'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[361] = wfWAFRule::create($this, 361, NULL, 'file_upload', '100', 'Modern WPBakery Page Builder Addons <= 3.0.1 Arbitrary File Upload', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'contains', 'kaswara', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'uploadFontIcon', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_uploadFontIcon', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'match', '/(\\.zip)($|\\.)/i', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[362] = wfWAFRule::create($this, 362, NULL, 'bypass', '100', 'Modern WPBakery Page Builder Addons <= 3.0.1 Unprotected AJAX Actions', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'contains', 'kaswara', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'exportShortcodeData', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_exportShortcodeData', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'importShortcodeData', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_importShortcodeData', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'exportCf7Styles', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_exportCf7Styles', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'importCf7Styles', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_importCf7Styles', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'deleteFontIcon', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'nopriv_deleteFontIcon', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[363] = wfWAFRule::create($this, 363, NULL, 'bypass', '100', 'WP-Buy Plugins <= Various Versions - Arbitrary Plugin Installation/Activation', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\-ajax\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'equals', 'do_button_job_later', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
))))));
$this->rules[364] = wfWAFRule::create($this, 364, NULL, 'auth-bypass', '100', 'WooCommerce Product Filter by WooBeWoo <= 1.4.9 Unprotected AJAXs', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'wpf', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'pl'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'pl'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'equals', 'save', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'deleteByID', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'drawFilterAjax', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'removeGroup', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'saveGroup', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'equals', 'createTable', array(wfWAFRuleComparisonSubject::create($this, array('request.queryString', 'action'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'action'), array (
))))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'currentUserIsNot', 'administrator', array(wfWAFRuleComparisonSubject::create($this, 'server.empty', array (
))))));
$this->rules[365] = wfWAFRule::create($this, 365, NULL, 'obji', '100', 'Thrive Plugins < 2021-05-11 Object Injection', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/(^|;|{|})(?:O|C):\\d+:"(?!stdClass")[^"]+":/', array(wfWAFRuleComparisonSubject::create($this, array('request.body', '__tcb_lg_msg'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', '__tcb_lg_fc'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'consent_config'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'tve_mapping'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'tve_labels'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
)),
wfWAFRuleComparisonSubject::create($this, array('request.body', 'config'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
))))));
$this->rules[366] = wfWAFRule::create($this, 366, NULL, 'priv-esc', '100', 'Thrive Plugins < 2021-05-11 Privesc', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '/\\$trusted/', array(wfWAFRuleComparisonSubject::create($this, array('request.body', 'tve_mapping'), array (
  0 => 
  array (
    0 => 'base64decode',
  ),
))))));
$this->rules[367] = wfWAFRule::create($this, 367, NULL, 'file_upload', '100', 'WAF-RULE-367', 0, 'block', new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'match', '#/wp\\-admin/admin\\.php$#i', array(wfWAFRuleComparisonSubject::create($this, 'server.script_filename', array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparison($this, 'md5Equals', '97ddaa88462e156f1e4769778df7a6fc', array(wfWAFRuleComparisonSubject::create($this, array('request.md5Body', '71860c77c6745379b0d44304d66b6a13'), array (
)),
wfWAFRuleComparisonSubject::create($this, array('request.md5QueryString', '71860c77c6745379b0d44304d66b6a13'), array (
)))), new wfWAFRuleLogicalOperator('AND'), new wfWAFRuleComparisonGroup(new wfWAFRuleComparison($this, 'filePatternsMatch', '', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'fileHasPHP', '', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
)))), new wfWAFRuleLogicalOperator('OR'), new wfWAFRuleComparison($this, 'match', '/\\.(p(h(pt?|t(ml?)?|ar)[0-9]?|l|y)|(j|a)sp|aspx|sh|shtml|html?|cgi|htaccess|user\\.ini)($|\\.)/i', array(wfWAFRuleComparisonSubject::create($this, 'request.fileNames', array (
)))))));
?>