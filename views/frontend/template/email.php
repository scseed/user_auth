<?php defined('SYSPATH') or die('No direct access allowed.');?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<style type="text/css">
html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,font,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td{margin:0;padding:0;border:0;outline:0;font-size:100%;vertical-align:baseline;background:transparent}body{line-height:1}blockquote,q{quotes:none}blockquote:before,blockquote:after,q:before,q:after{content:'';content:none}:focus{outline:0}ins{text-decoration:none}del{text-decoration:line-through}table{border-collapse:collapse;border-spacing:0}
html { font-size: 65.5%; font-family: Verdana; }
strong, th, thead td, h1, h2, h3, h4, h5, h6 { font-weight: bold; }
cite, em, dfn { font-style: italic; }
code, kbd, samp, pre, tt, var, input[type='text'], input[type='password'], textarea { font-size: 92%; font-family: monaco, "Lucida Console", courier, monospace; }
del { text-decoration: line-through; color: #666; }
ins, dfn { border-bottom: 1px solid #ccc; }
small, sup, sub { font-size: 85%; }
abbr, acronym { text-transform: uppercase; font-size: 85%; letter-spacing: .1em; }
a abbr, a acronym { border: none; }
abbr[title], acronym[title], dfn[title] { cursor: help; border-bottom: 1px solid #ccc; }
sup { vertical-align: super; }
sub { vertical-align: sub; }

blockquote { border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; color: #666; }
blockquote *:first-child:before { content: "\201C"; }
blockquote *:first-child:after { content: "\201D"; }

fieldset { padding:1.4em; margin: 0 0 1.5em 0; border: 1px solid #ccc; }
legend { font-weight: bold; font-size:1.2em; }
label { font-weight: bold; }
textarea, input[type='text'], input[type='password'], select { border: 1px solid #ccc; background: #fff; }
textarea:hover, input[type='text']:hover, input[type='password']:hover, select:hover { border-color: #aaa; }
textarea:focus, input[type='text']:focus, input[type='password']:focus, select:focus { border-color: #888; outline: 2px solid #ffffaa; }
input, select { cursor: pointer; }
input[type='text'],input[type='password'] { cursor: text; }

.container { font-size: 1.2em; line-height: 1.6em; }
h1 { font-size: 1.9em; }
h2 { font-size: 1.7em; }
h3 { font-size: 1.5em; }
h4 { font-size: 1.3em; }
h5 { font-size: 1.2em; }
h6 { font-size: 1em; }

ul li { margin-left: .85em; }
ul { list-style-type: disc; }
ul ul { list-style-type: square; }
ul ul ul { list-style-type: circle; }
ol { list-style-position: outside; list-style-type: decimal; }
dt { font-weight: bold; }

table { border-top: 1px solid #000; border-left: 1px solid #000; }
th, td { border-bottom: 1px solid #000; border-right: 1px solid #000; }

blockquote *:first-child { margin: .8em 0; }
hr, p, ul, ol, dl, pre, blockquote, address, table, form { margin-bottom: 1.6em; }
h1 { margin: 1em 0 .5em; }
h2 { margin: 1.07em 0 .535em; }
h3 { margin: 1.14em 0 .57em; }
h4 { margin: 1.23em 0 .615em; }
h5 { margin: 1.33em 0 .67em; }
h6 { margin: 1.6em 0 .8em; }
th, td { padding: .8em; }

blockquote { padding: 0 1em; margin: 1.6em 0; }

legend { padding-left: .8em; padding-right: .8em; }

textarea, input { padding: .3em .4em .15em .4em; }
select { padding: .1em .2em 0 .2em; }
option { padding: 0 .4em; }
a { position: relative; padding: 0.3em 0 .1em 0; } /*** for larger click-area ***/
dt { margin-top: .8em; margin-bottom: .4em; }
ul { margin-left: 1.5em; }
ol { margin-left: 2.35em; }
ol ol, ul ol { margin-left: 2.5em; }
form div { margin-bottom: .8em; }

a:link { text-decoration: underline; color: #36c; }
a:visited { text-decoration: underline; color: #99c; }
a:hover { text-decoration: underline; color: #c33; }
a:active, a:focus { text-decoration: underline; color: #000; }
code, pre { color: #c33; } /*** very optional, but still useful. W3C uses about the same colors for codes ***/

.container {width:auto;margin:0 10px;}
body {margin:1.5em 0;}
hr {background:#ddd;color:#ddd;clear:both;float:none;width:100%;height:.1em;margin:0 0 1.45em;border:none;}
hr.space {background:#fff;color:#fff;}
.clearfix:after, .container:after {content:"\0020";display:block;height:0;clear:both;visibility:hidden;max-height:0;overflow:hidden;}
.clearfix, .container {display:inline-block;}
* html .clearfix, * html .container {height:1%;}
.clearfix, .container {display:block;}
.clear {clear:both;}

.small {font-size:.8em;margin-bottom:1.875em;line-height:1.875em;}
.large {font-size:1.2em;line-height:2.5em;margin-bottom:1.25em;}
.hide {display:none;}
.quiet {color:#666;}
.loud {color:#000;}
.highlight {background:#ff0;}
.top {margin-top:0;padding-top:0;}
.bottom {margin-bottom:0;padding-bottom:0;}
.thin {font-weight: lighter;}
.error, .notice, .success {padding:.8em;margin-bottom:1.6em;border:2px solid #ddd;}
.error {background:#FBE3E4;color:#8a1f11;border-color:#FBC2C4;}
.notice {background:#FFF6BF;color:#514721;border-color:#FFD324;}
.success {background:#E6EFC2;color:#264409;border-color:#C6D880;}
.error a {color:#8a1f11; background:none; padding:0; margin:0; }
.notice a {color:#514721; background:none; padding:0; margin:0; }
.success a {color:#264409; background:none; padding:0; margin:0; }
.center {text-align: center;}
.width-full {width:100%}
</style>
<title></title>
</head>
<body>
<?php echo $content?>
</body>
</html>