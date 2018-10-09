<style>
.flash-error > div > div > span > button > span {color:#333333}
.closeFlash{
  	float:right;
    font-size:22.5px;
    font-weight:bold;
     line-height:1;
     color:#ffffff;
    text-shadow:0 1px 0 #ffffff;
    opacity:0.5;
    filter:alpha(opacity=20)
	}
	.closeFlash:hover,.closeFlash:focus{color:#ffffff;text-decoration:none;cursor:pointer;opacity:1;filter:alpha(opacity=50)}
    button.closeFlash{padding:0;cursor:pointer;background:transparent;border:0;-webkit-appearance:none}

	.equaltable {
		display:table;
		border-collapse:separate;
	}
	.rowtable {
		display:table-row;
	}
	.rowtable span {
		display:table-cell;
	}

</style>

{{ if or (.flash.success) (or .flash.error .flash.notice) }}
<div class="flash-error" style="margin-bottom:15px;text-align:left;padding-top:20px;padding-bottom:20px;background-color:#f5f5f5;overflow:auto;">
	<div class="col-sm-12 col-md-12  equaltable">
		<div class="rowtable">
			<span style="width:50px"><img {{ if .flash.error }} src="/static/img/ico-error.png" {{else if .flash.success}} src="/static/img/ico-ok.png" {{else if .flash.notice}} src="/static/img/info.png" {{ end }} alt="info" height="42" width="42"></span>
			<span style="margin-top:9px;" class="rowtable">{{ if .flash.error }} {{str2html .flash.error}} {{else if .flash.success}} {{str2html .flash.success}} {{else if .flash.notice}} {{str2html .flash.notice}} {{ end }}</span>
			<span style="padding-top:10px">
				<button onclick="document.getElementsByClassName('flash-error')[0].parentNode.removeChild(document.getElementsByClassName('flash-error')[0]);" style="margin-top:-20px;margin-right:-5px" type="button" class="closeFlash">
					<span href="#" style="font-size:13;font-weight:normal; text-decoration: underline;vertical-align: sub;" aria-hidden="true">{{ if .Lang }} {{i18n .Lang "common.close"}} {{else}} Close {{ end }}<img src="/static/img/close.png" alt="close" height="20" width="20"></span>
				</button>
			</span>
		</div>
	</div>
	
</div>
{{ end }}



